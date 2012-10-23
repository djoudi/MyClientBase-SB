<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Task extends Rb_Db_Obj
{
	const table = 'tasks';
	protected $module_folder = null;
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'tasks';
		
		//R::freeze( array($this->db_table));  //NOTE! comment this line when you develop!
		
		$this->initialize();	
	}
	
	private function fix_dates(){
		
		//TODO 14 should go in the config
		
		if(!$this->start_date) $this->start_date = date('Y-m-d');
		
		//set due_date if left blank
		if(!$this->due_date) {
			$this->due_date = date('Y-m-d', strtotime("$this->start_date +14 days"));
		}
		
		//set due_date if non-sense
		$duedate_unix = strtotime($this->due_date);
		$stardate_unix = strtotime($this->start_date);
		if($duedate_unix < $stardate_unix) {
			$this->due_date = date('Y-m-d', strtotime("$this->start_date +14 days"));
		}		
		
	}
	
	public function create() {
		
		if(!is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->creation_date = time();
		$this->created_by = $CI->mcbsb->user->id;
		$this->creator = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		$this->fix_dates();
				
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
				
		$this->fix_dates();
		
		return parent::update();
	}	
	
	public function close(){
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		$this->fix_dates();
		
		//add hidden system values
		$this->complete_date = date('Y-m-d');
		$this->completed_by = $CI->mcbsb->user->id;
		$this->completionist = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		return parent::update();
	}
	
	public function is_open(){
		return !$this->is_closed();
	}
	
	public function is_closed(){
		return is_null($this->complete_date) ? false : true;
	}
	
	
	public function delete() {
		//we do not delete tasks
		return false;
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
		$return = modules::run('/tasks/tasks/index');
		$CI->session->unset_userdata('contact_id');
		$CI->session->unset_userdata('contact_id_key');
		
		$return['buttons'] = array();
		$return['buttons'][] = $this->magic_button('create');
						
		return $return;
		
	}
	
	public function magic_button($type = 'create'){
		
		$tmp = array();
		
		switch ($type) {
			
			case 'close':
				$tmp['url'] = '/tasks/ajax/close_task';
				$tmp['form_title'] = 'Close Task';
				$tmp['procedure'] = 'close_task';
				$button_label = 'Close task';
				$button_id = 'close_task';
				
				$this->reset_obj_config();
				//Do not show the following fields when closing a task
				$this->_config['never_display_fields'][] = 'task';
				$this->_config['never_display_fields'][] = 'details';
				$this->_config['never_display_fields'][] = 'start_date';
				$this->_config['never_display_fields'][] = 'due_date';
				$this->_config['never_display_fields'][] = 'urgent';
			break;
			
			case 'edit':
				$tmp['form_title'] = 'Edit Task';
				$button_label = 'Edit task';
				$button_id = 'edit_task';			
			break;
			
			case 'create':
				$tmp['form_title'] = 'New Task';
				$button_label = 'Create task';
				$button_id = 'create_task';					
			break;
			
			default:
				return array();
			break;
		}
		
		//common stuff for some cases
		if($type == 'create' || $type == 'edit'){

			$this->reset_obj_config();
			//Do not show the endnote textarea when creating or editing
			$this->_config['never_display_fields'][] = 'endnote';
			$tmp['url'] = '/tasks/ajax/save_task';
			$tmp['procedure'] = 'automated_form';
		}
		
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_task';
		
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