<?php

use Extended\ACF\Fields\PostObject;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Url;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

$block_name = "latest-case-studies";
$case_study_posts = get_posts([
    'post_type'      => 'case-studies',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
$options = [];
if ($case_study_posts) {
    foreach ($case_study_posts as $post) {
        $options[$post->ID] = $post->post_title;
    }
}
return register_extended_field_group([
    'title' => __('Latest Case Studies Block', 'sms'),
    'key'   => 'group_' . $block_name,

    'fields' => [
        Text::make('Section Title', 'section_title')
            ->placeholder('Type Your Title'),

        PostObject::make('Select Case Studies', 'selected_case_studies')
            ->postTypes(['case-studies'])    
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
        Location::where('block', 'sms/latest-case-studies'),
    ],
]);
