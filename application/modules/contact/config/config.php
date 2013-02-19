<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'
 
$config['module'] = array(
	'module_name'	=>	'Contact',
	'module_path'	=>	'contact',
	'module_order'	=>	3,
	'module_enabled' => 1,
	'module_top_menu' =>	true,		
	'module_config'	=>	array(
		'settings_view'	=>	array(
									'Person' => 'contact/contact_settings/display_person',
									'Organization' => 'contact/contact_settings/display_organization',
									'Location' => 'contact/contact_settings/display_location'),
		'settings_save'	=>	'contact/contact_settings/save'
	)
		
);

?>