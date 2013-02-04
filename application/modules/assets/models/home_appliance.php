<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Home_Appliance extends Asset
{
	public function __construct() {
	
		parent::__construct();
		
		$this->category = 'home_appliance'; 
	}
	
	public function contact_tab($contact){
		return parent::contact_tab($contact);	
	}
	
	public function magic_button($type = 'create'){
	
		$tmp = array();
	
		switch ($type) {
				
			case 'create':
					
				$tmp['form_title'] = 'New home appliance';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$tmp['procedure'] = 'automated_form';
				$button_label = 'Add home appliance';
				$button_id = 'add_home_appliance';
				
				$but = array('contact_id','contact_id_key','contact_name');
				$this->clean($but);
				
				$this->reset_obj_config();
	
			break;

			case 'edit':
				$tmp['form_title'] = 'Edit home appliance';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$tmp['procedure'] = 'automated_form';
				$button_label = 'Edit home appliance';
				$button_id = 'edit_home_appliance';
				$this->reset_obj_config();
			break;			
			
			default:
	
				return array();
	
			break;
		}
	
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_home_appliance';
	
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