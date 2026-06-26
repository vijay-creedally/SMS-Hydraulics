<?php
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\WYSIWYGEditor;
use Extended\ACF\Location;
use Extended\ACF\Fields\TrueFalse;

$block_name = "team-lists-block";

return register_extended_field_group([
  'title' => 'Team Lists Block',
  'key' => $block_name,
  'fields' => [
    WYSIWYGEditor::make('Description'),
    Image::make('Team Image')->format('url'),
    TrueFalse::make('Hide Team Members', 'hide_team_members'),
  ],
  'location' => [
    Location::where('block', 'sms/team-lists-block'),
  ],
]);