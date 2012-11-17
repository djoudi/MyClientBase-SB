<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'

$config['module'] = array(
	'module_name'	=>	'Mailer',
	'module_path'	=>	'mailer',
	'module_order'	=>	5,
	'module_top_menu' => false,
	'module_config'	=>	array(
		'settings_view'	=>	'mailer/display_settings',
		'settings_save'	=>	'mailer/save_settings'
	)
);

?>