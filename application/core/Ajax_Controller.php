<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax_Controller extends Admin_Controller {
	
	protected $callback = null;
	protected $form_title = null;
	protected $form_name = null;
	protected $html = null;
	protected $html_id = null;
	protected $replace = null;
	protected $message = null;
	protected $procedure = null;
	protected $status = false;
	protected $url = null; //destination for form action
	
	function __construct() {
		
		parent::__construct();
		
		$this->callback = urldecode(trim($this->input->get('callback')));
	
	}
	
	function __destruct(){
		$this->output();
	}
	
	protected function get_post_values(){
		
		if(!$post = $this->input->post()) return false;
		
		if(!isset($post['form']) || !is_array($post['form'])) return false;
		
		$form = array();
		$tmp = $post['form'];
		foreach ($tmp as $item){

			//checks if there was already a value with that name: if true it should be a checkbox group
			if(isset($form[$item['field']])){
				if(!is_array($form[$item['field']])){
					$tmp = 	$form[$item['field']];
					$form[$item['field']] = array();
					$form[$item['field']][] = $tmp;
				}
				$form[$item['field']][] = $item['value'];
			} else {
				//just add the item to the form array
				$form[$item['field']] = $item['value'];
			} 
		}
		
		return $form;
		
	}
	
	private function output()
	{
		$to_js = array();
		
		//TODO what about using reflection here?
		$to_js['form_title'] = urlencode(trim($this->form_title));
		$to_js['form_name'] = urlencode(trim($this->form_name));
		$to_js['html'] = urlencode($this->html);
		$to_js['html_id'] = urlencode($this->html_id);
		$to_js['message'] = urlencode(trim($this->message));
		$to_js['procedure'] = urlencode(trim($this->procedure));
		$to_js['status'] = urlencode($this->status);
		$to_js['url'] = urlencode(trim($this->url));
		$to_js['replace'] = $this->replace;
		
		$output = json_encode($to_js);
		if(!is_null($this->callback) && $this->callback){
			echo $this->callback .'('.$output.');';
		} else {
			echo $output;
		}
		exit();
	}	
	
	public function getForm(array $params = null){
		
		if(is_null($params)){
			if(!$params = $this->input->post('params') or !is_array($params)){
				$this->message = 'Ajax: input parameters are missing';
				return false;
			}
		}
		
		//defaults
		$template = 'jquery_form.tpl';
		$module = null;
		
		extract($params);
		
		$data = array();
		
		if(isset($obj)){
			$object = json_decode($obj);
			if(isset($object->_fields) && is_object($object->_fields)) {
				foreach ($object->_fields as $attribute => $specifics){
					$object->_fields->$attribute = json_decode($specifics);
				}
			}
			$data['object'] = $object;
		}
		
		if(isset($procedure)) $this->procedure = $procedure;
		if(isset($form_title)) $this->form_title = $data['form_title'] = $form_title;
		if(isset($form_name)) $this->form_name = $data['form_name'] = $form_name;
		if(isset($form_title)) $data['form_title'] = $form_title;
		if(isset($url)) $this->url =  $data['url'] =  $url;
		  
		if($this->html = $this->load->view($template, $data, true, 'smarty')) $this->status = true;
		 
	}
}