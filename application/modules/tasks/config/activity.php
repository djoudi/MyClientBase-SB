<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['activity_attributes_aliases'] = array(
				'action_date' => 'Date'
);

$config['activity_hidden_fields'] = array('id', 'tasks_id','contact_id_key','contact_id','contact_name');

//note: default values do not apply to checkboxes
$config['activity_default_values'] = array(
				'action_date' => date('Y-m-d'),
				'billable' => 1,
);

$config['activity_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor', 'complete_date', 'completed_by', 'completionist');

$config['activity_mandatory_fields'] = array('activity','action_date','creation_date','created_by','creator','contact_name');

$config['activity_prototype'] = array(
				'id' => 1,
				'activity' => 'This is my text',
				'note' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
				'action_date' => 1354128792,
				'duration' => 1000.99,
				'mileage' => 112980.7,
				'billable' => 1,
				//'weight' => 99,
		
				'tasks_id' => 1354128792,

				'contact_id_key' => 'uid',
				'contact_id' => 12345678901,
				'contact_name' => 'fernando castillo de la torres',		
		
				'creation_date' => 1354128792,
				'created_by' => '4BC45678901',
				'creator' => 'fernando castillo de la torres',
				'update_date' => 1354128792,
				'updated_by' => '4BC45678901',
				'editor' => 'fernando castillo de la torres',
);

