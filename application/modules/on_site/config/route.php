<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['route_attributes_aliases'] = array(
				'route_name' => 'route name',
);

$config['route_hidden_fields'] = array('id', 'contact_id_key','contact_id','contact_name');

$config['route_default_values'] = array();

$config['route_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['route_mandatory_fields'] = array('city','route_name');

$config['route_prototype'] = array(
				'id' => 1,		

				'city' => 'Sed vitae nulla lorem',		
				'route_name' => 'Sed vitae nulla lorem',					
		
				'contact_id_key' => 'uid',
				'contact_id' => 12345678901,
				'contact_name' => 'fernando castillo de la torres',
				'creation_date' => 1354128792,
				'created_by' => 12345678901,
				'creator' => 'fernando castillo de la torres',
				'update_date' => 1354128792,
				'updated_by' => 12345678901,
				'editor' => 'fernando castillo de la torres',		
);

$config['route_modules_to_load'] = array();