<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Digital_Device extends Asset
{
	public function __construct() {
	
		parent::__construct();
		
		$this->category = 'digital_device';
	}
	
	private function set_network_name(){
		
		$this->network_name = strtolower(only_alphanum_dash_underscore($this->network_name));
		
		$CI = &get_instance();
		
		if($CI->mcbsb->is_module_enabled('tooljar')){
		
			if($org_name = $CI->mcbsb->get_mcbsb_org()) {
				
				if(!starts_with($this->network_name, $org_name)) {
					$this->network_name = $org_name . '_' . $this->network_name;
				}
			}
		}
		
		//TODO check the network_name length. It has to match openssl.conf rule for CommonName

		//check if there are already devices having the same network name
		$sql = 'select id from ' . $this->db_table . ' where network_name="' . $this->network_name . '"';
		$records = $this->readAll($sql,false);
		
		if(count($records) > 0) {
			
			//in case of update
			if(count($records) == 1 && $records[0]['id'] == $this->id){
				return true;
			}
			
			$CI->mcbsb->system_messages->error = t('Another device has already the same network name');
			return false;
		}
		
		return true;
	}
	
	public function create() {

		if(!$this->set_network_name()) return false;
		
		return parent::create();
	}
	
	public function update() {
		
		if(!$this->set_network_name()) return false;

		return parent::update();
	}
	
	public function magic_button($type = 'create'){
	
		$form_parameters = array();
		$button_properties = array();
		$js_function = 'jqueryForm';
		$ajax_url = '/' . $this->module_folder . '/ajax/getForm';
			
		switch ($type) {
				
			case 'create':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$form_parameters['form_name'] = 'jquery_form_edit_home_appliance';
				$form_parameters['form_title'] = 'New digital device';
				$form_parameters['procedure'] = 'post_to_ajax';
				
				$button_properties['label'] = 'Add digital device';
				$button_properties['id']  = 'add_digital_device';
				
				$but = array('category','contact_id','contact_id_key','contact_name');
				$this->clean($but);
				
				$this->reset_obj_config();
	
			break;
					
			case 'edit':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$form_parameters['form_name'] = 'jquery_form_edit_home_appliance';
				$form_parameters['form_title'] = 'Edit digital device';
				$form_parameters['procedure'] = 'post_to_ajax';
				
				$button_properties['label'] = 'Edit digital device';
				$button_properties['id']  = 'edit_digital_device';
				
				$this->reset_obj_config();
				
			break;			
							
			default:
	
				return array();
	
			break;
		}
	
		return $this->make_magic_button($button_properties, $form_parameters, $js_function, $ajax_url);
	}	
}