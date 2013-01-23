<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['digital_device_attributes_aliases'] = array(
);

$config['digital_device_hidden_fields'] = array('id', 'category', 'contact_id_key','contact_id','contact_name');

$config['digital_device_default_values'] = array(
				'category' => 'digital_device',
				'network_device' => 0,
);

$config['digital_device_never_display_fields'] = array('openvpn_certificate','creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['digital_device_mandatory_fields'] = array('category', 'brand', 'model', 'type');

$config['digital_device_prototype'] = array(
		
		'id' => 1,
		
		'brand' => 'HP',
		'model' => 'Z600',
		'code_number' => 'sateuoc1111885200ntoa snthaotnsoaeuntshoeasn stnhoasnthu',
		'description' => 'asset_description',
		
		'details' => 'Sed vitae nulla lorem. Nam sollicitudin suscipit laoreet. Cras eu arcu ac risus cursus convallis sed a nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat rhoncus felis, vitae congue dolor suscipit tempus. Morbi tempor leo eu justo consectetur pretium. Nulla facilisi. Fusce commodo, tellus vitae fermentum rhoncus, lacus mauris auctor sem, at vulputate metus.',
		'category' => 'digital_device',
		'type' => 'digital_device_type',
		'purchase_date' => '0000-00-00',
		'price' => '100000000.00',
		'value' => '100000000.00',		

		'ram' => '512MB',
		'storage_space' => '512GB',
		'operating_system' => 'Linux',		
		
		'serial' => uniqid(),
		'mac_address' => '00-50-FC-A0-67-2C',
		'ip' => '192.168.0.1',
		'network_device' => 0,
		'network_name' => 0,
		'openvpn_certificate' => 'sateuoc1111885200ntoa snthaotnsoaeuntshoeasn stnhoasnthu',
		
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