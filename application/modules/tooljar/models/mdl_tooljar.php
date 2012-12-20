<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Tooljar extends MY_Model {
	
	public $crr = null;
	public $tooljar_server = null;
	public $organization = null;
	
	public function __construct() {

		parent::__construct();
		
		//loads the config file from the folder "config" contained in this module
		$this->config->load('tooljar', false, true, 'tooljar');
		$this->tooljar_server = $this->config->item('tooljar_server');		
		
		// Load curl
		$this->load->spark('curl/1.2.1');
		
		// Load the configuration file
		$this->load->config('rest');
				
        // Load the rest client
        $this->load->spark('restclient/2.1.0');

        $this->load->model('contact/rest_return_object');
        $this->crr = new Rest_Return_Object();
        $host_sliced = explode('.', $_SERVER['HTTP_HOST']);
        
        if(count($host_sliced) < 3) {
        	$this->organization = 'acme'; //this is for nitro dev environment
        } else {
        	$this->organization = $host_sliced[1];
        } 
	}

	public function set_as_my_tj_organization($oid){
		
		if(empty($oid) || is_array($oid)) return false;

		//TODO this requires some security check: what about getting the company from Contact Engine to be sure it exists?
		
		$this->rest->initialize(array('server' => $this->tooljar_server));
		$method = 'set_as_tj_organization';
		$input = array();
		$input['oid'] = $oid;
		$input['organization'] = $this->organization;  //$a = base_url();
		
		$rest_return = $this->rest->post($method, $input, 'serialize');
		
		$this->crr->importCeReturnObject($rest_return);
		
		if($this->crr->has_errors) return false;
		
		$this->mcbsb->set_mcbsb_org_oid($oid);
		
		return true;
	}
	
	public function get_my_tj_organization(){
		
		$this->rest->initialize(array('server' => $this->tooljar_server));
		$method = 'get_tj_organization';
		$input = array();
		$input['organization'] = $this->organization;
	
		$rest_return = $this->rest->get($method, $input, 'serialize');
	
		$this->crr->importCeReturnObject($rest_return);
	
		if($this->crr->has_errors) return false;
		
		$oid = $this->crr->data[0];
		$this->mcbsb->set_mcbsb_org_oid($oid);
		
		return $oid;
	}	
	
	public function get_tj_admin_email(){
		$this->rest->initialize(array('server' => $this->tooljar_server));
		$method = 'get_tj_admin_email';
		$input = array();
		if($this->config->item('ce_key')) {
			$input['ce_key'] = $this->config->item('ce_key');
		} else {
			return false;
		}

		$rest_return = $this->rest->get($method, $input, 'serialize');
		
		$this->crr->importCeReturnObject($rest_return);
		
		if($this->crr->has_errors) return false;
		
		return $this->crr->data['email'];
		
	}
}