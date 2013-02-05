<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('assets/asset','asset');
		
		if($modules_to_load = $this->config->item('asset_modules_to_load')){
			
			foreach ($modules_to_load as $module_to_load){
			
				if(strtolower($module_to_load) != strtolower(get_class($this))){
					
					$this->load->model('assets/'.$module_to_load,$module_to_load);
				}
			}			
		}		
	}
	
	public function save_asset(){
		
		if($post = $this->input->post()){
			
			if(!isset($post['category']) || empty($post['category'])) {
				$this->mcbsb->system_messages->error = 'Error while saving the asset '.$asset_id;
				redirect('/'); //TODO here it would be nice to load the last position
			}
			
			$obj = $post['category'];
			
			foreach ($post as $attribute => $value) {
				if(empty($post['id']) && $attribute == 'id'){
					continue;
				} else {
					$this->$obj->$attribute = $value;
				}
			}
			
			if(empty($post['id'])) {
				$id = $this->$obj->create();
				$message = 'asset #' . $id . ' successfully created';
			} else {
				$id = $this->$obj->update();
				$message = 'asset #' . $id . ' successfully updated';
			}
			
			if($id){
				$this->mcbsb->system_messages->success = $message;
			} else {
				$asset_id = empty($this->$obj->id) ? '' : '#'.$this->$obj->id;
				$this->mcbsb->system_messages->error = 'Error while saving the asset '.$asset_id;
			}
			
			if($this->$obj->contact_id_key && $this->$obj->contact_id) {
				redirect('/contact/details/' .$this->$obj->contact_id_key. '/' . $this->$obj->contact_id .'/#tab_Assets');
			}			
		}
		
		redirect('/');
	}
	
	
	public function revoke_tj_vpn_certificate(){
		
		$this->procedure = 'show_alert_and_refresh_page';
		
		if(!$post = $this->input->post()) {
			$this->status = false;
			$this->message = t('Post is empty');
			exit();
		}
		
		if(!isset($post['json']) || empty($post['json'])){
			$this->status = false;
			$this->message = t('Json is empty');
			exit();
		}

		$obj = json_decode($post['json']['obj']);
		
		if(!is_object($obj)) {
			$this->status = false;
			$this->message = t('Json is not an object');
			exit();
		}
		
		$this->load->model('assets/digital_device','digital_device');
		
		$this->digital_device->id = $obj->id;
		
		if(!$this->digital_device->read()) {
			$this->status = false;
			$this->message = t('No digital device has been found with id') . ' ' . $obj->id;
			exit();
		}
		
		$this->load->model('assets/openvpn','openvpn');
		if(!$this->openvpn->revoke_certificate($obj->network_name)){
			$this->status = false;
			$this->message = t('Tooljar openvpn certificate can not be revoked');
			exit();
		}
		
		$this->digital_device->openvpn_certificate = '';
		$this->digital_device->update();
		
		$this->status = true;
		$this->message = t('Tooljar openvpn certificate has been revoked');
		exit();
	}
	
	public function create_tj_vpn_certificate(){
		
		$this->procedure = 'show_alert_and_refresh_page';
		
		if($this->mcbsb->is_module_enabled('tooljar')){
		
			$this->load->model('tooljar/mdl_tooljar','tooljar');
			
			$this->load->model('contact/mdl_contact','contact');
			$this->load->model('contact/mdl_organization','org');
			
			$this->org->oid = $this->mcbsb->get_mcbsb_org_oid();
			if(!$this->org->get(null,false)) {
				$this->status = false;
				$this->message = t('Your company can not be retrieved. Operation aborted.');
				exit();
			}
			
			if(empty($this->org->c) || empty($this->org->st) || empty($this->org->l)){
				$this->status = false;
				$this->message = t('Your company address is not complete. Please provide a full address. Operation aborted.');
				exit();				
			}		
		
		} else {
			
 			$this->status = false;
 			$this->message = t('Tooljar module is not enabled');
			exit();
		}
		
		if(!$post = $this->input->post()) {
			$this->status = false;
			$this->message = t('Post is empty');
			exit();
		}

		if(!isset($post['json']) || empty($post['json'])){
			$this->status = false;
			$this->message = t('Json is empty');
			exit();
		}
		
		$obj = json_decode($post['json']['obj']);
		
		if(!is_object($obj)) {
			$this->status = false;
			$this->message = t('Json is not an object');
			exit();
		}
		
		$this->load->model('assets/digital_device','digital_device');
		
		$this->digital_device->id = $obj->id;
		
		if(!$this->digital_device->read()) {
			$this->status = false;
			$this->message = t('No digital device has been found with id') . ' ' . $obj->id;
			exit();			
		}	
		
		$this->load->model('assets/openvpn','openvpn');

		$certificate_params = array();
		$certificate_params['countryName'] = 'US';//$this->org->c; TODO I need a list of countries
		$certificate_params['stateOrProvinceName'] = $this->org->st;
		$certificate_params['localityName'] = $this->org->l;
		$certificate_params['organizationName'] = $this->mcbsb->get_mcbsb_org();
		$certificate_params['organizationalUnitName'] = $this->mcbsb->get_mcbsb_org();
		$certificate_params['commonName'] = $this->digital_device->network_name;
		$certificate_params['name'] = $this->mcbsb->user->first_name . ' ' . $this->mcbsb->user->last_name;
		$certificate_params['emailAddress'] = $this->mcbsb->user->email;		
		
		if(!$this->openvpn->create_certificate($obj->network_name, null, $certificate_params)){
			$this->status = false;
			$this->message = t('Tooljar openvpn certificate not created. Check your openvpn.php config file.');
			exit();
		}

		
		$this->digital_device->openvpn_certificate = $this->openvpn->conf['zip_dir'] . $obj->network_name . '.zip';
												
 		if($tj_admin_email = $this->tooljar->get_tj_admin_email()){

			$subject = t('Tooljar Openvpn certificate for device') . ' ' . $obj->network_name;
			//TODO load a view here
			$body = t('In attachment the Tooljar openvpn certificate for device') . ' ' . $obj->network_name;
			$attachments = array($this->digital_device->openvpn_certificate);
		
			if($this->mcbsb->send_email($tj_admin_email,$subject,$body,'text', $attachments)) {
				
				//saves the path of the zipped certificate
				$this->digital_device->update();
				
				$this->status = true;
				$this->message = t('Tooljar openvpn certificate successfully created. Check the Tooljar Administrator email.');	
							
			} else {
				
				//TODO revoke the certificate
				
				$this->status = false;
				$this->message = t('The openvpn certificate can not be sent to email') . ' ' . $tj_admin_email;
				
			} 
								
		} else {
			
			$this->status = false;
			$this->message = t('Tooljar administrator email can not be retrieved');
			
		} 

		exit();
	}
}