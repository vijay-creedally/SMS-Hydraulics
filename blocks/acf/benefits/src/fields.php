<?php
use Extended\ACF\Location;

$block_name = 'benefits';

return register_extended_field_group([
    'title' => 'Benefits Block',
    'key'   => 'group_' . $block_name,
    'fields' => [],
    'location' => [
        Location::where('block', 'sms/benefits'),
    ],
]);
