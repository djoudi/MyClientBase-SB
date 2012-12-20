<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'

$config['module'] = array(
	'module_path'			=>	'tasks',
	'module_name'			=>	'Tasks',
	'module_enabled'		=>	true,
	'module_top_menu' 		=>	true,	
	'module_description'	=>	'A simple task manager which allows task based invoice creation.',
	'module_author'			=>	'Damiano Venturin',
	'module_homepage'		=>	'http://www.mcbsb.com',
	'module_version'		=>	'0.13.0',
	'module_order'			=>	9,
	'module_top_menu' => true,
	'module_config'			=>	array(
			'contact_tabs'	=>	array('Tasks' => '/tasks/task')
// 		'dashboard_widget'	=>	'tasks/dashboard_widget',
// 		'settings_view'		=>	'tasks/task_settings/display',
// 		'settings_save'		=>	'tasks/task_settings/save',
// 		'dashboard_menu'	=>	'tasks/header_menu'
	)
);
/* End of file config.php */
/* Location: ./application/modules_custom/tasks/config/config.php */
?>