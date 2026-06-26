<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*	Image Sizes
*/
add_action( 'init', 'sms_add_image_sizes' );
function sms_add_image_sizes() {
	add_image_size( 'squared', 600, 600, true );
	add_image_size( 'squared-uncropped', 600, 600, false );
	add_image_size( 'bottle', 425, 600, true );
}

// add custom image sizes to gutenberg
// add_filter( 'image_size_names_choose', 'nwp_custom_image_sizes' );
// function nwp_custom_image_sizes( $sizes ) {
// 	return array_merge( $sizes, array(
// 		'squared' => __( 'Squared' ),
// 		'squared-uncropped' => __( 'Squared (Uncropped)' ),
// 		'bottle' => __( 'Bottle' ),
// 	) );
// }
