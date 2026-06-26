<?php

use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\Group;
use Extended\ACF\Location;

$block_name = "challenges";

return register_extended_field_group([
    'title' => __('Challenges', 'sms'),
    'key'   => 'group_' . $block_name,

    'fields' => [

        Group::make('Challenge Intro', 'challenges_intro')
            ->layout('block')
            ->fields([
                Text::make('Title', 'intro_title'),
                Textarea::make('Text', 'intro_text'),
            ]),

        Repeater::make('Challenge Items', 'challenge_items')
            ->layout('block')
            ->fields([

                Group::make('Challenge Card', 'challenge_card')
                    ->layout('block')
                    ->fields([
                        Text::make('Card Title', 'card_title'),
                        Textarea::make('Card Description', 'card_description'),
                    ]),
                TrueFalse::make(__('Is solution?', 'sms'))
                    ->default(false)

            ])
        ->button('Add Challenge Item')
    ],

    'location' => [
        Location::where('block', 'sms/challenges'),
    ],
]);