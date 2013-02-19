<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['gtoken_attributes_aliases'] = array();

$config['gtoken_hidden_fields'] = array('id', 'contact_id_key','contact_id','contact_name');

//note: default values do not apply to checkboxes
$config['gtoken_default_values'] = array();

$config['gtoken_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['gtoken_mandatory_fields'] = array('id_token', 'email', 'access_token', 'token_type', 'expires_in', 'refresh_token', 'action_date','creation_date','created_by','creator', 'contact_id_key', 'contact_id', 'contact_name');

$config['gtoken_prototype'] = array(
				'id' => 1,
				'id_token' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
				'email' => 'user@snthoe.com',
				'access_token' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
				'token_type' => 'Bearer',
				'expires_in' => 1354128792,
				'refresh_token' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',

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

