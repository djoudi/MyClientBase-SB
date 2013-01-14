<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mcbsb  extends CI_Model {
	
	protected $_error;
	protected $_success;
	protected $_warning;
	protected $_mcbsb_org_oid = null;
	public $system_messages;  		//equal to: $this->load->model('system_messages');
	public $settings;				//equal to: $this->load->model('settings');
	public $user;				//equal to: $this->load->model('mcbsb_user');
	public $record_descriptor;
	public $field_descriptor;
	public $db_obj;
	public $module;
	public $_pp;
	
	public $_pagination_links = null;
	public $_total_rows;
	public $_version;
	public $_languages = array();	//contains the languages and the languages identifier (locales) supported by MCBSB
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
		
		$this->load->spark('phpgettext/1.0.11');
		$this->load->helper('phpgettext');		
		
		//Contact Engine related libraries
		//Rest client class
		$this->load->spark('curl/1.2.1');
		$this->load->spark('ion_auth/2.3.3');
		$this->load->library('ion_auth');
		$this->load->library('session');
	
		
		$this->_initialize();
		
		$this->_refresh_modules();
		
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
        
        $this->_get_supported_languages();
        
        $this->_load_language();
	}
	
	private function _initialize() {
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
								$this->load($property_name);
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
	
	public function set_mcbsb_org_oid($oid){
		
		if(!is_string($oid) || empty($oid)) return;
		
		$this->_mcbsb_org_oid = $oid;
		$this->session->set_userdata('mcbsb_org_oid',$oid);
	}
	
	public function get_mcbsb_org_oid(){
		$this->_mcbsb_org_oid = $this->session->userdata('mcbsb_org_oid');
		return $this->_mcbsb_org_oid;
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
		if(!empty($this->user->preferred_language) && in_array(ucwords($this->user->preferred_language),array_keys($this->_languages))) {
			$this->_language = $this->user->preferred_language;
		} else {
			if($default_language = $this->settings->get('default_language')) {
				$this->_language = $default_language;
			}
		}

		if(is_null($this->_language)) {
			$this->_language = 'english'; //default value
		} 
		
		if(is_null($this->_locale)) {
			$this->_locale = $this->_languages[ucwords($this->_language)];			
		}
		
		$this->load->language('mcb', strtolower($this->_language));		
	}	
	
	/**
	 * retrieves the languages installed for mcbsb
	 *
	 * @access		private
	 * @param		none
	 * @return		nothing
	 *
	 * @author 		Damiano Venturin
	 * @since		Nov 5, 2012
	 */
	private function _get_supported_languages(){
	
		//
		$this->load->helper('directory');
		$this->load->helper('inflector');
		
		$this->_languages = array('English' => 'en_US'); //default
		
		//parses the phpgettext folder looking for languages
		$this->load->config('phpgettext');
		$a = $this->config->item('gettextLocaleDir');
		//$language_identifiers = array_keys(directory_map(APPPATH . '/third_party/php-gettext-1.0.11/locale'));
		$language_identifiers = array_keys(directory_map($a));
		
		if(is_array($language_identifiers)){
			foreach ($language_identifiers as $key => $identifier) {
				if($language = locale_get_display_language($identifier))
				{
					$this->_languages[$language] = $identifier; 
				}
			}
			
		}
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
	private function _refresh_modules() {
	
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
		
		//builds the top menu items
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
												'item_selected' => false,
					);
				}
			}
		}
		
		$this->_set_top_menu();
	}
	
	private function _set_top_menu(){
		
		//adds mandatory items for top menu
		
		if($this->module->is_enabled('tooljar')){
			if($this->user->is_tj_admin()) {
				$this->_modules['top_menu'][] = array(
						'item_name' => 'System Settings',
						'item_link' => '/system_settings',
						'item_selected' => false,
				);
			}
			//TODO also add something for the MCBSB admin user?
		}		

		$this->_modules['top_menu'][] = array(
				'item_name' => 'Videos',
				'item_link' => '#',
				'item_selected' => false,
		);		
		
		$this->_modules['top_menu'][] = array(
				'item_name' => 'Logout',
				'item_link' => '/logout',
				'item_selected' => false,
		);
		
		//TODO I don't like this
		//sets the selected tab
		$current_url = current_url();
		foreach ($this->_modules['top_menu'] as $key => $top_menu) {
			if(strstr($current_url, $top_menu['item_link'])) {
				$this->_modules['top_menu'][$key]['item_selected'] = true;
			}
		}		
	}
	
	public function is_module_enabled($module_name) {
		if(is_array($module_name) || is_object($module_name)) return false; //TODO should be nice to trigger an error
		return $this->module->is_enabled($module_name);
	}
	
	public function paginate(){

		$sql = $this->db->last_query();
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS total_rows');
		
		$segments = $this->uri->segment_array();
		
		$string = '';
		$uri_segment = 3;
		foreach ($segments as $key => $value){
			if($value != 'from'){
				$string .= $value;
			} else {
				$uri_segment = $key + 1;
				break;
			}
		}
		
		$url = base_url($string);
		
		$config = array(
				'first_link'		=>	'<span class="pagination_first">&lt;&lt;</span>' . ucwords($this->lang->line('first')),
				'prev_link'			=>	'<span class="pagination_previous">&lt;</span>' . ucwords($this->lang->line('prev')),
				'next_link'			=>	ucwords($this->lang->line('next')) . '<span class="pagination_next">&gt;</span>',
				'last_link'			=>	ucwords($this->lang->line('last')) . '<span class="pagination_last">&gt;&gt;</span>',
				'cur_tag_open'		=>	'<span class="pagination_active_link">',
				'cur_tag_close'		=>	'</span>',
				'num_links'			=>	3
		);
				
		$config['base_url'] = $url.'/from/';
		$config['uri_segment'] = $uri_segment;
		$config['total_rows'] = $query->row()->total_rows;
		$config['per_page'] = $this->settings->setting('results_per_page');
		$config['page_query_string'] = false;
		
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$this->_pagination_links =  $this->pagination->create_links();	
	}

}