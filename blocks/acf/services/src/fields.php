<?php
use Extended\ACF\Fields\File;
use Extended\ACF\Location;

$block_name = 'services';

return register_extended_field_group([
    'title' => 'Services Block',
    'key'   => 'group_' . $block_name,

    'fields' => [
        File::make('Background Video File', 'video_file')
            ->library('all')
    ],

    'location' => [
        Location::where('block', 'sms/services'),
    ],
]);
