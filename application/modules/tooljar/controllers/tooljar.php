<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tooljar extends Admin_Controller {
	
	public $tooljar_server = null;
	public $tooljar = null;
	
	public function __construct() {

		parent::__construct();

		if (!$this->mdl_mcb_modules->check_enable('tooljar')) {
			redirect('/contact');
		}
		
		//loads the Task obj into the mcbsb obj
		$this->mcbsb->load('tooljar/mdl_tooljar','tooljar');
		
	}
	
	public function index(){
		redirect('/');
	}
}