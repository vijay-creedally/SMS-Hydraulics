<?php
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

$block_name = 'sms/customer-journey';

return register_extended_field_group([
    'title' => __('Customer Journey', 'sms'),
    'key'   => $block_name,
    'fields' => [
        Repeater::make('Journey Cards',)
        ->button('Add Journey Item')
        ->layout('block')
        ->fields([
            Image::make('Image')
                ->previewSize('thumbnail'),
            Text::make('Title'),
            Textarea::make('Description')
        ]),
    ],
    'location' => [
        Location::where('block', 'sms/customer-journey'),
    ],
]);
