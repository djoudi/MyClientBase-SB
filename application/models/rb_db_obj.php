<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NOTE: the object name is alway singular (=> not tasks but task). The db_table is normally plural (not necessarily) but HAS to match the hmvc module name!
 * 
 * @access		public
 * @param		
 * @var			
 * @return		
 * @example
 * @see
 * 
 * @author 		Damiano Venturin
 * @since		Nov 22, 2012
 * 	
 */
class Rb_Db_Obj extends CI_Model
{	
	private $db;
	private $db_name = null;
	private $db_table = null;
	protected $_config = array();
	protected $_fields = array();
		
	private $obj_ID_field = null;
	private $obj_ID_value = null;
	private $obj_name = null;
	
	
	
	public function __construct(){
		
		parent::__construct();
	
// 		R::freeze( true );  //NOTE! when the development slows down uncomment this line
		
		$CI = &get_instance();
		$this->db = $CI->db;
		$this->db_name = $CI->db->database;
		$this->obj_ID_field = 'id'; //NOTE the id field is always "id"
				
	}

	public function __destruct(){
	
	}
	
	protected function initialize(){
		
		if(is_null($this->db_name)) {
			die('The object: '.$this->obj_name.' has no db_name set');
		}
		
		if( is_null($this->db_table)){
			die('The object: '.$this->obj_name.' has no db_table set');
		}		
		
		$this->reset_obj_config();
		
		$this->retrieves_db_structure();
		
		//try to load the 1st bean
		$record = R::load($this->db_table,1);
		
		//TODO shouldn't it  be $record->$this->obj_ID_field instead of $record->id ?
		
		if(!$record->id && count($this->_fields) == 0){
			//the table has not been built yet. I have to call the prototype builder
			if(method_exists($this, 'prototype')){
				
				//creates the table from the prototype
				$record = R::load($this->db_table,$this->prototype());
				
				//removes the prototype record
				R::trash( $record );
				
				$this->retrieves_db_structure();
			}
		}
		
	}
	
	protected function prototype(){
	
		$record = R::dispense($this->db_table);
	
		$CI = &get_instance();
	
// 		$a = $this->obj_name;
// 		$b = $this->module_folder;
		
		//loads the config file from the folder "config" contained in this module
		$CI->config->load(strtolower($this->obj_name), false, true, $this->module_folder);
		$prototype = $CI->config->item(strtolower($this->obj_name).'_prototype');
	
		if(is_array($prototype)){
			foreach ($prototype as $attribute => $value) {
				$record->setAttr($attribute , $value);
			}
				
			return R::store($record);
		}
	
		return false;
	}	

	protected function reset_obj_config(){
	
		if(!isset($this->module_folder) || is_null($this->module_folder)) $this->module_folder = strtolower($this->db_table);
	
		//gets object configuration
		$CI = &get_instance();
		$CI->config->load(strtolower($this->obj_name), false, true, $this->module_folder);
	
		$items = array('attributes_aliases', 'default_values', 'hidden_fields','mandatory_fields','never_display_fields','prototype');
		foreach ($items as $item) {
			$this->_config[$item] = $CI->config->item(strtolower($this->obj_name) . '_' . $item);
		}
	
	}	
	
	/**
	 * Cleans all the public attributes of the object but those ones specified in $but
	 * 
	 * @access		protected
	 * @param		array $but	Simple list of the attributes to preserve
	 * @return		none
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 4, 2013
	 */
	protected function clean(array $but){
		
		//reflects this object
		$reflection = new ReflectionClass($this);
		
		//gets object properties
		$properties = $reflection->getProperties();
		
		//protects private and protected attributes from being wiped
		if(!empty($properties))
		{
			foreach ($properties as $property) {
		
				$property_name = (string) $property->name;

				if(!$property->isPublic()) {
					$but[] = $property_name;
				}
			}
		}
		
		//cleans values
		foreach (json_decode($this->toJson()) as $attribute => $value) {
			if(in_array($attribute, $but)) continue;
			$this->$attribute = '';
		}
			
	}
	
	//retrieves the table/object structure
	private function retrieves_db_structure(){
		
		$rd = new Record_Descriptor();
	
		$tmp_fields = $rd->getFields($this->db_name, $this->db_table);
		
		if(is_array($this->_config['prototype']) && count($tmp_fields) > 0 ){
			$fields = array();
			foreach (array_keys($this->_config['prototype']) as $key => $attribute){
				$fields[$attribute] = $tmp_fields[$attribute];
			}
			unset($tmp_fields);
		} else {
			$fields = $tmp_fields;
		}
		
		if($fields && count($fields) > 0){

			//marks mandatory fields
			if(is_array($this->_config['mandatory_fields'])){
				foreach ($this->_config['mandatory_fields'] as $key => $attribute){
					if(in_array($attribute, array_keys($fields))){
						$fields[$attribute]['mandatory'] = true;
					}
				}
			}
				
			//marks form hidden fields
			if(is_array($this->_config['hidden_fields'])){
				foreach ($this->_config['hidden_fields'] as $key => $attribute){
					if(in_array($attribute, array_keys($fields))){
						$fields[$attribute]['form_type'] = 'hidden';
						$fields[$attribute]['hidden'] = true;
					}
				}
			}
			
			//applies alias found in the config file
			if(is_array($this->_config['attributes_aliases'])){
				foreach ($this->_config['attributes_aliases'] as $attribute => $alias){
					if(in_array($attribute, array_keys($fields))){					
						$fields[$attribute]['alias'] = $alias;
					}
				}
			}
			
		}
				
		$this->_fields = $fields;
	}
	
	public function __set($attribute, $value) {
		if(in_array($attribute, array_keys($this->_fields))) {

			if(is_array($value)){
				//serialize multiple values
				$this->$attribute = serialize($value); //TODO delme -> implode(',',$value);
			} else {
				$this->$attribute = trim($value);
			}
		} else {
			if($attribute == 'obj_ID_value') {
				$id_field = $this->obj_ID_field;
				$this->$id_field = $value;
			} else {
				$this->$attribute = $value;
			}
		}
		
	}
	
	public function __get($attribute) {
		
		if($attribute == 'obj_ID_value')
		{
			$id_field = $this->obj_ID_field;
			$this->obj_ID_value = $this->$id_field;
		}
		//TODO what about the unserialize of multivalue fields?
		return isset($this->$attribute) ? $this->$attribute : null;
	}
	
	public function __isset($attribute) {
		return isset($this->$attribute) ? true : false;
	}	

	public function toArray(){
		
		$result = array();
		foreach (array_keys($this->_fields) as $key => $field) {
			if($this->$field){
				$result[$field] = $this->$field;
			} else {
				$result[$field] = '';
			}
		}
		return $result;
	}
	
	public function toJson(){
	
		$return = $this->toArray();
	
		$CI = &get_instance();
	
		//hides fields that should be never shown
		if(is_array($this->_config['never_display_fields'])){
			foreach ($this->_config['never_display_fields'] as $key => $attribute){
				unset($return[$attribute]);
			}
		}
	
		foreach ($return as $attribute => $value){
			
			if(is_array($value)) $return[$attribute] = json_encode($value);
		}
	
		$return['_fields'] = $this->fields_specifics_toJson();
	
		return json_encode($return);
	}
	
	public function fields_specifics_toJson(){
		//FIELDS SPECIFICS
		
		$fields_specifics = array();
		
		foreach ($this->_fields as $attribute => $specifics){
			$fields_specifics[$attribute] = json_encode($specifics);
		}

		return $fields_specifics;
	}
	
	protected function toObject(RedBean_OODBBean $rb_obj) {
		
		foreach (array_keys($this->_fields) as $key => $field) {
			
			if(isset($rb_obj->$field)) $this->$field = $rb_obj->$field;
				
		}	

	}	
	
	protected function create(){
		$rb_obj = R::dispense($this->db_table);
		
		foreach (array_keys($this->_fields) as $key => $field) {
			
			if($field != $this->obj_ID_field) {
			
				$rb_obj->setAttr($field, $this->$field);
			} 
		}
		
		return $this->save($rb_obj);

	}
	
	protected function update(){
	
		if(is_null($this->__get('obj_ID_value'))) return false;
	
		$rb_obj = R::load($this->db_table,$this->obj_ID_value);
	
		//it's an update and I won't find in the GET sent to ajax some fields because they never went to the form
		if(is_array($this->_config['never_display_fields'])){

			foreach ($this->_config['never_display_fields'] as $key => $attribute){
				
				if(empty($this->$attribute) && !empty($rb_obj->$attribute)) {
					
					//restores in the object the value set in the database
					$this->__set($attribute, $rb_obj->$attribute);
					
				}
			}
		}

		foreach (array_keys($this->_fields) as $key => $field) {
			$rb_obj->setAttr($field, $this->$field);
		}
		
		return $this->save($rb_obj);
	}
	
	private function save(RedBean_OODBBean $rb_obj){
		
		if(is_array($this->_config['mandatory_fields'])){
			foreach ($this->_config['mandatory_fields'] as $key => $attribute){
				if(in_array($attribute, array_keys($this->_fields))){
					if(empty($this->$attribute)){
						$CI->mcbsb->system_messages->error = "Mandatory field is missing: ".$attribute;
						return false;
					}
				}
			}
		}
		
		return $this->obj_ID_value = R::store($rb_obj);
	}
	
	
	protected function read(){
		
		if(is_null($this->__get('obj_ID_value'))) return false;
		
		$rb_obj = R::load($this->db_table,$this->obj_ID_value);
		
		if($rb_obj->{$this->obj_ID_field})	$this->toObject($rb_obj);
		
		return $rb_obj->{$this->obj_ID_field} ? true : false; 
	}

	public function readAll($sql = null, $paginate = false, $from = 0, $results_per_page = 0){
		
		if(!is_string($sql) || empty($sql)) $sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->db_table . ' order by id DESC';
		
		$CI = &get_instance();
		
		//pagination
		if($paginate){
			if(!is_int($from)) $from = 0;
			if(!is_int($results_per_page) || $results_per_page == 0) $results_per_page = $CI->mcbsb->settings->setting('results_per_page');
			
			//$from = ($page - 1) * $results_per_page;
			
			$sql_limit = ' limit ' . $from . ', '. $results_per_page;
			
			$sql = $sql . $sql_limit;
		}
					
		//runs the query
		$beans = R::getAll($sql);

		$CI->db->query($sql);
		
		$CI->mcbsb->paginate();

		return $beans;
	}
	
	
	protected function delete(){
		
		if(is_null($this->__get('obj_ID_value'))) return false;
		
		$rb_obj = R::load($this->db_table,$this->obj_ID_value);
		
		if($rb_obj->{$this->obj_ID_field})	{
		
			//note: redbean returns null on deletion
			R::trash($rb_obj);
			
			return $rb_obj->id == 0 ? true : false;
			
		} 
		return true;
	}
	
}