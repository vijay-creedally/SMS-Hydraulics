<?php
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Location;

$block_name = 'specifics';

return register_extended_field_group([
    'title' => 'Specifics Block',
    'key'   => 'group_' . $block_name,
    'fields' => [
        Repeater::make('Specifics', 'specifics')
            ->fields([
                Image::make('Icon', 'icon'),
                Text::make('Title', 'title'),
                Text::make('Value', 'value'),
                Text::make('suffix', 'suffix')
            ])
            ->layout('block')
            ->button('Add Specific'),
    ],
    'location' => [
        Location::where('block', 'sms/specifics'),
    ],
]);