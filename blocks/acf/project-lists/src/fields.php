<?php
use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Url;
use Extended\ACF\Location;

$block_name = "project-lists-block";

return register_extended_field_group([
	'title' => 'Project Lists Block',
	'key' => $block_name,
	'fields' => [
		Number::make('Per Page'),
		TrueFalse::make(__('Enable Pagination', 'sms'))
                ->default(false)
                ->message(__('Enable pagination for project lists', 'sms')),
		TrueFalse::make(__('Show Button', 'sms'))
			->default(false),
		Text::make('Button Label'),
		Url::make('Button URL')
	],
	'location' => [
		Location::where('block', 'sms/project-lists-block'),
	],
]);
