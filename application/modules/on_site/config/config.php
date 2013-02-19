<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'
 
$config['module'] = array(
	'module_name'		=>	'On_Site',
	'module_path'		=>	'on_site_assistance',
	'module_order'		=>	13,
	'module_enabled' 	=> 	true,
	'module_top_menu' 	=>	false,		
	'module_config'	=>	array(
 			'settings_view'	=>	array(
 									'Routes' => 'on_site/route_settings/display_routes',
			),
			'settings_save' => '',
			
	)
		
);

?>