<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model('brm/otr','otr');
	}


	public function save_otr(){
		
		if(!$post = $this->get_post_values()){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();			
		}
			
		foreach ($post as $attribute => $value) {
			if(empty($post['id']) && $attribute == 'id' || $attribute == 'colleagues'){
				continue;
			} else {
				$this->otr->$attribute = $value;
			}
		}
		
		//first I delete the previous relationships then I save the new ones
		if(!$this->otr->delete()){
			$this->status = false;
			$this->message = t('I can not delete the team relationships set previously');
			exit();			
		}
			
		if(isset($post['colleagues'])){
			
			if(!is_array($post['colleagues'])) $post['colleagues'] = array($post['colleagues']);
			
			if($post['object_name'] == 'appointment'){
				
				$this->load->model('tasks/appointment','appointment');
				$this->appointment->id = $post['object_id'];
				
				if($this->appointment->read()){
					
					//finds colleagues involved in the task
					$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $this->appointment->tasks_id . ' and object_name="task"';
					
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
					$this->message = t('Successfully involved colleague') . ' ' . $this->otr->colleague_name . ' -> ' . $this->otr->object_name .' #'.$this->otr->object_id ;
					
					//adds all the people involved in an appointment to the task connected to the appointment
					if($post['object_name'] == 'appointment' && !in_array($uid,$colleagues_involved_in_this_task)){
						
						$otr = new Otr();
					
						$otr->object_name = 'task';
						$otr->object_id = $this->appointment->tasks_id;
						$otr->colleague_id = $uid;
						$otr->colleague_name = $this->get_colleague_name($uid);
						$otr->create();
						
					}							
				} else {
					$this->message = t('Error involving colleague') . ' ' .$this->otr->colleague_name . ' -> ' . $this->otr->object_name . ' #'.$this->otr->object_id;
					$this->status = false;
					exit();
				}
				
				//TODO check if these colleagues are also involved in the task
			}
			
			$this->status = true;
			$this->procedure = 'refresh_page';
			exit();					
		}
		
		$this->status = true;
		$this->procedure = 'refresh_page';
		$this->message = t('Colleagues have been disassociated from') . $this->otr->object_name . ' #'.$this->otr->object_id ;
		exit();

	}
	
	private function get_colleague_name($uid){
		
		$team = $this->mcbsb->user->team;
		
		foreach ($team as $key => $colleague) {
			
			if($colleague['uid'] == $uid) return $colleague['name'];
			
		}
		
		return 'unknown';
	}

}