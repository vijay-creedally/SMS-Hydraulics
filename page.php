<?php
/**
 * Default Page Template
 *
 * The default page template.
 *
 * @package Themestrap
 * @since 1.0.0
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
endif;

get_footer();
