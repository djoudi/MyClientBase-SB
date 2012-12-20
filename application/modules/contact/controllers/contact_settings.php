<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Controller for the Contact Module settings 
 * 
 * @access		public
 * @author 		Damiano Venturin
 * @since		Nov 5, 2012
 */
class Contact_Settings extends Admin_Controller {
	
	public function __construct() {
	
		parent::__construct();
	
		$this->load->model('mdl_contacts');
	
	}
	
	/**
	 * This controller method (function) is defined as calledback in the config.php and is called by MCB when the System Settings Panel is displayed.
	 * MCB provide a specific tab for the module Contact. This function is called only once, when the System Settings is loaded.
	 * After that, during the accordion operations etc, this function is no more called.
	 * The aim of the function is to get, populate and return the html of several tpl files. The html returned will populate the "setting tab"
	 *
	 * @access		public
	 * @param		none
	 * @return		nothing
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 6, 2012
	 */	
	public function display_person()
	{
		$data = array();
	
		$obj = new Mdl_Person();
		//$obj->getMandatoryAttributes();
		//$data['mandatory_attributes'] = $obj->mandatoryAttributes;

		$data['default_values'] = $obj->get_default_values();
		
		//this is necessary only if the accordion is shown open at the beginning
		//$obj->getProperties();
		//$obj->prepareShow();		
		//$data['settings_person'] = $this->display_object_settings($obj);
				
 		$this->load->view('settings_person.tpl', $data, false, 'smarty','contact');
	}	

	/**
	 * This controller method (function) is defined as calledback in the config.php and is called by MCB when the System Settings Panel is displayed.
	 * MCB provide a specific tab for the module Contact. This function is called only once, when the System Settings is loaded.
	 * After that, during the accordion operations etc, this function is no more called.
	 * The aim of the function is to get, populate and return the html of several tpl files. The html returned will populate the "setting tab"
	 *
	 * @access		public
	 * @param		none
	 * @return		nothing
	 *
	 * @author 		Damiano Venturin
	 * @since		Nov 6, 2012
	 */
	public function display_organization()
	{
		$data = array();
				
 		$obj = new Mdl_Organization();
 		$obj->getProperties();
 		$data['default_values'] = $obj->get_default_values();
						
		$this->load->view('settings_organization.tpl', $data, false, 'smarty','contact');
	}	

	/**
	 * This controller method (function) is defined as calledback in the config.php and is called by MCB when the System Settings Panel is displayed.
	 * MCB provide a specific tab for the module Contact. This function is called only once, when the System Settings is loaded.
	 * After that, during the accordion operations etc, this function is no more called.
	 * The aim of the function is to get, populate and return the html of several tpl files. The html returned will populate the "setting tab"
	 *
	 * @access		public
	 * @param		none
	 * @return		nothing
	 *
	 * @author 		Damiano Venturin
	 * @since		Nov 6, 2012
	 */	
	public function display_location()
	{
		$data = array();
			
		return $this->load->view('settings_location.tpl', $data, true, 'smarty','contact');
	}	
	
	/**
	 * Refreshes the object attributes and loads the appropriate template
	 * 
	 * @access		public
	 * @param		object $obj		The object class. It can be person, organization, location
	 * @param		string $tpl		String containing the variant part of the template name Ex: settings_person_$tpl.tpl
	 * @return		html			The parsed template
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 6, 2012
	 */
	private function display_object_settings(&$obj, $tpl = null)
	{
		if(!is_object($obj)) return false;
		if(!is_string($tpl) && !is_null($tpl)) return false;
	
		//the config file can be manually edit to set a list of LDAP attributes that will be never displayed
		//in the configuration accordion. This is necessary because inetorg, core and cosine schema have 
		//some particular fields that have few to do with MCBSB contact management.
		//It's recomemmended to remove any field classified as LDAP RDN (Contact Engine can not handle errors on those fields)
		$never_display_fields = $this->config->item($obj->objName.'_never_display_fields');
		$properties = $obj->properties;
		foreach ($properties as $property => $value) {
			if(in_array($property, $never_display_fields)) unset($properties[$property]);
		}

		
		//collects data for the tpl files
		$data = array(
				$obj->objName.'_all_attributes' => $properties, //$obj->properties,
				$obj->objName.'_visible_attributes' => $obj->show_fields,
				$obj->objName.'_aliases' => $obj->aliases,
		);
			
		if(is_array($obj->properties) and is_array($obj->show_fields))
		{
			$data[$obj->objName.'_available_attributes'] = array_diff_key($properties, array_flip($obj->show_fields));
		} else {
			$data[$obj->objName.'_available_attributes'] = array();
		}
			
		//feeds and loads the right tpl file and returns the html output
		$tpls = array('visible','order','aliases');
		if(in_array($tpl, $tpls))
		{
			$template = 'settings_'.$obj->objName.'_'.$tpl.'.tpl';
		} else {
			return 'Template not found';
		}
		
		return $this->load->view($template, $data, true, 'smarty','contact');
	}
	
	/**
	 * This function is called by the javascript (System Settings) everytime there is an event (drag, sort, submit)
	 * It updates the config file for the specific object and returns the updated html to the javascript which
	 * replaces the old content with the new one
	 *
	 * @access		private
	 * @param
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
	public function update()
	{
		$output = '';
		$data = array();
		 
		$split = preg_split('/_/', $this->input->post('action'));
		if(isset($split['0'])) $objName = $split['0'];
		if(isset($split['1'])) $action = $split['1'];
		 
		switch ($objName) {
			case 'person':
				$obj = new Mdl_Person();
			break;
	
			case 'organization':
				$obj = new Mdl_Organization();
			break;
	
			case 'location':
				$obj = new Mdl_Location();
			break;
					
			default:
				return false;
			break;
		}
			
		$obj->getProperties();
		$obj->prepareShow();
		if($objName != 'location') $obj->default_values = $obj->get_default_values();
		
		$tpl = null;
	
		switch ($action) {
	
			case 'addToVisible':
				if($attribute = $this->getAjaxItem()) $this->toVisible($obj, 'add', $attribute);
				$tpl = 'visible';
			break;
	
			case 'removeFromVisible':
				if($attribute = $this->getAjaxItem()) $this->toVisible($obj, 'remove', $attribute);
				$tpl = 'visible';
			break;
				 
			case 'sort':
				//show_fields is the ordered array of fields coming from the accordion
				$show_fields = $this->input->post(ucfirst(strtolower($objName)).'VisibleAttributes');
	
				if(is_array($show_fields)) {
					if(is_array($obj->show_fields))
					{
						//let's check if the given array (show_fields) is not fake
						if(count(array_diff($obj->show_fields, $show_fields)) == 0) {
							//there are no differences so I can assume that the POST wasn't manipulated
							$obj->show_fields = $show_fields;
							$this->update_config($obj, $obj->objName);
						}
					}
				}
				$tpl = 'order';
			break;
	
			case 'visible':
				$tpl = 'visible';
			break;
			
			case 'aliases':
				if($this->input->post('save')){
					if(is_array($this->input->post('form'))) {
						$aliases = array();
						$form = $this->input->post('form');
						foreach ($form as $key => $item) {
							if(!empty($item['value']) and $item['type'] == 'TEXT') $aliases[$item['field']] = strtolower(only_chars_nums_underscore(($item['value'])));
						}
						$obj->aliases = $aliases;
						$this->update_config($obj,$obj->objName);
					}
				}
				$tpl = 'aliases';
			break;
			
			case 'defaultvalues':
				if($this->input->post('save')){
					if(is_array($this->input->post('form'))) {
						$default_values = array();
						$form = $this->input->post('form');
						foreach ($form as $key => $item) {
							if(!empty($item['value']) and $item['type'] == 'TEXT') $default_values[$item['field']] = only_chars_nums_underscore_plus(($item['value']));
						}
						$obj->default_values = $default_values;
						$this->update_config($obj,$obj->objName);
					}
				}
			break;
		}
	
		echo $this->display_object_settings($obj, $tpl);
	}
	
	/**
	 * This function adds or removes an attribute to the "visible-attributes-set" for the given object and writes it in the configuration file.
	 *
	 * @access		private
	 * @param		$obj	object	The given object (person, organization, location)
	 * @param		$action	string	The action to perform (add | remove)
	 * @param		$attribute	string	The attribute to add to the "visible-attributes-set"
	 * @return		boolean
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 6, 2012
	 */
	private function toVisible(&$obj, $action, $attribute)
	{
		if(!is_object($obj)) return false;
		if(is_array($attribute)) return false;
		if(is_array($action)) return false;
	
		switch ($action) {
			case 'add':
				if(in_array($attribute, $obj->show_fields))
				{
					return true; //no changes: there is nothing to do here
				} else {
					array_push($obj->show_fields, $attribute);;
				}
				break;
					
			case 'remove':
				if(count($obj->show_fields)>1)  //you can not remove all the fields
				{
					$tmp = array_flip($obj->show_fields);
					if(isset($tmp[$attribute]))
					{
						unset($tmp[$attribute]);
						$obj->show_fields = array_flip($tmp);
					} else {
						return true; //no changes: there is nothing to do here
					}
				}
			break;
					
			default:
				return false;
			break;
		}
	
		return $this->update_config($obj,$obj->objName);
	}	
	
	/**
	 * This function sets the new configuration values for the objects Person, Organization and Location using the common Code Igniter method
	 * $this->config->set_item. Afterwards, writes the values in the config file using the function write_config
	 *
	 * @access		private
	 * @param		object	$obj 	The object: it can be person, organization or location
	 * @param		string	$configfile	The config file name
	 * @return		boolean
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 6, 2012
	 * @todo		Maybe this should go in a helper
	 */
	private function update_config(&$obj, $configfile)
	{
		if(!is_object($obj)) return false;
		 
		//update the configuration file
		$this->load->helper('configfile');
		
		$never_display_fields = $this->config->item($obj->objName.'_never_display_fields');
		$this->config->set_item($obj->objName.'_never_display_fields',$never_display_fields);
		$this->config->set_item($obj->objName.'_show_fields', $obj->show_fields);
		$this->config->set_item($obj->objName.'_attributes_aliases', $obj->aliases);
		$this->config->set_item($obj->objName.'_default_values', $obj->default_values);
		
		if(write_config($configfile, array($obj->objName.'_show_fields', 
											$obj->objName.'_attributes_aliases', 
											$obj->objName.'_hidden_fields',
											$obj->objName.'_default_values',
											$obj->objName.'_never_display_fields'),true))
		{
			$obj->prepareShow();  //refreshes the object with the new values
			$this->mcbsb->system_messages->success = 'Contact settings have been updated' ;
			return true;
		}
		return false;
	}	
	
	/**
	 * Reads the post variable item looking for something like this:
	 * PersonAvailableAttributes_acceptsCommercialAgreement
	 * and returns  PersonAvailableAttributes
	 * 
	 * @access		private
	 * @param		none
	 * @return		nothing
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 5, 2012
	 */	 
	private function getAjaxItem()
	{
		if($this->input->post('item')!= "")
		{
			$split = preg_split('/_/', $this->input->post('item'));
			if(isset($split['1'])) return $split['1'];
		}
		return false;
	}

}