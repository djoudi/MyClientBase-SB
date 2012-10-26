<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Other_Settings extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model('invoices/mdl_invoices');

	}

	function display() {

		$this->load->module('mcb_language');

		$pdf_plugins = array(
			'dompdf'	=>	'dompdf'
		);

		if (file_exists(APPPATH . 'helpers/mpdf')) {

			$pdf_plugins['mpdf'] = 'mPDF';

		}

		$data = array(
			'pdf_plugins'	=>  $pdf_plugins,
			'languages'		=>  $this->mcb_language->languages,
			'date_formats'	=>  date_formats()
		);

		$this->load->view('other_settings', $data);

	}

	function save() {

		$this->mcbsb->settings->save('default_language', $this->input->post('default_language'));

		$this->mcbsb->settings->save('default_date_format', $this->input->post('default_date_format'));

		$this->mcbsb->settings->save('default_date_format_mask', date_formats($this->input->post('default_date_format'), 'mask'));

		$this->mcbsb->settings->save('default_date_format_picker', date_formats($this->input->post('default_date_format'), 'picker'));

		$this->mcbsb->settings->save('pdf_plugin', $this->input->post('pdf_plugin'));

		$this->mcbsb->settings->save('results_per_page', $this->input->post('results_per_page'));

        $this->mcbsb->settings->save('enable_profiler', ($this->input->post('enable_profiler')) ? 1 : 0);

        $this->mcbsb->settings->save('application_title', $this->input->post('application_title'));

	}

}

?>