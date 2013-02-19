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
		
		if(!empty($this->message)) {
			if($this->status){
				$this->mcbsb->system_messages->success = $this->message;
				log_message('info',$this->message);
			} else {
				$this->mcbsb->system_messages->error = $this->message;
				log_message('error',$this->message);
			}
		}
		
		//TODO what about using reflection here?
		$to_js = array();
		$to_js['form_title'] = urlencode(trim($this->form_title));
		$to_js['form_name'] = urlencode(trim($this->form_name));
		$to_js['html'] = urlencode($this->html);
		$to_js['html_id'] = urlencode($this->html_id);
		$to_js['message'] = urlencode(trim($this->message));
		$to_js['procedure'] = urlencode(trim($this->procedure));
		$to_js['status'] = $this->status;
		if(isset($this->url)) $to_js['url'] = urlencode(trim($this->url));
		if(isset($this->replace)) $to_js['replace'] = $this->replace;
		if(isset($this->focus_tab)) $to_js['focus_tab'] = $this->focus_tab;
		
		$output = json_encode($to_js);
		if(!is_null($this->callback) && $this->callback){
			echo $this->callback .'('.$output.');';
		} else {
			echo $output;
		}
		exit();
		
	}
	
	/**
	 * This function is useful when parsing POST values sent through postToAjax jquery function.
	 * It goes through POST values and returns the submitted form values as an array.
	 * 
	 * @access		public
	 * @param		none
	 * @return		array
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 12, 2013
	 */
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
	
	/**
	 * This method is generally trigged by a jquery ajax request.
	 * It gets some input parameters that are manipulated and sent to a template
	 * which returns some html. The html is then returned.
	 * 
	 * Typically the $params array might contain:
	 * - a json encoded Form Object: $params['obj']
	 * - a template url: $params['template'] 							(it has a default value used if not specified in $params)
	 * - a module name (to spot the template file): $params['module']	(it has a default value used if not specified in $params)
	 * - a js procedure name: $params['procedure']						(it has a default value used if not specified in $params)
	 * - the form method: $params['form_method']						(it has a default value used if not specified in $params)
	 * - the form name: $params['form_name']							(it has a default value used if not specified in $params)
	 * - the form title: $params['form_title']							(it has a default value used if not specified in $params)
	 * - the form action url or the url to send the next ajax request: $params['url']  
	 * 
	 * @access		public
	 * @param		array $params	
	 * @return		
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 5, 2013
	 */
	public function getForm(array $params = null){
		
		if(is_null($params)){
			$params = $this->input->post('params');
			if(!is_array($params)){
				$this->message = 'Ajax: input parameters are missing';
				return false;
			}
		}
		
		//defaults
		$form_method = 'POST';
		$form_name = 'my_form';
		$form_title	= 'Unknown Form';
		$module = '';
		$procedure = 'procedure_not_set';
		$template = 'jquery_form.tpl';
		$data = array();
		
		//if $params contains variables described among the "defaults" values written above will be overwritten
		extract($params,EXTR_OVERWRITE);
		
		if(isset($obj) && is_string($obj)){
			$object = json_decode($obj);
			if(isset($object->_fields) && is_object($object->_fields)) {
				foreach ($object->_fields as $attribute => $specifics){
					$object->_fields->$attribute = json_decode($specifics);
				}
			}
		
			$data['object'] = $object;
		}
		
		
		$this->form_name = $data['form_name'] = $form_name;
		$this->form_method = $data['form_method'] = $form_method;
		$this->form_title = $data['form_title'] = t($form_title);
		if(isset($url)) $this->url =  $data['url'] = $url;
		$this->procedure = $procedure;
		
		  
		if($this->html = $this->load->view($template, $data, true, 'smarty', $module)) {
			$this->status = true;
		}
		 
	}
	
	public function get_contact_locations($contact_id_key, $contact_id){
		
		if(!is_string($contact_id_key) && !empty($contact_id_key)) return false;
		if(!is_string($contact_id) && !empty($contact_id)) return false;
		
		$CI  = &get_instance();

		$CI->load->model('contact/mdl_contact','contact');
		

		
		
		if($contact_id_key == 'uid'){
			$CI->load->model('contact/mdl_person','person');
			$CI->person->uid = $contact_id;
			$contact = $CI->person;
		}
		
		if($contact_id_key == 'oid'){
			$CI->load->model('contact/mdl_organization','organization');
			$CI->organization->oid = $contact_id;
			$contact = $CI->organization;
		}
		
		//retrieves the contact
		if(isset($contact) && $contact->get(null,false)){
			if($contact->crr->has_no_errors){
				$contact_locs = array();
				
				if($main = $contact->hasProperAddress()){
					
					$city = '';
					if($contact->objName == 'person'){	
						$city = trim($contact->mozillaHomeLocalityName);
					}
					
					if($contact->objName == 'organization'){
						$city = trim($contact->l);
					}
					
					$contact_locs[] = array(
							'label' => 'main',
							'address' => $main,
							'city' => $city
							);
				}
			}
		}
		
		//retrieves contact's additional locations
		if(isset($contact->locRDN)) $locs = explode(",", $contact->locRDN);
		if(isset($locs) && count($locs) > 0){
		
			$CI->load->model('contact/mdl_location','location');
		
			foreach( $locs as $locId) {
		
				$CI->location->locId = $locId;
		
				if($CI->location->get(null,false)) {
						
					$loc_description = strtolower($CI->location->locDescription);
						
					if($loc_description == 'home' || $loc_description == 'registered address') {
						continue;
					}
		
					$address = $CI->location->locStreet . ', ' . $CI->location->locZip . ' ' . $CI->location->locCity . ' ' . $CI->location->locState . ' ' . $CI->location->locCountry;
					$contact_locs[] = array(
							'label' => $loc_description,
							'address' => $address,
							'city' => $CI->location->locCity);
		
				}
			}
		}

		return $contact_locs;
	}
}