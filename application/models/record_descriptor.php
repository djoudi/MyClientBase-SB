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
class Record_Descriptor extends CI_Model
{
	private $fields = array();
	
	public function __construct(){
		parent::__construct();
	}
	
	public function __destruct(){

	}	
	
	public function getFields($db_name, $table_name) {
		if(count($this->fields) == 0) $this->read($db_name, $table_name);
		$return = array();
		foreach ($this->fields as $key => $field){
			$tmp  = (array) $field;
			$name  = $tmp['name'];
			unset($tmp['name']);
			$return[$name] = (array) $tmp;
		}
		return $return;
	}	
	
	protected function read($db_name , $table_name) {
		$query = $this->db->query(	'select *
									from information_schema.columns 
									where table_schema="'.$db_name.'" and table_name="'.$table_name.'" 
									order by column_name');
		
		foreach ($query->result() as $row)
		{
			$fd = new Field_Descriptor();

			$fd->name = $row->COLUMN_NAME;
			$fd->type = $row->DATA_TYPE;

			if(!is_null($row->CHARACTER_MAXIMUM_LENGTH)){
				$fd->max_length = $fd->size = $row->CHARACTER_MAXIMUM_LENGTH;
			}

			if(preg_match('/_date$/', $fd->name) && $fd->type == 'int'){
				$fd->type = 'date';
			}
						
			if(preg_match('/_time$/', $fd->name) && $fd->type == 'int'){
				$fd->type = 'time';
			}
			//form stuff
			switch ($fd->type) {
				case 'tinyint':
					$fd->form_type = 'checkbox';
				break;

				case 'time':
					$fd->css_class = 'datetimepicker';
					$fd->form_type = 'text';
					$fd->max_length = $fd->size = 16;
				break;
				
				case 'date':
					$fd->css_class = 'datepicker';
					$fd->form_type = 'text';
					$fd->max_length = $fd->size = 10;
				break;
				
				case 'longtext':
				case 'text':
					$fd->form_type = 'textarea';
				break;
		
				default: //varchar, int,
					$fd->form_type = 'text';
				break;
			}
					
			$fd->mandatory = !$row->IS_NULLABLE;
			

			$this->fields[] = $fd;
		}		
	}
	
	//TODO left here for retrocompatibility. Replace it with return array_keys($this->getFields);
	public function getFieldsList($db_name, $table_name) {
		if(count($this->fields) == 0) $this->read($db_name, $table_name);
		$list = array(); 
		foreach ($this->fields as $key => $field) {
			$list[] = $field->name;
		}
		return $list;
	}
}