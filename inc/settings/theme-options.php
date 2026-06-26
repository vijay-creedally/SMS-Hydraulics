<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add ACF options page
 */
add_action('acf/init', 'sms_acf_options_page');
function sms_acf_options_page() {
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	=> 'Theme Options',
			'menu_title'	=> 'Theme Options',
			'menu_slug' 	=> 'theme-options',
			'capability'	=> 'edit_posts',
			'redirect'		=> false
		));
	}
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
add_action( 'after_setup_theme', 'sms_setup' );
function sms_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support('wp-block-styles'); // Enable block styles
	add_theme_support('editor-styles'); // Enable editor styles
	add_theme_support( 'align-wide' );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'disable-custom-font-sizes' );
	remove_theme_support( 'core-block-patterns' );

	// remove ability to add blocks from repo (find this)

}

// Remove WP version
add_filter('the_generator', 'sms_remove_version');
function sms_remove_version() {
	return '';
}

/*
* Remove Attachment Archives
*/
add_action('template_redirect', 'sms_disable_attachment_archives');
function sms_disable_attachment_archives() {
	global $wp_query;

	if ( is_attachment() ) {
		$wp_query->set_404();
		status_header(404);
	}
}

// disable comments
add_action('admin_init', 'sms_remove_comment_support');
function sms_remove_comment_support() {
	remove_post_type_support('post', 'comments');
	remove_post_type_support('page', 'comments');
}

add_filter('comment_form_default_fields','sms_disable_comment_url');
function sms_disable_comment_url($fields) { 
	unset($fields['url']);
	unset($fields['cookies']);
	return $fields;
}

add_filter( 'comment_form_fields', 'sms_move_comment_field_to_bottom' );
function sms_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}

/*
* Move Yoast to bottom
*/
add_filter( 'wpseo_metabox_prio', 'sms_yoasttobottom');
function sms_yoasttobottom() {
	return 'low';
}

/**
 * Disable the emoji's
 */
add_action( 'init', 'sms_disable_emojis' );
function sms_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'sms_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'sms_disable_emojis_remove_dns_prefetch', 10, 2 );
}

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param array $plugins 
 * @return array Difference between the two arrays
 */
function sms_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function sms_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}

// Remove empty p tags
add_filter('the_content', 'sms_remove_empty_p', 20, 1);
function sms_remove_empty_p($content){
	$content = force_balance_tags($content);
	return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

add_filter( 'gform_submit_button', 'gform_input_button_cb', 10, 2 );

/**
 * Check is gform_input_button_cb function exists or not.
 * 
 * @since 1.0.0
 */
if( ! function_exists('gform_input_button_cb') ) {

	/**
	 * Convert gform submit input intto submit button.
	 *
	 * @param string $button
	 * @param string $form
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	function gform_input_button_cb( $button, $form ) {
		$fragment = WP_HTML_Processor::create_fragment( $button );
		$fragment->next_token();
	
		$attributes      = array( 'id', 'type', 'class', 'onclick' );
		$data_attributes = $fragment->get_attribute_names_with_prefix( 'data-' );
		if ( ! empty( $data_attributes ) ) {
			$attributes = array_merge( $attributes, $data_attributes );
		}
	
		$new_attributes = array();

		if( ! empty( $attributes ) ) {
			foreach ( $attributes as $attribute ) {
				$value = $fragment->get_attribute( $attribute );
				if ( ! empty( $value ) ) {
					$new_attributes[] = sprintf( '%s="%s"', $attribute, esc_attr( $value ) );
				}
			}
		}

		$button_text = esc_html( $fragment->get_attribute( 'value' ) );

		return sprintf(
			'<button %s><span>%s</span></button>',
			implode( ' ', $new_attributes ),
			$button_text
		);
	}
}

/**
 * Check is get_file_type_from_url function exists or not.
 * 
 * @since 1.0.0
 */
if( ! function_exists( 'get_file_type_from_url' ) ){

	/**
	 * Get the file type based on the url.
	 *
	 * @param string $url Get the file url.
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	function get_file_type_from_url( $url = '' ) {

		if( empty( $url ) ){
			return 'unknown';
		}

		$image_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
		$video_ext = ['mp4', 'mov', 'avi', 'webm', 'mkv'];

		$ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

		if (in_array($ext, $image_ext)) {
			return 'image';
		}

		if (in_array($ext, $video_ext)) {
			return 'video';
		}

		return 'unknown';
	}
}
/**
 * Register styles for Core Button Block
 */
if ( ! function_exists( 'sms_register_button_block_styles' ) ) {

    function sms_register_button_block_styles() {

        register_block_style(
            'core/button',
            [
                'name'  => 'sms-primary',
                'label' => __( 'Primary', 'sms' ),
            ]
        );

        register_block_style(
            'core/button',
            [
                'name'  => 'sms-secondary',
                'label' => __( 'Secondary', 'sms' ),
            ]
        );
    }
}
add_action( 'init', 'sms_register_button_block_styles' );
add_filter( 'gform_field_input', function( $input, $field, $value, $lead_id, $form_id ) {

    // Change this to your field ID
    if ( $field->id != 6 ) {
        return $input;
    }

    // Build dynamic options
    ob_start(); ?>

    <div class="dropdown-item-wrapper" data-field-id="<?php echo $field->id; ?>">
        <div class="dropdown-item-trigger">
            <?php echo !empty( $field->placeholder ) ? esc_html( $field->placeholder ) : esc_html__('Select an option', 'sms'); ?>
        </div>

        <div class="dropdown-items">
            <?php 
			if( !empty( $field->choices ) ){
				foreach ( $field->choices as $choice ) { ?>
					<span class="dropdown-item" data-value="<?php echo esc_attr( $choice['value'] ); ?>">
						<?php echo esc_html( $choice['text'] ); ?>
					</span>
				<?php }
			} ?>
        </div>

        <!-- Hidden input to store actual value -->
        <input type="hidden"
            id="input_<?php echo $form_id; ?>_<?php echo $field->id; ?>"
            name="input_<?php echo $field->id; ?>"
            value="<?php echo esc_attr( $value ); ?>">
    </div>

    <?php
    return ob_get_clean();

}, 10, 5 );
