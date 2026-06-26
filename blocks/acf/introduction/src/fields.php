<?php
use Extended\ACF\Location;

$block_name = 'introduction';

return register_extended_field_group([
    'title' => 'Introduction Block',
    'key' => 'group_' . $block_name,
    'fields' => [],
    'location' => [
        Location::where('block', 'sms/introduction'),
    ],
]);
