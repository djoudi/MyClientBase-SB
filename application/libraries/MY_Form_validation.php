<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	public $_field_data = array();

	public function run($module = '', $group = '') {
		(is_object($module)) AND $this->CI =& $module;
		return parent::run($group);
	}
	
	public function reset_errors() {
		$this->_error_array = array();
	}
	
	/**
	 * Returns form validation errors as an array 
	 * 
	 * @access		public
	 * @param		none			
	 * @return		array
	 * 
	 * @author 		Damiano Venturin
	 * @since		Oct 25, 2012
	 */
	public function get_validation_errors(){
		return $this->_error_array;
	}
}

?>