<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//TODO delme

class Top_Menu extends MY_Model {

	function __construct() {

		parent::__construct();
		
	}

	//Get core modules status
	public function get_core_modules_status()
	{
/* 		$this->load->model('mdl_mcb_modules');
		$this->mdl_mcb_modules->refresh();
		$modules = $this->mcbsb->_modules['enabled'];
		$status_items = array();
		foreach ($modules as $key => $module)
		{
			if($module->module_enabled == "1")
			{
				$status_items[$key] = 'enabled';
			} else {
				$status_items[$key] = 'disabled';
			}
		}
		return $status_items; */
		return array();
	}
	
	public function generate() {

		//$this->load->model('mdl_mcb_modules');
		$this->load->config('mcb_menu');
        $menu_items = $this->config->item('mcb_menu');
		
        //DAM
        $status_items = $this->get_core_modules_status();
        
        foreach ($menu_items as $key => $menu_item) {
			//DAM
        	if(isset($status_items[$key]) && $status_items[$key]=="disabled") {
        		unset($menu_items[$key]); 
        	}
        	        	
            if (!$this->session->userdata('is_admin')) {

                if (isset($menu_item['is_admin'])) {

                    unset($menu_items[$key]);

                }

                elseif (isset($menu_item['submenu'])) {

                    foreach ($menu_item['submenu'] as $sub_key=>$sub_item) {

                        if (isset($sub_item['is_admin'])) {

                            unset($menu_items[$key]['submenu'][$sub_key]);
                        }
                    }
                }
            }
        }

        //TODO is the items position handled somewhere?
        
        
        //TODO this is a quick and dirty. It requires some adjustments: 
        //for instance submenus are not taken in consideration
         
        //adding custom modules items
        foreach ($this->mdl_mcb_modules->custom_modules as $module) {
        	if ($module->module_enabled) { 
        		//do not override core modules
        		if(!isset($menu_items[$module->module_name])){
        			$menu_items[$module->module_name]['title'] = $module->module_name;
        			$menu_items[$module->module_name]['href'] = $module->module_path;
        		}
        	}
        }
        			        
        return $menu_items;
    }
    
	function display($params = null) {
		
		$data = array(
			'menu_items'    =>  $this->generate()
		);
		
		//gets the html menu from the template
		$plenty_parser = new Plenty_parser();	
		$plenty_parser->parse('menu.tpl', $data, false, 'smarty');
		
	}
	
	function check_permission($uri_string, $global_admin) {

		$this->load->config('mcb_menu');
		$menu = $this->config->item('mcb_menu');
		foreach ($menu as $menu_item) {

        	//DAM
			$status_items = $this->get_core_modules_status();
			
        	//if the requested page is part of a disabled module, than redirect
        	foreach ($status_items as $key => $status)        
        	if(preg_match_all('/^'.$key.'/', $uri_string, $matches) && $status=="disabled")	
        	{
        		redirect('contact');
        		break;
        	}
			//\DAM
        	
        	if(!empty($uri_string)){
				if (strpos($menu_item['href'], $uri_string) === 0) {
	
					if (isset($menu_item['is_admin']) and !$global_admin) {
	
						redirect('contact');
	
					}
	
				}
        	}
        	
			if (isset($menu_item['submenu']) and is_array($menu_item['submenu'])) {

				foreach ($menu_item['submenu'] as $sub_item) {

					if(!empty($uri_string)){
						if (strpos($sub_item['href'], $uri_string) === 0) {
	
							if (isset($sub_item['is_admin']) and !$global_admin) {
	
								redirect('contact');
	
							}
	
						}
					}
				}

			}

		}
	}

}

?>
