<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Route extends Rb_Db_Obj
{
	const table = 'routes';
	protected $module_folder = null;
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'on_site';
		
		R::freeze( array($this->db_table));  //NOTE! comment this line when you develop!
		
		$this->initialize();
	}
	
	
	public function create() {
		
		if(!is_null($this->obj_ID_value)) return false;
		
		$sql = 'select id from ' . $this->db_table . ' where city="' . $this->city .'"';
		$records = $this->readAll($sql);
		if(count($records) > 0) {
			$this->id = $records[0]['id'];
			return $this->update();
		}
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->creation_date = time();
		$this->created_by = $CI->mcbsb->user->id;
		$this->creator = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
				
		return parent::create();
	}

	
	public function read() {
		
		if(is_null($this->obj_ID_value)) return false;

		$this->_config['never_display_fields'] = array();	

		return parent::read();
	}


	public function update() {
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->update_date = time();
		$this->updated_by = $CI->mcbsb->user->id;
		$this->editor = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;

		return parent::update();
	}	

	
	public function delete() {
		
		if(is_null($this->obj_ID_value)) return false;
		
		return parent::delete();
	}
	
	/**
	 * Returns a list of routes
	 * 
	 * @access		public
	 * @param		none
	 * @return		array	Returns an array containing route names
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 10, 2013
	 */
	public function get_routes(){
		$sql = 'select distinct(route_name) from ' . $this->db_table ;
		
		$routes_list = array();
		foreach ($this->readAll($sql) as $key => $route){
			$routes_list[] = $route['route_name'];
		}
		
		return $routes_list;
	}
	
	
	public function get_route($city){

		if(!is_string($city) || empty($city)) return false;
		
		$sql = 'select route_name from ' . $this->db_table . ' where city="' . $city . '"';
		
		if($records = $this->readAll($sql)) return $records[0]['route_name'];
		
		return false;
		
	}
	
	public function magic_button($type = 'create'){
		
		$form_parameters = array();
		$button_properties = array();
		$js_function = 'jqueryForm';
		$ajax_url = '/' . $this->module_folder . '/ajax/getForm';
		
		switch ($type) {
			
			case 'create':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_route';
				$form_parameters['form_name'] = 'jquery_form_create_route';
				$form_parameters['form_title'] = 'New Route or Node';
				$form_parameters['procedure'] = 'post_to_ajax';
			
				$button_properties['label'] = 'Create route';
				$button_properties['id'] = 'create_route';
			
				$this->reset_obj_config();
			break;
			
			case 'edit':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_route';
				$form_parameters['form_name'] = 'jquery_form_edit_route';
				$form_parameters['form_title'] = 'Edit Route';
				$form_parameters['procedure'] = 'edit_route';
			
				$button_properties['label'] = 'Edit';
				$button_properties['id'] = 'edit_route';
			
				$this->reset_obj_config();
			break;				
			
			default:
				return array();
			break;
		}

		return $this->make_magic_button($button_properties, $form_parameters, $js_function, $ajax_url);
	}
}