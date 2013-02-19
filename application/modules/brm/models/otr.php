<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Object - Team Relationship

class Otr extends Rb_Db_Obj
{
	const table = 'otrs';
	protected $module_folder = null;
	protected $team = array();
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'brm';
		
		//R::freeze( array($this->db_table));  //NOTE! comment this line when you develop!
		
		$this->initialize();
	}
	
	public function create() {
		
		if(!is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->creation_date = time();
		$this->created_by = $CI->mcbsb->user->id;
		$this->creator = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		$this->team = null;
		
		return parent::create();
	}

	public function read() {
		if(is_null($this->obj_ID_value)) return false;
		$this->_config['never_display_fields'] = array();				
		return parent::read();
	}
		
	public function update() {
		
		return false;
	}	
	
	

	public function delete() {
		
		if(!is_null($this->obj_ID_value)){
			return parent::delete();
		}
		
		if(!empty($this->object_name) && !empty($this->object_id)){
			$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->db_table . ' where object_id=' . $this->object_id . ' and object_name="' . $this->object_name . '"';
			if($beans = $this->readAll($sql)){
				foreach ($beans as $key => $bean) {
					$this->id = $bean['id'];
					if(!$this->delete()) return false;
				}
			}
		} 
		
		return true;
			
	}

	
	public function magic_button($type = 'create'){
		
		$form_parameters = array();
		$button_properties = array();
		$js_function = 'jqueryForm';
		$ajax_url = '/' . $this->module_folder . '/ajax/getForm';
		
		switch ($type) {
							
			case 'create_otr':
				$form_parameters['url'] = '/' . $this->module_folder . '/ajax/save_otr';
				$form_parameters['form_name'] = 'jquery_form_involve';
				$form_parameters['form_title'] = 'Involve';
				$form_parameters['procedure'] = 'create_otr';
					
				$button_properties['label'] = 'Involve';
				$button_properties['id'] = 'create_appointment';
			break;
					
			default:
				return array();
			break;
		}
		
		return $this->make_magic_button($button_properties, $form_parameters, $js_function, $ajax_url);
	}
	
	public function toJson(){
	
		$return = json_decode(parent::toJson());
	
		$CI = &get_instance();
		
		$return->team = json_encode($CI->mcbsb->user->team);
		
		if(!empty($this->object_id) && !empty($this->object_name)){
						
			if($CI->load->is_loaded_module(strtolower($this->object_name))) {
			
				$obj = new $this->object_name();
				
				$obj->id = $this->object_id;
				
				if($obj->read()) {
					if(isset($obj->involved) && count($obj->involved) > 0) {
						
						$return->involved = array();
						
						foreach ($obj->involved as $item) {
							$return->involved[] = $item['colleague_id'];
						}
	
					}
				}
			}		
		}
	
		return json_encode($return);
	}	
}