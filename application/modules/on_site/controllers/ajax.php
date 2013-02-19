<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model('on_site/route','route');
		
// 		if($modules_to_load = $this->config->item('route_modules_to_load')){
			
// 			foreach ($modules_to_load as $module_to_load){
			
// 				if(strtolower($module_to_load) != strtolower(get_class($this))){
					
// 					$parent_folder = $this->route->module_folder;
// 					if(!ends_with($parent_folder, '/')) $parent_folder = $parent_folder . '/';
// 					$this->load->model($parent_folder . $module_to_load , $module_to_load);
// 				}
// 			}			
// 		}		
	}
	
	public function save_route(){
		
		if(!$post = $this->get_post_values()){
			$this->status = false;
			$this->message = t('POST is empty');
			exit();
		}
		
		$this->procedure = 'refresh_page';
		$this->focus_tab = '#tab_Routes';
		
		$this->route->city = $post['city'];
		$this->route->route_name = $post['route_name'];
		
		if($this->route->create()) {
			$this->status = true;
			$this->message = t('Route successfully created');
		} else {
			$this->status = false;
			$this->message = t('Route has not been created');
		}
	}
	
	public function delete_route(){
	
		$post = $this->input->post();
		
		if(!isset($post['params']['object_id'])){
			$this->status = false;
			$this->message = t('Object ID is empty.');
			exit();
		}
	
		$this->procedure = 'refresh_page';
		$this->focus_tab = '#tab_Routes';
	
		$this->route->id = $post['params']['object_id'];
	
		if($this->route->delete()) {
			$this->status = true;
			$this->message = t('Route successfully deleted');
		} else {
			$this->status = false;
			$this->message = t('Route has not been deleted');
		}
	}	
	
}