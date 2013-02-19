<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Asset extends Rb_Db_Obj
{
	const table = 'assets';
	protected $module_folder = null;
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'assets';
		
		$this->category = 'asset';
		
		//R::freeze( array($this->db_table)); 
		
		$this->initialize();
	
	}
	
	private function normalize() {
		
		if(isset($this->category)) $this->category = strtolower($this->category);
		if(isset($this->type)) $this->type = strtolower($this->type);
		
	}
	
	public function create() {
		
		if(!is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		$this->normalize();
		
		//add hidden system values
		$this->creation_date = time();
		$this->created_by = $CI->mcbsb->user->id;
		$this->creator = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
				
		return parent::create();
	}

	public function read() {
		
		if(is_null($this->obj_ID_value)) return false;
		
		$this->_config['never_display_fields'] = array();
						
		return parent::read();
	}
		
	public function update() {
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		$this->normalize();
		
		//add hidden system values
		$this->update_date = time();
		$this->updated_by = $CI->mcbsb->user->id;
		$this->editor = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		return parent::update();
	}	
	
	public function contact_tab($contact){
	
		if(!is_object($contact)) return false;
	
		$CI = &get_instance();
		switch ($contact->objName) {
			case 'person':
				$CI->session->set_userdata('contact_id',$contact->uid);
				$CI->session->set_userdata('contact_id_key','uid');
				$this->contact_id = $contact->uid;
				$this->contact_id_key = 'uid';
				$this->contact_name = $contact->cn;				
			break;
			
			case 'organization':
				$CI->session->set_userdata('contact_id',$contact->oid);
				$CI->session->set_userdata('contact_id_key','oid');
				$this->contact_id = $contact->oid;
				$this->contact_id_key = 'oid';
				$this->contact_name = $contact->o;				
			break;			
		}
		
		$data = array();
		$return = array();		
		$return = modules::run('/assets/assets/get_by_contact');
		$CI->session->unset_userdata('contact_id');
		$CI->session->unset_userdata('contact_id_key');

		
		$return['buttons'] = array();
		$return['buttons'][] = $this->magic_button('create');
		
		//looks in the config to see if there are more modules to load and then get the magic buttons for each of them
		if($modules_to_load = $CI->config->item('asset_modules_to_load')){
			
			foreach ($modules_to_load as $module_to_load){
				
				if(strtolower($module_to_load) != strtolower(get_class($this))){

					$CI->load->model($this->module_folder . '/'. $module_to_load , $module_to_load);
					$CI->$module_to_load->contact_id = $this->contact_id;
					$CI->$module_to_load->contact_id_key = $this->contact_id_key;
					$CI->$module_to_load->contact_name = $this->contact_name;
					$return['buttons'][] = $CI->$module_to_load->magic_button('create');
				
				} 
			}
		}
						
		return $return;
		
	}
	
/*	
	public function delete() {
		if(is_null($this->obj_ID_value)) return false;
		if(!$this->read()) return false;
		return parent::delete();	
	}
*/
	
	public function magic_button($type = 'create'){
		
		$tmp = array();
		
		switch ($type) {
			
			case 'create':
					
				$tmp['form_title'] = 'New asset';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$tmp['procedure'] = 'post_to_ajax';
				$button_label = 'Add asset';
				$button_id = 'add_asset';
				
				$but = array('category','contact_id','contact_id_key','contact_name');
				$this->clean($but);
				
				$this->reset_obj_config();
				
			break;
			
			case 'edit':
				$tmp['form_title'] = 'Edit asset';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_asset';
				$tmp['procedure'] = 'post_to_ajax';				
				$button_label = 'Edit asset';
				$button_id = 'edit_asset';
				$this->reset_obj_config();
			break;			
							
			default:
				
				return array();
				
			break;
		}
				
		
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_asset';
		
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