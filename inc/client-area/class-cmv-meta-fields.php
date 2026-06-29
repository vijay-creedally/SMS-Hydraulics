<?php
/**
 * Client Media Vault — Attachment Custom Meta Fields (Assigned Clients)
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CMV_Meta_Fields {

	public static function init() {
		add_filter( 'attachment_fields_to_edit', [ __CLASS__, 'render_users_field' ], 10, 2 );
		add_action( 'edit_attachment',           [ __CLASS__, 'save_on_edit' ] );
	}

	/* ════════════════════════════════════════════════════════════
	   Render Assigned Clients field
	   ════════════════════════════════════════════════════════════ */

	public static function render_users_field( $fields, $post ) {
		$clients = get_users( [
			'role'    => 'client',
			'number'  => 500,
			'orderby' => 'display_name',
			'order'   => 'ASC',
		] );

		$current = get_post_meta( $post->ID, '_cmv_assigned_users', true );
		$current = is_array( $current ) ? array_map( 'intval', $current ) : [];

		$nonce = wp_create_nonce( 'cmv_save_attachment_' . $post->ID );

		if ( empty( $clients ) ) {
			$html = '<em style="color:#999">No client users yet — create a user with the "Client" role first.</em>';
		} else {
			$html  = '<select'
				   . ' id="cmv_users_' . esc_attr( $post->ID ) . '"'
				   . ' name="cmv_users_' . esc_attr( $post->ID ) . '[]"'
				   . ' multiple="multiple"'
				   . ' class="cmv-s2-users"'
				   . ' data-post-id="' . esc_attr( $post->ID ) . '"'
				   . ' data-nonce="' . esc_attr( $nonce ) . '"'
				   . ' style="width:100%">';
			$html .= '<option value=""></option>';
			foreach ( $clients as $c ) {
				$sel   = in_array( (int) $c->ID, $current, true ) ? ' selected="selected"' : '';
				$html .= '<option value="' . esc_attr( $c->ID ) . '"' . $sel . '>'
					   . esc_html( $c->display_name . ' (' . $c->user_email . ')' )
					   . '</option>';
			}
			$html .= '</select>';
		}

		$fields['cmv_assigned_users'] = [
			'label' => 'Assigned Clients',
			'input' => 'html',
			'html'  => $html,
		];
		return $fields;
	}

	/* ════════════════════════════════════════════════════════════
	   SAVE — Classic attachment edit page
	   ════════════════════════════════════════════════════════════ */

	public static function save_on_edit( $post_id ) {
		$key = 'cmv_users_' . $post_id;
		if ( ! isset( $_POST[ $key ] ) && ! isset( $_POST[ 'cmv_clear_users_' . $post_id ] ) ) {
			return;
		}

		$nonce_key = 'cmv_nonce_' . $post_id;
		if ( isset( $_POST[ $nonce_key ] ) ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_key ] ) ), 'cmv_save_attachment_' . $post_id ) ) {
				return;
			}
		}

		$ids = isset( $_POST[ $key ] ) ? array_map( 'intval', (array) $_POST[ $key ] ) : [];
		update_post_meta( $post_id, '_cmv_assigned_users', $ids );
	}

	/* ════════════════════════════════════════════════════════════
	   Helpers
	   ════════════════════════════════════════════════════════════ */

	/**
	 * Query attachments assigned to a user, optionally filtered by category.
	 *
	 * @param int      $user_id
	 * @param int|null $category_id
	 * @param int      $per_page
	 * @param int      $page
	 * @return WP_Query
	 */
	public static function get_user_attachments( $user_id, $category_id = null, $per_page = 20, $page = 1 ) {
		$uid  = (int) $user_id;
		$args = [
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'meta_query'     => [
				'relation' => 'OR',
				[
					'key'     => '_cmv_assigned_users',
					'value'   => ';i:' . $uid . ';',
					'compare' => 'LIKE',
				],
				// also catch if stored as JSON string array (legacy)
				[
					'key'     => '_cmv_assigned_users',
					'value'   => '"' . $uid . '"',
					'compare' => 'LIKE',
				],
			],
		];
		if ( $category_id ) {
			$args['tax_query'] = [ [
				'taxonomy' => 'media_category',
				'field'    => 'term_id',
				'terms'    => (int) $category_id,
			] ];
		}
		return new WP_Query( $args );
	}

	/**
	 * Check whether an attachment is assigned to a specific user.
	 *
	 * @param int $attachment_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function attachment_belongs_to_user( $attachment_id, $user_id ) {
		$assigned = get_post_meta( $attachment_id, '_cmv_assigned_users', true );
		if ( ! is_array( $assigned ) ) {
			return false;
		}
		return in_array( (int) $user_id, array_map( 'intval', $assigned ), true );
	}
}

/* ── Back-compat global helpers ───────────────────────────── */
function sms_cmv_get_user_attachments( $user_id, $category_id = null, $per_page = 20, $page = 1 ) {
	return CMV_Meta_Fields::get_user_attachments( $user_id, $category_id, $per_page, $page );
}

function sms_cmv_attachment_belongs_to_user( $attachment_id, $user_id ) {
	return CMV_Meta_Fields::attachment_belongs_to_user( $attachment_id, $user_id );
}
