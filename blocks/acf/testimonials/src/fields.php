<?php
use Extended\ACF\Location;

$block_name = "testimonials";

return register_extended_field_group([
	'title' => 'Testimonials Block',
	'key' => $block_name,
	'fields' => [],
	'location' => [
		Location::where('block', 'sms/testimonials'),
	],
]);
