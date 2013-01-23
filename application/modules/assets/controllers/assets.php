<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Assets extends Admin_Controller {
	
	function __construct() {

		parent::__construct();
		
		$this->load->helper('date');
		
		$this->load->model('assets/asset','asset');	
		
		if($modules_to_load = $this->config->item('asset_modules_to_load')){
				
			foreach ($modules_to_load as $module_to_load){
					
				if(strtolower($module_to_load) != strtolower(get_class($this))){
						
					$this->load->model('assets/'.$module_to_load,$module_to_load);
				}
			}
		}		
	}

	/**
	 * Retrieves all the assets of a contact and shows them in a table
	 * 
	 * @access		public
	 * @param		none		
	 * @return		none
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 25, 2012
	 */
	public function get_by_contact(){
		
		$from = (integer) uri_find('from');
		
		$contact_id = $this->session->userdata('contact_id');
		$contact_id_key = $this->session->userdata('contact_id_key');
		
		$data = array();
		if($contact_id && $contact_id_key) {
			
			$assets = array();
			$assets['asset'] = array();
			$assets_number = 0;
			
			$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->asset->db_table . ' where category="asset" and contact_id=' . $contact_id . ' and contact_id_key="' . $contact_id_key. '" order by id DESC';
			$assets['asset'] = $this->asset->readAll($sql,true,$from);
			$assets_number = $assets_number + count($assets['asset']);
			
			if($modules_to_load = $this->config->item('asset_modules_to_load')){
					
				foreach ($modules_to_load as $module_to_load){

					$sql = 'select SQL_CALC_FOUND_ROWS * from '.$this->asset->db_table . ' where category="' . $module_to_load . '" and contact_id=' . $contact_id . ' and contact_id_key="' . $contact_id_key. '" order by id DESC';
					$assets[$module_to_load] = $this->asset->readAll($sql,true,$from);
					$assets_number = $assets_number + count($assets[$module_to_load]);
				}
			}	
						
		} else {
			
			return false;
		}
		
		
		$data = array();
		$data['assets'] = $assets;
		$data['assets_number'] = $assets_number;
		//$data['pager'] = $this->mcbsb->_pagination_links;
		
		return array(
						'html' => $this->load->view('assets_table.tpl', $data, true, 'smarty', 'assets'),
						'counter' => $assets_number
		);		
	}
	
	
	/**
	 * Shows asset details
	 *
	 * @access		public
	 * @param		none
	 * @return		hmtl
	 *
	 * @author 		Damiano Venturin
	 * @since		Jan 23, 2013
	 */
	public function details(){
	
		$segments = $this->uri->uri_to_assoc();
		if(!isset($segments['id']) || !is_numeric($segments['id'])) redirect('/'); //TODO in this case would be nice to  roll back to last position
	
		$data = array();
	
		$this->asset->id = $segments['id'];
		
		if($this->asset->read()) {
			$obj = $this->asset->category;
			
			if($obj != 'asset'){
				//re-read the item
				$this->$obj->id = $segments['id'];
				if(!$this->$obj->read()) redirect('/'); //TODO in this case would be nice to  roll back to last position 
			}
		} else {
			redirect('/'); //TODO in this case would be nice to  roll back to last position
		}
	
		
		$data['asset'] = $this->$obj->toJson();
		$data['category'] = $obj;
		
		$data['buttons'][] = $this->$obj->magic_button('edit');
		
		//adds the button to create the openvpn certificate to connect to Tooljar
		if($obj == 'digital_device' && $this->$obj->contact_id_key == 'oid' && $this->$obj->network_device && !empty($this->$obj->network_name)) {

			if($this->mcbsb->is_module_enabled('tooljar')){
				
				$this->load->model('tooljar/mdl_tooljar','tooljar');
				
				if(empty($this->$obj->openvpn_certificate)) {
					if($this->$obj->contact_id == $this->tooljar->get_my_tj_organization()){
						
						$tmp = array();
						$tmp['url'] = '/assets/ajax/create_tj_vpn_certificate';
						$tmp['obj'] = $this->$obj->toJson();
							
						$string = json_encode($tmp, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
						$string = '$(this).live("click", postToAjax(' . $string .'))';
						
						$data['buttons'][] = array(
								'label' => 'Create tooljar vpn certificate',
								'id' => 'create_tj_vpn_certificate',
								'url' => '#',
								'onclick' => $string,
						);					
					}
				} else {
					//revoke button
				}	
			}
		}
		
		$this->load->view('asset_details.tpl', $data, false, 'smarty', 'assets');
	}	
}
?>