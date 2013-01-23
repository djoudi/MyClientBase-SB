<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['asset_attributes_aliases'] = array(
);

$config['asset_hidden_fields'] = array('id', 'category', 'contact_id_key','contact_id','contact_name');

$config['asset_default_values'] = array(
				'category' => 'asset',
);

$config['asset_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['asset_mandatory_fields'] = array('name','type');

$config['asset_prototype'] = array(
				'id' => 1,		
		
				'description' => 'asset_description',

				'details' => 'Sed vitae nulla lorem. Nam sollicitudin suscipit laoreet. Cras eu arcu ac risus cursus convallis sed a nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat rhoncus felis, vitae congue dolor suscipit tempus. Morbi tempor leo eu justo consectetur pretium. Nulla facilisi. Fusce commodo, tellus vitae fermentum rhoncus, lacus mauris auctor sem, at vulputate metus.',					
				'category' => 'asset_category',
				'type' => 'asset_type',
				'purchase_date' => '0000-00-00',
				'price' => '100000000.00',
				'value' => '100000000.00',
		
				'insurance' => 'Sed vitae nulla lorem. Nam sollicitudin suscipit laoreet. Cras eu arcu ac risus cursus convallis sed a nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat rhoncus felis, vitae congue dolor suscipit tempus. Morbi tempor leo eu justo consectetur pretium. Nulla facilisi. Fusce commodo, tellus vitae fermentum rhoncus, lacus mauris auctor sem, at vulputate metus.',
		
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

$config['asset_modules_to_load'] = array(
			'home_appliance',
			'digital_device'
		);