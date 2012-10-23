<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Extends Class Mdl_Contact
 * 
 * Created by Damiano Venturin @ squadrainformatica.com
 */

class Mdl_Organization extends Mdl_Contact {

	public $oid;	//mandatory
	public $o;		//mandatory
	
    public function __construct() {

        parent::__construct();
        
        $this->objName = 'organization';
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/organization/'));        
                
    }
        
    public function arrayToObject(array $organization, $ignore_oid = false)
    {
    	if(!$return = parent::arrayToObject($organization, $this->objName)) return false;

    	//this is for MCB compatibility
    	if(isset($this->oid)) $this->client_id = $this->oid;
    	if(isset($this->o)) $this->client_name = $this->o;
    	 
    	if($ignore_oid)
    	{
    		return true; //this is used only for contact creation, case in which the uid is unknown
    	} else {
    		if(!empty($this->oid))	return true;
    	}
    	 
    	return false;    	
    }
    
    public function prepareShow()
    {
    	$this->load->config('organization');
    	$this->show_fields = $this->config->item('organization_show_fields');
    	$this->aliases = $this->config->item('organization_attributes_aliases');
    	$this->hidden_fields = $this->config->item('organization_hidden_fields');    	
    }    

	public function get(array $input = null, $return_rest = true)
    {
    	if((is_null($input) || (is_array($input) && count($input)==0)) && is_null($this->oid)) return false;
    	 
    	if(empty($input['filter']) && isset($this->oid)) $input['filter'] = '(oid='.$this->oid.')';
    	 
    	if(empty($input)) return false;
    	     
    	return parent::get($input, $return_rest);
    }    
    
    /**
     * Returns the total number of organizations stored in the Contact Engine LDAP database
     *
     * @access		public
     * @param		none
     * @return		integer
     * @author 		Damiano Venturin
     * @since		Nov 2, 2012
     */
    public function count_all(){
    
    	$input = array('filter' => '(objectClass=dueviorganization)');
    
    	return parent::count($input);
    }    
    
    public function hasProperAddress()
    {
    	//some very basic validation of an address. If the address is validated we can try to save the "Residence" location.
    	if(!isset($this->street)) return false;
    	if(!isset($this->postalCode)) return false;
    	if(!isset($this->l)) return false;
    	if(!isset($this->st)) return false;
    	if(!isset($this->c)) return false;
    	
    	//there is no way to know how long it should be
    	if(mb_strlen($this->street) < 3) return false;
    	
    	//Postal Codes are between 3 and 8 digits. They can be numeric and alphanumeric
    	if(mb_strlen($this->postalCode) < 3 || mb_strlen($this->postalCode) > 8 ) return false;
    	
    	if(mb_strlen($this->l) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages    	
    	if(mb_strlen($this->st) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages
    	if(mb_strlen($this->c) < 3) return false;
    	
    	$address = $this->street . ', ' . $this->postalCode . ' ' . $this->l . ' ' . $this->st . ' ' . $this->c;
    	 
    	return $address;
    }
    
    
    public function save($with_form = true)
    {
    	$creation = empty($this->oid) ? true : false; //if uid is not set than it's a creation otherwise an update
        $return = parent::save($creation,$with_form);
    	if($return)
    	{
    		if($creation) $this->oid = $oid = $this->crr->data['oid'];
    		$update_return = $this->updateDefaultLocation($creation);
    		
    		return $return;
    	}
    	
    	return false;
    }   

    public function get_default_values(){
    	$this->load->config('organization');
    	 
    	if(count($this->mandatoryAttributes) == 0) $this->getMandatoryAttributes();
    	 
    	if(!$default_values = $this->config->item('organization_default_values')){
    		$default_values = array();
    	}
    	 
    	if(!is_array($default_values)) $default_values = array();
    	 
    	foreach ($this->mandatoryAttributes as $key => $attribute) {
    		if(!isset($default_values[$attribute]) || empty($default_values[$attribute])){
    			switch ($attribute) {
    				case 'enabled':
    					$default_value = 'TRUE';
    				break;
    
    				case 'oid':
    					$default_value = null;
    				break;
    
    				default:
    					$default_value = 'unknown';
    				break;
    			}
    			$default_values[$attribute] = $default_value;
    		}
    	}
    
    	return $default_values;
    }
    
    public function set_default_values(){
    	 
    	if(count($this->mandatoryAttributes) == 0) $this->getMandatoryAttributes();
    	 
    	foreach ($this->get_default_values() as $attribute => $value){
    	    if(empty($this->$attribute)){
    			$this->$attribute = $value;
    		} else {
    			if($this->$attribute == 'null') {
    				unset($this->$attribute);
    			}
    		}
    	}

    }
    
}

?>