<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));
         
        $this->load->library('form_validation');
                  
    }

    public function check_captcha(){
    	$posted_captcha = strtolower($this->input->post('captcha'));
    	$saved_captcha = strtolower($this->session->userdata('captcha'));
    	 
    	if($posted_captcha == $saved_captcha) return true;
  
    	return false;	
    }
    
    public function index() {
    	$this->login();
    }
    
    public function with_profile(){
    	
    	$login_settings = $this->session->userdata('login_settings');
    	
    	$this->session->unset_userdata('login_settings');
    	
    	$security_key = $this->input->post('security_key');
    	
    	if(!isset($login_settings['security_key'])) redirect('login');
    	
    	if($security_key != $login_settings['security_key']) redirect('/login');
    	
    	$email = $this->input->post('email');
    	$password = $this->input->post('password');
    	$remember = $this->input->post('remember');
    	$profile = $this->input->post('profile'); 
    	if($this->mcbsb->user->login($email,$password,$remember,$profile)) redirect('/contact');
    	
    	redirect('/login');
    }
    
    public function login() {
    	
    	$this->form_validation->set_error_delimiters('|', '');
    	$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[50]|valid_email');
    	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]');
    	$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|min_length[5]|max_length[5]'); // |callback_check_captcha
    	
    	$data = array();
    	$data['errors'] = array();
    	
    	if ($this->form_validation->run() == TRUE)
    	{
    		if(!$this->check_captcha()){
    			
    			$data['errors'][] = 'Wrong captcha';
    			
    		} else {		
    
	      		if($this->mcbsb->user->login($this->input->post('username'),$this->input->post('password'),true))	{
	      			redirect('/contact');
	      		} else {
	      			$data['errors'][] = 'Wrong credentials';
	      		}
    		}      		      		
    	}
    	
    	//set an error
    	if($this->input->post('username') || $this->input->post('password')) {
    		//adds validation errors
    		$data['errors'] = array_merge($data['errors'], array_filter(explode('|', validation_errors())));
    	}
    	      	
    	//adds captcha
    	$this->load->helper('captcha');
    	$captcha = rand_string(5);
    	$this->session->set_userdata(array('captcha' => $captcha));
    	
    	//remove old captcha pictures
    	$jpgpath = FCPATH . 'captcha/*.jpg';  //TODO this must go in the config
    	$files = glob($jpgpath);
    	foreach($files as $file){
    		if(is_file($file)) unlink($file);
    	}
    	 
    	//creates captcha picture
    	$vals = array(
    			'word'	 => $captcha,
    			'img_path'	 => './captcha/',
    			'img_url'	 => base_url() . 'captcha/',
    			'img_width'	 => '177',
    			'img_height' => 45,
    			'expiration' => 7200
    	);
    	 
    	//restores previously added values
    	$data['form'] = array(
    						'username' => set_value('username'),
    						'password' => set_value('password')
    	);
    	
    	$data['captcha'] = create_captcha($vals);
    	     	 
    	$this->load->view('login.tpl', $data, false, 'smarty');
    }

    public function choose_profile() {
    	if(!$data = $this->session->userdata('login_settings')) redirect('/');
    	
    	$this->load->view('choose_profile.tpl', $data, false, 'smarty');
    }
    
    public function logout() {

    	$this->mcbsb->user->logout();
		$this->session->sess_destroy();

		redirect('/login');
    }

    public function recover() {
		redirect('/contact');
    }


}

?>