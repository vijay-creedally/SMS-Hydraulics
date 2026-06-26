<?php
use Extended\ACF\Location;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;

$block_name = 'sectors';

return register_extended_field_group([
    'title' => 'Sectors Block',
    'key'   => 'group_' . $block_name,

    'fields' => [

        Repeater::make('Sector Items', 'sector_items')
            ->button('Add Sector Item')
            ->layout('block')
            ->fields([

                Group::make('Sector Item', 'sector_item')
                    ->layout('block')
                    ->fields([

                        Image::make('Image', 'image')
                            ->previewSize('thumbnail')
                            ->required(),

                        Text::make('Name', 'name')
                            ->placeholder('Enter Sector Name')
                            ->required(),

                    ]),
            ]),
    ],

    'location' => [
        Location::where('block', 'sms/sectors'),
    ],
]);
