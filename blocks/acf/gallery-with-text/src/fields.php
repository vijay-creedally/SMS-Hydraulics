<?php
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Image;
use Extended\ACF\Location;

$block_name = 'sms/gallery-with-text';

return register_extended_field_group([
    'title' => 'Gallery With Text Block',
    'key' => 'group_' . $block_name,
    'fields' => [
        Repeater::make('Galleries',)
        ->button('Add Gallery Item')
        ->layout('block')
        ->fields([

            Image::make('Image', 'image')
            ->previewSize('thumbnail')
            ->required(),
        ]),
    ],
    'location' => [
        Location::where('block', 'sms/gallery-with-text'),
    ],
]);
