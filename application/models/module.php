<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module object
 *  
 * @author 		Damiano Venturin
 * @link		http://www.mcbsb.com
 * @since		October 26, 2012
 * 	
 */
class Module extends Db_Obj
{
	protected $module_id = null;
	protected $module_path = null;
	protected $module_name = null;
	protected $module_description = null;
	protected $module_enabled = 0;
	protected $module_author = null;
	protected $module_homepage = null;
	protected $module_version = null;
	protected $module_available_version = null;
	protected $module_config = null;
	protected $module_change_status = 0;
	protected $module_order = 99;
	
	public function __construct(){
		parent::__construct();
	
		$this->obj_ID_field = 'module_id';  //here goes the name of the primary field identifying each record
		$this->obj_name = get_class($this);
		$this->db_table = 'mcb_modules';
	
		$this->initialize();
	}
	
	public function __destruct(){
	
	}
	
	public function __set($attribute, $value) {
		if($attribute == 'module_name') $value = strtolower($value);
		if($attribute == 'module_config') $value = serialize($value);
		parent::__set($attribute, $value);
	}
	
	public function __get($attribute) {
		$value = parent::__get($attribute);
		if($attribute == 'module_config') {
			$value = unserialize($value);
		}
		return $value;
	}
	
	public function create(){
		if(!is_null($this->obj_ID_value)) return false;
		if(is_array($this->module_config)) $this->module_config = serialize($this->module_config);
		return parent::create();
	}
	
	public function read(){
		if(is_null($this->obj_ID_value)) return false;
		return parent::read();
	}
	
	public function update(){
		if(is_null($this->obj_ID_value)) return false;
		if(is_array($this->module_config)) $this->module_config = serialize($this->module_config);
		return parent::update();
	}
	
	public function delete(){
		if(is_null($this->obj_ID_value)) return false;
		if(!$this->read()) return false;
		return parent::delete();
	}
	
	public function search(array $where = null, $logic_operator = 'AND'){
		$ids = parent::search($where, $logic_operator);
		
		if(!$ids || count($ids) == 0) return false;
		
		if(count($ids) == 1) {
			$this->obj_ID_value = $ids[0];
			$this->read();
		}
		return $ids;
	}
	
	public function is_enabled($module_name){
		if(is_array($module_name) || is_object($module_name)) return false;
		
		$sql = 'select module_enabled from ' . $this->db_table . ' where module_name="' . $module_name .'" limit 1';
		$result = parent::performSearch($sql,true);
		
		if(!isset($result[0])) return false;	//actually no record has been found ...
		
		return $result[0]['module_enabled'] == 1 ? true : false;
	}
	
	/**
	 * Receives in input the configuration for the module read in the config file and then
	 * it makes the appropriate changes in the database (delete, update, insert)
	 * 
	 * @access		public
	 * @param		array $config It contains the configuration read in the module config file
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Oct 26, 2012
	 */
	public function check_and_adjust(array $config){
		if(count($config) == 0) return false;
		
		//little validation of the configuration
		$mandatory_configuration_items = array('module_name', 'module_path', 'module_order', 'module_config');
		
		foreach ($mandatory_configuration_items as $mandatory_item){
			$config_items = array_keys($config);
			if(!in_array($mandatory_item, $config_items)) {
				return false;
			}
		}
				
		$where = array('module_name' => $config['module_name']);
		if($ids = $this->search($where))
		{
			if(count($ids) > 1){
				//this case shouldn't happen but I write it anyway
				
				//delete the entries
				foreach ($ids as $id){
					$this->obj_ID_value = $id;
					if(!$this->delete()) return false;
				}
		
				//insert the new entry
				$this->fill_obj_with_array($config);
				return $this->create();
			}
				
			if(count($ids) == 1) {
				//check if I have to update the entry
				if($this->diff($config)) {
					$this->fill_obj_with_array($config);
					$this->obj_ID_value = $ids[0];
					return $this->update();
				}
			}
		} else {
			//insert the module
			$this->fill_obj_with_array($config);
			return $this->create();
		}

		return true;
	}
	
	/**
	 * Populates the objects with values set in the given configuration array
	 *
	 * @access		public
	 * @param		array $config
	 * @return		nothing
	 *
	 * @author 		Damiano Venturin
	 * @since		Oct 27, 2012
	 */
	private function fill_obj_with_array(array $config){
		
		$this->obj_ID_value = null;
		
		foreach ($config as $key => $value){
			$this->$key = $value;
		}
	}
	
	/**
	 * It checks if the given config array is different from the object 
	 * 
	 * @access		public
	 * @param		array $config
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Oct 27, 2012
	 */
	private function diff(array $config){
		
		foreach ($config as $key => $value){
			//the config file can not overwrite the module_enabled attribute
			if($key == 'module_enabled') continue;
			
			if($key == 'module_config') {
				if(serialize($value) != serialize($this->$key)) return true;
			} else {
				if($value != $this->$key) return true;
			}
		}
		return false;
	}
	
	public function searchProvidingSql($sql){
		//generally on a specific database object you don't want to perform generic queries.
		//if you need to perform generic queries, use db_obj
		return false;
	}
		
/* 	
 	//TODO maybe in the future
	public function module_upgrade_notice() {
	
		$module_upgrade_notice = FALSE;
	
		foreach ($this->mdl_mcb_modules->custom_modules as $module) {
	
			if ($module->module_version < $module->module_available_version) {
	
				$module_upgrade_notice = $this->lang->line('module_upgrade_available');
	
			}
	
		}
	
		return $module_upgrade_notice;
	
	} 
*/	
	
/*
 	//TODO this is interesting
    public function load_custom_languages() {

        foreach ($this->custom_modules as $module) {

            $lang = '';

            if ($module->module_enabled) {

                if (file_exists(APPPATH . '/modules_custom/' . $module->module_path . '/language/' . $this->mcbsb->settings->setting('default_language') . '/' . $module->module_path . '_lang.php')) {

                    $lang = $this->mcbsb->settings->setting('default_language');

                }

                elseif (file_exists(APPPATH . '/modules_custom/' . $module->module_path . '/language/english/' . $module->module_path . '_lang.php')) {

                    $lang = 'english';

                }

                if ($lang) {

                    $this->lang->load($module->module_path . '/' . $module->module_path, $lang);

                }

            }

        }

    }

 */	
	
}
	
/* End of file module.php */
/* Location: ./application/models/module.php */