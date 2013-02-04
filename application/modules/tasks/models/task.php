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

		if(!parent::read()) return false;
		
		//gets the assets associated to the task
		$this->assets = $this->get_assets($this->id);

		$this->activities = $this->get_activities($this->id);
		return true;
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
	private function get_activities($id){
	
		$CI = &get_instance();
		$CI->load->model('tasks/activity','activity');
	
		$activities = array();
	
		$task = R::load('tasks',$id);
		if($bean_activities = $task->ownActivities){
				
			foreach ($bean_activities as $activity) {
	
				$CI->activity->id = $activity->id;
				if($CI->activity->read()) {
					$activities[] = $CI->activity->toArray();
				}
	
			}
				
		}
	
		return $activities;
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
	private function get_assets($id){
		
		$CI = &get_instance();
		$CI->load->model('assets/asset','asset');
		$CI->load->model('assets/home_appliance','home_appliance');
		$CI->load->model('assets/asset','digital_device');
		
		$assets = array();
		
		$task = R::load('tasks',$id);
		if($bean_assets = $task->sharedAssets){
			
			foreach ($bean_assets as $asset) {
				
				$CI->{$asset->category}->id = $asset->id;
				if($CI->{$asset->category}->read()) {
					$assets[] = $CI->{$asset->category}->toArray();
				}

			}
			
		}
		
		return $assets;
	}
	
	/**
	 * Returns all the records matching the sql select plus the assets related to each record
	 * 
	 * @access		public
	 * @param		
	 * @return		
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 1, 2013
	 */
	public function readAll($sql = null, $paginate = false, $from = 0, $results_per_page = 0){
		
		$records = parent::readAll($sql, $paginate, $from, $results_per_page);
		
		foreach ($records as $key => $record) {
			
			$records[$key]['assets'] = $this->get_assets($record['id']);
			$records[$key]['activities'] = $this->get_activities($record['id']);
		}
		
		return $records;
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
	
	public function open(){
	
		if(is_null($this->obj_ID_value)) return false;
	
		$CI = &get_instance();
	
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
				$tmp['url'] = '/' . $this->module_folder . '/ajax/change_task_status';
				$tmp['form_name'] = 'jquery_form_close_task';
				$tmp['form_method'] = 'POST';
				$tmp['form_title'] = 'Close Task';
				$tmp['procedure'] = 'close_task';
				$button_label = 'Close';
				$button_id = 'close_task';
				
				$this->reset_obj_config();
				//Do not show the following fields when closing a task
				$this->_config['never_display_fields'][] = 'task';
				$this->_config['never_display_fields'][] = 'details';
				$this->_config['never_display_fields'][] = 'start_date';
				$this->_config['never_display_fields'][] = 'due_date';
				$this->_config['never_display_fields'][] = 'urgent';
			break;
			
			case 'open':
				$tmp['url'] = '/' . $this->module_folder . '/ajax/change_task_status';
				$tmp['procedure'] = 'open_task';
				$button_label = 'Open task';
				$button_id = 'open_task';
			
				$this->reset_obj_config();
			break;			
			
			case 'edit':
				$tmp['form_name'] = 'jquery_form_edit_task';
				$tmp['form_method'] = 'POST';
				$tmp['form_title'] = 'Edit Task';
				$tmp['procedure'] = 'edit_task';
				$button_label = 'Edit';
				$button_id = 'edit_task';			
			break;
			
			case 'create':
				$tmp['form_name'] = 'jquery_form_create_task';
				$tmp['form_method'] = 'POST';				
				$tmp['form_title'] = 'New Task';
				$tmp['procedure'] = 'create_task';
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
			$tmp['url'] = '/' . $this->module_folder . '/ajax/save_task';
		}
		
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		
		$string = json_encode($tmp, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
		
		if($type == 'open'){
			
			$string = '$(this).live("click", jqueryChangeStatus(' . $string . ',"' . $tmp['url'] .'"))';
			
		} else {
			
			$tmp['form_name'] = 'jquery_form_task';
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