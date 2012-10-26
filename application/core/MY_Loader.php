<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
	
	public function __construct(){
		parent::__construct();	
	}
	
	public function view($view, array $vars = array(), $return = FALSE, $template_engine = 'php')
	{
		$a = $this->mcbsb;
		
		$vars = $this->add_default_vars($vars);
		
		if($template_engine == 'smarty'){
			
			$html = $this->plenty_parser->parse($view, $vars, $return, 'smarty');
			
			if($return) return $html;
			
			return;
		} 
		
		return parent::view($view, $vars, $return);
		
	}
	
	private function add_default_vars(array $vars = array()){
		
		$mcbsb_settings = $this->mcbsb->settings->get_all();
		
		//TODO maybe including all the settings is too much. We'll see
		$vars = array_merge($vars, $mcbsb_settings);
		
		$vars['environment'] = ENVIRONMENT;  //development or production
		$vars['top_menu'] = $this->mcbsb->top_menu->generate();
		$vars['system_messages'] = $this->mcbsb->system_messages->all;
		return $vars;
	}
}