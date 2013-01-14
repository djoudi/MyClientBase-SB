<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NOTE: REFACTORY IN SLOW PROGRESS. If you are not refactoring please rely on the other files
 * 
 * @access		public
 * @param		
 * @var			
 * @return		
 * @example
 * @see
 * 
 * @author 		Damiano Venturin
 * @since		Feb 25, 2012
 * 	
 */
class Field_Descriptor extends CI_Model
{
	var $alias = '';
	var $css_class = '';
	var $disabled = false;
	var $form_type = 'text';
	var $hidden = false;
	var $mandatory = false;
	var $max_length = 255;
	var $name;
	var $type;
	var $size = 45;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function __destruct(){
	
	}
	
}