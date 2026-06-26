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

// Load sub-modules
require_once SMS_CMV_PATH . 'roles.php';
require_once SMS_CMV_PATH . 'taxonomy.php';
require_once SMS_CMV_PATH . 'meta-fields.php';
require_once SMS_CMV_PATH . 'shortcodes.php';
require_once SMS_CMV_PATH . 'auth.php';
require_once SMS_CMV_PATH . 'secure-download.php';
require_once SMS_CMV_PATH . 'admin.php';

// Initialization / Activation logic check
add_action( 'init', 'sms_cmv_init_setup' );
function sms_cmv_init_setup() {
	if ( ! get_option( 'sms_cmv_activated' ) ) {
		sms_cmv_register_client_role();
		sms_cmv_create_pages();
		flush_rewrite_rules();
		update_option( 'sms_cmv_activated', 1 );
	}
}

// Function to create standard portal pages
function sms_cmv_create_pages() {
	$pages = [
		'cmv-login'         => [ 'title' => 'Client Login',          'content' => '[cmv_login]' ],
		'cmv-forgot'        => [ 'title' => 'Forgot Password',        'content' => '[cmv_forgot_password]' ],
		'cmv-reset'         => [ 'title' => 'Reset Password',         'content' => '[cmv_reset_password]' ],
		'cmv-portal'        => [ 'title' => 'My Files',               'content' => '[cmv_media_portal]' ],
	];
	foreach ( $pages as $slug => $data ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post([
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_content' => $data['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			]);
		}
	}
}
