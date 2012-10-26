<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['module'] = array(
	'module_name'	=>	$this->lang->line('general'),
	'module_path'	=>	'settings',
	'module_order'	=>	1,
	'module_config'	=>	array(
		'settings_view'	=>	'settings/other_settings/display',
		'settings_save'	=>	'settings/other_settings/save'
	)
);

?>