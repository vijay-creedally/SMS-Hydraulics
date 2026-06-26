<?php
use Extended\ACF\Fields\Image;
use Extended\ACF\Location;

$block_name = "project-map-block";

return register_extended_field_group([
	'title' => 'Project Map Block',
	'key' => $block_name,
	'fields' => [],
	'location' => [
		Location::where('block', 'sms/project-map-block'),
	],
]);
