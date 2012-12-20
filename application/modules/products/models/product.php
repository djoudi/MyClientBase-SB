<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Product extends Rb_Db_Obj
{
	const table = 'products';
	protected $module_folder = null;
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'products';
		
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
	
	public function close(){
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		$this->fix_dates();
		
		//add hidden system values
		$this->complete_date = date('Y-m-d');
		$this->completed_by = $CI->mcbsb->user->id;
		$this->completionist = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		return parent::update();
	}
	
	public function is_open(){
		return !$this->is_closed();
	}
	
	public function is_closed(){
		return is_null($this->complete_date) ? false : true;
	}
	
/*	
	public function delete() {
		if(is_null($this->obj_ID_value)) return false;
		if(!$this->read()) return false;
		return parent::delete();	
	}
*/
	
	public function magic_button($type = 'create'){
		
		$tmp = array();
		
		switch ($type) {
			
			case 'close':
				$tmp['url'] = '/' . $this->module_folder . '/ajax/close_product';
				$tmp['form_title'] = 'Close product';
				$tmp['procedure'] = 'close_product';
				$button_label = 'Close product';
				$button_id = 'close_product';
				
				$this->reset_obj_config();
				//Do not show the following fields when closing a product
				$this->_config['never_display_fields'][] = 'product';
				$this->_config['never_display_fields'][] = 'details';
				$this->_config['never_display_fields'][] = 'start_date';
				$this->_config['never_display_fields'][] = 'due_date';
				$this->_config['never_display_fields'][] = 'urgent';
			break;
			
			case 'edit':
				$tmp['form_title'] = 'Edit product';
				$button_label = 'Edit product';
				$button_id = 'edit_product';			
			break;
			
			case 'create':
				$tmp['form_title'] = 'New product';
				$button_label = 'Create product';
				$button_id = 'create_product';					
			break;
			
			default:
				return array();
			break;
		}
		
		//common stuff for some cases
		if($type == 'create' || $type == 'edit'){

			$this->reset_obj_config();
			//Do not show the endnote textarea when creating or editing
			$this->_config['never_display_fields'][] = 'endnote';
			$tmp['url'] = '/' . $this->module_folder . '/ajax/save_product';
			$tmp['procedure'] = 'automated_form';
		}
		
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_product';
		
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