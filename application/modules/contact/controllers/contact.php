<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Contact extends Admin_Controller {

	public $enabled_modules;
	
    function __construct() {

        parent::__construct();
                
        //$this->_post_handler();

        $this->load->model('mdl_contacts');
    }
    
    public function index(){
    	$this->search();
    }
    
    public function search() {
    	 
    	$search = urldecode(trim($this->input->post('search')));
    
    	//let's look in the URL
    	if(!$search) {
    		$segs = $this->uri->segment_array();
    		if(isset($segs['2']) && isset($segs['3']) && $segs['2'] == 'search') {
    			$search = urldecode(trim($segs['3']));
    		}
    	}
    
    	if(!$search)
    	{
    		$reset = $this->input->post('reset');
    		if($reset) {
    			$this->session->set_userdata('search', '');
    			$search = '';
    			$wanted_page = 0;
    			$this->mcbsb->system_messages->success = 'Contact search has been reset' ;
    		} else {
    			//try to retrieve from session
    			$search = $this->session->userdata('search');
    			$wanted_page = $this->get_wanted_page();
    		}
    	} else {
    		//the user just submit a search
    		$this->session->set_userdata('search', $search);
    		$this->redir->set_last_index(site_url('contact/index'));
    		$wanted_page = '0';
    	}
    
    	$params = array(
    			'paginate'		=>	TRUE,
    			'items_page'	=>	$this->mcbsb->settings->setting('results_per_page'),
    			'wanted_page'	=>	$wanted_page,
    			'search'		=>  $search,
    			'sort_by'		=>  $this->get_set_sort_by('search_sort_by'),
    	);
    	if(isset($uid)) $params['uid'] = $uid;
    	if(isset($oid)) $params['oid'] = $oid;
    
    
    	//FLOW_ORDER
    	if($this->input->get('flow_order')) {
    		if($this->input->get('flow_order') == 'asc' || $this->input->get('flow_order') == 'desc') {
    			$params['flow_order'] = $this->input->get('flow_order');
    		}
    	}
    
    	$data = array();
    
    	$people = new Mdl_Person();
    	$data['people_total_number'] = $people->count_all();
    
    	$organizations = new Mdl_Organization();
    	$data['organizations_total_number'] = $organizations->count_all();
    
    	//PERFORM SEARCH
    	$data['contacts'] =	$this->mdl_contacts->get($params);
    
    	//sets the next_flow_order variable for the template to the opposite order of the current view
    	if(isset($params['flow_order'])){
    		if($params['flow_order'] == 'asc') {
    			$data['next_flow_order'] = 'desc';
    		} else {
    			$data['next_flow_order'] = 'asc';
    		}
    	} else {
    		$data['next_flow_order'] = 'desc';
    	}
    	 
    	$data['pager'] = $this->mdl_contacts->page_links;
    	if($search) {
    		//generates system message for the notification area
    		$message = 'Last search';
    		if($translation = $this->lang->line($message)) $message = $translation;
    		$final_message = $message . ' "' . $search . '"';
    		 
    		$message = 'produced';
    		if($translation = $this->lang->line($message)) $message = $translation;
    		$final_message .= ' '.$message;
    		 
    		$num_contacts = $data['contacts']['total_number'];
    		if($num_contacts == 0){
    			$message = 'no results';
    			if($translation = $this->lang->line($message)) $message = $translation;
    			$final_message .= ' '.$message;
    		}
    		 
    		if($num_contacts == 1){
    			$message = 'result';
    			if($translation = $this->lang->line($message)) $message = $translation;
    			$final_message .= ' '.$num_contacts.' '.$message;
    		}
    
    		if($num_contacts > 1){
    			$message = 'results';
    			if($translation = $this->lang->line($message)) $message = $translation;
    			$final_message .= ' '.$num_contacts.' '.$message;
    		}
    		 
    
    		$this->mcbsb->system_messages->success = $final_message ;
    		 
    		$data['searched_string'] = $search;
    		$data['made_search'] = true;
    	}
    
    	//loading Smarty template
    	$this->load->view('contact_search.tpl', $data, false, 'smarty','contact');
    }

    public function all_people() {
    	 
    	$this->all('all_people');
    }
    
    public function all_organizations() {
    
    	$this->all('all_organizations');
    }

    public function by_location(){
    	 
    	$city = urldecode(trim($this->input->post('city')));
    	$state = urldecode(trim($this->input->post('state')));
    	$country = urldecode(trim($this->input->post('country')));
    	 
    	$search = array();
    	if($city) $search['city'] = $city;
    	if($state) $search['state'] = $state;
    	if($country) $search['country'] = $country;
    	
    	if(count($search) == 0)
    	{
    		$reset = $this->input->post('reset');
    		if($reset) {
    			$this->session->set_userdata('search_by_location', '');
    			$search = array();
    			$this->mcbsb->system_messages->success = 'Contact search by location has been reset' ;
    			$data = array();
    			$this->load->view('by_location.tpl', $data, false, 'smarty','contact');
    			return;
    		} else {
    			//try to retrieve from session
    			$search = $this->session->userdata('search_by_location');
    
    			if(empty($search) || !$search) {
    				$data = array();
    				$this->load->view('by_location.tpl', $data, false, 'smarty','contact');
    				return;
    			}
    		}
    	} else {
    		//the user just submit a search
    		$this->session->set_userdata('search_by_location', $search);
    		//$this->redir->set_last_index(site_url('contact/by_location'));
    		$wanted_page = '0';
    	}
    
    	$searched_string = implode(',',$search);
    	 
    	$data = array();
    	 
    	//performs a search without pagination to calculate statistics
    	$params = array(
    			'paginate'		=>	FALSE,
    			'search'		=>  $search,
    	);
    	 
    	if($contacts = $this->mdl_contacts->get($params,true)) {
    		$statistics = array(
    				'unknown_city' => array('people' => 0, 'organizations' => 0),
    				'unknown_state' => array('people' => 0, 'organizations' => 0),
    				'unknown_country' => array('people' => 0, 'organizations' => 0),
    		);
    
    		foreach ($contacts['people'] as $key => $person) {
    			 
    			if(isset($person->mozillaHomeLocalityName)) {
    				if(!isset($statistics[$person->mozillaHomeLocalityName])) {
    					$statistics[$person->mozillaHomeLocalityName] = array('people' => 1, 'organizations' => 0);
    				} else {
    					$statistics[$person->mozillaHomeLocalityName]['people']++;
    				}
    			}
    			 
    			if(!isset($person->mozillaHomeLocalityName) || empty($person->mozillaHomeLocalityName)) {
    				$statistics['unknown_city']['people']++;
    			}
    		}
    
    		foreach ($contacts['orgs'] as $key => $org) {
    			 
    			if(isset($org->l)) {
    				if(!isset($statistics[$org->l])) {
    					$statistics[$org->l] = array('people' => 0, 'organizations' => 1);
    				} else {
    					$statistics[$org->l]['organizations']++;
    				}
    			}
    			 
    			if(!isset($org->l) || empty($org->l)){
    				$statistics['unknown_city']['organizations']++;
    			}
    		}
    
    		if($statistics['unknown_city']['people'] == 0 && $statistics['unknown_city']['organizations'] == 0) unset($statistics['unknown_city']);
    		if($statistics['unknown_state']['people'] == 0 && $statistics['unknown_state']['organizations'] == 0) unset($statistics['unknown_state']);
    		if($statistics['unknown_country']['people'] == 0 && $statistics['unknown_country']['organizations'] == 0) unset($statistics['unknown_country']);
    		 
    		foreach ($statistics as $stat_city => $values) {
    			$statistics[$stat_city]['total'] = ($values['people'] + $values['organizations'] );
    		}
    		 
    		ksort($statistics);
    		 
    		$data['summary'] = array(
    				'total_people' => count($contacts['people']),
    				'total_organizations' => count($contacts['orgs']),
    				'total_number' => $contacts['total_number']
    		);
    
    		//TODO translation
    		$this->mcbsb->system_messages->success = 'Last search ' . $searched_string . ' produced ' . $data['summary']['total_number'] . ' results' ;
    	}
    	 
    	//performs the same search using pagination (to show results in the bottom of the page)
    	$params = array(
    			'paginate'		=>	TRUE,
    			'items_page'	=>	$this->mcbsb->settings->setting('results_per_page'),
    			'wanted_page'	=>	$this->get_wanted_page(),
    			'search'		=>  $search,
    			'sort_by'		=>  $this->get_set_sort_by('search_by_location_sort_by'),
    	);
    
    	$data['contacts'] =	$this->mdl_contacts->get($params,true);
    	$data['statistics'] = $statistics;
    	$data['pager'] = $this->mdl_contacts->page_links;
    	$data['searched_string'] = $searched_string;
    	$tmp = $search;
    	array_walk($tmp, create_function('&$i,$k','$i=" $k=\"$i\"";'));
    	$data['searched_string_extended'] = implode(" ", $tmp);
    	    	 
    	//loading Smarty template
    	$this->load->view('by_location.tpl', $data, false, 'smarty','contact');
    }
    
    public function details() {
    
    	//array sent to the view
    	$data = array();
    
    	//TODO is this necessary?
    	//set the focus of the tab
    	if ($this->session->flashdata('tab_index')) {
    			
    		$tab_index = $this->session->flashdata('tab_index');
    
    	} else {
    
    		$tab_index = 0;
    
    	}
    
    	$data['tab_index']	= $tab_index;
    	$data['profile_view'] = true;
    
    	if($contact = $this->retrieve_contact()) {
    		$data['contact'] =	$contact;
    	} else {
    		$this->mcbsb->system_messages->error = 'Contact not found';
    		redirect('/');
    	}
    		
    	//getting Locations
    	if(isset($contact->locRDN)) $locs = explode(",", $contact->locRDN);
    	if(isset($locs) && is_array($locs))
    	{
    		$contact_locs = array();
    
    		foreach( $locs as $locId)
    		{
    			$this->location->locId = $locId;
    			if($this->location->get())
    			{
    				$this->location->prepareShow();
    				$contact_locs[] = clone $this->location;
    			}
    		}
    	}
    
    	//getting Organizations of which the contact is member
    	if(isset($contact->oRDN)) $orgs = explode(",", $contact->oRDN);
    	if(isset($orgs) && is_array($orgs))
    	{
    		$contact_orgs = array();
    		 
    		foreach( $orgs as $oid)
    		{
    
    			$this->organization->oid = $oid;
    
    			if($this->organization->get(null, false))
    			{
    				$this->organization->prepareShow();
    				$contact_orgs[] = clone $this->organization;
    			}
    		}
    	}
    
    	//in case it's an organization I retrieve the members
    	if(isset($contact->oid))
    	{
    		$members = array();
    		 
    		$input = array('filter' => '(oRDN='.$contact->oid.')',
    				'wanted_attributes' => array('uid'));
    		if($crr = $this->person->get($input, true))
    		{
    			$uids = $crr['data'];
    			foreach ($uids as $item)
    			{
    				$this->person->uid = $item['uid']['0'];
    				if($this->person->get(null, false))
    				{
    					$this->person->prepareShow();
    					$members[] = clone $this->person;
    				}
    			}
    		}
    	}
    
    	/*
    	 //sparkleshare plugin
    	//retrieves documents for the contact
    	//user: git
    	$ss_ident =	'z0S9ZSya';
    	$ss_authCode = '1AFDm30dwMXkL0pdHTZmAGQATgJ1AV1Yi50clqm0RUV_EbRxaN4EiDO8c59NB662p4AVWUeBHkihrKArK0L_RqF3ugs7U3Cf9lqmr_1XbynEdVaJbevKAVvQiuWDusdMskSQewFf1Gya4ZIVqbniTiJiYtl-wm45En1txvWtKfBfcrj7iL77hGtCfCZWq_o4Ivr4lXHd';
    	$ss_host = 'tooljardev';
    	$ss_port = '3000';
    
    	// Load the configuration file
    	$this->load->config('rest');
    		
    	// Load the rest client
    	$this->load->spark('restclient/2.1.0');
    
    	$this->rest->initialize(array('server' => 'http://'.$ss_host.':'.$ss_port.'/api/'));
    	$this->rest->api_key($ss_ident, 'X-SPARKLE-IDENT');
    	$this->rest->api_key($ss_authCode, 'X-SPARKLE-AUTH');
    	$result = $this->rest->get('getFolderList', null, 'json');
    	if($result)
    	{
    	foreach ($result as $key => $folder) {
    	if($folder->name = 'Contacts documents') {
    	$ss_contacts_doc_folder_id = $folder->id;
    	$ss_contacts_doc_folder_url = 'http://'.$ss_host.':'.$ss_port.'/folder/'.$ss_contacts_doc_folder_id;
    	}
    	}
    	}
    
    	if($ss_contacts_doc_folder_id) {
    	//list folder content
    	$this->rest->api_key($ss_ident, 'X-SPARKLE-IDENT');
    	$this->rest->api_key($ss_authCode, 'X-SPARKLE-AUTH');
    	$ss_contacts_doc_folder_content = $this->rest->get('getFolderContent/'.$ss_contacts_doc_folder_id, null, 'json');
    	 
    	//look for the contact's folder
    	$contact_folder = 'coyote_willy';
    	if($ss_contacts_doc_folder_content) {
    	foreach ($ss_contacts_doc_folder_content as $key => $item) {
    	if($item->type == 'dir' && $item->name == $contact_folder) {
    	$ss_contact_folder_id = $item->id;
    	$ss_contact_folder_url = $item->url;
    	}
    	}
    	}
    	 
    	if($ss_contact_folder_id) {
    	//get contact's content
    	$this->rest->api_key($ss_ident, 'X-SPARKLE-IDENT');
    	$this->rest->api_key($ss_authCode, 'X-SPARKLE-AUTH');
    	$ss_contact_folder_content = $this->rest->get('getFolderContent/'.$ss_contacts_doc_folder_id.'?'.$ss_contact_folder_url, null, 'json');
    	}
    	 
    	if($ss_contact_folder_content) {
    
    	//I don't want to list directories for now => I remove the folder items
    	//I also count the items
    	foreach ($ss_contact_folder_content as $key => $item) {
    	if($item->type == 'dir') {
    	unset($ss_contact_folder_content[$key]);
    	} else {
    	//if it's a hidden file I do not show it
    	$match = preg_match('/^\./', $item->name);
    	if($match == 1)	unset($ss_contact_folder_content[$key]);
    	}
    	}
    
    	$ss_contact_folder_num_items = count($ss_contact_folder_content);
    	}
    	}
    	*/
    
    	$location_model = clone $this->location;
    	$data['location_model'] = $location_model;
    
    
    	//getting invoices and quotes
    	if($this->mcbsb->is_module_enabled('invoices')) {
    		$this->load->model('invoices/mdl_invoices');
    
    		$data['invoice_module_is_enabled'] = true;
    
    		$tmpdata = array('invoices/mdl_invoices');  //TODO is this necessary?
    
    		$invoice_params = array(
    				'where'	=>	array(
    						'mcb_invoices.client_id'        =>	$contact->client_id,
    						'mcb_invoices.invoice_is_quote' =>  0
    				)
    		);
    
    		$invoices = $this->mdl_invoices->get($invoice_params);
    		$tmpdata['invoices'] = $invoices;
    		$data['invoices'] = $invoices;
    		$data['invoices_html'] = $this->load->view('invoices/invoice_table',$tmpdata,true);
    
    		$quote_params = array(
    				'where'	=>	array(
    						'mcb_invoices.client_id'        =>	$contact->client_id,
    						'mcb_invoices.invoice_is_quote' =>  1
    				)
    		);
    
    		$quotes = $this->mdl_invoices->get($quote_params);
    		$tmpdata['invoices'] = $quotes;
    		$data['quotes'] = $quotes;
    		$data['quotes_html'] = $this->load->view('invoices/invoice_table',$tmpdata,true);
    
    	}
    
    	if(isset($contact_locs)) $data['contact_locs'] = $contact_locs;
    	if(isset($contact_orgs)) $data['contact_orgs'] = $contact_orgs;
    	if(isset($members))
    	{
    		$data['members'] = $members;
    		$data['member_fields'] = array('mobile', 'homePhone', 'companyPhone', 'facsimileTelephoneNumber',  'mail');
    	}
    
    	if(isset($ss_contact_folder_num_items) && $ss_contact_folder_num_items > 0) {
    		$data['ss_contacts_doc_folder_url'] = $ss_contacts_doc_folder_url;
    		$data['ss_contact_folder_content'] = $ss_contact_folder_content;
    		$data['ss_contact_folder_num_items'] = $ss_contact_folder_num_items;
    	}
    
    	$data['tooljar_module_is_enabled'] = $this->mcbsb->is_module_enabled('tooljar');
    
    	//looking for tabs provided by other modules
		$data['extra_tabs'] = $this->get_extra_tabs($contact);
    	
    	//loading Smarty templates
    	$this->load->view('contact_details.tpl', $data, false, 'smarty','contact');
    
    }
    
    public function get_extra_tabs($contact){
    
    	$tabs = array();
    	
    	foreach ($this->mcbsb->_modules['enabled'] as $item) {
    			
    		$module = new Module();
    		$module->module_name = $item;
    		if($module->read()){

    			if (!empty($module->module_config['contact_tabs'])) {
    					
    				$contact_tabs = $module->module_config['contact_tabs'];
    					
    				//transform the value in array to simplify the process
    				if(!is_array($contact_tabs)){
    					$tmp = $contact_tabs;
    					$contact_tabs = array($tmp);
    				}
    					
    				//clean up
    				$contact_tabs = array_filter(array_map('trim',$contact_tabs));

					foreach ($contact_tabs as $key => $selected_module_path){

						$tmp = array();
						$html = '';
						$tab_name = !is_numeric($key)? $key : null;
						if($return = $this->get_tab_content($selected_module_path, $contact)){
						$tabs[]= array(
										'title' => $tab_name,
										'counter' => $return['counter'],
										'buttons' => $return['buttons'],
										'html' => $return['html']
										);
						}
     				} 
    			}
    		}
    	}

    	return $tabs;
    }    
    
    private function parse_contact_tabs(Module $module , array $contact_tabs) {
    	
    	
  	
    }
    
    private function get_tab_content($selected_module_path, $contact){
    	
    	$module_name = array_pop(array_filter(preg_split('/\//', $selected_module_path)));
    	$this->load->model($selected_module_path,$module_name);
    	
    	if(method_exists($this->$module_name,'contact_tab')){
    		return $this->$module_name->contact_tab($contact);
    	}
    	
    	return false;
    }
    
    
    /**
     * Shows the Edit profile page or handles the update after a submit 
     * 
     * @access		public
     * @param		none		
     * @return				
     * 
     * @author 		Damiano Venturin
     * @since		Nov 7, 2012
     */
    public function form() {
    	
    	//let's look in GET, POST and URL to see with which kind of object we are dealing with.
    	//if uid or oid is found the object $this->$obj_name is retrieved
    	$obj_name = $this->getContactById();
    	
    	
    	//The contact creation always happens in ajax => this controller will handle only the contact update but
    	//I need to know if I should provide a populated form or if I should start the update/creation procedure

    	if(empty($obj_name) || !in_array($obj_name, array('person','organization'))){
    		$this->mcbsb->system_messages->error = 'The specified contact can not be found';
    		redirect('/');
    	}    	
    	
    	//prefill the object with default settings
    	$this->$obj_name->set_default_values();
    	
    	//$a = $this->$obj_name;
    	
    	if(!$this->input->post('contact_save')){
    		
    		$this->redir->set_last_index();
    		
    		//It's not a submit => I have to provide a populated form
    		$contact_id = $this->$obj_name->uid ? $this->$obj_name->uid : $this->$obj_name->oid;   
    		$this->prepare_form($obj_name);
    	}    		
    	
     	if($this->input->post('contact_save')){
     		
     		//$b = $this->$obj_name;
     		
     		//Sets the form rules for the specified object
     		if($obj_name) $this->$obj_name->setFormRules();
     		
     		//it's a submit and there are these possibilities:
     		// 1) the submitted form doesn't match the form rules => then I stay on the form page and I notify the errors
     		// 2) the submitted form matches the form and it's an update
    		
	    	if($this->$obj_name->validateForm()){
	    		
	    		//$c = $this->$obj_name;
	    		
	    		//The form has been validated. Let's check if there is any binary file uploaded
	    		$upload_info = saveUploadedFile();

	    		//error handling for upload
				if(is_array($upload_info['error'])) {
					foreach ($upload_info['error'] as $key => $error) {
						$this->mcbsb->system_messages->warning = t(trim(strip_tags($error)));
					}
				}
				
				//converts to base_64 the uploaded file
	    		if(is_array($upload_info['data'])) {
	    		
	    			$this->load->helper('file');
	    		
	    			foreach ($upload_info['data'] as $element => $element_status)
	    			{
	    				//reads the file and converts it in base64 and stores it in $obj_name
	    				if($element_status['full_path']){
	    					$binary_file = base64_encode(read_file($element_status['full_path']));
	    					if($binary_file) $this->$obj_name->$element = $binary_file;
	    					unlink($element_status['full_path']);
	    				}
	    			}
	    		}
	    		
	    		//ready to save in ldap
	    		if($this->$obj_name->save()) {
	    		
	    			if(isset($this->$obj_name->uid))  redirect("/contact/details/uid/".$this->$obj_name->uid);
	    		
	    			if(isset($this->$obj_name->oid))  redirect("/contact/details/oid/".$this->$obj_name->oid);
	    		}
	    		
	    	} else {

	    		$validation_errors = array_filter(array_map('trim',explode('|', validation_errors())));
	    		foreach ($validation_errors as $key => $error) {
	    			$this->mcbsb->system_messages->error = $error;
	    		}	 
    		}
    	}     	
             
    	$upload_settings = array();
    	
    	if($conf = $this->load->config('upload',true,true)){
    		
	    	$upload_settings['max_size'] = $conf['max_size'];
	    	$upload_settings['max_width'] = $conf['max_width'];
	    	$upload_settings['max_height'] = $conf['max_height'];
	    	
    	}
    			
    	$data = array(
    			'contact'			=>  $this->$obj_name,							
    			'form_url'			=> 	$this->get_form_action_url($obj_name),
    			'upload_settings'	=> 	$upload_settings,
    			
    			//'custom_fields'     =>	$this->mdl_contacts->custom_fields,   //TODO delme
    			//'invoice_groups'    =>  $this->mdl_invoice_groups->get()			//TODO delme
    	);
    	
    	//loading Smarty template
    	$this->load->view('contact_form.tpl', $data, false, 'smarty','contact'); 
        
    }
    
    public function delete() {
    
    	$this->index();
    	 
    	//TODO delme
    	//     	$client_id = uri_assoc('client_id');
    	//     	if ($client_id) $deleted = $this->mdl_contacts->delete($client_id);
    	//     	$this->redir->redirect('contact');
    
    }    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    private function prepare_form($obj_name){
    	 
    	if(!is_string($obj_name) || empty($obj_name)) return false;
    	 
    	$this->$obj_name->getProperties();
    	$a = $this->$obj_name;
    	foreach ($this->$obj_name->properties as $key => $property) {
    		$this->mdl_contacts->form_values[$key] = $this->$obj_name->$key;
    	}
    	 
    	$this->$obj_name->prepareShow();
    	return true;
    }
    
    private function get_form_action_url($obj_name){
    	 
    	if(!is_string($obj_name) || empty($obj_name)) return false;
    	 
    	//sets form submit url
    	if(isset($this->$obj_name->uid) && !empty($this->$obj_name->uid)) $form_url = "/contact/form/uid/".$this->$obj_name->uid;
    
    	if(isset($this->$obj_name->oid) && !empty($this->$obj_name->oid)) $form_url = "/contact/form/oid/".$this->$obj_name->oid;
    
    	if(!isset($form_url)) $form_url = "/contact/form/add/".$obj_name;
    	 
    	return $form_url;
    }
        
    private function get_set_sort_by($tag){
    	$params = array('sort_by' => array());
    	 
    	if($sort_by = $this->input->get('sort_by')) {
    		if($sort_by == 'sn' || $sort_by == 'o') {
    			$params['sort_by'][] = 'sn';
    			$params['sort_by'][] = 'o';
    		}
    		 
    		if($sort_by == 'mozillaHomeLocalityName' || $sort_by == 'l') {
    			$params['sort_by'][] = 'mozillaHomeLocalityName';
    			$params['sort_by'][] = 'l';
    		}
    		 
    		$this->mcbsb->session->set_userdata($tag,$params['sort_by']);
    	} else {
    		$sort_by = $this->mcbsb->session->userdata($tag);
    		if(!empty($sort_by)) $params['sort_by'] = $sort_by;
    	}
    	 
    	return $params['sort_by'];
    }
    
    
    private function getContactById()
    {
    	//I can get the $contact_id in 4 possible ways: by uid, by oid, by POST and GET
    	$uid = $this->input->post('uid');
    	if(!$uid) unset($uid);
    
    	if($uid_value = uri_assoc('uid')) //retrieving uid from GET
    		if($uid_value) $uid = $uid_value;
    
    	$oid = $this->input->post('oid');
    	if(!$oid) unset($oid);
    
    	if($oid_value = uri_assoc('oid')) //retrieving oid from GET
    		if($oid_value) $oid = $oid_value;
    
    	if(isset($uid) && isset($oid))
    	{
    		return false; //I can't understand if it's a person or an organization
    	} else {
    		if(!isset($uid) && !isset($oid))
    		{
    			//let's look for the contact_id
    			$contact_id = uri_assoc('client_id');
    
    			if(empty($contact_id))
    			{
    				if($this->input->post('client_id')) $contact_id = $this->input->post('client_id'); //retrieving client_id from POST
    			}
    			if(empty($contact_id)) return false; //there is no other way to get the object
    		}
    	}
    
    	//retrieve the exact object (person or organization)
    	if(isset($contact_id)) {
    
    		$this->contact->client_id = $contact_id;
    		if(! $this->contact->get(null,false)) return false;
    			
    		//TODO something better
    		if(isset($this->contact->uid) && !empty($this->contact->uid))
    		{
    			$uid = $this->contact->uid;
    		}
    
    		if(isset($this->contact->oid) && !empty($this->contact->oid))
    		{
    			$oid = $this->contact->oid;
    		}
    			
    	}
    
    	if(isset($uid)) {
    		$this->person->uid = $uid;
    		if(! $this->person->get(null,false)) return false;
    		$this->person->prepareShow();
    		return $this->person->objName;
    	}
    
    	if(isset($oid)) {
    		$this->organization->oid = $oid;
    		if(! $this->organization->get(null,false)) return false;
    		$this->organization->prepareShow();
    		return $this->organization->objName;
    	}
    
    	return false;
    }    
    
    private function retrieve_contact()
    {	
    	$params = retrieve_uid_oid();
    	
    	if(is_null($params) || count($params)==0) return false;
    	
    	//when the request is performed using client_id || uid || oid as input I get an object in return, not an array
    	if(is_object($obj = $this->get($params)))
    	{
    		//$obj = $rest_return;
    		$obj->prepareShow();
    		return $obj;
    	}
    	
    	return false;
    }

    private function prepareShow($obj, &$contact)
    {
    	$this->$obj->prepareShow();
    	$contact->show_fields = $this->$obj->show_fields;
    	$contact->hidden_fields = $this->$obj->hidden_fields;
    	$contact->aliases = $this->$obj->aliases;
    	return $contact;
    }
    
    private function all($search){
    	 
    	if(!is_string($search) || empty($search)) redirect('/');
    	 
    	$params = array(
    			'paginate'		=>	TRUE,
    			'items_page'	=>	$this->mcbsb->settings->setting('results_per_page'),
    			'wanted_page'	=>	$wanted_page = $this->get_wanted_page(),
    			'search'		=>  $search
    	);
    	 
    	$data = array();
    	 
    	$people = new Mdl_Person();
    	$data['people_total_number'] = $people->count_all();
    	 
    	$organizations = new Mdl_Organization();
    	$data['organizations_total_number'] = $organizations->count_all();
    	 
    	$data['contacts'] =	$this->mdl_contacts->get($params);
    	$data['pager'] = $this->mdl_contacts->page_links;
    	 
    	//loading Smarty template
    	$this->load->view('contact_all.tpl', $data, false, 'smarty','contact');
    	 
    }    

    function get($params = NULL) {

        return $this->mdl_contacts->get($params);

    }

    
    /**
     * work around to make CodeIgniter pagination fit ContactEngine pagination
     *
     * @access		private
     * @param
     * @var
     * @return		string
     * @example
     * @see
     *
     * @author 		Damiano Venturin
     * @copyright 	2V S.r.l.
     * @license		GPL
     * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
     * @since		Feb 6, 2012
     *
     */
    private function get_wanted_page()
    {
    	$results_per_page = $this->mcbsb->settings->setting('results_per_page');
    	 
    	if($results_per_page == 0) return 0;
    	 
    	$uripage = uri_assoc('page');
    	$page = ceil(uri_assoc('page') / $results_per_page);
    	 
    	if($page <= 0) return 0;
    	 
    	return $page;
    }
    
    
/*     function _post_handler() {

        if ($this->input->post('btn_save')) {

            return $this->form(true);

        }

        elseif ($this->input->post('btn_edit_client')) {

            redirect('contact/form/client_id/' . uri_assoc('client_id'));

        }

        elseif ($this->input->post('btn_cancel')) {

            redirect($this->session->userdata('last_index'));

        }

        elseif ($this->input->post('btn_add_contact')) {

            redirect('contact/contacts/form/client_id/' . uri_assoc('client_id'));

        }

        elseif ($this->input->post('btn_add_invoice')) {

            redirect('invoices/create/client_id/' . uri_assoc('client_id'));

        }

        elseif ($this->input->post('btn_add_quote')) {

            redirect('invoices/create/quote/client_id/' . uri_assoc('client_id'));

        }

    } */

    
    
}

?>