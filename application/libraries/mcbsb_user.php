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
	//public $is_admin = false;
	private $ion_auth = null;
	public $groups = array();
	public $member_of_groups = array();
	public $team = array();
	
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
		$this->team = $this->session->userdata('team');
	}
	
	public function set_team(){

		if($this->mcbsb->module->is_enabled('tooljar')){
			 //check that the tj_admin already set the organization
			$this->load->model('tooljar/mdl_tooljar','tooljar');
			$this->tooljar = new Mdl_Tooljar();
			$this->tooljar->get_my_tj_organization();
			unset($this->tooljar);
		} else {
			//TODO what happens if the tooljar module is not enabled?
			//ref https://github.com/damko/MyClientBase-SB/issues/131
		}
		 		
		$oid = $this->mcbsb->get_mcbsb_org_oid();
		
		if(!$oid) return false;
		
		$this->team = array();
		
		$this->load->model('contact/mdl_contact');
		$this->load->model('contact/mdl_person');
		$contact = new Mdl_Person();
		
		$input = array();
		$input['filter'] = '(&(oRDN='.$oid.')(enabled=TRUE))';
		$input['wanted_attributes'] = array('cn','mail','uid'); 
		$rest_return = $contact->get($input,true);
		
		if($contact->crr->has_no_errors) {	
			foreach ($contact->crr->data as $key => $item){
				 $colleague = array();
				 if(isset($item['cn'][0])) $colleague['name'] = $item['cn'][0];
				 if(isset($item['mail'][0])) $colleague['email'] = $item['mail'][0];
				 if(isset($item['uid'][0])) $colleague['uid'] = $item['uid'][0];
				 $colleague['oid'] = $oid;
				 $this->team[] = $colleague; 
			}
		}
		
		$this->session->set_userdata('team',$this->team);		
	}
	
	public function login($email,$password,$remember = false, $profile = false){
		
		if(!$this->pre_login_checks($email, $password, $remember)) return false;
		
		$this->set_team();
		
		
		if(!$profile) $profile = $this->login_as($email, $password,$remember);
		
		switch ($profile) {
			case 'tj_admin':
				if(!$logged_in = $this->login_tj_admin($email,$password,$remember)) return false;		
			break;
			
			default:
				//he should be one of the team members
				
				//TODO there is something wrong here. I'm not supposed to load the model by hand: it should load automatically
				$this->load->model('ion_auth_contact_engine_model');
				$this->ion_auth = new Ion_auth_contact_engine_model();
				
				if(!$logged_in = $this->ion_auth->login($email, $password, $remember)) return false;
			break;
		}
				
		$this->get_groups();
		
		//it saves current base_url into session. This should solve problems in multisite installations => this session is valid only for this subdomain
		$this->session->set_userdata(array('authenticated_for_url' => base_url()));
		
		$this->read_from_session();
		
		return true;
	}
	
	private function pre_login_checks($email,$password,$remember = false){
		$this->load->helper('email');
		
		//for test purposes
		//$b = get_defined_vars();
		
		//security checks. Most of these security checks are already performed by the controller
		foreach (get_defined_vars() as $var_name => $var_value){
			
			//I want plain valid strings as parameters
			if(is_array($var_value)) return false;
			if(is_null($var_value) || empty($var_value)) return false;
		
			$var_value = trim($var_value);
			
			if($var_name == 'email') {
				if(!valid_email($var_value)) return false;
			}
				
			if($var_name == 'remember') {
				if(!is_bool($var_value)) $remember = false;
			}
		}
		
		return true;	
	}
	
	private function login_as($email,$password,$remember = false){
		
		if ($this->mcbsb->is_module_enabled('tooljar')) {
					
			//is it the tj admin?
			$this->load->model('tooljar/mdl_tooljar','tooljar');
			$tj_admin_email = $this->tooljar->get_tj_admin_email();
			unset($this->tooljar);
			
			if($tj_admin_email == $email) {
						
				//does this email also belong to one of the team members
				$is_team_member = false;
				foreach ($this->team as $key => $member) {
					if(isset($member['email']) && $member['email'] == $email) $is_team_member = true;
				}
				
				if($is_team_member){
					
					//ask if he wants to login an team user or as tooljar administrator
					$login_settings = array();
					$login_settings['email'] = $email;
					$login_settings['password'] = $password;
					$login_settings['remember'] = $remember;
					$login_settings['security_key'] = uniqid();
					$this->session->set_userdata('login_settings',$login_settings);
					redirect('/login/choose_profile');
				
				} else {
					
					return 'tj_admin';
					
				}
			}
		}

		return 'user';
	}
	
	private function get_groups(){
		
		if($query = $this->ion_auth->get_users_groups()){
			$this->groups = $query->result();
			foreach ($this->groups as $key => $group){
				$this->member_of_groups[] = $group->name;
			}
		}		
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
				
				//TODO there is something wrong here. I'm not supposed to load the model by hand: it should load automatically
				$this->load->model('ion_auth_contact_engine_model');
				$this->ion_auth = new Ion_auth_contact_engine_model();
				$this->ion_auth->ce_key = $this->config->item('tooljar_ce_key');
				
				if($logged_in = $this->ion_auth->login($email, $password, $remember)) {
					
					$this->id = $this->session->userdata('user_id');
					$this->ion_auth->remove_from_group(3,$this->id);
					return $this->ion_auth->add_to_group(1);
		
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