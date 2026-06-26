<?php
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Location;

$block_name = 'mission-statement';

return register_extended_field_group([
    'title' => __('Mission Statement', 'sms'),
    'key'   => $block_name,

    'fields' => [
        Text::make('Tagline heading', 'tagline_heading'),
        Image::make('Background Image', 'background_image')
    ],

    'location' => [
        Location::where('block', 'sms/mission-statement'),
    ],
]);
