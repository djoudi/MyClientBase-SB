<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Goo extends CI_Model {
	
	public $client = null;
	public $oauth2 = null;
	public $calendar = null;
	public $gplus = null;
	public $gdrive = null;
	
	public function __construct () {
		
		parent::__construct();
		
		if(!$conf = $this->load->config('goo',false,true,'google')) {
			$this->mcbsb->system_messages->error = t('Config file is missing') . ': goo.php';
			return;
		}
		
		set_include_path(get_include_path() . PATH_SEPARATOR . SPARKPATH .'GoogleAPIClient/0.6.0/src/');
		
		//global $apiConfig;
		
		require_once "Google_Client.php";

		$this->client = new Google_Client();
		
		
		//https://code.google.com/apis/console?api=calendar
		$tooljar_name = '';
		if($this->mcbsb->is_module_enabled('tooljar')){
			$tooljar_name = '-'.$this->mcbsb->tooljar->get_tooljar_name();
		}
		$this->client->setApplicationName("MCBSB".$tooljar_name);
		$this->client->setClientId($conf['ClientId']);
		$this->client->setClientSecret($conf['ClientSecret']);
		$this->client->setDeveloperKey($conf['DeveloperKey']);
		$this->client->setRedirectUri($conf['RedirectUri']);
		
		$this->client->setAccessType('offline');
		$this->client->setUseObjects(true);
		$final_redirect_url = site_url() . 'google/google';  
		$this->client->setState($final_redirect_url);  //returns $final_redirect_url from Google as $_GET['state']
		
		require_once "contrib/Google_Oauth2Service.php";
		$this->oauth2 = new Google_Oauth2Service($this->client);
		
		require_once "contrib/Google_CalendarService.php";
		$this->calendar = new Google_CalendarService($this->client);
		
		//require_once "contrib/Google_PlusService.php";
		//$this->gplus = new Google_PlusService($this->client);
		
		require_once "contrib/Google_DriveService.php";
		$this->gdrive = new Google_DriveService($this->client);
				
		
		$this->load->model('google/gtoken','gtoken');
		
	    if ($stoken = $this->session->userdata('gtoken')) {
	    	
			$token = json_decode($stoken);
			
			$this->client->refreshToken($token->refresh_token);
			$this->client->setAccessToken($stoken);
			
			
			if($user = $this->get_user_info()){
	    		
		    	if(!$this->gtoken->is_present($user->email)) {
		    		
					$this->save_token($token,$user);
					
		    	}
	    	}    		
    	} else {
    		
    		$CI = &get_instance();

    		if($gtoken_id = $this->gtoken->is_present($CI->mcbsb->user->email)){
    			
				$this->gtoken->id = $gtoken_id;
				
				if($stoken = $this->gtoken->get_google_format()){
					
					$this->session->set_userdata('gtoken',$stoken);

					$token = json_decode($stoken);
						
					$this->client->refreshToken($token->refresh_token);
						
					$this->client->setAccessToken($stoken);
					
				}
    		}
    		
    	}
	}
	

	
	private function save_token($token,$user){

		$this->load->model('google/gtoken','gtoken');
		 
		$this->gtoken->email = $user->email;
		 
		$this->load->model('contact/mdl_contact','contact');
		$this->load->model('contact/mdl_person','person');
		$input = array('filter' => 'mail='.$user->email);
		 
		if($result = $this->person->get($input,false)){
	
			$this->gtoken->contact_id_key = 'uid';
			$this->gtoken->contact_id = $this->person->uid;
			$this->gtoken->contact_name = $this->person->cn;
	
			foreach ($token as $attribute => $value){
				$this->gtoken->$attribute = $value;
			}
	
			return $this->gtoken->create();
		}

		return false;
	}
	
	
	public function get_user_info(){
		
		return $this->oauth2->userinfo->get();
		
		// These fields are currently filtered through the PHP sanitize filters.
		// See http://www.php.net/manual/en/filter.filters.sanitize.php
// 		$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
// 		$img = filter_var($user['picture'], FILTER_VALIDATE_URL);
// 		return $user;
		//$personMarkup = "$email<div><img src='$img?sz=50'></div>";
	}
	
	public function get_calendar_ids(){

		$calendarIDs = array();
		
		$calendarList = $this->calendar->calendarList->listCalendarList();
			
		if(is_object($calendarList)) {
			
			foreach ($calendarList->items as $key => $item) {
				
				$calendarIDs[] = $item->id;
				
			}
			
		}	

		return $calendarIDs;
	}
	
	public function delete_calendar_event($appointment){
		
		if(!is_object($appointment) || empty($appointment->id)) return false;
		
		$this->load->model('google/ogr','ogr');
		
		$ogr = new Ogr();

		$sql = 'select google_id, google_resource_name, google_resource_id from ' . $ogr->db_table . ' where object_name="' . $appointment->obj_name . '" and google_resource_name="calendar" and object_id=' . $appointment->id;
		$records = $ogr->readAll($sql);
		
		foreach ($records as $record) {

			try {
				$this->calendar->events->delete($record['google_resource_id'], $record['google_id']);
			
			} catch (Google_ServiceException $e) {
		
				$CI->mcbsb->system_messages->warning = $e->getMessage();
				return false;
			}	
			
		}
		
		$sql = 'delete from ' . $ogr->db_table . ' where object_name="' . $appointment->obj_name . '" and google_resource_name="calendar" and object_id=' . $appointment->id;
		$records = $ogr->readAll($sql);
		return count($records) ? false : true;
		
	}
	
	public function update_calendar_event(array $colleagues, $appointment){
		
		$this->delete_calendar_event($appointment);
		
		$this->create_calendar_event($colleagues, $appointment);		
	}
	
	public function create_calendar_event(array $colleagues, $appointment){
		
		if(!count($colleagues)) return false;
		if(!is_object($appointment)) return false;
		
		$CI = &get_instance();
		
		//fills the Google Event Object with the values of the appointment
		$event = new Google_Event();
		
		$event->location = $appointment->where;
		$event->summary = $appointment->what;
		$event->description = $appointment->description;
		$event->recurrence = array();
		$event->guestsCanModify = false;
		$event->anyoneCanAddSelf = false;
		// Z represents the timezone_offset
		$event->start->dateTime = date('Y-m-d', $appointment->start_time) . "T" . date('H:i:s', $appointment->start_time).'Z';
		$event->end->dateTime = date('Y-m-d', $appointment->end_time) . "T" . date('H:i:s', $appointment->end_time).'Z';
		
				
		$calendarIDs = $this->get_calendar_ids();
		
		if($appointment->creator_is_owner) {

			//sets the reminders. Note this setting works only for the owner: all the other attendees will get their default settings 
			//TODO checkbox in personal settings
			$reminder = new Google_EventReminders();
			$reminder->setUseDefault('false');
			$overrides = array(array("method"=> "email","minutes" => "20"));  //TODO 20 should be in a config file and/or form
			$reminder->setOverrides($overrides);
			$event->setReminders($reminder);			
			
			$attendees = array();
			$all_attendees = array();
			
			foreach ($colleagues as $uid) {
			
				$attendee = new Google_EventAttendee();
				$attendee->setDisplayName($CI->mcbsb->get_colleague_name($uid));
				$attendee->setEmail($CI->mcbsb->get_colleague_email($uid));
				$all_attendees[] = $attendee;
		
			}
			
			if(count($all_attendees)) $event->setAttendees($all_attendees);
			
			$calendarId = $CI->mcbsb->user->email;
			$colleagues = array($CI->mcbsb->user->id);
			
		} 

		
		foreach ($colleagues as $uid) {
			
			if($appointment->creator_is_owner) {
				
				return $this->submit_calendar_event($calendarId, $event, $appointment) ? true : false;
				
			} else {
				
				if(!$calendarId = $CI->mcbsb->get_colleague_email($uid)) continue;
				
				if(in_array($calendarId,$calendarIDs)){
						
					if(!$this->submit_calendar_event($calendarId, $event, $appointment)) return false;
					
				}
								
			}
			
		}
		
		return true;

		
	}
	
	private function submit_calendar_event($calendarId, $event, $appointment){
		
		if(!is_string($calendarId) || empty($calendarId)) return false;
		if(!is_object($event)) return false;
		if(!is_object($appointment)) return false;
		
		$CI = &get_instance();
		
		$CI->load->model('google/ogr','ogr');
		
		//tries to save the appointment in Google Calendar
		try {
		
			$gcalendar_response = $this->calendar->events->insert($calendarId, $event);  //$this->calendar->events->insert($configObj['calendarId'], $postBody);
			
			if(is_object($gcalendar_response) && !empty($gcalendar_response->id)){
		
				$ogr = new Ogr();
			
				$ogr->google_id = $gcalendar_response->id;
				$ogr->google_resource_name = 'calendar';
				$ogr->google_resource_id = $calendarId;
				$ogr->object_name = $appointment->obj_name;
				$ogr->object_id = $appointment->id;
				
				if(!$ogr->create()) {
					$CI->mcbsb->system_messages->warning = t('Ogr entry was not created');
					return false;
				}
				
				return true;
			}
		
			return false;
			
		} catch (Google_ServiceException $e) {
		
			$CI->mcbsb->system_messages->warning = $e->getMessage();
			return false;
		}		
	}
}
