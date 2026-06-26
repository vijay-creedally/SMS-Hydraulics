<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\File;
use Extended\ACF\Location;

$block_name = 'inquiry-block';

return register_extended_field_group([
    'title' => __('Inquiry Block', 'sms'),
    'key'   => $block_name,

    'fields' => [
        File::make(__('Background cover', 'sms'), 'background_cover')
			->library('all'),
        Text::make('Form Title', 'form_title'),

        Text::make('Form Shortcode', 'form_shortcode'),

        Image::make('Telephone Icon', 'telephone_icon'),
        Text::make('Telephone Label', 'telephone_label'),
        Text::make('Telephone Number', 'telephone_number'),

        Image::make('Email Icon', 'email_icon'),
        Text::make('Email Label', 'email_label'),
        Text::make('Email ID', 'email_id'),

    ],

    'location' => [
        Location::where('block', 'sms/inquiry-block'),
    ],
]);
