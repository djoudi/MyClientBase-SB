<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Admin_Controller extends MX_Controller {

	public static $is_loaded;
	
	public function __construct() {
		
		parent::__construct();
			
		//$a = get_class($this);
		
        $this->load->helper('url');
        
		if (!isset(self::$is_loaded)) {

			self::$is_loaded = TRUE;

            $this->load->config('mcb_menu/mcb_menu');

			$this->load->database();

			$this->load->helper(array('uri', 'mcb_currency', 'mcb_invoice',
				'mcb_date', 'mcb_icon', 'mcb_custom', 'mcb_app',
				'mcb_invoice_amount', 'mcb_invoice_item',
				'mcb_invoice_payment', 'mcb_numbers'));

			$this->load->model(array('mcb_modules/mdl_mcb_modules','mcb_data/mdl_mcb_userdata'));

            modules::run('mcb_menu/check_permission', $this->uri->uri_string(), $this->mcbsb->user->is_admin);
            
			$this->mdl_mcb_modules->set_module_data();

			$this->mdl_mcb_modules->load_custom_languages();

			$this->load->language('mcb', $this->mcbsb->settings->setting('default_language'));

            $this->load->model('fields/mdl_fields');

			$this->load->library(array('form_validation', 'redir'));

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if ($this->mcbsb->settings->setting('enable_profiler')) {

                $this->output->enable_profiler();

            }

		}

	}

}

?>