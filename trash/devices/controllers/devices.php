<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Devices extends Admin_Controller {
	
	function __construct() {

		parent::__construct();
		
		$this->load->helper('date');
		
		$this->load->model('devices/device','device');
	
	}

	/**
	 * Shows device details
	 * 
	 * @access		public
	 * @param		none
	 * @return		hmtl
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 26, 2012
	 */	 
/*  	public function details(){
		
		$segments = $this->uri->uri_to_assoc();
		if(!isset($segments['id']) || !is_numeric($segments['id'])) redirect('/'); //TODO in this case would be nice to  roll back to last position
		
		$data = array();
		
		$this->device->id = $segments['id'];
		$this->device->read();
		
		$data['device'] = $this->device->toJson();
		
		$data['buttons'][] = $this->device->magic_button('edit');
		//$data['buttons'][] = $this->device->magic_button('close');
		
		$this->load->view('device_details.tpl', $data, false, 'smarty', 'devices');
	}  */
	
	/**
	 * Retrieves all the devices and shows them in a table
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
			$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->device->db_table . ' where contact_id=' . $contact_id . ' and contact_id_key="' . $contact_id_key. '" order by id DESC';
			$devices = $this->device->readAll($sql,true,$from);
		} else {
			$devices = $this->device->readAll(null,true,$from);
		}
		
		
		$data = array();
		$data['devices'] = $devices;
		$data['buttons'][] = $this->device->magic_button('create');
		$data['pager'] = $this->mcbsb->_pagination_links;
		
		
		
		if($contact_id && $contact_id_key) {
			return array('html' => $this->load->view('devices_table.tpl', $data, true, 'smarty', 'devices'),
					'counter' => count($devices));
		} else {
			$this->load->view('devices_all.tpl', $data, false, 'smarty', 'devices');
		}		
	}
}
?>