<?php 

class Ajax extends CI_Controller {
	
	public function __construct() {
	
		parent::__construct();
	
	}	
	
	function tr(){
	
		$post = $this->input->post();
	
		if(isset($post['text'])) {
			$text = $post['text'];
			echo t($text);
		}
	}
}
?>