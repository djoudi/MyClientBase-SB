<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'

$config['module'] = array(
	'module_path'			=>	'devices',
	'module_name'			=>	'Devices',
	'module_enabled'		=>	false,
	'module_top_menu' 		=>	false,	
	'module_description'	=>	'A device list manager.',
	'module_author'			=>	'Damiano Venturin',
	'module_homepage'		=>	'http://tooljar.biz',
	'module_version'		=>	'0.13.0',
	'module_order'			=>	10,
	'module_config'			=>	array(
			'contact_tabs'	=>	array('Devices' => '/devices/device')
	)
);
/* End of file config.php */
/* Location: ./application/modules_custom/devices/config/config.php */
?>