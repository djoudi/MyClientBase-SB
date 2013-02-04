<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('tasks/task','task');
		
	}
	
	public function getForm(){
	
		if(!$params = $this->input->post('params') or !is_array($params)){
			$this->message = 'Ajax: input parameters are missing';
			return false;
		}
		
		extract($params);

		//injection of additional parameters
		if(isset($procedure) && isset($obj)){
			
			$object = json_decode($obj);
			
			switch ($procedure) {
				
				case 'create_task':
				case 'edit_task':
					//looks for the assets owned by the contact
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
						
						$params['obj'] = json_encode($object, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
					}
					
				break;
				
				case 'create_appointment_for_task':
				case 'edit_appointment_for_task':
					//get the team
					$CI = &get_instance();
					$object->team = json_encode($CI->mcbsb->user->team);
					
					//get contact's locations
					if(!empty($object->task_id)){
						
						$CI->load->model('contact/mdl_contact','contact');
						
						$this->task->id = $object->task_id;
						$this->task->read();
						
						if($this->task->contact_id_key == 'uid'){
							$CI->load->model('contact/mdl_person','person');
							$a = $CI;
							$CI->person->uid = $this->task->contact_id;
							$contact = $CI->person;
						}

						if($this->task->contact_id_key == 'oid'){
							$CI->load->model('contact/mdl_organization','organization');
							
							$CI->organization->oid = $this->task->contact_id;
							$contact = $CI->organization;
						}
						
						if(isset($contact) && $contact->get(null,false)){
							if($contact->crr->has_no_errors){
								$contact_locs = array();
								if($main = $contact->hasProperAddress()){
									$contact_locs[] = array(
															'label' => 'main', 
															'address' => $main);
								}
							}
						}
						
						if(isset($contact->locRDN)) $locs = explode(",", $contact->locRDN);
						if(isset($locs) && count($locs) > 0){
						
							$CI->load->model('contact/mdl_location','location');
						
							foreach( $locs as $locId)
							{
								$CI->location->locId = $locId;
								
								if($CI->location->get(null,false)) {
									
									$loc_description = strtolower($CI->location->locDescription);
									
									if($loc_description == 'home' || $loc_description == 'registered address') {
										continue;
									}

									$address = $CI->location->locStreet . ', ' . $CI->location->locZip . ' ' . $CI->location->locCity . ' ' . $CI->location->locState . ' ' . $CI->location->locCountry;
									$contact_locs[] = array(
															'label' => $loc_description,
															'address' => $address);
										
								}
							}
						}
						
						if(isset($contact_locs)) $object->contact_locs = json_encode($contact_locs);
					}
					
					$params['obj'] = json_encode($object, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
				break;
				
			}
		}

		parent::getForm($params);	
	}
	
	public function delete_appointment(){
		
		$post = $this->input->post('params');
		
		if(!isset($post['obj'])){
			$this->status = false;
			return false;
		}
		
		$obj = json_decode($post['obj']);
		if($obj->id){
			$this->load->model('tasks/appointment','appointment');
			$this->appointment->id = $obj->id;
			
			if($this->appointment->read()){

				//delete all the relationships between the team and the appointment
				$this->load->model('brm/otr','otr');
				$this->otr->object_id = $obj->id;
				$this->otr->object_name = 'appointment';
				
				//delete the appointment
				if($this->otr->delete()){
	
					if($this->appointment->delete()){
						$this->mcbsb->system_messages->success = 'Appointment successfully deleted';
						$this->status = true;
						return true;
					}
					
				}
				
				$this->mcbsb->system_messages->error = 'Error deleting appointment';
				$this->status = false;
				return false;
				
			}
		}
		
		$this->status = false;
		return false;
	}

	public function save_appointment_for_task(){
	
		if($post = $this->get_post_values()){
			
			$this->load->model('tasks/appointment','appointment');
	
			foreach ($post as $attribute => $value) {
				if(empty($post['id']) && $attribute == 'id'){
					continue;
				} else {
					if(preg_match('/_time$/',$attribute)){
						
						$this->appointment->$attribute = strtotime($value);
						
					} else {
						
						//the attribute where will be always rewritten with the content of contact_locs (radio boxes)
						if($attribute == 'where' && !empty($post['contact_locs'])) continue;
						
						if($attribute == 'contact_locs'){
							$post['where'] = $post[$attribute];
							$this->appointment->where = $post[$attribute];
							
						} else {
							
							$this->appointment->$attribute = $value;
						}
					}
				}
			}
			
			if(empty($post['id'])) {
				if($id = $this->appointment->create()){
					$message = 'appointment #' . $id . ' successfully created';
				} else {
					$this->mcbsb->system_messages->error = 'Error creating appointment';
					$this->status = false;
					return false;
				}
			} else {
				if($id = $this->appointment->update()){
					$message = 'appointment #' . $id . ' successfully updated';
				} else {
					$this->mcbsb->system_messages->error = 'Error creating appointment';
					$this->status = false;
					return false;
				}
			}
						
			//removes all the colleagues involved in the appointment
			$this->load->model('brm/otr','otr');
			$this->otr = new Otr();
			$this->otr->object_name = 'appointment';
			$this->otr->object_id = $id;
			$a = $this->otr->delete();
			
			if(isset($post['colleagues'])){
				
				if(!is_array($post['colleagues'])){
					$tmp = $post['colleagues'];
					$post['colleagues'] = array($tmp);
				}
				
				//finds colleagues involved in the task 
				$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $this->appointment->task_id . ' and object_name="task"';
				$involved_in_this_task = $this->otr->readAll($sql);
				$colleagues_involved_in_this_task = array();
				foreach ($involved_in_this_task as $key => $colleague){
					$colleagues_involved_in_this_task[] = $colleague['colleague_id'];
				}
				
				
				
				
				foreach ($post['colleagues'] as $key => $uid) {
					
					//adds the just selected colleagues to the appointment
					$this->otr = new Otr();
					
					$this->otr->object_name = 'appointment';
					$this->otr->object_id = $id;
					$this->otr->colleague_id = $uid;
					$this->otr->colleague_name = $this->get_colleague_name($uid);
					
					if($this->otr->create()){
						$this->mcbsb->system_messages->success = 'Successfully involved colleague ' . $this->otr->colleague_name . ' to ' . $this->otr->object_name .' #'.$this->otr->object_id ;
						
						//adds all the people involved in the appointment to the task connected to the appointment
						if(!in_array($uid,$colleagues_involved_in_this_task)){
							
							$this->otr = new Otr();
								
							$this->otr->object_name = 'task';
							$this->otr->object_id = $this->appointment->task_id;
							$this->otr->colleague_id = $uid;
							$this->otr->colleague_name = $this->get_colleague_name($uid);	
							$this->otr->create();
						}
					} else {
						$this->mcbsb->system_messages->error = 'Error involving colleague: '.$this->otr->colleague_name . 'in ' . $this->otr->object_name . ' #'.$this->otr->object_id;
						$this->status = false;
						return false;
					}
					
				}
			}
			$this->status = true;
			$this->mcbsb->system_messages->success = $message;
			$this->procedure = 'refresh_page';
			$this->focus_tab = 'tab_Tasks';
			return true;
		}	
	}
	
	private function get_colleague_name($uid){
		$team = $this->mcbsb->user->team;
		foreach ($team as $key => $colleague) {
			if($colleague['uid'] == $uid) return $colleague['name'];
		}
	
		return 'unknown';
	}	

	public function change_task_status(){
	
		if(!$post = $this->input->post()){
			$this->status = false;
			$this->message = t('POST is empty.');
			exit();			
		}
		
		//$post['params'] is set when the procedure is "open task"
		if(empty($post['params'])) {
			
			//this returns true when the procedure is "close task" 
			if(!$post = $this->get_post_values()){
				$this->status = false;
				$this->message = t('POST id is empty') . ' ' .  t('or is missing field' . ' : endnote');
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
	
	
	public function save_task(){
				
		if(!$post = $this->get_post_values()){
			$this->status = false;
			$this->message = t('POST is empty.');
			exit();			
		}
		
			
		if(!isset($post['task'])) {
			//TODO this should return something to js
			$this->status = false;
			$this->message = t('POST task is empty');
			exit();
		}
		
		foreach ($post as $attribute => $value) {
			if(empty($post['id']) && $attribute == 'id'){
				continue;
			} else {
				$this->task->$attribute = $value;
			}
		}
		
		if(empty($post['id'])) {
			
			if( $id = $this->task->create()) {

				$this->message  = t('Task successfully created.') . ' Task: #' . $id;
			
			} else {

				$this->status = false;
				$this->message = t('Record not saved.') . t('Record') . ': task';
				exit();
				
			}
							
		} else {
			
			if($id = $this->task->update()) {
				
				$this->message  = t('Task successfully updated.') . ' Task: #' . $id;;
									
			} else {
				
				$this->status = false;
				$this->message = t('Record not updated.') . t('Record') . ': task';
				exit();
					
			}

		}
	
			
		//if there are Assets associated to the tasks this code saves the many-to-many relationship between assets and tasks
		$task = R::load('tasks',$id);
		
		//removes all the relationships previously set
		$task->sharedAssets = array();
		R::store($task);

		$found_assets = false;
		
		foreach ($post as $attribute => $value) {
			
			if(strstr($attribute, 'asset_')){
				$found_assets = true;
				$asset = R::load('assets',$value);
				$task->sharedAssets[] = $asset;
				
			}
		}
		
		if($found_assets) R::store($task);
	
		//TODO should I update contact's LDAP attribute lastAssignment?
		
		$this->status = true;
		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';
		
// 		if($this->task->contact_id_key && $this->task->contact_id) {
// 			redirect('/contact/details/' .$this->task->contact_id_key. '/' . $this->task->contact_id .'/#tab_Tasks');
// 		}	
		
	
// 		redirect('/');
	}
	
	
	
	public function save_activity(){
		
		if(!$post = $this->get_post_values()){
			$this->status = false;
			$this->message = t('GET is empty.');
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
			
		if(empty($post['id'])) {
				
			if($id = $this->activity->create()){
	
				$this->message  = t('Activity successfully created.') . ' Activity: #' . $id;
	
			} else {
	
				$this->status = false;
				$this->message = t('Record not saved.') . t('Record') . ': activity';
				exit();
	
			}
				
		} else {
				
			if($id = $this->activity->update()){
				
				$this->message  = t('Activity successfully updated.') . ' Activity: #' . $id;
				
			} else {
	
				$this->status = false;
				$this->message = t('Record not updated.') . t('Record') . ': activity';
				exit();
	
			}
		}

		$this->status = true;
		$this->procedure = 'refresh_page';
		$this->focus_tab = 'tab_Tasks';		
	
	}
	
}