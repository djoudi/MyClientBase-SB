<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['otr_attributes_aliases'] = array(
				'due_date' => 'Due date',
				'start_date' => 'Start date',
);

$config['otr_hidden_fields'] = array('id','object_name','object_id');

$config['otr_default_values'] = array();

$config['otr_never_display_fields'] = array('colleague_id', 'colleague_name', 'creation_date', 'created_by', 'creator');

$config['otr_mandatory_fields'] = array('object_name','object_id','colleague_id','colleague_name');

$config['otr_prototype'] = array(
				'id' => 1,
				'object_name' => 'This is my text',
				'object_id' => 1111885200,
				'colleague_id' => 1111885200,
				'colleague_name' => 'fernando castillo de la torres',
				'creation_date' => 1354128792,
				'created_by' => 12345678901,
				'creator' => 'fernando castillo de la torres',
);

