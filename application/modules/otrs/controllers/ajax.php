<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('otrs/otr','otr');
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
				case 'create_otr':
					$CI = &get_instance();
					$object->team = $team = json_encode($CI->mcbsb->user->team);
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
	
	public function save_otr(){
		
		if($post = $this->get_post_values()){
		
			
			foreach ($post as $attribute => $value) {
				if(empty($post['id']) && $attribute == 'id' || $attribute == 'colleagues'){
					continue;
				} else {
					$this->otr->$attribute = $value;
				}
			}
			
			
			if($this->otr->delete()){
			
				if(isset($post['colleagues'])){
					
					if(!is_array($post['colleagues'])){
						$tmp = $post['colleagues'];
						$post['colleagues'] = array($tmp);
					}
					
					if($post['object_name'] == 'appointment'){
						
						$this->load->model('tasks/appointment','appointment');
						$this->appointment->id = $post['object_id'];
						
						if($this->appointment->read()){
							//finds colleagues involved in the task
							$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $this->appointment->task_id . ' and object_name="task"';
							$involved_in_this_task = $this->otr->readAll($sql);
							$colleagues_involved_in_this_task = array();
							foreach ($involved_in_this_task as $key => $colleague){
								$colleagues_involved_in_this_task[] = $colleague['colleague_id'];
							}
						}
					}										
					
					foreach ($post['colleagues'] as $key => $uid) {
						
						unset($this->otr->id);
						$this->otr->colleague_id = $uid;
						$this->otr->colleague_name = $this->get_colleague_name($uid);
						
						if($this->otr->create()){
							$this->mcbsb->system_messages->success = 'Successfully involved colleague ' . $this->otr->colleague_name . ' to ' . $this->otr->object_name .' #'.$this->otr->object_id ;
							
							//adds all the people involved in an appointment to the task connected to the appointment
							if($post['object_name'] == 'appointment' && !in_array($uid,$colleagues_involved_in_this_task)){
								
								$otr = new Otr();
							
								$otr->object_name = 'task';
								$otr->object_id = $this->appointment->task_id;
								$otr->colleague_id = $uid;
								$otr->colleague_name = $this->get_colleague_name($uid);
								$otr->create();
								
							}							
						} else {
							$this->mcbsb->system_messages->error = 'Error involving colleague: '.$this->otr->colleague_name . 'in ' . $this->otr->object_name . ' #'.$this->otr->object_id;
							$this->status = false;
							return;
						}
						
						//TODO check if these colleagues are also involved in the task
					}
					$this->status = true;
					return true;					
				}
				$this->status = true;
				$this->mcbsb->system_messages->success = 'All colleagues have been estranged '. 'from ' . $this->otr->object_name . ' #'.$this->otr->object_id ;
				return true;
			}
			
			$this->mcbsb->system_messages->error = 'Error involving colleagues';
			$this->status = false;
			return false;		
		}
	}
	
	private function get_colleague_name($uid){
		$team = $this->mcbsb->user->team;
		foreach ($team as $key => $colleague) {
			if($colleague['uid'] == $uid) return $colleague['name'];
		}
		
		return 'unknown';
	}

}