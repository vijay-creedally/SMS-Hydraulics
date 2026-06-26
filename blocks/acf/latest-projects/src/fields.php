<?php

use Extended\ACF\Fields\PostObject;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Url;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

$block_name = "latest-projects";

return register_extended_field_group([
    'title' => __('Latest Projects', 'sms'),
    'key'   => 'group_' . $block_name,

    'fields' => [
        Text::make('Section Title', 'section_title')
            ->placeholder('Type Your Title'),

        PostObject::make('Select Projects', 'selected_projects')
            ->postTypes(['projects'])    
            ->multiple()                   
            ->nullable(true),

		Group::make('Button', 'button_group')
    		->fields([
    		    TrueFalse::make('Show Button', 'show_button')
    		        ->default(true)
    		        ->stylized(),
			
    		    Text::make('Button Label', 'button_label'),
			
    		    Url::make('Button URL', 'button_url')
    		]),

    ],

    'location' => [
        Location::where('block', 'sms/latest-projects'),
    ],
]);
