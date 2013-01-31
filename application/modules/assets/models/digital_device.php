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
	
		$tmp = array();
	
		switch ($type) {
				
			case 'create':
					
				$tmp['form_title'] = 'New digital device';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$tmp['procedure'] = 'automated_form';
				$button_label = 'Add digital device';
				$button_id = 'add_digital_device';
				$this->reset_obj_config();
	
			break;
					
			case 'edit':
				$tmp['form_title'] = 'Edit digital device';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$tmp['procedure'] = 'automated_form';				
				$button_label = 'Edit digital device';
				$button_id = 'edit_digital_device';
				$this->reset_obj_config();
			break;			
							
			default:
	
				return array();
	
			break;
		}
	
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_digital_device';
	
		$string = json_encode($tmp, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
		$string = '$(this).live("click", jqueryForm(' . $string . ',"/' . $this->module_folder . '/ajax/getForm"))';
	
		$button_url = '#';
	
		$button = array(
				'label' => $button_label,
				'id' => $button_id,
				'url' => $button_url,
				'onclick' => $string,
		);
	
	
		return $button;
	}	
}