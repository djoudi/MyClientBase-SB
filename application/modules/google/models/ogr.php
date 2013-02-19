<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Object - Google Relationship

class Ogr extends Rb_Db_Obj
{
	const table = 'ogrs';
	protected $module_folder = null;
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'google';
		
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
				
		return parent::create();
	}

	public function read() {
		
		if(is_null($this->obj_ID_value)) return false;

		if(!parent::read()) return false;
		
		return true;
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

}