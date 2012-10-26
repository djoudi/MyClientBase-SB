<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper(array('form', 'url'));
         
        $this->load->library('form_validation');
                
        $this->load->helper('mcb_app');   
    }

    public function check_captcha(){
    	$posted_captcha = strtolower($this->input->post('captcha'));
    	$saved_captcha = strtolower($this->session->userdata('captcha'));
    	 
    	if($posted_captcha == $saved_captcha) return true;
    	
    	$this->form_validation->set_message('check_captcha', 'Wrong captcha');
    	return false;	
    }
    
    public function index() {
    	
    	//TODO should I add addslashes?
    	$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[50]|valid_email');
    	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]');
    	$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|min_length[5]|max_length[5]|callback_check_captcha');

    	
    	if ($this->form_validation->run() == TRUE)
    	{
       		if($this->mcbsb->user->login($this->input->post('username'),$this->input->post('password'),true))	{
       			redirect('/contact');
       		} else {
       			//TODO set an error    				
       		}
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
    			'img_width'	 => '115',
    			'img_height' => 45,
    			'expiration' => 7200
    	);
    	 
    	$data = array();
    	$data['form'] = array(
    						'username' => set_value('username'),
    						'password' => set_value('password')
    	);
    	
    	//TODO this should go in mcbsb system messages
    	$data['errors'] = $this->form_validation->get_validation_errors();
    	$data['captcha'] = create_captcha($vals);
    	 
    	$this->load->view('login.tpl', $data, false, true);    	 

    }

    function logout() {

    	$this->mcbsb->user->logout();
		$this->session->sess_destroy();

		redirect('/login');
    }

    function recover() {
		redirect('/contact');
    }


}

?>