<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mcbsb  extends CI_Model {
	
	protected $_error;
	protected $_success;
	protected $_warning;
	public $system_messages;  //equal to: $this->load->model('system_messages');
	
	public $_enabled_modules;
	public $_total_rows;
	public $_user;
	public $_version;
	public $_language = null;  	//like english, italian, russian (always english words i.e. italian, not italiano)
	public $_locale = null; 		//like en_us, it_it, ru_RU
	
	public function __construct() {
		
		parent::__construct();
		
		$this->config->load('mcbsb');
		
		$this->_version = $this->config->item('mcbsb_version'); 
		
		$this->load->model('record_descriptor');
		$this->load->model('field_descriptor');
		$this->load->model('db_obj');
		
		//Contact Engine related libraries
		//Rest client class
		$this->load->spark('curl/1.2.1');
		$this->load->spark('ion_auth/2.3.3');
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('mcbsb_user');
		
		$this->initialize();
		
        if(!$this->_user->logged_in()){
        	//check if segments have login to avoid infinite loop
        	if(!in_array('login',$this->uri->segment_array())) redirect('/login');
        }
        
        //this check is required to increase security in a multi hosting environment where different urls
        //point to different installations of MCBSB
        //the current base_url has to match the value stored in session
        if($this->config->item('validate_url')) {
        	
        	$authenticated_for_url = $this->session->userdata('authenticated_for_url');
        	
        	if($authenticated_for_url != base_url()){
        		if(!in_array('login',$this->uri->segment_array()) && !in_array('logout',$this->uri->segment_array())) redirect('/logout');
        	}
        }
        
        $this->_load_language();
		
		//$this->load->model('users/mdl_users','users');  //mdl_users is loaded in $this->users
	}
	
	private function initialize() {
		$CI = get_instance();
	
		//reflects this object
		$reflection = new ReflectionClass($this);
	
		//gets object properties
		$properties = $reflection->getProperties();
	
		if(!empty($properties))
		{
			foreach ($properties as $property) {
	
				$property_name = (string) $property->name;
				//$property_value = $property->getvalue($this);
	
				//if(is_null($property_value) and $property->isPublic()) {
				if($property->isPublic()) {
					if(!preg_match('/^_/', $property_name, $matches))
					{
						//loads the class
						$this->load->model($property_name);
	
						//references the loaded object
						$this->$property_name =& $CI->$property_name;
					}
				}
			}
		}
		
		$this->_user = new Mcbsb_User();
	}

	//loads an object into $this->$objname
	public function load($obj_name, $alias = null){
		if(!is_string($obj_name)) return false;
		
		$obj_name = strtolower($obj_name);
		if(is_null($alias)) {
			$this->$obj_name = $this->load->model($obj_name);
		} else {
			$this->$alias = $this->load->model($obj_name);
		}
	}
	
	public function __destruct() {

		//this is executed after the controller has been unloaded
		
		//TODO I think I can load the header from here
	}
	
	/**
	 * Sets the attributes "_language" and "_locale".
	 * $this->_language will be used by the standard Code Igniter translation system
	 * $this->_locale will be used by phpgettext 
	 * 
	 * $this->_language contains the name of the language like italian, english, russian ...
	 * $this->_locale contains the I18N locale code like it_IT, en_US, ru_RU ... 
	 * 
	 * @access		private
	 * @param		Nothing
	 * @return		Nothing	
	 * 
	 * @author 		Damiano Venturin
	 * @since		Oct 25, 2012
	 */
	private function _load_language() {
		
		$this->load->config('phpgettext');
		
		//load the preferred language for the current user or the default system language
		if(!empty($this->_user->preferred_language) && in_array($this->_user->preferred_language,array_keys($this->config->item('gettextSupportedLocales')))) {
			$this->_language = $this->_user->preferred_language;
		} else {
			$this->load->model('mcb_data/mdl_mcb_data');
		
			if($default_language = $this->mdl_mcb_data->get('default_language')) {
				$this->_language = $default_language;
			}
		}

		if(is_null($this->_language)) {
			$this->_language = 'english'; //default value
		} 
		
		if(is_null($this->_locale)) {
			$tmp = $this->config->item('gettextSupportedLocales');
			$this->_locale = $tmp[$this->_language];			
		}
		
		$this->load->language('mcb', $this->_language);		
	}	
	
	public function get_enabled_modules() {
		$this->load->model('mcb_modules/mdl_mcb_modules');
		return $this->_enabled_modules = $this->mdl_mcb_modules->get_enabled();		
	}
	
	public function is_module_enabled($module) {
		$this->get_enabled_modules();
		if(in_arrayi($module,$this->_enabled_modules['all'])) {
			return true;
		}
		return false;
	}
	
	private function set_system_message($type, $text) {
		
		//log_message('debug',$text);
		
// 		if(!is_string($text)) return false;
		
// 		//retrieve messages from session
//  		$tmp = $this->CI->session->flashdata('system_messages');
// 		//$tmp = $this->session->flashdata('system_messages');
// 		if(isset($tmp[$type])) $this->system_messages[$type] = $tmp[$type];
		
// 		//update with the new message
// 		$this->system_messages[$type][] = $text;
// 		$this->CI->session->set_flashdata('system_messages',$this->system_messages);
	}

	private function get_system_messages() {
	
// 		//retrieve messages from session
// 		$tmp = $this->CI->session->flashdata('system_messages');
// 		if(is_array($tmp)) {
// 			$this->system_messages = $tmp;
// 		} else {
// 			return array();
// 		}
		
// 		//translate if possible otherwise show as it is
// 		foreach ($this->system_messages as $type => $messages) {
// 			foreach ($messages as $key => $message) {
// 				if($translation = $this->CI->lang->line($message)) {
// 					$this->system_messages[$type][$key] = $translation;
// 				} 
// 			}	
// 		}
		
// 		//join all the messages in one line by group
// 		foreach ($this->system_messages as $type => $messages) {			
// 			$this->system_messages[$type] = implode(' - ', $messages);
// 		}
		
		return $this->system_messages;
	}
	
	public function display_menu() {
		$plenty_parser = new Plenty_parser();
		$data = array();
		$menu = $plenty_parser->parse('menu.tpl', $data, true, 'smarty');
		return $menu;
	}
	
}