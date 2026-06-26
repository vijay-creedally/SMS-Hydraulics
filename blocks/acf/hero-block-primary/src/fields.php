<?php
use Extended\ACF\Fields\File;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

$block_name = "hero-block-primary";

return register_extended_field_group([
	'title' => __('Hero Block Primary', 'sms'),
	'key' => $block_name,
	'fields' => [
		File::make(__('Background cover', 'sms'), 'background_cover')
			->library('all'),
		TrueFalse::make(__('Is Overlay', 'sms'))
                ->default(true)
                ->message(__('Enable overlay on hero image', 'sms')),
	],
	'location' => [
		Location::where('block', 'sms/hero-block-primary'),
	]
]);
