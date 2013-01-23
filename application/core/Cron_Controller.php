<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Cron_Controller extends MX_Controller {

    function __construct() {

        parent::__construct();

        $this->load->database();

        $this->load->helper('url');

        $this->mcbsb->settings->set_session_data();

        $this->load->helper(array('uri', 'mcb_currency', 'mcb_invoice', 'mcb_date', 'mcb_icon', 'mcb_custom'));

        $this->load->language('mcb', strtolower($this->mcbsb->settings->setting('default_language')));

        $this->load->model('fields/mdl_fields');

    }

}

?>