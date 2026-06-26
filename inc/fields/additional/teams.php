<?php
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Number;
use Extended\ACF\Location;

$fields = register_extended_field_group([
    'title' => 'Team Settings',
    'fields' => [
        Text::make('Designation'),
        Number::make('Order'),
    ],
    'location' => [
        Location::where('post_type', 'teams'),
    ],
]);
