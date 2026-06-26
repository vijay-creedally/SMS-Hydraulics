<?php
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Email;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\URL;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\Link;
use Extended\ACF\Location;
use Extended\ACF\Fields\Textarea;

$fields = register_extended_field_group([
	'title' => 'General Options',
	'fields' => [
		Tab::make('Site Options'),
		Image::make('Logo')
			->format('id'),
		
		Tab::make('Contact Details'),
		Text::make('Telephone Number'),
		Email::make('Email Address'),
		
		Tab::make('Social Links'),
		Repeater::make('Social')
			->layout('table')
			->fields([
				Text::make('Social Icon')
					->HelperText('Font Awesome Class Name'),
				URL::make('Social URL'),
			]),
		
		Tab::make('Site Notice'),
		Group::make('Sitewide Notice')
			->fields([
				TrueFalse::make('Enable Sitewide Notice'),
				Text::make('Sitewide Notice Text'),
				Link::make('Sitewide Notice Link'),
			]),
		
		Tab::make('Footer'),
		Group::make('Footer Logo')
			->fields([
				Image::make('Logo')->format('id'),
				Text::make('Description'),
			]),
		Group::make('Footer Contact')
			->fields([
				Text::make('Contact Title'),
				Image::make('Telephone Logo')->format('id'),
				Text::make('Telephone Label')->default('Tel:'),
				Text::make('Telephone Number'),
				Text::make('Telephone Link'),
				Image::make('Email Logo')->format('id'),
				Text::make('Email Label'),
				Email::make('Email Address'),
			]),

		Group::make('Footer Newsletter')
			->fields([
				TrueFalse::make('Hide Newsletter Section'),
				Text::make('Newsletter Heading'),
				Text::make('Newsletter Description'),
				Text::make('Gravity Form Shortcode'),
			]),

		Repeater::make('Footer Bottom Links')
			->layout('table')
			->fields([
				Text::make('Link Text'),
				URL::make('Link URL'),
			]),

		Group::make('Footer Copyright')
			->fields([
				Text::make('Copyright'),
				Text::make('Address'),
			]),
		Tab::make('404 Page'),
		Group::make('404 Options', 'page_not_found_option')
			->fields([
				Image::make('Cover Image')->format('id'),
				Text::make('Page title'),
				Text::make('Sub Title'),
				Textarea::make('Content'),
				Text::make('Button Label'),
				URL::make('Button URL')
			]),
		],
		'location' => [
			Location::where('options_page', 'theme-options'),
		],
	]
);
