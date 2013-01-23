<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Admin_Controller extends MX_Controller {

	public static $is_loaded;
	
	public function __construct() {
		
		parent::__construct();
		
		//TODO ACLs should go somewhere here. When a module controller is loaded it always calls the parent constructor which is this class
		
		//$a = get_class($this);
		
        $this->load->helper('url');
        
		if (!isset(self::$is_loaded)) {

			self::$is_loaded = TRUE;

			$this->load->database();

			$this->load->helper(array('uri', 'mcb_currency', 'mcb_invoice',
				'mcb_date', 'mcb_icon', 'mcb_custom',
				'mcb_invoice_amount', 'mcb_invoice_item',
				'mcb_invoice_payment', 'mcb_numbers'));

			$this->load->model(array('mcb_data/mdl_mcb_userdata'));  //TODO is this necessary?

			$this->load->language('mcb', strtolower($this->mcbsb->settings->setting('default_language')));

            $this->load->model('fields/mdl_fields'); //TODO is this necessary?

			$this->load->library(array('form_validation', 'redir'));
 
			//customization of validation_errors()
			$this->form_validation->set_error_delimiters('|', ''); //TODO maybe this should go in mcbsb class 
			//$this->form_validation->set_error_delimiters('<div class="error">', '</div>');  //TODO delme

            if ($this->mcbsb->settings->setting('enable_profiler')) {

                $this->output->enable_profiler();

            }

		}

	}

}

?>