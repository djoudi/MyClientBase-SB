<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['ogr_attributes_aliases'] = array();

$config['ogr_hidden_fields'] = array('id');

//note: default values do not apply to checkboxes
$config['ogr_default_values'] = array();

$config['ogr_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['ogr_mandatory_fields'] = array('event_id', 'google_resource_name', 'creation_date', 'created_by', 'creator');

$config['ogr_prototype'] = array(
				'id' => 1,
				'google_id' => 'This is my text',
				'google_name' => 'This is my text',
				'google_mimeType' => 'This is my text',
				'google_url' => 'This is my text',
				'google_icon_url' => 'This is my text',		
				'google_resource_name' => 'This is my text',  	//this is used by to identify the resource type, for ex.: 'calendar'
				'google_resource_id' => 'This is my text',		//this is used by Google to identify, for ex.: a calendar_id
				'object_name' => 'This is my text',
				'object_id' => 1111885200,	
		
				'creation_date' => 1354128792,
				'created_by' => '4BC45678901',
				'creator' => 'fernando castillo de la torres',
				'update_date' => 1354128792,
				'updated_by' => '4BC45678901',
				'editor' => 'fernando castillo de la torres',
);

