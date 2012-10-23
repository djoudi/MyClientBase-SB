<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['device_attributes_aliases'] = array(
);

$config['device_hidden_fields'] = array('id','contact_id_key','contact_id','contact_name');

$config['device_default_values'] = array(
				//'homePhone' => '+3902',
);

$config['device_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['device_mandatory_fields'] = array('category');

$config['device_prototype'] = array(
				'id' => 1,
				'category' => 'lavatrice',
				'brand' => 'Electrolux',
				'model' => 'LI1400JE',
				'serial' => uniqid(),
				'code_number' => 'sateuoc1111885200ntoa snthaotnsoaeuntshoeasn stnhoasnthu',
				'registration_number' => uniqid(),
				'purchase_date' => '0000-00-00',
				'under_warranty' => 0,
				'insurance' => 'Sed vitae nulla lorem. Nam sollicitudin suscipit laoreet. Cras eu arcu ac risus cursus convallis sed a nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat rhoncus felis, vitae congue dolor suscipit tempus. Morbi tempor leo eu justo consectetur pretium. Nulla facilisi. Fusce commodo, tellus vitae fermentum rhoncus, lacus mauris auctor sem, at vulputate metus.',
				'details' => 'Sed vitae nulla lorem. Nam sollicitudin suscipit laoreet. Cras eu arcu ac risus cursus convallis sed a nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat rhoncus felis, vitae congue dolor suscipit tempus. Morbi tempor leo eu justo consectetur pretium. Nulla facilisi. Fusce commodo, tellus vitae fermentum rhoncus, lacus mauris auctor sem, at vulputate metus.',
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

