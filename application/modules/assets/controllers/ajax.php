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
		
		if($get = $this->input->get()){
			
			if(!isset($get['category']) || empty($get['category'])) {
				$this->mcbsb->system_messages->error = 'Error while saving the asset '.$asset_id;
				redirect('/'); //TODO here it would be nice to load the last position
			}
			
			$obj = $get['category'];
			
			foreach ($get as $attribute => $value) {
				if(empty($get['id']) && $attribute == 'id'){
					continue;
				} else {
					$this->$obj->$attribute = $value;
				}
			}
			
			if(empty($get['id'])) {
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

	private function vpn_return(){
		
		$this->procedure = 'show_alert_and_refresh_page';
		
		if($this->status){
			$this->mcbsb->system_messages->success = $this->message;
		} else {
			$this->mcbsb->system_messages->error = $this->message;
		}
		
		if($this->status){
			$this->message = t('Tooljar openvpn certificate successfully created. Check the Tooljar Administrator email');
		} else {
			$this->message = t('Tooljar openvpn certificate not created');
		}

		$this->__destruct();		
	}
	
	public function create_tj_vpn_certificate(){
		
		if($this->mcbsb->is_module_enabled('tooljar')){
		
			$this->load->model('tooljar/mdl_tooljar','tooljar');
			
			$this->load->model('contact/mdl_contact','contact');
			$this->load->model('contact/mdl_organization','org');
			
			$this->org->oid = $this->mcbsb->get_mcbsb_org_oid();
			if(!$this->org->get(null,false)) {
				$this->status = false;
				$this->message = t('Your company can not be retrieved. Operation aborted.');
				$this->vpn_return();
			}
			
			if(empty($this->org->c) || empty($this->org->st) || empty($this->org->l)){
				$this->status = false;
				$this->message = t('Your company address is not complete. Please provide a full address. Operation aborted.');
				$this->vpn_return();				
			}
			
			$certificate_params = array();
			$certificate_params['countryName'] = 'US';//$this->org->c;
			$certificate_params['stateOrProvinceName'] = $this->org->st;
			$certificate_params['localityName'] = $this->org->l;
			$certificate_params['organizationName'] = $this->mcbsb->get_mcbsb_org();
			$certificate_params['organizationalUnitName'] = $this->mcbsb->get_mcbsb_org();
			$certificate_params['commonName'] = $this->mcbsb->user->first_name . ' ' . $this->mcbsb->user->last_name;
			$certificate_params['emailAddress'] = $this->mcbsb->user->email;			
		
		} else {
			
 			$this->status = false;
 			$this->message = t('Tooljar module is not enabled');
			$this->vpn_return();
		}
		
		if(!$post = $this->input->post()) {
			$this->status = false;
			$this->message = t('Post is empty');
			$this->vpn_return();
		}

		if(!isset($post['json']) || empty($post['json'])){
			$this->status = false;
			$this->message = t('Json is empty');
			$this->vpn_return();
		}
		
		$obj = json_decode($post['json']['obj']);
		
		if(!is_object($obj)) {
			$this->status = false;
			$this->message = t('Json is not an object');
			$this->vpn_return();
		}
		
		$this->load->model('assets/digital_device','digital_device');
		
		$this->digital_device->id = $obj->id;
		
		if(!$this->digital_device->read()) {
			$this->status = false;
			$this->message = t('No digital device has been found with id') . ' ' . $obj->id;
			$this->vpn_return();			
		}	
		
		$this->load->model('assets/openvpn','openvpn');
		if(!$this->openvpn->create_certificate($obj->network_name,null,$certificate_params)){
			$this->status = false;
			$this->message = t('Tooljar openvpn certificate not created');
			$this->vpn_return();
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
				$this->message = t('Tooljar openvpn certificate successfully created.');	
							
			} else {
				
				//TODO revoke the certificate
				
				$this->status = false;
				$this->message = t('The openvpn certificate can not be sent to email') . ' ' . $tj_admin_email;
				
			} 
								
		} else {
			
			$this->status = false;
			$this->message = t('Tooljar administrator email can not be retrieved');
			
		} 

		$this->vpn_return();
	}
}