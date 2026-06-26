<?php

use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Location;

$block_name = 'locations';

return register_extended_field_group([
    'title' => 'Locations Block',
    'key'   => 'group_' . $block_name,

    'fields' => [

        Repeater::make('Location Tabs', 'location_tabs')
            ->layout('block')
            ->fields([

                Text::make('Tab Title', 'tab_title'),

                Image::make('Tab Image', 'tab_image'),

                Text::make('Location Title', 'location_title'),

                Textarea::make('Location Address', 'location_address'),

                Text::make('Phone Label', 'phone_label'),

                Text::make('Phone Number', 'phone_number'),
                
                Image::make('Phone Icon', 'phone_icon'),
            ]),
    ],

    'location' => [
        Location::where('block', 'sms/locations')
    ],
]);
