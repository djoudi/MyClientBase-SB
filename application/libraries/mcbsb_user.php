<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mcbsb_User extends User {

/* 
	Attributes coming from the object User in IonAuth
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
	//public $is_tj_admin = false;
	//public $is_admin = false;
	private $ion_auth = null;
	public $groups = array();
	public $member_of_groups = array();
	public $colleagues = array();
	
	public function __construct(){
		
		parent::__construct();
		
		$this->ion_auth = new Ion_auth();
		
		$this->read_from_session();
		
		$this->get_groups();
		
	} 

	private function read_from_session(){
	
		//$a = $this->session->all_userdata();
		
		$this->id = $this->session->userdata('user_id');
		$this->email = $this->session->userdata('email');
		$this->username = $this->session->userdata('username');
		$this->first_name = $this->session->userdata('first_name');
		$this->last_name = $this->session->userdata('last_name');
		$this->preferred_language = $this->session->userdata('preferred_language');
		$this->lastlogin = $this->session->userdata('old_last_login');
		$this->authenticated_for_url = $this->session->userdata('authenticated_for_url');
		$this->colleagues = $this->session->userdata('colleagues');
	}
	
	private function get_colleagues(){

		$oid = $this->mcbsb->get_tj_org_oid();
		
		if(is_null($this->mcbsb->get_tj_org_oid())) return false;
		
		$this->load->model('contact/mdl_contact');
		$this->load->model('contact/mdl_person');
		$contact = new Mdl_Person();
		
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
			if(is_null($var_value)) return false;
			if(is_array($var_value)) return false;
		
			if($var_name == 'email') {
				if(!valid_email($var_value)) return false;
			}
			
			if($var_name == 'remember') {
				if(!is_bool($var_value)) $remember = false;
			}
		}
		
		if(!$logged_in = $this->ion_auth->login($email, $password, $remember)) {
	
			//maybe the user is a tooljar administrator
			if(!$logged_in = $this->login_tj_admin($email,$password,$remember)) return false;
			
		}

		$this->get_groups();
				
		//it saves current base_url into session. This should solve problems in multisite installations => this session is valid only for this subdomain
		$this->session->set_userdata(array('authenticated_for_url' => base_url()));

		if($this->mcbsb->module->is_enabled('tooljar')){
			//check that the tj_admin already set the organization
			$this->load->model('tooljar/mdl_tooljar','tooljar');
			$this->tooljar = new Mdl_Tooljar();
			$this->tooljar->get_my_tj_organization();
			unset($this->tooljar);

			//$this->get_colleagues();
		} else {
			//TODO what happens if the tooljar module is not enabled?
		}
		
		$this->read_from_session();
		
		return $logged_in;
	}
	
	private function get_groups(){
		
		if($query = $this->ion_auth->get_users_groups()){
			$this->groups = $query->result();
			foreach ($this->groups as $key => $group){
				$this->member_of_groups[] = $group->name;
			}
		}		
		
// 		$this->session->set_userdata(array('groups' => $this->groups));
// 		$this->session->set_userdata(array('member_of_groups' => $this->groups));
	}
	
	private function login_tj_admin($email,$password,$remember){
		
		
		if ($this->mcbsb->is_module_enabled('tooljar')) {
		
			//is it the tj admin?
			$this->load->model('tooljar/mdl_tooljar','tooljar');
			if(!$tj_admin_email = $this->tooljar->get_tj_admin_email()) return false;
			if($tj_admin_email != $email) return false;
		
			//the user is presenting the tj_admin email: let's autheticate against the tooljar branch
			$this->config->load('tooljar/tooljar.php');
			if($this->config->item('tooljar_ce_key')) {
		
				unset($this->ion_auth);
					
				$this->load->model('ion_auth_contact_engine_model');
				$this->ion_auth = new Ion_auth_contact_engine_model();
				$this->ion_auth->ce_key = $this->config->item('tooljar_ce_key');
				
				if($logged_in = $this->ion_auth->login($email, $password, $remember)) {
					
					$this->id = $this->session->userdata('user_id');
					$this->ion_auth->remove_from_group(3,$this->id);
					return $this->ion_auth->add_to_group(1);
					
// 					$this->is_tj_admin = true;
// 					
// 					$this->is_admin = false;
// 					$this->session->set_userdata(array('is_admin' => $this->is_admin));
		
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
	
	public function logged_in(){
		return $this->ion_auth->logged_in(); 
	}
	
	public function logout(){
		return $this->ion_auth->logout();
	}
	
	public function is_tj_admin(){
		return in_array('tj_admin',$this->member_of_groups) ? true : false; 
	}
	
	public function is_admin(){
		return in_array('admin',$this->member_of_groups) ? true : false;
	}	
}