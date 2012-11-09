<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class System_Settings extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->_post_handler();

	}

	private function get_settings_tabs($module, $settings_view, $tab_name = null){
		
		if(!is_object($module)) return false;
		if(!is_string($settings_view)) return false;
		if(!is_null($tab_name) && !is_string($tab_name)) return false;
		
		//TODO fixme
		if($module->module_name != 'Tooljar') {
			$html = modules::run($settings_view);
		} else {
			$html = '';
		}
			
		return array(
				'path'			=>	$module->module_path,
				'title'			=>	is_null($tab_name) ? $module->module_name : $tab_name,
				'settings_view'	=>	$module->module_config['settings_view'],
				'html'			=>  $html
		);
		
	}
	
	public function index() {
        
		$this->load->helper('mcb_date');
		
        //gets data about the tabs to display
		foreach ($this->mcbsb->_modules['enabled'] as $item) {
			
			$module = new Module();
			$module->module_name = $item;
			if($module->read()){
				//TODO maybe I don't need isset($module->module_config['settings_save'])
				if (!empty($module->module_config['settings_view']) and isset($module->module_config['settings_save'])) {
					
					$settings_views = $module->module_config['settings_view'];
					
					//transform the value in array to simplify the process
					if(!is_array($settings_views)){
						$tmp = $settings_views;
						$settings_views = array($tmp);
					}
					
					//clean up
					$settings_views = array_filter(array_map('trim',$settings_views));
					
					foreach ($settings_views as $key => $settings_view){
						!is_numeric($key)? $tab_name = $key : $tab_name = null;
						if($tab = $this->get_settings_tabs($module, $settings_view, $tab_name)){
							$tabs[] = $tab;
						} 
					}
				}
			}
		}

		$data = array(
			'tabs'		=>	$tabs,
			'tab_index'		=>	0,
			'languages' => $this->mcbsb->_languages,
			'date_formats'	=>  date_formats(),
		);

		//loading Smarty template
		$this->load->view('system_settings.tpl', $data, false, 'smarty');
	}

	//TODO maybe this should go in cron
	function optimize_db() {

// 		$this->load->dbutil();

// 		$this->dbutil->optimize_database();
		
// 		$this->session->set_flashdata('custom_success', $this->lang->line('database_optimized'));
		
		redirect('/');

	}

	public function save(){
		foreach ($this->input->post() as $key => $value) {
			$this->mcbsb->settings->save($key,$value);
		}
	}
	
	function _post_handler() {

		if ($this->input->post('btn_backup')) {

			$prefs = array(
					'format'      => 'zip',
					'filename'    => 'mcbsb_' . date('Y-m-d')
				);
		
			$this->load->library('db_backup');
		
			if(!$this->db_backup->backup($prefs)){
				$this->mcbsb->system_messages->error = 'Mysql backup failed' ;
			}
		}

		if ($this->input->post('btn_save_settings')) {

			$this->save();

			$this->mcbsb->system_messages->success = 'System settings have been saved';

			redirect('/system_settings');
		}

	}

	/*
	//TODO maybe this will be handy in the future
	function _core_save() {

		foreach ($this->mdl_mcb_modules->core_modules as $module) {

			if (isset($module->module_config['settings_save'])) {

				modules::run($module->module_config['settings_save']);

			}

		}

	}
	*/

}

?>