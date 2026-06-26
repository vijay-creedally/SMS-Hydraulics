<?php
use Extended\ACF\Fields\Image;
use Extended\ACF\Location;

$block_name = "sms-block";

return register_extended_field_group([
	'title' => 'Block Name',
	'key' => $block_name,
	'fields' => [
		Image::make('Image')
			->format('id'),
	],
	'location' => [
		Location::where('block', 'sms/sms-block'),
	],
]);
