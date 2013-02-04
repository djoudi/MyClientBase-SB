<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @since		June 24, 2012 	
 */
class Activity extends Rb_Db_Obj
{
	const table = 'activities';
	protected $module_folder = null;
	//public $ownAssets = null;
	
	public function __construct() {
		
		parent::__construct();

		$this->db_table = self::table;
		$this->obj_name = get_class($this);
		$this->module_folder = 'tasks';
		
		//R::freeze( array($this->db_table));  //NOTE! comment this line when you develop!
		
		$this->initialize();

	}
	
	private function fix_dates(){
		
		if(!$this->action_date) $this->action_date = date('Y-m-d');		
		
	}
	
	public function create() {
		
		if(!is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->creation_date = time();
		$this->created_by = $CI->mcbsb->user->id;
		$this->creator = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		$this->fix_dates();
				
		return parent::create();
	}

	public function read() {
		if(is_null($this->obj_ID_value)) return false;

		$this->_config['never_display_fields'] = array();	

		if(!parent::read()) return false;
		
		//gets the assets associated to the Activity
		$this->assets = $this->get_assets($this->id);
		
		return true;
	}

	/**
	 * Retrieves the Assets associated to the Activity and returns them an array of objects
	 * 
	 * @access		public
	 * @param		int $id		Activity id
	 * @return		array		Array containing the Asset objects found
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 1, 2013
	 */
	private function get_assets($id){
		
		$CI = &get_instance();
		$CI->load->model('assets/asset','asset');
		$CI->load->model('assets/home_appliance','home_appliance');
		$CI->load->model('assets/asset','digital_device');
		
		$assets = array();
		
		$Activity = R::load('activities',$id);
		if($bean_assets = $Activity->sharedAssets){
			
			foreach ($bean_assets as $asset) {
				
				$CI->{$asset->category}->id = $asset->id;
				if($CI->{$asset->category}->read()) {
					$assets[] = $CI->{$asset->category}->toArray();
// 					$tmp_asset = $CI->{$asset->category};
// 					unset($tmp_asset->_config);
// 					unset($tmp_asset->_fields);
// 					$assets[] = $tmp_asset;
				}

			}
			
		}
		
		return $assets;
	}
	
	/**
	 * Returns all the records matching the sql select plus the assets related to each record
	 * 
	 * @access		public
	 * @param		
	 * @return		
	 * 
	 * @author 		Damiano Venturin
	 * @since		Feb 1, 2013
	 */
	public function readAll($sql = null, $paginate = false, $from = 0, $results_per_page = 0){
		
		$records = parent::readAll($sql, $paginate, $from, $results_per_page);
		
		foreach ($records as $key => $record) {
			
			$records[$key]['assets'] = $this->get_assets($record['id']);
			
		}
		
		return $records;
	}
	
	public function update() {
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		//add hidden system values
		$this->update_date = time();
		$this->updated_by = $CI->mcbsb->user->id;
		$this->editor = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
				
		$this->fix_dates();
		
		return parent::update();
	}	
	
	public function close(){
		
		if(is_null($this->obj_ID_value)) return false;
		
		$CI = &get_instance();
		
		$this->fix_dates();
		
		//add hidden system values
		$this->complete_date = date('Y-m-d');
		$this->completed_by = $CI->mcbsb->user->id;
		$this->completionist = $CI->mcbsb->user->first_name . ' ' . $CI->mcbsb->user->last_name;
		
		return parent::update();
	}
	
	public function is_open(){
		return !$this->is_closed();
	}
	
	public function is_closed(){
		return is_null($this->complete_date) ? false : true;
	}
	
	
	public function delete() {
		//we do not delete activities
		return false;
	}

	
	public function contact_tab($contact){
	
		if(!is_object($contact)) return false;
	
		$CI = &get_instance();
		switch ($contact->objName) {
			case 'person':
				$CI->session->set_userdata('contact_id',$contact->uid);
				$CI->session->set_userdata('contact_id_key','uid');
				$this->contact_id = $contact->uid;
				$this->contact_id_key = 'uid';
				$this->contact_name = $contact->cn;				
			break;
			
			case 'organization':
				$CI->session->set_userdata('contact_id',$contact->oid);
				$CI->session->set_userdata('contact_id_key','oid');
				$this->contact_id = $contact->oid;
				$this->contact_id_key = 'oid';
				$this->contact_name = $contact->o;				
			break;			
		}
		
		$data = array();
		$return = array();		
		$return = modules::run('/activities/activities/index');
		$CI->session->unset_userdata('contact_id');
		$CI->session->unset_userdata('contact_id_key');
		
		$return['buttons'] = array();
		$return['buttons'][] = $this->magic_button('create');
						
		return $return;
		
	}
	
	public function magic_button($type = 'create'){
		
		$tmp = array();
		
		switch ($type) {
			
			case 'edit':
				$tmp['form_title'] = 'Edit Activity';
				$tmp['procedure'] = 'automated_form';
				$button_label = 'Edit activity';
				$button_id = 'edit_Activity';			
			break;
			
			case 'create':
				$tmp['form_title'] = 'New Activity';
				$tmp['procedure'] = 'create_activity';
				$button_label = 'Add activity';
				$button_id = 'create_Activity';
			break;
			
			default:
				return array();
			break;
		}
		
		//common stuff for some cases
		if($type == 'create' || $type == 'edit'){
			$this->reset_obj_config();
			$tmp['url'] = '/' . $this->module_folder . '/ajax/save_activity';
		}
		
		//common stuff for all cases
		$tmp['obj'] = $this->toJson();
		$tmp['form_name'] = 'jquery_form_Activity';
		
		$string = json_encode($tmp, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
		$string = '$(this).live("click", jqueryForm(' . $string . ',"/' . $this->module_folder . '/ajax/getForm"))';
		
		$button_url = '#';		
		
		$button = array(
						'label' => $button_label,
						'id' => $button_id,
						'url' => $button_url,
						'onclick' => $string,
		);
	

		return $button;
	}
}