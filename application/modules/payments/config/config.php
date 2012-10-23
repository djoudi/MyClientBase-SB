<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

// mandatory items: 'module_name', 'module_path', 'module_order', 'module_config'

$config['module'] = array(
	'module_name'	=>	'Payments',
	'module_path'	=>	'payments',
	'module_order'	=>	4,
	'module_top_menu' => false,
	'module_config'	=>	array(
		'settings_view'	=>	'payments/payment_settings/display',
		'settings_save'	=>	'payments/payment_settings/save'
	)
);

?>