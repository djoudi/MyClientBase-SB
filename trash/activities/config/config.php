<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'

$config['module'] = array(
	'module_path'			=>	'activities',
	'module_name'			=>	'Activities',
	'module_description'	=>	'An supplementary module for the task manager.',
	'module_enabled'		=> 	false,
	'module_author'			=>	'Damiano Venturin',
	'module_homepage'		=>	'http://www.mcbsb.com',
	'module_version'		=>	'0.0.1',
	'module_top_menu'		=>	true,		
	'module_config'			=>	array(
		//'settings_view'		=>	'activities/activity_settings/display',
		//'settings_save'		=>	'activities/activity_settings/save',
	)
);

/* End of file config.php */
/* Location: ./application/modules_custom/activities/config/config.php */
?>