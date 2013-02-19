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
	//public $ownAssets = null;
	
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
	
	public function create(array $assets = null) {
		
		if(!is_null($this->obj_ID_value)) return false;
		if(!is_null($assets) && !is_array($assets)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->creation_date = time();
		$this->created_by = $CI->mcbsb->user->id;
		$this->creator = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		$this->fix_dates();
				
		if(parent::create()) {

			$this->auto_involve();
			
			if(!is_null($assets)) $this->associate_asset($assets);
			
			return $this->obj_ID_value;
		}
		
		return false;		
	}

	private function associate_asset(array $assets){
		
		if(!is_array($assets)) return false;
		
		//if there are Assets associated to the tasks this code saves the many-to-many relationship between assets and tasks
		$task = R::load($this->db_table,$this->obj_ID_value);
		
		//removes all the relationships previously set
		$task->sharedAssets = array();
		R::store($task);
		
		foreach ($assets as $key => $asset_id) {
			
			$asset = R::load('assets',$asset_id);
			$task->sharedAssets[] = $asset;
			
		}
		
		return R::store($task);
		
		//TODO should I update contact's LDAP attribute lastAssignment?
	}
	
	public function read($return_relationships = true) {
		
		if(is_null($this->obj_ID_value)) return false;

		$this->_config['never_display_fields'] = array();	

		if(!parent::read()) return false;
		
		if($return_relationships){
				
			//gets colleagues involved
			$this->involved = $this->get_people_involved();
		
			//gets the assets associated to the task
			$this->assets = $this->get_assets();
	
			//gets the appointments associated to the task
			$this->appointments = $this->get_appointments(false);
	
			//gets the activities associated to the task
			$this->activities = $this->get_activities(false);

			$CI = &get_instance();
			$this->show_add_file_button = false;
			if($CI->mcbsb->is_module_enabled('google')){
				$this->show_add_file_button = true;
				//gets files associated to the task
				$this->files = $this->get_files();
			}
							
			$this->get_summary();
		}
		
		return true;
	}

	
	/**
	 * Retrieves the Assets associated to the task and returns them as an array
	 *
	 * @access		public
	 * @param		int $id		Task id
	 * @return		array		Array containing the Asset objects found
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 1, 2013
	 */
	public function get_assets($return_array = true){
	
		$CI = &get_instance();
		
		$CI->load->model('assets/asset','asset');
		$CI->load->model('assets/home_appliance','home_appliance');
		$CI->load->model('assets/digital_device','digital_device');
	
		$assets = array();
	
		$task = R::load('tasks',$this->id);
		if($bean_assets = $task->sharedAssets){
				
			foreach ($bean_assets as $bean_asset) {
				
				$obj = new $bean_asset->category();
				$obj->id = $bean_asset->id;
				
				if($obj->read()) {
					
					if($return_array) {
						
						$assets[] = $obj->toArray();
						
					} else {
						
						$assets[] = $obj;
						
					}
					
				}
	
			}
				
		}
	
		return $assets;
	}	
	
	/**
	 * Retrieves the Appointments associated to the task and returns them as an array
	 *
	 * @access		public
	 * @param		int $id		Task id
	 * @return		array		Array containing the Appointment objects found
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 6, 2013
	 */
	public function get_appointments($return_array = true){

		$CI = &get_instance();
		
		$CI->load->model('tasks/appointment','appointment');
		
		$appointments = array();
	
		$task = R::load('tasks',$this->id);
		if($bean_appointments = $task->ownAppointments){
	
			foreach ($bean_appointments as $bean_appointment) {
				
				$appointment = new Appointment();
				$appointment->id = $bean_appointment->id;
				
				if($appointment->read(true,true)) {
					
					if($return_array){

						$appointments[] = $appointment->toArray();
						
					} else {
						
						$appointments[] = $appointment;
						
					}
				}
	
			}
	
		}
	
		return $appointments;
	}	
	
	/**
	 * Retrieves the Activities associated to the task and returns them as an array
	 *
	 * @access		public
	 * @param		int $id		Task id
	 * @return		array		Array containing the Activity objects found
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 1, 2013
	 */
	public function get_activities($return_array = true){
		
		$CI = &get_instance();
	
		$CI->load->model('tasks/activity','activity');
	
		$activities = array();
	
		$task = R::load('tasks',$this->id);
		if($bean_activities = $task->ownActivities){
				
			foreach ($bean_activities as $bean_activity) {
				
				$activity = new Activity();
				$activity->id = $bean_activity->id;
				
				if($activity->read(true)) {
					
					if($return_array){
						
						$activities[] = $activity->toArray();
						
					} else {
						
						$activities[] = $activity;
						
					}
				}
	
			}
				
		}
	
		return $activities;
	}
	
	
	public function get_files($return_array = true){
		
		$CI = &get_instance();
		$CI->load->model('google/ogr','ogr');
		
		$ogr = new Ogr();
		$sql = 'select * from ' . $ogr->db_table . ' where object_name="' . $this->obj_name . '" and object_id="' . $this->obj_ID_value . '" and google_resource_name="gdrive"';
		return $ogr->readAll($sql);
	}
	
	public function get_summary(){
		
		$this->summary = array();
		
		if(isset($this->activities)) $this->summary['activities'] = count($this->activities);
		if(isset($this->appointments)) $this->summary['appointments'] = count($this->appointments);
		
		if($this->summary['activities'] == 0 && $this->summary['appointments'] == 0) {
			unset($this->summary);
			return;
		}
		
		
		$this->summary['mileage'] = 0;
		$this->summary['worked_hours'] = 0;

		foreach ($this->activities as $activity) {
			if(isset($activity->mileage)) $this->summary['mileage'] = $this->summary['mileage'] + $activity->mileage;
			if(isset($activity->duration)) $this->summary['worked_hours'] = $this->summary['worked_hours'] + $activity->duration;
		}
		
		if(isset($this->hours_budget) && $this->hours_budget > 0) $this->summary['hours_left'] = $this->hours_budget - $this->summary['worked_hours'];		
	}

	public function update(array $assets = null) {
		
		if(is_null($this->obj_ID_value)) return false;
		if(!is_null($assets) && !is_array($assets)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->update_date = time();
		$this->updated_by = $CI->mcbsb->user->id;
		$this->editor = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
				
		$this->fix_dates();
		
		if(parent::update()) {
			
			$this->auto_involve();
			
			if(!is_null($assets)) $this->associate_asset($assets);
			
			return $this->obj_ID_value;
				
		}
		
		return false;		
	}	
	
	public function close(){
		
		if(is_null($this->obj_ID_value)) return false;
		
		$this->fix_dates();
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->complete_date = date('Y-m-d');
		$this->completed_by = $CI->mcbsb->user->id;
		$this->completionist = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		return parent::update();
	}
	
	public function open(){
	
		if(is_null($this->obj_ID_value)) return false;
	
		$this->fix_dates();
	
		//clear hidden system values
		$this->complete_date = null;
		$this->completed_by = null;
		$this->completionist = null;
		$this->endnote = null;
	
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

	public function auto_involve(){
		
		//none will be automatically involved if someone is already involved. I give priority to user's decisions
		$this->involved = $this->get_people_involved();
		if(count($this->involved) > 0) return false;
		
		//loads the route class
		$CI  = &get_instance();
		
		if(!$CI->mcbsb->is_module_enabled('on_site')) return false;
		
		$CI->load->model('on_site/route','route');
		
		
		//gets the route name by passing the selected city
		if(!$route = $CI->route->get_route($this->city)) return false;
		
		//looks for a route match among people
		$CI->load->model('contact/mdl_contact','contact');
		$CI->load->model('contact/mdl_person','person');
		
		$input = array('filter' => '(routes=' . $route . ')');
		$return = $CI->person->get($input);
		
		if($return['status']['status_code'] != 200) return false;
		
		$CI->load->model('brm/otr','otr');
		foreach ($return['data'] as $key => $person){
			
			//if a team member matches a route then involve him
			$otr = new Otr();
			
			$otr->object_name = $this->obj_name;
			$otr->object_id = $this->id;
			$otr->colleague_id = $person['uid'][0];
			$otr->colleague_name = $person['cn'][0];
			if(!$otr->create()) return false;
		}
		
		return true;	
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
		
		$form_parameters = array();
		$button_properties = array();
		$js_function = 'jqueryForm';
		$ajax_url = '/' . $this->module_folder . '/ajax/getForm';
		
		switch ($type) {
			
			case 'close':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/change_task_status';
				$form_parameters['form_name'] = 'jquery_form_close_task';
				$form_parameters['form_title'] = 'Close Task';
				$form_parameters['procedure'] = 'close_task';
				
				$button_properties['label'] = 'Close';
				$button_properties['id'] = 'close_task';
				
				$this->reset_obj_config();
				
				//Do not show the following fields when closing a task
				$this->_config['never_display_fields'][] = 'task';
				$this->_config['never_display_fields'][] = 'details';
				$this->_config['never_display_fields'][] = 'start_date';
				$this->_config['never_display_fields'][] = 'due_date';
				$this->_config['never_display_fields'][] = 'urgent';
				$this->_config['never_display_fields'][] = 'budget';
				$this->_config['never_display_fields'][] = 'hours_budget';
			break;
			
			case 'create':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_task';
				$form_parameters['form_name'] = 'jquery_form_create_task';
				$form_parameters['form_title'] = 'New Task';
				$form_parameters['procedure'] = 'create_task';
			
				$button_properties['label'] = 'Create task';
				$button_properties['id'] = 'create_task';
			
				$this->reset_obj_config();
			
				//Do not show the endnote textarea when creating or editing
				$this->_config['never_display_fields'][] = 'endnote';
			break;
			
			case 'edit':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_task';
				$form_parameters['form_name'] = 'jquery_form_edit_task';
				$form_parameters['form_title'] = 'Edit Task';
				$form_parameters['procedure'] = 'edit_task';
			
				$button_properties['label'] = 'Edit';
				$button_properties['id'] = 'edit_task';
			
				$this->reset_obj_config();
			
				//Do not show the endnote textarea when creating or editing
				$this->_config['never_display_fields'][] = 'endnote';
			break;			
							
			case 'open':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/change_task_status';
				$form_parameters['procedure'] = 'open_task';
				
				$button_properties['label'] = 'Open task';
				$button_properties['id'] = 'open_task';
			
				$this->reset_obj_config();
				
				$js_function = 'jqueryChangeStatus';
				$ajax_url = $form_parameters['url'];
			break;			
			
			default:
				return array();
			break;
		}

		return $this->make_magic_button($button_properties, $form_parameters, $js_function, $ajax_url);
	}
	
	public function toJson(){
		
		$return = json_decode(parent::toJson());

		//adds the assets already associated with the task
		if(isset($this->assets) && count($this->assets)>0){

			$return->assets = array();
			
			foreach ($this->assets as $asset){
				$return->assets[] = $asset['id'];
			}
			
		}
		
		return json_encode($return);
	}
}