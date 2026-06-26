<?php
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\RadioButton;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Location;

$block_name = 'sms/process';

return register_extended_field_group([
    'title' => __('Process', 'sms'),
    'key'   => $block_name,
    'fields' => [
        Repeater::make('Process Cards',)
        ->button('Add Process Item')
        ->layout('block')
        ->fields([
            Image::make('Image')
                ->previewSize('thumbnail'),
            Text::make('Title'),
            Textarea::make('Description'),
            RadioButton::make('Placement')
                ->choices(['left'=>'Left', 'right' => 'Right']) 
        ]),
    ],
    'location' => [
        Location::where('block', 'sms/process'),
    ],
]);
