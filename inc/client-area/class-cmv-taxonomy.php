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
}
