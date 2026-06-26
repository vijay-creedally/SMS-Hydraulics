<?php
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Location;

$block_name = 'sms/thank-you';

return register_extended_field_group([
    'title' => __('Thank you', 'sms'),
    'key'   => $block_name,
    'fields' => [],
    'location' => [
        Location::where('block', 'sms/thank-you'),
    ],
]);
