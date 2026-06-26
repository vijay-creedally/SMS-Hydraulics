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

/* ════════════════════════════════════════════════════════════
   Render Assigned Clients field
   ════════════════════════════════════════════════════════════ */
add_filter( 'attachment_fields_to_edit', 'sms_cmv_attachment_users_field', 10, 2 );
function sms_cmv_attachment_users_field( $fields, $post ) {
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
add_action( 'edit_attachment', 'sms_cmv_users_save_on_edit' );
function sms_cmv_users_save_on_edit( $post_id ) {
	$key = 'cmv_users_' . $post_id;
	if ( ! isset( $_POST[ $key ] ) && ! isset( $_POST['cmv_clear_users_' . $post_id] ) ) {
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
function sms_cmv_get_user_attachments( $user_id, $category_id = null, $per_page = 20, $page = 1 ) {
	/*
	 * WordPress serializes arrays saved via update_post_meta as:
	 *   a:2:{i:0;i:5;i:1;i:12;}
	 * So we must search for  ;i:USER_ID;  (integer value in a serialized array).
	 * We also search  i:0;i:USER_ID;  to catch the first element (index 0).
	 * Using two LIKE clauses with OR covers both positions reliably.
	 */
	$uid = (int) $user_id;
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

function sms_cmv_attachment_belongs_to_user( $attachment_id, $user_id ) {
	// get_post_meta with no third arg returns the raw unserialized value
	$assigned = get_post_meta( $attachment_id, '_cmv_assigned_users', true );
	// WordPress auto-unserializes on get, so $assigned is a PHP array
	if ( ! is_array( $assigned ) ) {
		return false;
	}
	return in_array( (int) $user_id, array_map( 'intval', $assigned ), true );
}
