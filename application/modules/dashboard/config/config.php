<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'

$config['module'] = array(
	'module_name'	=>	'Dashboard',
	'module_path'	=>	'dashboard',
	'module_order'	=>	10,
	'module_top_menu'		=>	true,		
	'module_config'	=>	array(
		'settings_view'	=>	'dashboard/dashboard_settings/display',
		'settings_save'	=>	'dashboard/dashboard_settings/save'
	)
);

?>