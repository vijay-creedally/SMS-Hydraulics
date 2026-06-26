<?php
/**
 * @link https://riweb.uk/
 * @package WordPress
 * @subpackage Ri Web
 * @since 1.0.0
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="shortcut icon" type="image/png" href="/wp-content/themes/sms/assets/images/favicon.png">
	<script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=ysigc9drkvalix6agwzrna" async="true"></script>
	<!-- Google Tag Manager here-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php
	// Include Header
	get_template_part( 'template-parts/base/headers/content', 'header-inline' );