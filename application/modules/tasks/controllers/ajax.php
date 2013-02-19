<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model('tasks/task','task');
		
	}
	
	/**
	 * This method extends Ajax_Controller getForm method with the aim to includes more parameters for the form that will be build by jquery.
	 * 
	 * @access		public
	 * @param		none
	 * @return		parent::getForm($params)
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 12, 2013
	 */
	public function getForm(){
	
		$params = $this->input->post('params');
		if(!is_array($params)){
			$this->message = 'Ajax: input parameters are missing';
			return false;
		}
		
		extract($params);

		//injection of additional parameters
		if(isset($procedure) && isset($obj)){
			
			$object = json_decode($obj);
			
			//accordingly to the specified procedure, it attach to the object additional information but not strictly related to the object
			//Ex. contacts location are attached to the appointment to quickly set the appointment "where" attribute
			switch ($procedure) {
				
				case 'create_task':
				case 'edit_task':
		
					//get contact's locations so that they can be listed in the form
					if(!empty($object->contact_id) && !empty($object->contact_id_key)){
										
						if($contact_locs = $this->get_contact_locations($object->contact_id_key, $object->contact_id)){
					
							$object->contact_locs = json_encode($contact_locs);
							
							$object->contact_locs_input_type = 'radio';
						}
					}					
					
					
					//gets the assets owned by the contact so that they can be listed in the form
					$CI = &get_instance();
					$CI->load->model('assets/asset','asset');
					
					$sql = 'select * from assets where contact_id_key="' . $object->contact_id_key . '" and contact_id="' . $object->contact_id . '"';
					$assets = $this->asset->readAll($sql,false);
					
					if(count($assets)>0){
						$contact_assets = array();
						foreach ($assets as $key => $asset) {
							$contact_assets[] = array(
													'id' => $asset['id'],
													'type' => $asset['type'],
													'brand' => $asset['brand'],
													'model' => $asset['model'],
													'serial' => $asset['serial']
												);
								
						}						
						
						$object->contact_assets = json_encode($contact_assets);						
					}
					
					$params['obj'] = json_encode($object, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
				break;
				
				case 'create_appointment':
				case 'edit_appointment':
					
					
					//get the team so that it can be listed in the form
					$CI = &get_instance();
					$object->team = json_encode($CI->mcbsb->user->team);
					
					
					
					//get contact's locations so that they can be listed in the form
					if(!empty($object->tasks_id)){
						
						$this->task->id = $object->tasks_id;
						if(!$this->task->read()) return parent::getForm($params);

						//if a location was already set for the task then inherits it
						if(!empty($this->task->where)){
							$object->where = $this->task->where;	
						}
						
						//attaches contacts locations to the form
						if($contact_locs = $this->get_contact_locations($this->task->contact_id_key, $this->task->contact_id)){

							$object->contact_locs = json_encode($contact_locs);
							
							$object->contact_locs_input_type = 'radio';
							
							$params['obj'] = json_encode($object, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
						}
					}
				break;
				
			}
		}

		parent::getForm($params);	
	}
	
	
	/**
	 * Common checks for delete activity and delete appointment
	 * 
	 * @access		public
	 * @param		
	 * @return		
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 12, 2013
	 */
	private function delete_checks($obj_name) {
		
		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';
		
		$post = $this->input->post('params');
		
		if(!isset($post['obj'])){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();
		}
		
		$obj = json_decode($post['obj']);
		if(!$obj->id || empty($obj->id)){
			$this->status = false;
			$this->message = t(ucwords($obj_name)) . ' ' . t('ID not found');
			exit();
		}
		
		return $obj;
	}
	
	/**
	 * Deletes an activity and its relationships
	 *
	 * @access		public
	 * @param		none
	 * @return		none
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 12, 2013
	 */	
	public function delete_activity(){
		
		$obj = $this->delete_checks('activity');
		
		$this->load->model('tasks/activity','activity');
		
		$activity = new Activity();
		$activity->id = $obj->id;

		if($activity->delete()){
			$this->status = false;
			$this->message = t('Activity can not be deleted') . ': #' . $obj->id;
			exit();	
		} 

		$this->status = true;
		$this->message = t('Activity successfully delete') . ': #' . $obj->id;
		
	}
	
	/**
	 * Deletes a task and its relationships
	 * 
	 * @access		public
	 * @param		none
	 * @return		none
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 12, 2013
	 */
	public function delete_appointment(){
		
		$obj = $this->delete_checks('activity');
		
		$this->load->model('tasks/appointment','appointment');
		
		$appointment = new Appointment();
		$appointment->id = $obj->id;
		
		if(!$appointment->delete()){
			$this->status = false;
			$this->message = t('Appointment can not be deleted');
			exit();			
		}
		
		$this->message = 'Appointment successfully deleted';
		$this->status = true;

	}


	/**
	 * Opens or closes a task. If the task has to be closed then it opens a form to allow the addition of an endnote for the task
	 * 
	 * @access		public
	 * @param		none
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 12, 2013
	 */
	public function change_task_status(){
	
		if(!$post = $this->input->post()){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();			
		}
		
		//$post['params'] is set when the procedure is "open task"
		if(empty($post['params'])) {
			
			//this returns true when the procedure is "close task" 
			if(!$post = $this->get_post_values()){
				$this->status = false;
				$this->message = t('POST id is empty') . ' ' .  t('or is missing field') . ' : endnote';
				exit();
			}
		}		
		
		$CI = &get_instance();
		$CI->load->model('tasks/task','task');
		
		if(isset($post['params']['procedure'])) {
			$procedure = 'open_task';
			$object = json_decode($post['params']['obj']);
			$CI->task->id = $object->id;
		}
		
		if(isset($post['endnote'])){
			$procedure = 'close_task';
			if(empty($post['endnote'])) $post['endnote'] = '';
			$CI->task->id = $post['id'];
		}
	
		if($CI->task->read()){
			
			switch ($procedure) {
				
				case 'close_task':
					$CI->task->endnote = $post['endnote'];
					if($CI->task->close()){
 						$this->status = true;
 						$this->procedure = 'refresh_page';
 						$this->message  = t('The task has been closed');
 						exit();
					}
					
				break;
				
				case 'open_task':
					
					if($CI->task->open()){
						$this->status = true;
						$this->message  = t('The task has been opened');
						exit();
					}
									
				break;
			}
		}

		$this->status = false;
		$this->message = t('Task status not updated');
	}	
	
	
	public function detach_file_from_task(){
		
		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';
		
		$post = $this->input->post();

		if(!$post || !isset($post['json'])){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();
		}		
		
		$post = $post['json'];
		
		if(!isset($post['gfile_id'])){
			$this->status = false;
			$this->message = t('No file specified');
			exit();
		}
		
		$this->load->model('google/ogr','ogr');
		
		$ogr = new Ogr();
		$ogr->id = $post['gfile_id'];
		$ogr->delete();
		
		$this->status = true;
	
	}
	
	public function attach_file_to_task(){

		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';
		
		$post = $this->input->post();
		if(!$post || !isset($post['json'])){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();
		}		
		
		$post = $post['json'];
		
		if(!isset($post['task_id']) || !isset($post['google_response'])){
			$this->status = false;
			$this->message = t('No file or task specified');
			exit();
		}
		
		$this->task->id = $post['task_id'];
		
		if(!$this->task->read()) {
			$this->status = false;
			$this->message = t('Task not found');
			exit();
		}
	
		$this->load->model('google/ogr','ogr');
		
		foreach ($post['google_response'] as $key => $gfile) {
			$ogr = new Ogr();
			$ogr->google_id = $gfile['id'];
			$ogr->google_name = $gfile['name'];
			$ogr->google_mimeType = $gfile['mimeType'];
			$ogr->google_url = $gfile['url'];
			$ogr->google_icon_url = $gfile['iconUrl'];
			$ogr->google_resource_name = 'gdrive'; //$gfile['serviceId'];
			//$ogr->google_resource_id = '';
			$ogr->object_name = $this->task->obj_name;
			$ogr->object_id = $this->task->id;
			
			$ogr->create();
		}
		
		$this->status = true;
	}
	
	public function save_task(){
				
		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';
		
		if(!$post = $this->get_post_values()){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();			
		}
		
			
		if(!isset($post['task'])) {
			$this->status = false;
			$this->message = t('POST task is empty');
			exit();
		}
		
		foreach ($post as $attribute => $value) {
			if(empty($post['id']) && $attribute == 'id'){
				continue;
			} else {
				if($attribute) $this->task->$attribute = $value;
			}
		}
		
		$assets = array();
		foreach ($post as $attribute => $value) {		
			
			if(strstr($attribute, 'asset_')) $assets[] = $value;		
		}
	
		//this is a radio so I don't need to cycle values
		if(isset($post['contact_locs'])){
			$this->task->city = strtolower(strstr($post['contact_locs'], ':', true));
			$this->task->where = trim(strstr($post['contact_locs'], ':', false),':');
		}		
		
		if(empty($post['id'])) {
			
			if( $id = $this->task->create($assets)) {

				$this->message  = t('Task successfully created.') . ' Task: #' . $id;
			
			} else {

				$this->status = false;
				$this->message = t('Task has not been created');
				exit();
				
			}
							
		} else {
			
			if($id = $this->task->update($assets)) {
				
				$this->message  = t('Task successfully updated.') . ' Task: #' . $id;;
									
			} else {
				
				$this->status = false;
				$this->message = t('Task has not been updated');
				exit();
					
			}

		}
		
		$this->status = true;
		
	}
	
	
	public function save_appointment(){
	
		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';
	
		if(!$post = $this->get_post_values()){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();
		}
	
		//I want to leave the possibility to create an appointment indipendent from a task, so I don't check for $post['tasks_id']
			
		$this->load->model('tasks/appointment','appointment');
	
		//default
		$this->appointment->creator_is_owner = 0; //common checkbox issue ...
		
		foreach ($post as $attribute => $value) {
				
			if($attribute == 'id'){
				
				if(empty($value)) {
					
					continue;
					
				} else {
					
					$this->appointment->id = $value;
					
				}
				
			} else {
				
				if(preg_match('/_time$/',$attribute)){

					//converts the start_time and end_time into unix timestamp
					$this->appointment->$attribute = strtotime($value);
						
				} else {
						
					//the attribute where will be always rewritten with the content of contact_locs (radio boxes)
					if($attribute == 'where' && !empty($post['contact_locs'])) continue;
						
					if($attribute == 'contact_locs'){
	
						$this->appointment->where = trim(strstr($post[$attribute], ':', false),':');
	
					} else {
	
						$this->appointment->$attribute = $value;
					}
				}
			}
		}
		
		$colleagues = array();
		if(isset($post['colleagues'])){
		
			if(!is_array($post['colleagues'])){
				
				$colleagues[] = $post['colleagues'];
				
			} else {
				
				$colleagues = $post['colleagues'];
				
			}
		}		
	
		if(empty($post['id'])) {
				
			if($id = $this->appointment->create($colleagues)){
	
				$this->message = t('Appointment successfully created') .': #' . $id;
	
			} else {
	
				$this->status = false;
				$this->message = t('Appointment has not been created');
				exit();
	
			}
				
		} else {
				
			if($id = $this->appointment->update($colleagues)){
	
				$this->message = t('Appointment successfully updated') .': #' . $id;
	
			} else {
	
	
				$this->status = false;
				$this->message = t('Appointment has not been updated');
				exit();
			}
		}
	
		$this->status = true;
	
	}
	
	
	public function save_activity(){
		
		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';
		
		if(!$post = $this->get_post_values()){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();			
		}
				
		$this->load->model('tasks/activity','activity');
		
		if(!$post['activity']) {
			$this->status = false;
			$this->message = t('A mandatory field is missing.') . t('Field') . ': activity';
			exit();
		}
		
	
		//binds POST values with the attributes of the object
		foreach ($post as $attribute => $value) {
	
			if(empty($post['id']) && $attribute == 'id'){
				
				continue; //it's a new record. There is no need to set the id.
				
			} else {
				
				$this->activity->$attribute = $value;
			}
				
		}
		
		if(!isset($post['billable'])) $this->activity->billable = 0;
		$a = $this->activity;
		
		if(empty($post['id'])) {
				
			if($id = $this->activity->create()){
	
				$this->message  = t('Activity successfully created') . ' : #' . $id;
	
			} else {
	
				$this->status = false;
				$this->message = t('Activity has not been created');
				exit();
	
			}
				
		} else {
				
			if($id = $this->activity->update()){
				
				$this->message  = t('Activity successfully updated.') . ' : #' . $id;
				
			} else {
	
				$this->status = false;
				$this->message = t('Activity has not been updated');
				exit();
	
			}
		}

		$this->status = true;		
	
	}
	
}