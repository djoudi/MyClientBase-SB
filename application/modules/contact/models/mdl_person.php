<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Extends Class Mdl_Contact
 * 
 * Created by Damiano Venturin @ squadrainformatica.com
 */


class Mdl_Person extends Mdl_Contact {

	public $uid;	//mandatory
	public $cn;		//mandatory
	
    public function __construct() {
		
        parent::__construct();
        
        $this->objName = 'person';
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName.'/'));        
    	
    }

    //TODO shouldn't be at least protected?
    public function arrayToObject(array $data, $ignore_uid = false)
    {
    	if(!$return = parent::arrayToObject($data, $this->objName)) return false;
    	
    	//this is for MCB compatibility
    	if(isset($this->uid)) $this->client_id = $this->uid;
    	if(isset($this->cn)) $this->client_name = $this->cn;
    	
    	if($ignore_uid)
    	{
    		return true; //this is used only for contact creation, case in which the uid is unknown
    	} else {
    		if(!empty($this->uid))	return true;
    	}
    	    
    	
    	return false;
    }

    public function get(array $input = null, $return_rest = true)
    {
    	//$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName.'/'));
    	
    	if((is_null($input) || (is_array($input) && count($input)==0)) && is_null($this->uid)) return false;
    	
    	if(empty($input['filter']) && isset($this->uid)) $input['filter'] = '(uid='.$this->uid.')';
    	
    	if(empty($input)) return false;
    	
    	return parent::get($input, $return_rest);
    }   
     

    /**
     * Returns the total number of people stored in the Contact Engine LDAP database
     * 
     * @access		public
     * @param		none
     * @return		integer	
     * @author 		Damiano Venturin
     * @since		Nov 2, 2012
     */     
    public function count_all(){
    	 
    	$input = array('filter' => '(objectClass=dueviperson)');
    	     	 
    	return parent::count($input);
    }
    
    public function get_default_values(){
    	$this->load->config('person');
    	
    	if(count($this->mandatoryAttributes) == 0) $this->getMandatoryAttributes();
    	
    	if(!$default_values = $this->config->item('person_default_values')){
    		$default_values = array();	
    	}
    	
    	if(!is_array($default_values)) $default_values = array();
    	
    	foreach ($this->mandatoryAttributes as $key => $attribute) {
    		if(!isset($default_values[$attribute]) || empty($default_values[$attribute])){
	    		switch ($attribute) {
	    			case 'enabled':
	    				$default_value = 'TRUE';
	    			break;
	    	
	    			case 'userPassword':
	    				$default_value = uniqid();
	    			break;
	    			
	    			case 'uid':
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
    	
    	if(count($this->properties) == 0) $this->getProperties();
    	foreach ($this->properties as $attribute => $properties) {
    		switch ($attribute) {    				     				 
    			case 'cn':
    				$this->$attribute = $this->sn.' '.$this->givenName;
    			break;
    				 
    			case 'displayName':
    				$this->$attribute = $this->givenName.' '.$this->sn;
    			break;
    				 
    			case 'fileAs':
    				$this->$attribute = $this->sn.' '.$this->givenName;
    			break;

    		}
    	}    	
    }
     
    public function prepareShow()
    {
    	$this->load->config('person');
    	$this->show_fields = $this->config->item('person_show_fields');
    	$this->aliases = $this->config->item('person_attributes_aliases');
    	$this->hidden_fields = $this->config->item('person_hidden_fields');
    }
    
    public function hasProperAddress()
    {
    	//some very basic validation of an address. If the address is validated we can try to save the "Residence" location.
    	if(!isset($this->homePostalAddress)) return false;
    	if(!isset($this->mozillaHomePostalCode)) return false;
    	if(!isset($this->mozillaHomeLocalityName)) return false;
    	if(!isset($this->mozillaHomeState)) return false;
    	if(!isset($this->mozillaHomeCountryName)) return false;
    	
    	//there is no way to know how long it should be
    	if(mb_strlen($this->homePostalAddress) < 3) return false;
    	
    	//Postal Codes are between 3 and 8 digits. They can be numeric and alphanumeric
    	if(mb_strlen($this->mozillaHomePostalCode) < 3 || mb_strlen($this->mozillaHomePostalCode) > 8 ) return false;
    	
    	if(mb_strlen($this->mozillaHomeLocalityName) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages    	
    	if(mb_strlen($this->mozillaHomeState) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages
    	if(mb_strlen($this->mozillaHomeCountryName) < 3) return false;
    	
    	$address = $this->homePostalAddress. ', ' . $this->mozillaHomePostalCode . ' ' . $this->mozillaHomeLocalityName . ' ' . $this->mozillaHomeState . ' ' . $this->mozillaHomeCountryName;
    	 
    	return $address;
    }
      

    public function save($with_form = true)
    {
    	$creation = empty($this->uid) ? true : false; //if uid is not set than it's a creation otherwise an update
    	
    	$return = parent::save($creation, $with_form);
    	
    	if($return)
    	{
    		if($creation) $this->uid = $uid = $this->crr->data['uid'];
    		$update_return = $this->updateDefaultLocation($creation);
    		
    		return $return;
    	}    	
    	
    	return false;
    }    
}

?>