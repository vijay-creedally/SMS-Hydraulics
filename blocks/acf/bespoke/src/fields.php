<?php
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

$block_name = "bespoke";

return register_extended_field_group([
    'title' => __('Bespoke Content', 'sms'),
    'key'   => $block_name,

    'fields' => [
        Image::make('Main Masked Image', 'masked_image'),
        TrueFalse::make(__('Image Align Right?', 'sms'))
			->default(false),
    ],

    'location' => [
        Location::where('block', 'sms/bespoke'),
    ],
]);
