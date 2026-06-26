<?php
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\Image;
use Extended\ACF\Location;

$block_name = "certifications";

return register_extended_field_group([
    'title' => __('Certifications', 'sms'),
    'key'   => $block_name,

    'fields' => [
        Repeater::make('Certifications Cards', 'certifications_cards')
            ->layout('row')
            ->fields([
                Image::make('Icon', 'icon'),
                Text::make('Title', 'title'),
                Textarea::make('Description', 'description')
                    ->rows(3)
            ]),
	],
    'location' => [
        Location::where('block', 'sms/certifications'),
    ],
]);
