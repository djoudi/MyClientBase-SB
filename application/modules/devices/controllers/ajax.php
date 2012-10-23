<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('devices/device','device');
	}
	
	public function save_device(){
		
		if($get = $this->input->get()){
			
			foreach ($get as $attribute => $value) {
				if(empty($get['id']) && $attribute == 'id'){
					continue;
				} else {
					$this->device->$attribute = $value;
				}
			}
			
			if(empty($get['id'])) {
				$id = $this->device->create();
				$message = 'device #' . $id . ' successfully created';
			} else {
				$id = $this->device->update();
				$message = 'device #' . $id . ' successfully updated';
			}
			
			if($id){
				$this->mcbsb->system_messages->success = $message;
			} else {
				$device_id = empty($this->device->id) ? '' : '#'.$this->device->id;
				$this->mcbsb->system_messages->error = 'Error while saving the device '.$device_id;
			}
			
			if($this->device->contact_id_key && $this->device->contact_id) {
				redirect('/contact/details/' .$this->device->contact_id_key. '/' . $this->device->contact_id .'/#tab_Devices');
			}			
		}
		
		redirect('/devices/');
	}

}