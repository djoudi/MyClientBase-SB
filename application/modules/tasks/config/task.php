<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['task_attributes_aliases'] = array(
				'due_date' => 'Due date',
				'start_date' => 'Start date',
				'endnote' => 'End note',
);

$config['task_hidden_fields'] = array('id','contact_id_key','contact_id','contact_name');

$config['task_default_values'] = array(
				'homePhone' => '+3902',
);

$config['task_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor', 'complete_date', 'completed_by', 'completionist');

$config['task_mandatory_fields'] = array('task','creation_date','created_by','creator','contact_name');

$config['task_prototype'] = array(
				'id' => 1,
				'task' => 'This is my text',
				'details' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
				'urgent' => 0,

				'creation_date' => 1354128792,
				'created_by' => 12345678901,
				'creator' => 'fernando castillo de la torres',
				'update_date' => 1354128792,
				'updated_by' => 12345678901,
				'editor' => 'fernando castillo de la torres',
				
				'contact_id_key' => 'uid', 
				'contact_id' => 12345678901,
				'contact_name' => 'fernando castillo de la torres',
				
				'start_date' => '0000-00-00',
				'due_date' => '0000-00-00',
				'complete_date' => '0000-00-00',
				'completed_by' => 12345678901,
				'completionist' => 'fernando castillo de la torres',
				'endnote' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',

);

