<?php
/**
 * Ri Web functions and definitions
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */


/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'sms_scripts' );
function sms_scripts() {
	$deps = require('assets/build/frontend.asset.php');
	// CSS
	wp_enqueue_style( 'sms', get_stylesheet_directory_uri() . '/assets/build/frontend.css', array(), $deps['version'] );
	// JS
	wp_enqueue_script( 'sms', get_stylesheet_directory_uri() . '/assets/build/frontend.js', array(), $deps['version'], true );

	wp_localize_script( 'sms', 'smsObj', [
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	] );
}

/**
 * Enqueue block editor scripts and styles.
 */
add_action( 'enqueue_block_editor_assets', 'sms_block_editor_assets', 0 );
function sms_block_editor_assets() {
	$deps = require('assets/admin/build/admin.asset.php');

	if ( is_admin() ) {
		wp_enqueue_style( 'sms-editor', get_stylesheet_directory_uri() . '/assets/admin/build/admin.css', array(), $deps['version'] );
		wp_enqueue_script( 'sms-editor', get_stylesheet_directory_uri() . '/assets/admin/build/admin.js', array('wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-hooks', 'wp-data'), $deps['version'], true );
	}
}

/**
 * Enqueue admin tutorial video styles.
 */
add_action('admin_enqueue_scripts', 'tutorial_video_style');
function tutorial_video_style() {
	wp_enqueue_style( 'tutorial-video-style', get_template_directory_uri() . '/assets/admin/tutorial-video.css', array(), '1.0.0' );
}

// do preload for fonts
add_action( 'wp_head', 'sms_preload_fonts' );
function sms_preload_fonts() {
	$fonts = glob(get_template_directory() . '/assets/build/fonts/*');
	foreach ($fonts as $font) {
		$font_name = basename($font);
		echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/build/fonts/' . $font_name . '" as="font" type="font/woff2" crossorigin>' . PHP_EOL;
	}
}

// Include Vendor
require_once 'vendor/autoload.php';

require_once 'inc/utility.php';
require_once 'inc/ajax-actions.php';

// Theme Setup
require_once 'inc/settings/theme-options.php';
require_once 'inc/settings/taxonomies.php';
require_once 'inc/settings/nav-menus.php';
require_once 'inc/settings/post-types.php';
require_once 'inc/settings/image-sizes.php';
require_once 'inc/settings/other/site_opts.php';
require_once 'inc/settings/blocks.php';
require_once 'inc/settings/tutorial-playlist.php';

// Documentation
require_once 'inc/admin/class.docs.php';

// Additional Custom Fields
require_once 'inc/fields/field-builder.php';


require_once 'inc/client-area/client-area.php';