<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mcbsb_User extends User {

/* 
	Attributes coming for the object User
  	public $active = null;
	public $email = null;
	public $first_name = null;
	public $last_name = null;
	public $id = null;
	public $username = null;
	public $preferred_language = null;
*/		
	public $lastlogin = null;    //TODO I guess this should go in the User class
	public $authenticated_for_url = null;
	public $is_admin = false;
	private $ion_auth = null;
	public $colleagues = array();
	
	public function __construct(){
		
		parent::__construct();
		
		$this->ion_auth = new Ion_auth();
		
		$this->read_from_session();
		
	} 

	private function read_from_session(){
	
		$this->id = $this->session->userdata('user_id');
		$this->email = $this->session->userdata('email');
		$this->username = $this->session->userdata('username');
		$this->first_name = $this->session->userdata('first_name');
		$this->last_name = $this->session->userdata('last_name');
		$this->preferred_language = $this->session->userdata('preferred_language');
		$this->lastlogin = $this->session->userdata('old_last_login');
		$this->is_admin = $this->session->userdata('is_admin');
		$this->authenticated_for_url = $this->session->userdata('authenticated_for_url');
		$this->colleagues = $this->session->userdata('colleagues');
	}
	
	private function get_colleagues(){

		$this->load->model('contact/mdl_contact');
		$this->load->model('contact/mdl_person');
		$contact = new Mdl_Person();
		$oid = 10000000; //TODO put here the oid of the organization owning the app
		$input = array('filter' => '(&(oRDN='.$oid.')(enabled=TRUE))');
		$rest_return = $contact->get($input,true);
		
		if($contact->crr->has_no_errors) {
			$this->load->library('colleague');
			foreach ($contact->crr->data as $key => $item){
				 $colleague = new Colleague();
				 if(isset($item['cn'][0])) $colleague->name = $item['cn'][0];
				 if(isset($item['mail'][0])) $colleague->email = $item['mail'][0];
				 if(isset($item['uid'][0])) $colleague->uid = $item['uid'][0];
				 $colleague->oid = $oid;
				 $this->colleagues[] = $colleague; 
			}
		}
		
		$this->session->set_userdata(array('colleagues' => $this->colleagues));		
	}
	
	public function login($email,$password,$remember = false){
		
		$this->load->helper('email');
		
		//for test purposes
		//$b = get_defined_vars();
		
		//security checks. Most of these security checks are already performed by the controller
		foreach (get_defined_vars() as $var_name => $var_value){
			if(is_null($var_value)) return false; //TODO maybe add a system error?
			if(is_array($var_value)) return false; //TODO maybe add a system error?
		
			if($var_name == 'email') {
				if(!valid_email($var_value)) return false; //TODO maybe add a system error?
			}
			
			if($var_name == 'remember') {
				if(!is_bool($var_value)) $remember = false;
			}
		}

		if(!$logged_in = $this->ion_auth->login($email, $password, $remember)) {

			$this->load->model('mcb_modules/mdl_mcb_modules');
			$this->mdl_mcb_modules->get_enabled();
			if ($this->mdl_mcb_modules->check_enable('tooljar')) {
				
				$this->config->load('tooljar/tooljar.php');
				if($this->config->item('tooljar_ce_key')) {
					//maybe it's the tooljar administrator: look in the tooljar users database
					unset($this->ion_auth);
					
					$this->load->model('ion_auth_contact_engine_model');
					$this->ion_auth = new Ion_auth_contact_engine_model();
					$this->ion_auth->ce_key = $this->config->item('tooljar_ce_key'); 
					if($logged_in = $this->ion_auth->login($email, $password, $remember)) {
						$this->is_admin = true;
						$this->session->set_userdata(array('is_admin' => $this->is_admin));
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
						
		//it saves current base_url into session. This should solve problems in multisite installations => this session is valid only for this subdomain
		$this->session->set_userdata(array('authenticated_for_url' => base_url()));
 		
		$this->get_colleagues();
		
		$this->read_from_session();
		
		return $logged_in;
	}
	
	public function logged_in(){
		return $this->ion_auth->logged_in(); 
	}
	
	public function logout(){
		return $this->ion_auth->logout();
	}
}