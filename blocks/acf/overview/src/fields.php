<?php
use Extended\ACF\Location;

$block_name = 'overview';

return register_extended_field_group([
    'title' => 'Overview Block',
    'key'   => 'group_' . $block_name,
    'fields' => [],
    'location' => [
        Location::where('block', 'sms/overview'),
    ],
]);
