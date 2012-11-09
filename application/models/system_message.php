<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class System_Message extends CI_Model{
	
	protected $text = '';
	protected $time = '';
	protected $type = '';
	protected $uid = '';
	protected $username = '';
	
	protected $types = array('success','warning','error');
	
	public function __construct(){
		
	}
	
	public function __set($attribute, $value) {	
	
		if($attribute == 'types') return false;
		
		if(isset($this->$attribute)) {
			$CI = get_instance();
			
			//tries to translate the message
			if($attribute == 'text'){
				if($translation = $CI->lang->line($value)) $value = $translation;  //TODO the method CI->lang shoud be capable to use also phpgettext
			}
			
			//stores the values
			$this->$attribute = $value;
			$this->time = time();
			$this->uid = $CI->mcbsb->user->id;
			$this->username = $CI->mcbsb->user->username;
			
			//TODO what about saving an entry in the database?
			return true;
		}
		return false;
	}
	
	public function __get($attribute) {
		return isset($this->$attribute) ? $this->$attribute : null;
	}
	
	public function to_array(){
		$result = array(
						'text' => $this->text,
						'time' => $this->time,
						'type' => $this->type,
						'uid' => $this->uid,
						'username' => $this->username
				);
		return $result;
	}
	
}