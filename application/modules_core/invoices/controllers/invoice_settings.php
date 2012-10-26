<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoice_Settings extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model('invoices/mdl_invoices');

	}

	function display() {

		$this->load->model(
			array(
			'invoice_statuses/mdl_invoice_statuses',
			'tax_rates/mdl_tax_rates',
			'templates/mdl_templates',
			'invoices/mdl_invoice_groups'
			)
		);

		$params = array(
			'select'	=>	'*',
			'where'		=>	array(
				'invoice_status_type'	=>	1
			)
		);

		$open_invoice_statuses = $this->mdl_invoice_statuses->get($params);

		$params = array(
			'select'	=>	'*',
			'where'		=>	array(
				'invoice_status_type'	=>	3
			)
		);

		$closed_invoice_statuses = $this->mdl_invoice_statuses->get($params);

		$data = array(
			'open_invoice_statuses'		=>  $open_invoice_statuses,
			'closed_invoice_statuses'	=>  $closed_invoice_statuses,
			'tax_rates'					=>  $this->mdl_tax_rates->get(array('select'=>'*')),
			'templates'					=>  $this->mdl_templates->get('invoices'),
			'invoice_logos'				=>  $this->mdl_invoices->get_logos(),
			'invoice_groups'			=>	$this->mdl_invoice_groups->get()
		);

		

		$this->load->view('settings', $data);

	}

	function save() {

		/**
		 * As per the config file, this function will
		 * execute when the core system settings are saved.
		 */
		
		$this->mcbsb->settings->save('currency_symbol', $this->input->post('currency_symbol'));
		$this->mcbsb->settings->save('currency_symbol_placement', $this->input->post('currency_symbol_placement'));
		$this->mcbsb->settings->save('default_invoice_group_id', $this->input->post('default_invoice_group_id'));
		$this->mcbsb->settings->save('default_quote_group_id', $this->input->post('default_quote_group_id'));
		$this->mcbsb->settings->save('default_tax_rate_id', $this->input->post('default_tax_rate_id'));
        $this->mcbsb->settings->save('default_tax_rate_option', $this->input->post('default_tax_rate_option'));
		$this->mcbsb->settings->save('default_item_tax_rate_id', $this->input->post('default_item_tax_rate_id'));
		$this->mcbsb->settings->save('default_item_tax_option', $this->input->post('default_item_tax_option'));
		$this->mcbsb->settings->save('default_invoice_template', $this->input->post('default_invoice_template'));
		$this->mcbsb->settings->save('default_quote_template', $this->input->post('default_quote_template'));
		$this->mcbsb->settings->save('invoices_due_after', $this->input->post('invoices_due_after'));
		$this->mcbsb->settings->save('default_open_status_id', $this->input->post('default_open_status_id'));
		$this->mcbsb->settings->save('default_closed_status_id', $this->input->post('default_closed_status_id'));
		$this->mcbsb->settings->save('decimal_symbol', $this->input->post('decimal_symbol'));
		$this->mcbsb->settings->save('thousands_separator', $this->input->post('thousands_separator'));
        $this->mcbsb->settings->save('cron_key', $this->input->post('cron_key'));

        if ($this->input->post('invoice_logo')) {

			$this->mcbsb->settings->save('invoice_logo', $this->input->post('invoice_logo'));

		}

		if ($this->input->post('include_logo_on_invoice')) {

			$this->mcbsb->settings->save('include_logo_on_invoice', "TRUE");

		}

		else {

			$this->mcbsb->settings->save('include_logo_on_invoice', "FALSE");

		}

		if ($this->input->post('display_quantity_decimals')) {

			$this->mcbsb->settings->save('display_quantity_decimals', 1);

		}

		else {

			$this->mcbsb->settings->save('display_quantity_decimals', 0);

		}

		if ($this->input->post('disable_invoice_audit_history')) {

			$this->mcbsb->settings->save('disable_invoice_audit_history', 1);

		}

		else {

			$this->mcbsb->settings->save('disable_invoice_audit_history', 0);

		}

		if ($this->input->post('default_apply_invoice_tax')) {

			$this->mcbsb->settings->save('default_apply_invoice_tax', 1);

		}

		else  {

			$this->mcbsb->settings->save('default_apply_invoice_tax', 0);

		}

		if ($this->input->post('update_decimal_taxes')) {

			if ($this->input->post('decimal_taxes_num') == 2 or $this->input->post('decimal_taxes_num') == 3) {

				$this->mcbsb->settings->save('decimal_taxes_num', $this->input->post('decimal_taxes_num'));

				$this->_adjust_decimal_taxes($this->input->post('decimal_taxes_num'));

			}

		}

	}

	function _adjust_decimal_taxes($num) {

		$this->db->query("ALTER TABLE `mcb_tax_rates` CHANGE `tax_rate_percent` `tax_rate_percent` DECIMAL( 10, " . $num . " ) NOT NULL");


	}

}

?>