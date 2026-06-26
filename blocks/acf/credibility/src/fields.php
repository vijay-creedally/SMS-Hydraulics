<?php
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Location;

$block_name = 'credibility';

return register_extended_field_group([
    'title' => 'Credibility Block',
    'key'   => 'group_' . $block_name,
    'fields' => [
        TrueFalse::make(__('Hide Top curve?', 'sms'))
			->default(false),
        Repeater::make('Stats', 'stats')
            ->fields([
                Text::make('Value', 'value')
                    ->required(),
                Text::make('Preffix', 'preffix'),
                Text::make('Suffix', 'suffix'),
                Text::make('Label', 'label')
                    ->required(),
            ])
            ->layout('block')
            ->button('Add Stat'),
        Repeater::make('Logos', 'logos')
            ->fields([
                Image::make('Logo Image', 'image')
                    ->required(),
                Text::make('Alt Text', 'alt')
            ])
            ->layout('table')
            ->button('Add Logo')
    ],
    'location' => [
        Location::where('block', 'sms/credibility'),
    ],
]);