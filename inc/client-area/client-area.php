<?php
/**
 * Client Media Vault Bootstrapper
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SMS_CMV_VERSION', '1.0.0' );
define( 'SMS_CMV_PATH',    get_template_directory() . '/inc/client-area/' );

// Load classes
require_once SMS_CMV_PATH . 'class-cmv-roles.php';
require_once SMS_CMV_PATH . 'class-cmv-taxonomy.php';
require_once SMS_CMV_PATH . 'class-cmv-meta-fields.php';
require_once SMS_CMV_PATH . 'class-cmv-auth.php';
require_once SMS_CMV_PATH . 'class-cmv-secure-download.php';
require_once SMS_CMV_PATH . 'class-cmv-shortcodes.php';
require_once SMS_CMV_PATH . 'class-cmv-admin.php';

// Boot all modules
CMV_Roles::init();
CMV_Taxonomy::init();
CMV_Meta_Fields::init();
CMV_Auth::init();
CMV_Secure_Download::init();
CMV_Shortcodes::init();
CMV_Admin::init();

// Initialization / Activation logic check
add_action( 'init', 'sms_cmv_init_setup' );
function sms_cmv_init_setup() {
	if ( ! get_option( 'sms_cmv_activated' ) ) {
		CMV_Roles::register_client_role();
		sms_cmv_create_pages();
		flush_rewrite_rules();
		update_option( 'sms_cmv_activated', 1 );
	}
}

// Function to create standard portal pages
function sms_cmv_create_pages() {
	$pages = [
		'client-login'  => [ 'title' => 'Client Login',   'content' => '[cmv_login]' ],
		'forgot-password' => [ 'title' => 'Forgot Password', 'content' => '[cmv_forgot_password]' ],
		'reset-password' => [ 'title' => 'Reset Password', 'content' => '[cmv_reset_password]' ],
		'client-media-vault' => [ 'title' => 'Client Media Vault',        'content' => '[cmv_media_portal]' ],
	];
	foreach ( $pages as $slug => $data ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post( [
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_content' => $data['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			] );
		}
	}
}
