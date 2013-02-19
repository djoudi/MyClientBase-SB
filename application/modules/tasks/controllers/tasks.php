<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tasks extends Admin_Controller {
	
	function __construct() {

		parent::__construct();

		$this->load->helper('date');
		
		$this->load->model('tasks/task','task');
		$this->load->model('tasks/appointment','appointment');
		$this->load->model('brm/otr','otr');
		$this->load->model('tasks/activity','activity');		
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
		$tasks = array();
		$tmp = array();
		
		$this->task->id = $segments['id'];
		if(!$this->task->read()) redirect('/');

		if($return = $this->process_task($this->task,0,$tasks,$tmp)) {
			$tasks = $return['tasks'];
			$tmp = $return['tmp'];
		}		
		
		$data['task'] = $tasks[0];
		$data['task_json'] = $this->task->toJson();
		$data['timeline'] = $this->sort_timeline($tmp);
				
		//BUTTONS
		if($this->task->is_open()) {
			
			//adds the edit button
			$data['buttons'][] = $this->task->magic_button('edit');
			
			//adds the "add appointment" button
			$this->appointment->tasks_id = $this->task->id;
			$this->appointment->what = $this->task->task;
			$data['buttons'][] = $this->appointment->magic_button('create_appointment');
			
			//adds the "add activity" button
			$this->load->model('tasks/activity','activity');
			$this->activity->tasks_id = $this->task->id;
			$this->activity->contact_id_key = $this->task->contact_id_key;
			$this->activity->contact_id = $this->task->contact_id;
			$this->activity->contact_name = $this->task->contact_name;
			$data['buttons'][] = $this->activity->magic_button('create');

			//TODO adds the "add file" button
			$data['buttons'][] = array(
								'label' => 'Attach file',
								'id' => '',
								'url' => '#',
								'onclick' => '$(this).live("click",createPicker(' . $this->task->id . '))',
			);				
			
			//adds close button
			$data['buttons'][] = $this->task->magic_button('close');
				
		} else {
			
			//adds open button
			$data['buttons'][] = $this->task->magic_button('open');
		}
		
		$this->load->view('task_details.tpl', $data, false, 'smarty', 'tasks');
	}
	
	
	private function process_task(Task $task,$key,array $tasks, array $tmp){
		
		if(!is_object($task)) return false;
		
		$tasks[$key] = $task;
		
		//status button
		if($task->is_open()){
			$tasks[$key]->edit_button = $task->magic_button('edit');
			$tasks[$key]->close_button = $task->magic_button('close');
		}
			
		//button to involve people in the task
		$otr = new Otr();
		$otr->object_id = $task->id;
		$otr->object_name = $task->obj_name;
		$tasks[$key]->involve_button = $otr->magic_button('create_otr');
		
			
		//adds the buttons to add appointments
		$appointment = new Appointment();
		$appointment->tasks_id = $task->id;
		$appointment->what = '#'.$task->id . ' ' . $task->contact_name;
		$appointment->description = $task->task;
		
		$tasks[$key]->create_appointment_button = $appointment->magic_button('create_appointment');
		
		
		//adds the buttons to add activities
		$activity = new Activity();
		$activity->tasks_id = $task->id;
		$activity->contact_id_key = $task->contact_id_key;
		$activity->contact_id = $task->contact_id;
		$activity->contact_name = $task->contact_name;
		$tasks[$key]->create_activity_button = $activity->magic_button();
		
		
		
		
		
		
		
		//buttons for each appointment
		$tasks[$key]->edit_appointment_buttons = array();
		$tasks[$key]->delete_appointment_buttons = array();
		$tasks[$key]->appointment_involve_buttons = array();
		foreach ($task->appointments as $k => $appointment){
		
			$tasks[$key]->edit_appointment_buttons[$k] = $appointment->magic_button('edit_appointment');
			$tasks[$key]->delete_appointment_buttons[$k] = $appointment->magic_button('delete_appointment');
		
		
			$otr = new Otr();
			$otr->object_id = $appointment->id;
			$otr->object_name = 'appointment';
			$tasks[$key]->appointment_involve_buttons[$k] = $otr->magic_button('create_otr');
		
		}
		
		
		
		
		
		//buttons for each activity
		$tasks[$key]->edit_activity_buttons = array();
		$tasks[$key]->delete_activity_buttons = array();
		foreach ($task->activities as $k => $activity){
		
			$tasks[$key]->edit_activity_buttons[$k] = $activity->magic_button('edit');
			$tasks[$key]->delete_activity_buttons[$k] = $activity->magic_button('delete');
		
		}
		
		
		
		//stores info to create the timeline
		if(count($task->appointments) > 0 || count($task->activities) > 0) {
				
			$tmp[$task->id][] = array(
					'id'  	=> 'none',
					'day' 	=> $task->start_date,
					'type' 	=> 'start'
			);
			$tmp[$task->id][] = array(
					'id'  	=> 'none',
					'day' 	=> $task->due_date,
					'type' 	=> 'end'
			);
		
		}
		
		foreach ($task->appointments as $appointment) {
			$tmp[$task->id][] = array(
					'id'  	=> $appointment->id,
					'day' 	=> $appointment->start_time,
					'type' 	=> 'appointment'
			);
		}
			
		foreach ($task->activities as $activity) {
			$tmp[$task->id][] = array(
					'id' 	=> $activity->id,
					'day' 	=> $activity->action_date,
					'type'	=> 'activity'
			);
		}
		
		return array('tasks' => $tasks, 'tmp' => $tmp);
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
		$tasks = array();
		$tmp = array();
		
		//filters tasks
		if($contact_id && $contact_id_key) {
			$sql = 'select SQL_CALC_FOUND_ROWS id from '.$this->task->db_table . ' where contact_id=' . $contact_id . ' and contact_id_key="' . $contact_id_key. '" order by id DESC';
			$tasks_id = $this->task->readAll($sql,true,$from);
		} else {
			$sql = 'select id from '.$this->task->db_table;
			$tasks_id = $this->task->readAll($sql,true,$from);
		}
		
		
		if(is_array($tasks_id) && count($tasks_id) > 0){
			
			foreach ($tasks_id as $key => $item) {

				//edit button
				$task = new Task();
				$task->id = $item['id'];
				
				if(!$task->read()) continue;

				if($return = $this->process_task($task,$key,$tasks,$tmp)) {
					$tasks = $return['tasks'];
					$tmp = $return['tmp'];
				}
			}
		}
		

		$data['tasks'] = $tasks;
		$data['pager'] = $this->mcbsb->_pagination_links;
		$data['timeline'] = $this->sort_timeline($tmp);
		
		
		if($contact_id && $contact_id_key) {
			return array(
							'html' => $this->load->view('tasks_table.tpl', $data, true, 'smarty', 'tasks'),
							'counter' => count($tasks)
						  );
		} else {
			$this->load->view('tasks_all.tpl', $data, false, 'smarty', 'tasks');
		}
	}
	
	private function sort_timeline(array $tmp) {
		
		$timeline = array();
		
		foreach ($tmp as $tasks_id => $events) {
			$e = $events;
			usort($e,'cmp');
			$timeline[$tasks_id] = $e;
		}
		
		return $timeline;
	}
	
}

function cmp($a,$b){
	
	if($a['day'] == $b['day']) {
		
		if($a['type'] == 'appointment' && $b['type'] == 'appointment') return 0;
		
		if($a['type'] == 'start') return 1;
		if($b['type'] == 'start') return -1;

		if($a['type'] == 'end') return -1;
		if($b['type'] == 'end') return 1;
				
		//gives priority to appointments
		if($a['type'] == 'appointment') return -1;
		return 1;
	}
	
	return (strtotime($a['day']) < strtotime($b['day'])) ? 1 : -1;
}
?>