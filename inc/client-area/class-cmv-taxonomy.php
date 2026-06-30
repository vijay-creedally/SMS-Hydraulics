<?php
/**
 * Client Media Vault — Media Taxonomy Registration and Fields
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CMV_Taxonomy {

	public static function init() {
		add_action( 'init',       [ __CLASS__, 'register_media_taxonomy' ] );
		add_action( 'admin_menu', [ __CLASS__, 'add_to_media_menu' ], 9 );

		add_filter( 'attachment_fields_to_edit', [ __CLASS__, 'render_taxonomy_field' ], 10, 2 );
		add_action( 'edit_attachment',           [ __CLASS__, 'save_on_edit' ] );
		add_filter( 'wp_mail', [ __CLASS__, 'override_password_reset_email' ] );
	}

	/* ════════════════════════════════════════════════════════════
	   Register taxonomy
	   ════════════════════════════════════════════════════════════ */

	public static function register_media_taxonomy() {
		register_taxonomy( 'media_category', 'attachment', [
			'labels' => [
				'name'          => 'Media Categories',
				'singular_name' => 'Media Category',
				'menu_name'     => 'Media Categories',
				'add_new_item'  => 'Add New Media Category',
				'edit_item'     => 'Edit Media Category',
				'all_items'     => 'All Media Categories',
			],
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => false, // placed manually in admin menus
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => false,
			'query_var'         => false,
		] );
	}

	/* ── Place "Media Categories" under the Media menu ── */

	public static function add_to_media_menu() {
		add_submenu_page(
			'upload.php',
			'Media Categories',
			'Media Categories',
			'manage_options',
			'edit-tags.php?taxonomy=media_category&post_type=attachment'
		);
	}

	/* ════════════════════════════════════════════════════════════
	   Render category field on attachment edit
	   ════════════════════════════════════════════════════════════ */

	public static function render_taxonomy_field( $fields, $post ) {
		$all_terms = get_terms( [ 'taxonomy' => 'media_category', 'hide_empty' => false ] );
		if ( is_wp_error( $all_terms ) ) {
			$all_terms = [];
		}

		$current = wp_get_object_terms( $post->ID, 'media_category', [ 'fields' => 'ids' ] );
		if ( is_wp_error( $current ) ) {
			$current = [];
		}
		$current = array_map( 'intval', $current );

		$nonce = wp_create_nonce( 'cmv_save_attachment_' . $post->ID );

		if ( empty( $all_terms ) ) {
			$html = '<em style="color:#999">No categories yet — add some under Media › Media Categories.</em>';
		} else {
			$html  = '<select'
				   . ' id="cmv_cats_' . esc_attr( $post->ID ) . '"'
				   . ' name="cmv_cats_' . esc_attr( $post->ID ) . '[]"'
				   . ' multiple="multiple"'
				   . ' class="cmv-s2-cats"'
				   . ' data-post-id="' . esc_attr( $post->ID ) . '"'
				   . ' data-nonce="' . esc_attr( $nonce ) . '"'
				   . ' style="width:100%">';
			$html .= '<option value=""></option>';
			foreach ( $all_terms as $t ) {
				$sel   = in_array( (int) $t->term_id, $current, true ) ? ' selected="selected"' : '';
				$html .= '<option value="' . esc_attr( $t->term_id ) . '"' . $sel . '>'
					   . esc_html( $t->name ) . '</option>';
			}
			$html .= '</select>';
		}

		$fields['cmv_media_category'] = [
			'label' => 'Media Category',
			'input' => 'html',
			'html'  => $html,
		];
		return $fields;
	}

	/* ════════════════════════════════════════════════════════════
	   SAVE — Classic attachment edit page only
	   ════════════════════════════════════════════════════════════ */

	public static function save_on_edit( $post_id ) {
		$key = 'cmv_cats_' . $post_id;
		if ( ! isset( $_POST[ $key ] ) && ! isset( $_POST[ 'cmv_clear_cats_' . $post_id ] ) ) {
			return;
		}

		$nonce_key = 'cmv_nonce_' . $post_id;
		if ( isset( $_POST[ $nonce_key ] ) ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_key ] ) ), 'cmv_save_attachment_' . $post_id ) ) {
				return;
			}
		}

		$terms = isset( $_POST[ $key ] ) ? array_map( 'intval', (array) $_POST[ $key ] ) : [];
		wp_set_object_terms( $post_id, $terms, 'media_category' );
	}

	/**
     * Intercepts wp_mail arguments to force our HTML template 
     * ONLY for password reset emails.
     */
    public static function override_password_reset_email( $args ) {

	    if ( isset( $args['subject'] ) && strpos( $args['subject'], 'Password Reset' ) !== false ) {

	        if ( preg_match( '/key=([^&]+)&login=([^%\s\r\n>]+)/', $args['message'], $matches ) ) {

	            $key        = $matches[1];
	            $user_login = rawurldecode( $matches[2] );

	            $site_name = get_bloginfo( 'name' );

	            $logo_id  = get_field( 'logo', 'option' );
	            $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

	            $user_data = get_user_by( 'login', $user_login );

	            if ( ! $user_data ) {
	                return $args;
	            }

	            $user_roles = ! empty( $user_data->roles ) ? $user_data->roles : [];

	            /**
	             * ADMIN → default WordPress reset
	             */
	            if ( in_array( 'administrator', $user_roles, true ) ) {

	                $locale = get_user_locale( $user_data );

	                $reset_link = add_query_arg(
	                    [
	                        'action'  => 'rp',
	                        'key'     => $key,
	                        'login'   => $user_login,
	                        'wp_lang' => $locale,
	                    ],
	                    network_site_url( 'wp-login.php', 'login' )
	                );

	            } 
	            /**
	             * NON-ADMIN → EMAIL in custom link
	             */
	            else {

	                $reset_link = add_query_arg(
	                    [
	                        'key'   => $key,
	                        'email' => $user_data->user_email,
	                    ],
	                    home_url( '/cmv-reset/' )
	                );
	            }

	            $args['message'] = self::template(
	                $site_name,
	                $user_login,
	                $reset_link,
	                $logo_url
	            );

	            // Force HTML email
	            if ( empty( $args['headers'] ) ) {
	                $args['headers'] = [];
	            }

	            if ( is_array( $args['headers'] ) ) {
	                $args['headers'][] = 'Content-Type: text/html; charset=UTF-8';
	            } else {
	                $args['headers'] .= "\r\nContent-Type: text/html; charset=UTF-8\r\n";
	            }
	        }
	    }

	    return $args;
	}

	public static function template($site_name, $user_login, $reset_link, $logo_url) {

		$year = date('Y');

		return "
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset='UTF-8'>
			<title>Reset Password</title>
		</head>

		<body style='margin:0;padding:0;background:#f4f6fb;font-family:Arial,sans-serif;'>

			<table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 0;'>
				<tr>
					<td align='center'>

						<table width='600' style='background:#ffffff;border-radius:10px;overflow:hidden;
						box-shadow:0 10px 30px rgba(0,0,0,0.1);'>

							<tr>
								<td style='background:#111827;padding:20px;text-align:center;color:#fff;font-size:20px;font-weight:bold;'>
									<img src='{$logo_url}' alt='{$site_name}' style='max-width:200px; margin:auto; display:block;'>
								</td>
							</tr>

							<tr>
								<td style='padding:40px;'>

									<h2 style='margin:0 0 20px;color:#111827;'>Reset Your Password</h2>

									<p style='font-size:15px;color:#374151;line-height:1.6;'>
										Hi <strong>{$user_login}</strong>,<br><br>
										We received a request to reset your password.
									</p>

									<div style='text-align:center;margin:30px 0;'>
										<a href='{$reset_link}'
											style='background:#2563eb;color:#fff;padding:12px 24px;
											text-decoration:none;border-radius:6px;display:inline-block;'>
											Reset Password
										</a>
									</div>

									<p style='font-size:13px;color:#6b7280;'>
										If you did not request this, you can ignore this email.
									</p>

									<hr style='border:none;border-top:1px solid #eee;margin:30px 0;'>

									<p style='font-size:12px;color:#9ca3af;text-align:center;'>
										© {$year} {$site_name}
									</p>

								</td>
							</tr>

						</table>

					</td>
				</tr>
			</table>

		</body>
		</html>";
	}
}
