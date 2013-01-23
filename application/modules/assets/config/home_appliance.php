<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['home_appliance_attributes_aliases'] = array(
);

$config['home_appliance_hidden_fields'] = array('id', 'category', 'contact_id_key','contact_id','contact_name');

$config['home_appliance_default_values'] = array(
				'category' => 'home_appliance',
);

$config['home_appliance_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['home_appliance_mandatory_fields'] = array('category', 'brand', 'model');

$config['home_appliance_prototype'] = array(
		
		'id' => 1,
		
		'brand' => 'Electrolux',
		'model' => 'LI1400JE',
		'code_number' => 'sateuoc1111885200ntoa snthaotnsoaeuntshoeasn stnhoasnthu',
		'description' => 'asset_description',
		
		'details' => 'Sed vitae nulla lorem. Nam sollicitudin suscipit laoreet. Cras eu arcu ac risus cursus convallis sed a nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat rhoncus felis, vitae congue dolor suscipit tempus. Morbi tempor leo eu justo consectetur pretium. Nulla facilisi. Fusce commodo, tellus vitae fermentum rhoncus, lacus mauris auctor sem, at vulputate metus.',
		'category' => 'home_appliance',
		'type' => 'home_appliance_type',
		'purchase_date' => '0000-00-00',
		'price' => '100000000.00',
		'value' => '100000000.00',		

		'serial' => uniqid(),
		'registration_number' => uniqid(),
		//'under_warranty' => 0,
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