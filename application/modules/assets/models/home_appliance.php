<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Home_Appliance extends Asset
{
	public function __construct() {
	
		parent::__construct();
		
		$this->category = 'home_appliance'; 
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
				$form_parameters['form_title'] = 'New home appliance';
				$form_parameters['procedure'] = 'post_to_ajax';
				
				$button_properties['label'] = 'Add home appliance';
				$button_properties['id'] = 'add_home_appliance';
				
				$but = array('category','contact_id','contact_id_key','contact_name');
				$this->clean($but);
				
				$this->reset_obj_config();
	
			break;

			case 'edit':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$form_parameters['form_name'] = 'jquery_form_edit_home_appliance';
				$form_parameters['form_title'] = 'Edit home appliance';
				$form_parameters['procedure'] = 'post_to_ajax';
								
				$button_properties['label'] = 'Edit home appliance';
				$button_properties['id'] = 'edit_home_appliance';
				
				$this->reset_obj_config();
			break;			
			
			default:
				return array();
			break;
		}
	
		return $this->make_magic_button($button_properties, $form_parameters, $js_function, $ajax_url);
	}	
}