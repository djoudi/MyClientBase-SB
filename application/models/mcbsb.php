<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mcbsb  extends CI_Model {
	
	protected $_error;
	protected $_success;
	protected $_warning;
	public $system_messages;  		//equal to: $this->load->model('system_messages');
	public $settings;				//equal to: $this->load->model('settings');
	public $user;				//equal to: $this->load->model('mcbsb_user');
	public $record_descriptor;
	public $field_descriptor;
	public $db_obj;
	//public $top_menu;
	public $module;
		
	public $_enabled_modules;
	public $_total_rows;
	public $_version;
	public $_language = null;  	//like english, italian, russian (always english words i.e. italian, not italiano)
	public $_locale = null; 		//like en_us, it_it, ru_RU
	public $_modules = array(
						'all' => array(),
						'enabled' => array(),
						'top_menu' => array(),
	);
	
	public function __construct() {
		
		parent::__construct();
		
		$this->config->load('mcbsb');
		
		$this->_version = $this->config->item('mcbsb_version'); 
		
		//Contact Engine related libraries
		//Rest client class
		$this->load->spark('curl/1.2.1');
		$this->load->spark('ion_auth/2.3.3');
		$this->load->library('ion_auth');
		$this->load->library('session');
		
		$this->load->driver('plenty_parser');
		
		$this->initialize();
		
        if(!$this->user->logged_in()){
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
		
        $this->refresh_modules();
		//$this->load->model('users/mdl_users','users');  //mdl_users is loaded in $this->users  //TODO delme
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
						switch ($property_name) {
							case 'user':
								$this->load->library('mcbsb_user');
								$this->$property_name = new Mcbsb_User();
							break;
							
							default:
								//TODO what about $this->load($obj_name, $alias = null){
								
								$this->load->model($property_name);
								//references the loaded object
								$this->$property_name =& $CI->$property_name;								
							break;
						}
					}
				}
			}
		}
		
		$this->settings->set_application_title();
		
		$this->settings->get_all();
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
		if(!empty($this->user->preferred_language) && in_array($this->user->preferred_language,array_keys($this->config->item('gettextSupportedLocales')))) {
			$this->_language = $this->user->preferred_language;
		} else {
			//$this->load->model('mcb_data/mdl_mcb_data'); //TODO delme
		
			if($default_language = $this->settings->get('default_language')) {
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
	
	
	/**
	 * This method parses the config file of every module and refreshes the database module records
	 *
	 * @access		private
	 * @param		nothing
	 * @return		nothing
	 *
	 * @author 		Damiano Venturin
	 * @since		Jun 21, 2012
	 */
	private function refresh_modules() {
	
		$this->load->helper('directory');
	
		//Gather list of directories inside modules
		$modules_dir = array_pop(array_keys($this->config->item('modules_locations')));
		$modules = directory_map($modules_dir, TRUE);
	
		// Delete any orphaned module records from database
		$all_modules_in_db = $this->module->getAllRecords();
		foreach ($all_modules_in_db as $key => $record){
			if(!in_array($record['module_path'], $modules)){
				$this->module->obj_ID_value = $record['module_id'];
				$this->module->delete();
			}
		}		
		
		// Reads the modules config files and updates the modules table
		foreach ($modules as $module) {
				
			// This should be the location of the module's config file
			$config_file = $modules_dir . $module . '/config/config.php';
	
			// If the config file exists, adjust the modules table accordingly
			if (file_exists($config_file)) {
	
				$config = array();
				$config['module'] = array();
				$skip = false;
				
				include($config_file);
				
				if(!isset($config['module']['module_order'])) $config['module']['module_order'] = 99;
				
				$this->module = new Module();
				if(!$this->module->check_and_adjust($config['module'])) continue;				
	
			}
		}
		
		
		$all_modules_in_db = $this->module->getAllRecords();
		$this->_modules['all'] = array();
		$this->_modules['enabled'] = array();
		foreach ($all_modules_in_db as $key => $record){
			
			$this->_modules['all'][] = $record['module_name'];
			
			if($record['module_enabled'] == 1) {
				$this->_modules['enabled'][] = $record['module_name'];
				
				//TODO module ACL here
				if($record['module_top_menu']) {
					$this->_modules['top_menu'][] = array(
												'item_name' => $record['module_name'],
												'item_link' => '/'.$record['module_path'],
					);
				}
			}
			
		}
	}
	
	public function is_module_enabled($module_name) {
		if(is_array($module_name) || is_object($module_name)) return false; //TODO should be nice to trigger an error
		return $this->module->is_enabled($module_name);
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