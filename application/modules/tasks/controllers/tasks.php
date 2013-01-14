<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tasks extends Admin_Controller {
	
	function __construct() {

		parent::__construct();

		$this->load->helper('date');
		
		$this->load->model('tasks/task','task');
	
	}

	/**
	 * Shows task details
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
		
		$data['task'] = $task = $this->task->toJson();
		
		
		$this->load->model('tasks/appointment','appointment');
		$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->appointment->db_table . ' where task_id=' . $this->task->id . ' order by id DESC';
		$data['appointments'] = $appointments = $this->appointment->readAll($sql);
		
		$this->load->model('otrs/otr','otr');
		$data['involved_in_appointment'] = array();
		foreach ($appointments as $k => $appointment){
			$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $appointment['id'] . ' and object_name="appointment" order by id ASC';
			$data['involved_in_appointment'][$k] = $this->otr->readAll($sql);
		}
				
		
		
		$data['buttons'][] = $this->task->magic_button('edit');
		$data['buttons'][] = $this->task->magic_button('close');
		
		$this->appointment->task_id = $this->task->id;
		$this->appointment->what = $this->task->task;
		$data['buttons'][] = $this->appointment->magic_button('create_appointment_for_task');
		
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
						
			$this->load->model('otrs/otr','otr');
			$this->load->model('tasks/appointment','appointment');
			foreach ($tasks as $key => $task) {

				//people involved
				$this->otr->object_name = 'task';
				$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $task['id'] . ' and object_name="task" order by colleague_name ASC';
				$tasks[$key]['involved'] = $this->otr->readAll($sql);
				
				$this->otr->object_id = $task['id'];
				$tasks[$key]['involve_buttons'][] = $this->otr->magic_button('create_otr');
				
				//appointments				
				$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->appointment->db_table . ' where task_id=' . $task['id'] . ' order by id DESC';
				$tasks[$key]['appointments'] = $appointments = $this->appointment->readAll($sql);
				
 				foreach ($appointments as $k => $appointment){
 					$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->otr->db_table . ' where object_id=' . $appointment['id'] . ' and object_name="appointment" order by id ASC';
 					$tasks[$key]['involved_in_appointment'][$k] = $this->otr->readAll($sql);

 					$this->appointment->id = $appointment['id'];
 					$tasks[$key]['delete_appointment_buttons'][$k] = $this->appointment->magic_button('delete_appointment');
 					
 					$this->otr = new Otr();
 					$this->otr->object_id = $appointment['id'];
 					$this->otr->object_name = 'appointment';
 					$tasks[$key]['appointment_involve_buttons'][$k] = $this->otr->magic_button('create_otr'); 					
 				}
				
 				unset($this->appointment->id);
				$this->appointment->task_id = $task['id'];
				$this->appointment->what = '#'.$task['id'] . ' ' . $task['contact_name'];
				$this->appointment->description = $task['task'];
				$tasks[$key]['create_appointment_buttons'][] = $this->appointment->magic_button('create_appointment_for_task');
	
			}
		}
		
		$data['tasks'] = $tasks;
		$data['pager'] = $this->mcbsb->_pagination_links;
		
/* 		$data['tomorrow_summary'] = array();
		$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->appointment->db_table . ' where task_id=' . $task['id'] . ' order by id DESC';
		$tomorrow_appointments[$key]['appointments'] = $appointments = $this->appointment->readAll($sql);
		
		foreach ($this->mcbsb->user->team as $key => $colleague){
			
				
			
			$data['tomorrow_summary'][][$colleague['uid']] = array(); 
			$a = '';
		} */
		
		if($contact_id && $contact_id_key) {
			return array('html' => $this->load->view('tasks_table.tpl', $data, true, 'smarty', 'tasks'),
							'counter' => count($tasks));
		} else {
			$this->load->view('tasks_all.tpl', $data, false, 'smarty', 'tasks');
		}
	}
	


/*
	function form() {

		$this->redir->set_last_index();

		$data = array();
		
		$this->_post_handler();

		if ($_POST) { 	//it's an update or a new task
				
			//is it a validated submit? (i.e. are all the rules respected?)
			if ($this->mdl_tasks->validate()) {
				
				if(empty($this->mcbsb->task->task_id)) {
					
					//it's a new task
					if($this->mcbsb->task->create()) {
						
						$this->mcbsb->system_messages->success = 'Task has been successfully created';
						
					} else {
						
						$this->mcbsb->system_messages->error = 'Task has not been created';
					}
				
				} else {
				
					//it's an update
					if($this->mcbsb->task->update()) {
						$this->mcbsb->system_messages->success = 'The task has been successfully updated';
					} else {
						$this->mcbsb->system_messages->error = 'The task has not been updated';
					}
				}
				
				redirect('tasks');
				
			} else {
				$this->mcbsb->system_messages->error = 'The task has been successfully created';
			}			
		}
		
		if($task_id = uri_assoc('task_id', 3)) {
			
			//it's an old task to be edit
			
			$this->mcbsb->task->task_id = $task_id;
			if($this->mdl_tasks->get()) { //this populates $this->mcbsb->task with contact information 
			
				//task and contact have been found.				
				$data['task'] = $this->mcbsb->task;
												
			} else {
				$this->mcbsb->system_messages->error = 'The specified task can not be retrieved';
				redirect('tasks');
			}
			
			$modules = $this->mcbsb->_modules;
						
			//TODO FIXME this is no good. Somehow it translates the modules names
			if(in_arrayi('activities',$modules['all']) || in_arrayi('attivitá',$modules['all'])) {
				
				//loads the Activity obj into the mcbsb obj
				$this->load->model('activities/mdl_activities','activities');
	
				$params = array('where' => array('task_id' => $this->mcbsb->task->task_id));
				if($activities = $this->activities->getAll($params)) {
					$data['activities'] = $activities;
					$data['table'] = $this->pp->parse('table.tpl', $data, true, 'smarty', 'activities');
				}
			}			
		} else {
						
			//it's a new task
			
			$values = retrieve_uid_oid();
			if(isset($values['client_id'])) $this->mcbsb->task->client_id = $values['client_id'];
			if(isset($values['client_id_key'])) $this->mcbsb->task->client_id_key = $values['client_id_key'];

			if(! $this->mdl_tasks->retrieve_contact_name($this->mcbsb->task)) {
				$this->mcbsb->system_messages->error = 'The contact related to the task can not be retrieved';
				redirect('contact');
			}
			
			$this->mcbsb->task->start_date = now();
			$data['task'] = $this->mcbsb->task;
		}
		
		$data['site_url'] = site_url($this->uri->uri_string());
		$data['actions_panel'] = $this->pp->parse('actions_panel.tpl', $data, true, 'smarty', 'tasks');
		$this->load->view('form', $data);
	}

	function create_invoice() {
		
		$this->redir->set_last_index();
		
		if($task_id = uri_assoc('task_id', 3)) {
			$this->mcbsb->task->task_id = $task_id;
			if($this->mdl_tasks->get()) { //this populates $this->mcbsb->task with contact information
					
				//task and contact have been found.
		
				$this->load->model('invoices/mdl_invoices');
				
				if(!$this->mcbsb->task->complete_date) {
					$this->mcbsb->task->complete_date = now();
					$this->mcbsb->task->update();
				}
				
				$invoice_items = array();
				$invoice_items[] = array(
						'item_name'			=>	$this->mcbsb->task->title,
						'item_description'	=>	$this->mcbsb->task->description,
						'item_qty'			=>	1,
						'item_price'		=>	0
				);

				if($this->mcbsb->task->client_id_key == 'uid') {
					$invoice_due_date = now();
				} else {
					//the end of the month
					//TODO this should go in the settings
					$current_month = date('m');
					$current_year = date('Y');
					$final_date = $current_year . '-' . $current_month . '-' . days_in_month($current_month,$current_year);
					$invoice_due_date = strtotime($final_date);
				}
				
				$package = array(
						'client_id'				=>	$this->mcbsb->task->client_id,
						'client_id_key'			=>	$this->mcbsb->task->client_id_key,
						'invoice_date_entered'	=>	$this->mcbsb->task->complete_date,
						//'invoice_due_date'		=>	$invoice_due_date,
						'invoice_is_quote'		=>	0,
						'invoice_group_id'		=>	1,//$this->input->post('invoice_group_id'),
						'invoice_items'			=>	$invoice_items
				);
				
				if($invoice_id = $this->mdl_invoices->create_invoice($package)) {
				
					$this->mdl_tasks->save_invoice_relation($invoice_id, $task_id);
					
					redirect('invoices/edit/invoice_id/' . $invoice_id);
				} else {
					$this->mcbsb->system_messages->error = 'The invoice can not be created';
					redirect('tasks/form/task_id/'.$task_id);
				}
								
			} else {
				$this->mcbsb->system_messages->error = 'The specified task can not be retrieved';	
				redirect('tasks');
			}
				
		} else {
			//no task_id in the url
			$this->mcbsb->system_messages->error = 'The specified task can not be retrieved';
			redirect('tasks');
		}
		
	}
	

	function delete() {

		if ($task_id = uri_assoc('task_id', 3)) {

			$this->mcbsb->task->task_id = $task_id;
			if($this->mdl_tasks->get()) {
				
				//TODO if is not invoiced ...
				
				if($this->mcbsb->task->delete()) {
					$this->mcbsb->system_messages->success = 'Task has been successfully deleted';
				} else {
					$this->mcbsb->system_messages->error = 'Task has not been deleted';
				}
			}
			
		} else {
			
			$this->mcbsb->system_messages->error = 'Task_id is missing';
			
		} 
		
		$this->redir->redirect('tasks');

	}

	
	function save_settings() {

		if ($this->input->post('dashboard_show_open_tasks')) {

			$this->mcbsb->settings->save('dashboard_show_open_tasks', "TRUE");

		}

		else {

			$this->mcbsb->settings->save('dashboard_show_open_tasks', "FALSE");

		}

	}

	
	function dashboard_widget() {

		if ($this->mcbsb->settings->setting('dashboard_show_open_tasks') == "TRUE") {

			$params = array(
				'limit'	=>	10,
				'where'	=>	array(
					'complete_date'=>''
				)
			);

			$data = array(
				'tasks'	=>	$this->mdl_tasks->get($params)
			);

			$this->load->view('dashboard_widget', $data);

		}

	}

	
	function _post_handler() {
		
		if ($this->input->post('btn_cancel')) {

			redirect('tasks/index');

		}
	}

	*/
}

?>