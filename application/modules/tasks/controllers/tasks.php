<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tasks extends Admin_Controller {
	
	function __construct() {

		parent::__construct();

		$this->load->helper('date');
		
		$this->load->model('tasks/task','task');
	
	}

	/**
	 * Shows task details and builds the buttons for the Action Panel
	 * 
	 * @access		public
	 * @param		none
	 * @return		hmtl
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 26, 2012
	 */	 
	public function details(){
		
		$segments = $this->uri->uri_to_assoc();
		if(!isset($segments['id']) || !is_numeric($segments['id'])) redirect('/'); //TODO in this case would be nice to  roll back to last position
		
		$data = array();
		
		$this->task->id = $segments['id'];
		$this->task->read();
		
		$data['task'] = $this->task;
		$data['task_json'] = $this->task->toJson();
		
		$this->load->model('tasks/appointment','appointment');
		$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->appointment->db_table . ' where task_id=' . $this->task->id . ' order by id DESC';
		$data['appointments'] = $appointments = $this->appointment->readAll($sql);
		
		$this->load->model('brm/otr','otr');
		$data['involved_in_appointment'] = array();
		foreach ($appointments as $k => $appointment){
			$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $appointment['id'] . ' and object_name="appointment" order by id ASC';
			$data['involved_in_appointment'][$k] = $this->otr->readAll($sql);
		}
				
		//BUTTONS
		
		
		if($this->task->is_open()) {
			
			//adds the edit button
			$data['buttons'][] = $this->task->magic_button('edit');
			
			//adds the "add appointment" button
			$this->appointment->task_id = $this->task->id;
			$this->appointment->what = $this->task->task;
			$data['buttons'][] = $this->appointment->magic_button('create_appointment_for_task');
			
			//adds the "add activity" button
			$this->load->model('tasks/activity','activity');
			$this->activity->tasks_id = $this->task->id;
			$this->activity->contact_id_key = $this->task->contact_id_key;
			$this->activity->contact_id = $this->task->contact_id;
			$this->activity->contact_name = $this->task->contact_name;
			$data['buttons'][] = $this->activity->magic_button('create');

			//adds close button
			$data['buttons'][] = $this->task->magic_button('close');
				
		} else {
			
			//adds open button
			$data['buttons'][] = $this->task->magic_button('open');
		}
		

		
		//$this->load->model('brm/otr','otr');
		
		$this->load->view('task_details.tpl', $data, false, 'smarty', 'tasks');
	}
	
	/**
	 * Retrieves all the tasks and shows them in a table
	 * 
	 * @access		public
	 * @param		none		
	 * @return		none
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 25, 2012
	 */
	function index(){
		
		$from = (integer) uri_find('from');
		
		$contact_id = $this->session->userdata('contact_id');
		$contact_id_key = $this->session->userdata('contact_id_key');
		
		$data = array();
		if($contact_id && $contact_id_key) {
			$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->task->db_table . ' where contact_id=' . $contact_id . ' and contact_id_key="' . $contact_id_key. '" order by id DESC';
			$tasks = $this->task->readAll($sql,true,$from);
		} else {
			$tasks = $this->task->readAll(null,true,$from);
		}
		
		
		if(is_array($tasks) && count($tasks) > 0){
						
			$this->load->model('brm/otr','otr');
			$this->load->model('tasks/appointment','appointment');
			$this->load->model('tasks/activity','activity');
			
			foreach ($tasks as $key => $task) {

				//edit button
				$tmp_task = new Task();
				$tmp_task->id = $task['id'];
				
				if($tmp_task->read()) {
					
					//status button
					if($tmp_task->is_open()){
						$tasks[$key]['edit_button'] = $tmp_task->magic_button('edit');
						$tasks[$key]['close_button'] = $tmp_task->magic_button('close');
					}
					
				}

				
				//people involved
				$this->otr->object_name = 'task';
				$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $task['id'] . ' and object_name="task" order by colleague_name ASC';
				$tasks[$key]['involved'] = $this->otr->readAll($sql);
				
				$this->otr->object_id = $task['id'];
				$tasks[$key]['involve_buttons'][] = $this->otr->magic_button('create_otr');
				
				//gets all the appointments related to the task				
				$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->appointment->db_table . ' where task_id=' . $task['id'] . ' order by id DESC';
				$tasks[$key]['appointments'] = $appointments = $this->appointment->readAll($sql);
				
 				foreach ($appointments as $k => $appointment){
 					
 					//gets the colleagues involved in the task
 					$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $appointment['id'] . ' and object_name="appointment" order by id ASC';
 					$tasks[$key]['involved_in_appointment'][$k] = $this->otr->readAll($sql);

 					//reads the appointment and makes the buttons for the edit and delete
 					$this->appointment->id = $appointment['id'];
 					
 					//reads the appointment but doesn't get in return the "never_display_fields"
 					if($this->appointment->read(true)) { 
 						
 						$tasks[$key]['edit_appointment_buttons'][$k] = $this->appointment->magic_button('edit_appointment_for_task');
 						$tasks[$key]['delete_appointment_buttons'][$k] = $this->appointment->magic_button('delete_appointment');
 						
 					} else {
 						
 						$tasks[$key]['edit_appointment_buttons'][$k] =
 						$tasks[$key]['delete_appointment_buttons'][$k] = null;
 					}
 					
 					$this->otr = new Otr();
 					$this->otr->object_id = $appointment['id'];
 					$this->otr->object_name = 'appointment';
 					$tasks[$key]['appointment_involve_buttons'][$k] = $this->otr->magic_button('create_otr'); 					
 				}

 				
 				
 				//gets all the appointments related to the task
 				//?? come fanno ad esserci gli appuntamenti se qui non li leggo via sql? magari non serve nemmeno la query degli appuntamenti
 				
 				
 				
 				
 				//adds the buttons to add appointments
 				$appointment = new Appointment();
				$appointment->task_id = $task['id'];
				$appointment->what = '#'.$task['id'] . ' ' . $task['contact_name'];
				$appointment->description = $task['task'];
				
				$tasks[$key]['create_appointment_buttons'][] = $appointment->magic_button('create_appointment_for_task');
				
		
				//adds the buttons to add activities
				$activity = new Activity();
				$activity->tasks_id = $task['id'];
				$activity->contact_id_key = $task['contact_id_key'];
				$activity->contact_id = $task['contact_id'];
				$activity->contact_name = $task['contact_name'];				
				$tasks[$key]['create_activity_buttons'][] = $activity->magic_button();
			}
		}
		
		$data['tasks'] = $tasks;
		$data['pager'] = $this->mcbsb->_pagination_links;
		
		
		
		
		$timeline = array();
		
		if(is_array($tasks) && count($tasks) > 0){
			$tmp = array();
			foreach ($tasks as $key => $task) {
			
				foreach ($task['appointments'] as $appointment) {
					$tmp[$task['id']][] = array(
							'id'  	=> $appointment['id'],
							'day' 	=> date('Y-m-d',$appointment['start_time']),
							'type' 	=> 'appointment'
					);
				}
			
				foreach ($task['activities'] as $activity) {
					$tmp[$task['id']][] = array(
							'id' 	=> $activity['id'],
							'day' 	=> $activity['action_date'],
							'type'	=> 'activity'
					);
				}
			
			}
			
			
			foreach ($tmp as $task_id => $events) {
				$e = $events;
				usort($e,'cmp');
				$timeline[$task_id] = $e;
			}			
		}
			
		$data['timeline'] = $timeline;		
		
		
		
		if($contact_id && $contact_id_key) {
			return array(
							'html' => $this->load->view('tasks_table.tpl', $data, true, 'smarty', 'tasks'),
							'counter' => count($tasks)
						  );
		} else {
			$this->load->view('tasks_all.tpl', $data, false, 'smarty', 'tasks');
		}
	}
	
}

function cmp($a,$b){
	
	if($a['day'] == $b['day']) {
		
		if($a['type'] == 'appointment' && $b['type'] == 'appointment') return 0;
		
		//gives priority to appointments
		if($a['type'] == 'appointment') return -1;
		return 1;
	}
	
	return (strtotime($a['day']) < strtotime($b['day'])) ? 1 : -1;
}
?>