<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
	
	public function __construct(){
		parent::__construct();	
	}
	
	public function view($view, array $vars = array(), $return = FALSE, $template_engine = 'php', $module_path = '')
	{
		//$a = $this->mcbsb;
		
		$vars = $this->add_default_vars($vars);
		
		if($template_engine == 'smarty'){
			
			$html = $this->plenty_parser->parse($view, $vars, $return, 'smarty', $module_path);
			
			if($return) return $html;
			
			return;
		} 
		
		return parent::view($view, $vars, $return);
		
	}
	
	private function add_default_vars(array $vars = array()){
		
		$mcbsb_settings = $this->mcbsb->settings->get_all();
		$a = $this->mcbsb;
		
		//TODO maybe including all the settings is too much. We'll see
		//$vars = array_merge($vars, $mcbsb_settings);
		
		$vars['mcbsb_version'] = $this->mcbsb->_version;
		$vars['language'] = $this->mcbsb->_language;
		$vars['environment'] = ENVIRONMENT;  //development or production
		$vars['fcpath'] = FCPATH;
		$vars['site_url'] = site_url($this->uri->uri_string());
		
		$vars['top_menu'] = $this->mcbsb->_modules['top_menu'];
		$vars['system_messages'] = $this->mcbsb->system_messages->all;
		$vars['colleagues'] = array();
		$vars['colleagues'][0]['name'] = 'pippo';
		$vars['colleagues'][1]['name'] = 'pluto';		
// 		foreach ($this->mcbsb->user->colleagues as $key => $colleague){
// 			$vars['colleagues'][$key] = $colleague->name;
// 		}
		return $vars;
	}
}