<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config = array(
	'module_name'	=>	'Tooljar',
	'module_path'	=>	'tooljar',
	'module_order'	=>	3,
	'module_description'	=>	'API client for Tooljar',
	'module_author'			=>	'Damiano Venturin',
	'module_homepage'		=>	'http://www.tooljar.biz',
	'module_version'		=>	'0.0.1',
	'module_config'	=>	array(
		'settings_view'	=>	'tooljar/display_settings',
		'settings_save'	=>	'tooljar/save_settings'
	)
);


/* End of file config.php */
/* Location: ./application/modules_core/tooljar/config/config.php */
?>