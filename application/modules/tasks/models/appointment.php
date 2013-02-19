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
	
		R::freeze( array($this->db_table));  //NOTE! comment this line when you develop!
		
		$this->initialize();	
	}
	
	private function fix_dates(){
		
		//TODO 1h should go in the config
		
		if(!$this->start_time) {
			$this->start_time = time() + 24*3600;
		}
		
		//converts to timestamp if it's not
		//if(!is_int($this->start_time)) $this->start_time = strtotime($this->start_time);
		
		//set due_date if left blank
		if(!$this->end_time) {
			$this->end_time = $this->start_time + 3600;
		}

		//converts to timestamp if it's not
		//if(!is_int($this->end_time)) $this->end_time = strtotime($this->end_time);
		
		//set due_date if non-sense
		if($this->end_time < $this->start_time) {
			$this->end_time = $this->start_time + 3600;
		}		
		
	}
	
	private function delete_otr(){
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI  = &get_instance();
		$CI->load->model('brm/otr','otr');
		
		$otr = new Otr();
		$otr->object_name = $this->obj_name;
		$otr->object_id = $this->obj_ID_value;
		
		return $otr->delete();		
	}
	
	private function involve(array $colleagues){
	
		if(is_null($this->obj_ID_value)) return false;
	
		//loads the otr class
		$CI  = &get_instance();	
		$CI->load->model('brm/otr','otr');
		
		//delete previous settings for this appointment
		$this->delete_otr();
		
		foreach ($colleagues as $key => $uid){
				
			$otr = new Otr();
			$otr->object_name = $this->obj_name;
			$otr->object_id = $this->obj_ID_value;
			$otr->colleague_id = $uid;
			$otr->colleague_name = $CI->mcbsb->get_colleague_name($uid);
			
			if($otr->create()) {

				if(!empty($this->tasks_id)) {
					
					$CI->load->model('tasks/task','task');
					$task = new Task();
					$task->id = $this->tasks_id;
					if($task->read()){
						
						$colleagues_involved_in_this_task = array();
						foreach ($task->get_people_involved() as $key => $person) {
							$colleagues_involved_in_this_task[] = $person['colleague_id'];
						}
						
						//adds all the people involved in the appointment to the task connected to the appointment
						if(!in_array($uid,$colleagues_involved_in_this_task)){
						
							$otr = new Otr();
						
							$otr->object_name = $task->obj_name;
							$otr->object_id = $this->tasks_id;
							$otr->colleague_id = $uid;
							$otr->colleague_name = $CI->mcbsb->get_colleague_name($uid);
							$otr->create();
						}
					}
											
				}
								
			} else {
				return false;
			}
		}
	
		return true;
	}	
	
	public function create(array $colleagues = null) {
		
		if(!is_null($this->obj_ID_value)) return false;
		if(!is_null($colleagues) && !is_array($colleagues)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->creation_date = time();
		$this->created_by = $CI->mcbsb->user->id;
		$this->creator = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		$this->fix_dates();
		
		unset($this->team);
		
		if(parent::create()) {
			
			if(!is_null($colleagues)) {
				
				$this->involve($colleagues);
				
				//Creates Google appointments
				if($CI->mcbsb->is_module_enabled('Google')) {
						
					$CI->load->model('google/goo','goo');
							
					$CI->goo->create_calendar_event($colleagues,$this);
					
				}				
			}
			
			return $this->obj_ID_value;
		}
		
		return false;
	}

	/**
	 * Reads and returns an appointment
	 * 
	 * @access		public
	 * @param		string $limited_return			If true it doesn't return the  fields included in "never_display_fields"
	 * @param		string $return_relationships	If true returns also the relationships related to the appointment
	 * @return		
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 6, 2013
	 */
	public function read($limited_return = false, $return_relationships = true) {
		
		if(is_null($this->obj_ID_value)) return false;
		
		if(!$limited_return) $this->_config['never_display_fields'] = array();		
				
		if(! parent::read()) return false;
		
		//converts timestamp in a human readable format so that the js form fields will be displayed correctly
		$this->start_time = date('Y-m-d H:i', $this->start_time);
		$this->end_time = date('Y-m-d H:i', $this->end_time);
		
		if($return_relationships){
			
			$this->involved = $this->get_people_involved();
			
		}
		return true;
	}
		
	public function update(array $colleagues = null) {
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->update_date = time();
		$this->updated_by = $CI->mcbsb->user->id;
		$this->editor = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
				
		//$this->fix_dates();
		unset($this->team);
		
		if(parent::update()) {
			
			if(!is_null($colleagues)) {
				
				$this->involve($colleagues);

				//Updates Google appointments
				if($CI->mcbsb->is_module_enabled('Google')) {
				
					$CI->load->model('google/goo','goo');

					$CI->goo->update_calendar_event($colleagues,$this);
						
				}				
			}
						
			return $this->obj_ID_value;
		}
		
		return false;
	}	
	
	
	public function delete() {
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//Updates Google appointments
		if($CI->mcbsb->is_module_enabled('Google')) {
		
			$CI->load->model('google/goo','goo');
		
			$CI->goo->delete_calendar_event($this);
		
		}		
		
		$this->delete_otr();
		
		return parent::delete();
	}


	public function magic_button($type = 'create'){
		
		$form_parameters = array();
		$button_properties = array();
		$js_function = 'jqueryForm';
		$ajax_url = '/' . $this->module_folder . '/ajax/getForm';
				
		switch ($type) {

			case 'create_appointment':
				$form_parameters['url'] = '/' . $this->module_folder. '/ajax/save_appointment';
				$form_parameters['form_name'] = 'jquery_form_create_appointment';
				$form_parameters['form_title'] = 'New appointment';
				$form_parameters['procedure'] = 'create_appointment';
			
				$button_properties['label'] = 'Add appointment';
				$button_properties['id'] = 'create_appointment';
			break;
					
			
			case 'delete_appointment':
				$form_parameters['url'] = '/' . $this->module_folder. '/ajax/delete_appointment';  //TODO this should not be necessary
				$form_parameters['form_name'] = 'jquery_form_create_appointment';
				$form_parameters['form_title'] = 'Edit appointment';
				$form_parameters['procedure'] = 'delete_appointment';
				
				$button_properties['label'] = 'Delete';
				$button_properties['id'] = 'delete_appointment';

				$js_function = 'jqueryDelete';
				$ajax_url = '/' . $this->module_folder . '/ajax/delete_appointment';
			break;
			
			
			case 'edit_appointment':
				$form_parameters['url'] = '/' . $this->module_folder. '/ajax/save_appointment';
				$form_parameters['form_name'] = 'jquery_form_edit_appointment';
				$form_parameters['form_title'] = 'Edit appointment';
				$form_parameters['procedure'] = 'edit_appointment';
			
				$button_properties['label'] = 'Edit';
				$button_properties['id'] = 'edit_appointment';
			break;	
						
			default:
				return array();
			break;
		}
		
		return $this->make_magic_button($button_properties, $form_parameters, $js_function, $ajax_url);
	}
	
	
	public function toJson(){
	
		$return = json_decode(parent::toJson());
	
		$CI = &get_instance();
		
		if(isset($this->involved) && count($this->involved) > 0) {
			$return->involved = array();
			
			foreach ($this->involved as $item) {
				$return->involved[] = $item['colleague_id'];
			}
		}
		
		return json_encode($return);
	}
}