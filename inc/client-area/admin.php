<?php
/**
 * Client Media Vault — Admin Pages and Asset Enqueue
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ════════════════════════════════════════════════════════════
   Admin sidebar menu
   ════════════════════════════════════════════════════════════ */
add_action( 'admin_menu', 'sms_cmv_admin_menu' );
function sms_cmv_admin_menu() {
	// Main Media Vault entry
	add_menu_page(
		'Client Media Vault',
		'Media Vault',
		'manage_options',
		'cmv-settings',
		'sms_cmv_settings_page',
		'dashicons-lock',
		30
	);
	// Media Categories under Media Vault too
	add_submenu_page(
		'cmv-settings',
		'Media Categories',
		'Media Categories',
		'manage_options',
		'edit-tags.php?taxonomy=media_category&post_type=attachment'
	);
}

/* ════════════════════════════════════════════════════════════
   Enqueue Select2 + admin assets
   ════════════════════════════════════════════════════════════ */
add_action( 'admin_enqueue_scripts', 'sms_cmv_admin_enqueue' );
function sms_cmv_admin_enqueue( $hook ) {
	// Load on media-related screens and on our dashboard
	$load = [ 'upload.php', 'post.php', 'post-new.php', 'toplevel_page_cmv-settings' ];
	if ( ! in_array( $hook, $load, true ) ) {
		return;
	}

	wp_enqueue_style(
		'cmv-select2',
		'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css',
		[], '4.0.13'
	);
	wp_enqueue_script(
		'cmv-select2',
		'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js',
		[ 'jquery' ], '4.0.13', true
	);

	$asset_file = get_stylesheet_directory() . '/assets/admin/build/admin.asset.php';
	$version    = SMS_CMV_VERSION;
	$deps       = [ 'jquery', 'cmv-select2' ];

	if ( file_exists( $asset_file ) ) {
		$assets_info = require $asset_file;
		$version     = $assets_info['version'] ?? $version;
		$deps        = array_merge( $deps, $assets_info['dependencies'] ?? [] );
	}

	wp_enqueue_style(
		'sms-cmv-admin',
		get_stylesheet_directory_uri() . '/assets/admin/build/admin.css',
		[ 'cmv-select2' ], $version
	);
	wp_enqueue_script(
		'sms-cmv-admin',
		get_stylesheet_directory_uri() . '/assets/admin/build/admin.js',
		$deps, $version, true
	);

	wp_localize_script( 'sms-cmv-admin', 'CMV_Admin', [
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	] );
}

/* ════════════════════════════════════════════════════════════
   AJAX SAVE ENDPOINT
   ════════════════════════════════════════════════════════════ */
add_action( 'wp_ajax_cmv_save_attachment_meta', 'sms_cmv_ajax_save_attachment_meta' );
function sms_cmv_ajax_save_attachment_meta() {
	// Verify nonce
	$nonce       = isset( $_POST['nonce'] )         ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) )   : '';
	$post_id     = isset( $_POST['post_id'] )       ? (int) $_POST['post_id']                                : 0;

	if ( ! $post_id || ! wp_verify_nonce( $nonce, 'cmv_save_attachment_' . $post_id ) ) {
		wp_send_json_error( 'Invalid nonce or post ID.' );
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( 'Permission denied.' );
	}

	// --- Assigned Users ---
	$users_raw = isset( $_POST['users'] ) ? $_POST['users'] : '';
	if ( $users_raw === '' || $users_raw === null ) {
		$user_ids = [];
	} else {
		$user_ids = array_filter( array_map( 'intval', (array) $users_raw ) );
	}
	update_post_meta( $post_id, '_cmv_assigned_users', array_values( $user_ids ) );

	// --- Taxonomy categories ---
	$cats_raw = isset( $_POST['cats'] ) ? $_POST['cats'] : '';
	if ( $cats_raw === '' || $cats_raw === null ) {
		$cat_ids = [];
	} else {
		$cat_ids = array_filter( array_map( 'intval', (array) $cats_raw ) );
	}
	wp_set_object_terms( $post_id, array_values( $cat_ids ), 'media_category' );

	wp_send_json_success( [
		'post_id'  => $post_id,
		'users'    => $user_ids,
		'cats'     => $cat_ids,
	] );
}

/* ════════════════════════════════════════════════════════════
   Dashboard / settings page
   ════════════════════════════════════════════════════════════ */
function sms_cmv_settings_page() {
	$portal_url  = sms_cmv_page_url( 'cmv-portal' );
	$login_url   = sms_cmv_page_url( 'cmv-login' );
	$clients     = get_users( [ 'role' => 'client', 'number' => 999 ] );
	$total_media = (int) wp_count_posts( 'attachment' )->inherit;
	?>
	<div class="wrap cmv-admin-wrap">
		<h1 class="cmv-admin-title"><span class="dashicons dashicons-lock"></span> Client Media Vault</h1>
		<p class="description">Assign media files to clients from the Media Library. Open any attachment and use the <strong>Assigned Clients</strong> and <strong>Media Category</strong> fields.</p>

		<div class="cmv-stat-cards">
			<div class="cmv-stat-card">
				<div class="cmv-stat-icon dashicons dashicons-groups"></div>
				<div class="cmv-stat-body">
					<span class="cmv-stat-num"><?php echo count( $clients ); ?></span>
					<span class="cmv-stat-label">Client Users</span>
				</div>
				<div class="cmv-stat-actions">
					<a href="<?php echo esc_url( admin_url( 'users.php?role=client' ) ); ?>" class="button">Manage</a>
					<a href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" class="button button-primary">+ Add Client</a>
				</div>
			</div>

			<div class="cmv-stat-card">
				<div class="cmv-stat-icon dashicons dashicons-admin-media"></div>
				<div class="cmv-stat-body">
					<span class="cmv-stat-num"><?php echo $total_media; ?></span>
					<span class="cmv-stat-label">Media Files</span>
				</div>
				<div class="cmv-stat-actions">
					<a href="<?php echo esc_url( admin_url( 'upload.php' ) ); ?>" class="button">Media Library</a>
				</div>
			</div>

			<div class="cmv-stat-card">
				<div class="cmv-stat-icon dashicons dashicons-category"></div>
				<div class="cmv-stat-body">
					<span class="cmv-stat-num"><?php echo (int) wp_count_terms( 'media_category' ); ?></span>
					<span class="cmv-stat-label">Media Categories</span>
				</div>
				<div class="cmv-stat-actions">
					<a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=media_category&post_type=attachment' ) ); ?>" class="button">Manage</a>
				</div>
			</div>

			<div class="cmv-stat-card">
				<div class="cmv-stat-icon dashicons dashicons-admin-links"></div>
				<div class="cmv-stat-body">
					<span class="cmv-stat-label" style="margin-bottom:4px">Login URL</span>
					<a href="<?php echo esc_url( $login_url ); ?>" target="_blank" style="font-size:12px;word-break:break-all"><?php echo esc_html( $login_url ); ?></a>
					<a href="<?php echo esc_url( $portal_url ); ?>" target="_blank" style="font-size:12px;word-break:break-all;margin-top:4px;display:block"><?php echo esc_html( $portal_url ); ?></a>
				</div>
			</div>
		</div>

		<h2 class="cmv-section-title">Client Overview</h2>
		<table class="wp-list-table widefat fixed striped cmv-clients-table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Download Permission</th>
					<th>Files Assigned</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $clients ) ) : ?>
					<tr><td colspan="5" style="padding:20px;color:#777">No clients yet. Create a user and set their role to <strong>Client</strong>.</td></tr>
				<?php else : ?>
					<?php foreach ( $clients as $c ) :
						$can_dl   = get_user_meta( $c->ID, 'cmv_can_download', true ) === '1';
						$count    = sms_cmv_admin_count_assigned( $c->ID );
					?>
					<tr>
						<td><strong><?php echo esc_html( $c->display_name ); ?></strong></td>
						<td><?php echo esc_html( $c->user_email ); ?></td>
						<td>
							<?php if ( $can_dl ) : ?>
								<span class="cmv-badge-yes">&#10003; Download + View</span>
							<?php else : ?>
								<span class="cmv-badge-no">View Only</span>
							<?php endif; ?>
						</td>
						<td><?php echo (int) $count; ?></td>
						<td><a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $c->ID ) ); ?>">Edit User</a></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

function sms_cmv_admin_count_assigned( $user_id ) {
	global $wpdb;
	$uid = (int) $user_id;
	// Serialized: a:N:{i:0;i:UID;...}  — match ;i:UID; (any position) OR i:0;i:UID; (first position)
	return (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM {$wpdb->postmeta}
		 WHERE meta_key = '_cmv_assigned_users'
		 AND (
			 meta_value LIKE %s
			 OR meta_value LIKE %s
		 )",
		'%' . $wpdb->esc_like( ';i:' . $uid . ';' ) . '%',
		'%' . $wpdb->esc_like( '"' . $uid . '"' ) . '%'
	) );
}

/* ════════════════════════════════════════════════════════════
   Debug AJAX — shows raw stored meta value for an attachment
   Visit: /wp-admin/admin-ajax.php?action=cmv_debug_meta&id=POST_ID
   ════════════════════════════════════════════════════════════ */
add_action( 'wp_ajax_cmv_debug_meta', 'sms_cmv_ajax_debug_meta' );
function sms_cmv_ajax_debug_meta() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'No.' );
	}
	$id  = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
	if ( ! $id ) {
		wp_die( 'Pass ?id=POST_ID' );
	}

	$raw = get_post_meta( $id, '_cmv_assigned_users', true );
	global $wpdb;
	$db_row = $wpdb->get_var( $wpdb->prepare(
		"SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key='_cmv_assigned_users' LIMIT 1",
		$id
	) );
	header( 'Content-Type: text/plain' );
	echo "=== CMV Debug: attachment $id ===\n\n";
	echo "get_post_meta() (PHP-unserialized):\n";
	var_dump( $raw );
	echo "\nRaw DB string:\n$db_row\n";
	wp_die();
}
