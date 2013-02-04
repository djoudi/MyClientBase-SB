<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Appointment extends Rb_Db_Obj
{
	const table = 'appointments';
	protected $module_folder = null;
	protected $team = array();
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'tasks';
	
		//R::freeze( array($this->db_table));  //NOTE! comment this line when you develop!
		
		$this->initialize();	
	}
	
	private function fix_dates(){
		
		//TODO 1h should go in the config
		
		if(!$this->start_time) $this->start_time = time() + 24*3600;
		
		//set due_date if left blank
		if(!$this->end_time) {
			$this->end_time = $this->start_time + 3600;
		}
		
		//set due_date if non-sense
		if($this->end_time < $this->start_time) {
			$this->end_time = $this->start_time + 3600;
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
		
		unset($this->team);
				
		return parent::create();
	}

	public function read($limited_return=false) {
		
		if(is_null($this->obj_ID_value)) return false;
		
		if(!$limited_return) $this->_config['never_display_fields'] = array();		
				
		if(! parent::read()) return false;
		
		//converts timestamp in a human readable format so that the js form fields will be displayed correctly
		$this->start_time = date('Y-m-d H:i', $this->start_time);
		$this->end_time = date('Y-m-d H:i', $this->end_time);
		
		return true;
	}
		
	public function update() {
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->update_date = time();
		$this->updated_by = $CI->mcbsb->user->id;
		$this->editor = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
				
		//$this->fix_dates();
		unset($this->team);
		
		return parent::update();
	}	
	
	
	public function delete() {
		if(is_null($this->obj_ID_value)) return false;
		
		return parent::delete();	
	}


	public function magic_button($type = 'create'){
		
		$tmp = array();
		
		switch ($type) {

			case 'delete_appointment':
				$tmp['procedure'] = 'delete_appointment';
				$button_label = 'Delete';
				$button_id = 'delete_appointment';
				$tmp['url'] = '/' . $this->module_folder. '/ajax/delete_appointment';  //TODO this should not be necessary
			break;
			
			case 'edit_appointment':
				$tmp['form_title'] = 'Edit appointment';
				$button_label = 'Edit appointment';
				$button_id = 'edit_appointment';
				$tmp['procedure'] = 'edit_appointment';
				$tmp['url'] = '/' . $this->module_folder. '/ajax/save_appointment';
			break;
			
			case 'create_appointment':
				$tmp['form_title'] = 'New appointment';
				$button_label = 'Create appointment';
				$button_id = 'create_appointment';
				$tmp['procedure'] = 'create_appointment';
				$tmp['url'] = '/' . $this->module_folder. '/ajax/save_appointment';
			break;
			
			//this is a special case for an appointment attached to a task. The "what" field will be hidden
			case 'create_appointment_for_task':
				$tmp['form_title'] = 'New appointment';
				$button_label = 'Add appointment';
				$button_id = '';
				$tmp['procedure'] = 'create_appointment_for_task';
				$tmp['url'] = '/' . $this->module_folder. '/ajax/save_appointment_for_task';
			break;			

			//this is a special case for an appointment attached to a task. The "what" field will be hidden
			case 'edit_appointment_for_task':
				$tmp['form_title'] = 'Edit appointment';
				$button_label = 'Edit';
				$button_id = '';
				$tmp['procedure'] = 'edit_appointment_for_task';
				$tmp['url'] = '/' . $this->module_folder. '/ajax/save_appointment_for_task';
			break;
						
			default:
				return array();
			break;
		}
		
		//common procedure for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_appointment';
		
		
		$string = json_encode($tmp, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
		
		if($type == 'delete_appointment'){
			$string = '$(this).live("click", jqueryDelete(' . $string . ',"/' . $this->module_folder . '/ajax/delete_appointment"))';
		} else {
			$string = '$(this).live("click", jqueryForm(' . $string . ',"/' . $this->module_folder . '/ajax/getForm"))';
		}
		
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