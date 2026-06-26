<?php
/**
 * Client Media Vault — Client Role and Permissions Settings
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ── Register "client" role ──────────────────────────────── */
function sms_cmv_register_client_role() {
	remove_role( 'client' ); // remove stale copy on re-activation
	add_role( 'client', __( 'Client', 'sms' ), [
		'read'               => true,
		'cmv_view_files'     => true,
		'cmv_download_files' => false,   // off by default; toggled per-user
	]);
}

/* ── Per-user download permission meta ──────────────────── */
add_action( 'show_user_profile',   'sms_cmv_user_download_field' );
add_action( 'edit_user_profile',   'sms_cmv_user_download_field' );
add_action( 'user_new_form',       'sms_cmv_user_download_field' );

function sms_cmv_user_download_field( $user ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$val = ( $user instanceof WP_User ) ? get_user_meta( $user->ID, 'cmv_can_download', true ) : '';
	?>
	<h3><?php _e( 'Client Media Vault', 'sms' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="cmv_can_download"><?php _e( 'Download Permission', 'sms' ); ?></label></th>
			<td>
				<label>
					<input type="checkbox" id="cmv_can_download" name="cmv_can_download" value="1" <?php checked( $val, '1' ); ?>>
					<?php _e( 'Allow this client to download assigned files', 'sms' ); ?>
				</label>
				<p class="description"><?php _e( 'If unchecked, the client can view files but not download them.', 'sms' ); ?></p>
			</td>
		</tr>
	</table>
	<?php wp_nonce_field( 'cmv_save_user_meta', 'cmv_user_meta_nonce' ); ?>
	<?php
}

add_action( 'personal_options_update',  'sms_cmv_save_user_download_field' );
add_action( 'edit_user_profile_update', 'sms_cmv_save_user_download_field' );
add_action( 'user_register',            'sms_cmv_save_user_download_field' );

function sms_cmv_save_user_download_field( $user_id ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! isset( $_POST['cmv_user_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cmv_user_meta_nonce'], 'cmv_save_user_meta' ) ) {
		return;
	}
	update_user_meta( $user_id, 'cmv_can_download', isset( $_POST['cmv_can_download'] ) ? '1' : '0' );
}

/* ── Helper: does current user have download permission? ── */
function sms_cmv_user_can_download( $user_id = null ) {
	$user_id = $user_id ?: get_current_user_id();
	return (string) get_user_meta( $user_id, 'cmv_can_download', true ) === '1';
}

/* ── Restrict wp-admin for clients ──────────────────────── */
add_action( 'admin_init', 'sms_cmv_block_client_admin_access' );
function sms_cmv_block_client_admin_access() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}
	$user = wp_get_current_user();
	if ( in_array( 'client', (array) $user->roles ) ) {
		$portal = get_page_by_path( 'cmv-portal' );
		wp_redirect( $portal ? get_permalink( $portal ) : home_url() );
		exit;
	}
}

add_filter( 'show_admin_bar', 'sms_cmv_hide_admin_bar_for_clients' );
function sms_cmv_hide_admin_bar_for_clients( $show ) {
	$user = wp_get_current_user();
	if ( in_array( 'client', (array) $user->roles ) ) {
		return false;
	}
	return $show;
}
