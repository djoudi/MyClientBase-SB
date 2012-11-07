<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['module'] = array(
	'module_name'	=>	'Invoices',
	'module_path'	=>	'invoices',
	'module_order'	=>	3,
	'module_top_menu' => true,
	'module_config'	=>	array(
		'settings_view'	=>	'invoices/invoice_settings/display',
		'settings_save'	=>	'invoices/invoice_settings/save'
	)
);

?>