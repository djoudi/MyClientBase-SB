
<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Colleague {
	
	protected $name = '';
	protected $email = '';
	protected $uid = '';
	protected $oid = '';
	
	public function __construct(){
	}
	
	public function __set($attribute, $value) {
		if(isset($this->$attribute)) $this->$attribute = $value;
	}
	
	public function __get($attribute) {
		return $this->$attribute;
	}
}

?>