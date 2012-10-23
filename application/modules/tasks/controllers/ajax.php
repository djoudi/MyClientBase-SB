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
				case 'create_appointment_for_task':
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
				
				default:
					parent::getForm();
					return;
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
				$this->load->model('otrs/otr','otr');
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
						
						if($attribute == 'contact_locs' && empty($post['where'])){
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
						
			if(isset($post['colleagues'])){
				
				if(!is_array($post['colleagues'])){
					$tmp = $post['colleagues'];
					$post['colleagues'] = array($tmp);
				}
				
				$this->load->model('otrs/otr','otr');
				
				//finds colleagues involved in the task
				$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $this->appointment->task_id . ' and object_name="task"';
				$involved_in_this_task = $this->otr->readAll($sql);
				$colleagues_involved_in_this_task = array();
				foreach ($involved_in_this_task as $key => $colleague){
					$colleagues_involved_in_this_task[] = $colleague['colleague_id'];
				}
				
				
				foreach ($post['colleagues'] as $key => $uid) {

					$this->otr = new Otr();
					
					$this->otr->object_name = 'appointment';
					$this->otr->object_id = $id;
					$this->otr->colleague_id = $uid;
					$this->otr->colleague_name = $this->get_colleague_name($uid);
					
					if($this->otr->create()){
						$this->mcbsb->system_messages->success = 'Successfully involved colleague ' . $this->otr->colleague_name . ' to ' . $this->otr->object_name .' #'.$this->otr->object_id ;
						
						//adds all the people involved in an appointment to the task connected to the appointment
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
	
	public function close_task(){
		
		$post = $this->get_post_values();
		
		if($post){
			if($post['id'] ){
				$this->task->id = $post['id'];
				if($this->task->read()){
					$this->task->endnote = $post['endnote'];
					$a = $this->task;
					if($this->task->close()){
						$this->status = true;
						
						$data = array();
						$data['task'] = $this->task->toJson();
						
						$data['buttons'][] = $this->task->magic_button('edit');
						$data['buttons'][] = $this->task->magic_button('close');
						
						$this->procedure = 'replace_html';
						$this->replace = array();
						$this->replace[] = array(
												'id' => 'box_task_details',
												'html' => $this->load->view('task_details_core.tpl', $data, true, 'smarty', 'tasks')
						);
						
						$button = $this->task->magic_button('close');
						$this->replace[] = array(
								'id' => 'li_'.$button['id'],
								'html' => '<a class="button" href="' . $button['url'] . '" id="' . $button['id'] . '"  onClick=\''. $button['onclick'] .'\'>'. $button['label'] .'</a>',
						);												
					}  
				}
			}
		} else {
			//TODO do something
		}
	}
	
	public function save_task(){
		
		if($get = $this->input->get()){
			
			if(!$get['task']) {
				//TODO this should return something to js
				$this->status = false;
				return;
			}
			
			foreach ($get as $attribute => $value) {
				if(empty($get['id']) && $attribute == 'id'){
					continue;
				} else {
					$this->task->$attribute = $value;
				}
			}
			
			if(empty($get['id'])) {
				$id = $this->task->create();
				$message = 'Task #' . $id . ' successfully created';
			} else {
				$id = $this->task->update();
				$message = 'Task #' . $id . ' successfully updated';
			}
			
			if($id){
				$this->mcbsb->system_messages->success = $message;
			} else {
				$task_id = empty($this->task->id) ? '' : '#'.$this->task->id;
				$this->mcbsb->system_messages->error = 'Error while saving the task '.$task_id;
			}
			
			//TODO should I update contact's LDAP attribute lastAssignment?
			if($this->task->contact_id_key && $this->task->contact_id) {
				redirect('/contact/details/' .$this->task->contact_id_key. '/' . $this->task->contact_id .'/#tab_Tasks');
			}	
		}
		
		redirect('/');
	}

}