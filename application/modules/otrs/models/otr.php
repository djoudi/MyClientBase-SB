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
		$this->module_folder = 'otrs';
		
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
		
		$tmp = array();
		
		switch ($type) {
			
			
			case 'create_otr':
				//$tmp['form_title'] = 'Involve';
				$button_label = 'Involve';
				$button_id = 'create_otr';
				$tmp['procedure'] = 'create_otr';
				$tmp['url'] = '/' . $this->module_folder . '/ajax/save_otr';
			break;
			
			default:
				return array();
			break;
		}
		
		
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_otr';
		
		$string = json_encode($tmp, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
		$string = '$(this).live("click", jqueryForm(' . $string . ',"/' . $this->module_folder . '/ajax/getForm"))';
		
		$button_url = '#';		
		
		$button = array(
						'label' => $button_label,
						'id' => $button_id,
						'url' => $button_url,
						'onclick' => $string,
		);
	

		return $button;
	}
}