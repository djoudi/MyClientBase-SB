<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class System_Messages extends CI_Model {
	
	private $CI;
	private $sm = '';
	
	protected $error = '';
	protected $success = '';
	protected $warning = '';
	
	public function __construct() {
		parent::__construct();
		$this->CI = &get_instance();
		$this->CI->load->model('system_message');
		$this->sm = new System_Message();
		$this->reset();
	}
	
	public function __destruct() {

	}
	
	public function __set($attribute, $value) {
		if($attribute == 'all') return false;
		
		if(isset($this->$attribute) && in_array($attribute, $this->sm->types)) {
			$this->set_system_message($attribute, $value);
			return true;
		}
		return false;
	}
	
	public function __get($attribute) {
		if($attribute == 'all') return $this->get_system_messages();
		
		return isset($this->$attribute) ? $this->$attribute : null;
	}
	
	/**
	 * Erases the oldests system messages stored in session
	 * 
	 * @access		private
	 * @param		none
	 * @return		nothing
	 * @author 		Damiano Venturin
	 * @since		Oct 30, 2012
	 */	 
	private function reset(){
		//$this->CI->session->set_userdata('system_messages',array());
		
		//Instead of erasing the whole history I leave in session the last 5 messages
		$history_items = 4;  //careful, cookie can hold no more than 4Kb: keep the history short
		$system_messages = $this->CI->session->userdata('system_messages');;
		if(count($system_messages) < $history_items) return;
		
		krsort($system_messages);
		while (count($system_messages) > $history_items) {
			array_pop($system_messages);
		}
		ksort($system_messages);
		$system_messages = array_values($system_messages);  //restores index keys
		$this->CI->session->set_userdata('system_messages',$system_messages);
	}
	
	/**
	 * Stores a system message in SESSION and logs it if in DEVELOPMENT mode
	 * 
	 * @access		private
	 * @param		$type	string	This is the system message type
	 * @param		$text	string 	This is the system message	
	 * @return		boolean
	 * @author 		Damiano Venturin
	 * @since		Oct 30, 2012
	 */
	private function set_system_message($type, $text) {
		
		if(!is_string($text)) return false;
		
		if(ENVIRONMENT == 'development') log_message('debug',$text);
		
		//creates a new system message
		$this->sm = new System_Message();
		$this->sm->text = $text;
		$this->sm->type = $type;
		
		//retrieves messages from session
		$system_messages =$this->get_system_messages();

		//update the messages array with the new message
		$system_messages[] = $this->sm->to_array();
		$this->CI->session->set_userdata('system_messages',$system_messages);
		return true;
	}

	/**
	 * Retrieves system messages from SESSIONS and returns them as an array
	 * 
	 * @access		private
	 * @param		None
	 * @return		array		
	 * @author 		Damiano Venturin
	 * @since		Oct 30, 2012
	 */	 
	private function get_system_messages() {
	
		//retrieves messages from session
		$system_messages = $this->CI->session->userdata('system_messages');
		if(!is_array($system_messages)) {
			return array();
		}
		
		return $system_messages;
	}
	
	/**
	 * Returns system messages as an array sorting them from the youngest to the older
	 * 
	 * @access		public
	 * @param		none			
	 * @return		array
	 * @author 		Damiano Venturin
	 * @copyright 	2V S.r.l.
	 * @since		Oct 30, 2012
	 */	 
	public function get_all(){
		$system_messages = $this->get_system_messages();
		$a = validation_errors();
		krsort($system_messages);  //descending sort. The youngest item goes first
		return $system_messages;
	}
	
}