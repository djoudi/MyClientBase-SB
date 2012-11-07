<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['module'] = array(
	'module_name'	=>	'Google',
	'module_path'	=>	'google',
	'module_order'	=>	2,
	'module_top_menu'		=>	false,		
	'module_config'	=>	array(
		'settings_view'	=>	'google/display_settings',
		'settings_save'	=>	'google/save_settings'
	)
);

?>