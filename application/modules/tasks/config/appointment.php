<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['appointment_attributes_aliases'] = array(
				'start_time' => 'Start',
				'end_time' => 'End',
);

$config['appointment_hidden_fields'] = array('id','task_id');

$config['appointment_frozen_fields'] = array();

$config['appointment_default_values'] = array(
				//'homePhone' => '+3902',
);

$config['appointment_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor',);

$config['appointment_mandatory_fields'] = array('what','start_time', 'end_time', 'creation_date','created_by','creator');

$config['appointment_prototype'] = array(
				'id' => 1,
				'task_id' => 12345678901,
		
				'what' => 'This is my text',
				'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
				'where' => 'This is my text',
				'start_time' => 1354128792,
				'end_time' => 1354128792,
		
				'creation_date' => 1354128792,
				'created_by' => 12345678901,
				'creator' => 'fernando castillo de la torres',
				'update_date' => 1354128792,
				'updated_by' => 12345678901,
				'editor' => 'fernando castillo de la torres',	
);

