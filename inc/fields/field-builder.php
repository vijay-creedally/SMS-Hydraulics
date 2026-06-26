<?php
$fields = glob( get_stylesheet_directory() . '/inc/fields/*.php' );
$additional_fields = glob( get_stylesheet_directory() . '/inc/fields/additional/*.php' );

foreach ($additional_fields as $flex_field) {
	add_action('acf/include_fields', function() use ($flex_field) {
		require_once $flex_field;
	});
}
