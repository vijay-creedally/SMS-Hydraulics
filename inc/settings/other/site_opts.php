<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Allows robot indexing after dev database has been pushed
$url = get_bloginfo('url');

if (strpos($url, 'localhost') || strpos($url, 'dev.') || strpos($url, 'staging.') || strpos($url, 'cloudwaysapps.com')) {
	if ( get_option('blog_public') === "1" ) {
		update_option('blog_public', "0");
	}

	add_action( 'admin_notices', 'sms_dev_mode' );
} else {
	if ( get_option('blog_public') === "0" ) {
		update_option('blog_public', "1");
	}
}

function sms_dev_mode() {
	echo '<div class="notice notice-error"><p>Site in development mode. If this is a <strong>LIVE</strong> site, please report this notice to your technical team.</p></div>'; 
}
