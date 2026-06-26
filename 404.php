<?php get_header(); ?>

<div class="full-width">
	<div class="page-content">
		<div class="error-404 not-found">

			<?php 
			$options	= get_field('page_not_found_option', 'option');
			$cover_image = !empty( $options['cover_image'] ) ? $options['cover_image'] : '';
			$page_title = !empty( $options['page_title'] ) ? esc_html($options['page_title']) : '404';
			$sub_title = !empty( $options['sub_title'] ) ? esc_html($options['sub_title']) : 'Oops! That page can\'t be found.';
			$content = !empty( $options['content'] ) ? esc_textarea($options['content']) : 'It looks like nothing was found at this location.';
			$button_label = !empty( $options['button_label'] ) ? esc_html($options['button_label']) : 'BACK TO HOME';
			$button_url = !empty( $options['button_url'] ) ? esc_url($options['button_url']) : home_url();

			$block_content = '<!-- wp:sms/hero-block-secondary {"name":"sms/hero-block-secondary","data":{"background_cover":'.$cover_image.',"_background_cover":"field_a640738f","is_overlay":"1","_is_overlay":"field_23ca7c3c"},"mode":"preview"} -->
			<!-- wp:heading {"level":1,"className":"hero-secondary__title ani-fade ani-top"} -->
			<h1 class="wp-block-heading hero-secondary__title ani-fade ani-top">'.$page_title.'</h1>
			<!-- /wp:heading -->
			<!-- /wp:sms/hero-block-secondary -->

			<!-- wp:sms/thank-you {"name":"sms/thank-you","data":{},"mode":"preview"} -->
			<!-- wp:heading -->
			<h2 class="wp-block-heading">'.$sub_title.'</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"thank-you__text ani-top ani-fade"} -->
			<p class="thank-you__text ani-top ani-fade">'.$content.'</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--medium);margin-bottom:var(--wp--preset--spacing--medium)"><!-- wp:button {"className":"is-style-sms-primary"} -->
			<div class="wp-block-button is-style-sms-primary"><a href="'.$button_url.'" class="wp-block-button__link wp-element-button">'.$button_label.'</a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons -->
			<!-- /wp:sms/thank-you -->';

			echo apply_filters('the_content', $block_content); 
			?>

		</div>
	</div>
</div>

<?php get_footer();
