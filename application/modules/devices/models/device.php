<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Device extends Rb_Db_Obj
{
	const table = 'devices';
	protected $module_folder = null;
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'devices';
		
		//R::freeze( array($this->db_table));  //NOTE! comment this line when you develop!
		
		$this->initialize();
	}
	
	public function create() {
		
		if(!is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
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
		$return = modules::run('/devices/devices/index');
		$CI->session->unset_userdata('contact_id');
		$CI->session->unset_userdata('contact_id_key');
		
		$return['buttons'] = array();
		$return['buttons'][] = $this->magic_button('create');
						
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

								
				$tmp['form_title'] = 'New device';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_device';
				$tmp['procedure'] = 'automated_form';
				$button_label = 'Create device';
				$button_id = 'create_device';
				$this->reset_obj_config();
			break;
			
			default:
				return array();
			break;
		}
				
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_device';
		
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