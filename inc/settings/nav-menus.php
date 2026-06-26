<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'sms_register_menus' ); 
function sms_register_menus() {
	register_nav_menus(
		array(
			'main-menu' => __( 'Main Menu' ),
			'mobile-menu' => __( 'Mobile Menu' ),
			'footer-menu' => __( 'Footer Menu'),
		)
	);
}

add_filter('wp_nav_menu_items', 'sms_homepage_menu_item', 10, 2);

/**
 * Check sms_homepage_menu_item function exists or not.
 * 
 * @since 1.0.0
 */
if( ! function_exists( 'sms_homepage_menu_item' ) ) {

	/**
	 * Add home page menu item for mobile.
	 *
	 * @param string $items Get menu items html.
	 * @param object $args Get the menu parameters.
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	function sms_homepage_menu_item($items, $args) {
		if ($args->theme_location === 'main-menu') {
			$home_url = home_url('/');
			$image = '<img src="' . esc_url( get_stylesheet_directory_uri() . '/assets/images/home-icon.svg' ) . '" alt="'.__('Custom Icon', 'sms').'" style="width:20px;height:auto;">';
			$custom_item = '<li id="menu-item-99999" class="menu-item home-menu-link menu-item-type-post_type menu-item-object-page menu-item-99999"><a href="'.$home_url.'">'.$image.'</a></li>';
			$items = $custom_item.$items;
		}
		return $items;
	}
}