<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Gtoken extends Rb_Db_Obj
{
	const table = 'gtokens';
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

	public function is_present($email) {
		
		if(!is_string($email)) return false;
	
		$sql = 'select id from ' . $this->db_table . ' where email="' . $email . '"';
		$records = $this->readAll($sql);
		
		if(count($records) == 0) return false;
		
		if(count($records) >= 1) return $records[0]['id'];
			
	}
	
	public function get_google_format(){
		
		if(is_null($this->obj_ID_value)) return false;
		
		if($this->read()){
			
			$gtoken_attributes = array('id_token','access_token','expires_in','refresh_token');
			
			$gtoken = array();
			
			foreach ($gtoken_attributes as $attribute){
				$gtoken[$attribute] = $this->$attribute;
			}
			
			return json_encode($gtoken);
		}
		
		return false;
	}	
}