<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Calendar extends CI_Controller {
	
	public function __construct () {
		
		parent::__construct();
		
	}
	
	function index () {
		
		$this->load->model('google/goo','goo');

		$calList = $this->goo->calendar->calendarList->listCalendarList();
		print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
		
	}
}
