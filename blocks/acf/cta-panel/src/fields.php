<?php
use Extended\ACF\Fields\File;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

$block_name = "cta-panel";

return register_extended_field_group([
	'title' => __('CTA Panel', 'sms'),
	'key' => $block_name,
	'fields' => [
		File::make('Cover Video')
				->acceptedFileTypes(['jpg','jpeg','png','gif','webp','svg','mp4','mov','avi','webm','mkv'])
				->format('url'),
		TrueFalse::make(__('Overlay', 'sms'))
                ->default(false)
                ->message(__('Enable overlay on hero image', 'sms')),
	],
	'location' => [
		Location::where('block', 'sms/cta-panel'),
	]
]);
