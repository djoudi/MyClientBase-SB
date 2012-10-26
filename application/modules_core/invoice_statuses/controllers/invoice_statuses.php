<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoice_Statuses extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->_post_handler();

		$this->load->model('mdl_invoice_statuses');

	}

	function index() {

		$this->redir->set_last_index();
		
		$params = array(
			'paginate'	=>	TRUE,
			'limit'		=>	$this->mcbsb->settings->setting('results_per_page'),
			'page'		=>	uri_assoc('page')
		);

		$data = array(
			'invoice_statuses' =>	$this->mdl_invoice_statuses->get($params)
		);

		$data['site_url'] = site_url($this->uri->uri_string());
		$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'invoices');		
		
		$this->load->view('index', $data);

	}

	function form() {

		if (!$this->mdl_invoice_statuses->validate()) {

			if (!$_POST AND uri_assoc('invoice_status_id')) {

				$this->mdl_invoice_statuses->prep_validation(uri_assoc('invoice_status_id'));

			}

			$data = array();
			$data['site_url'] = site_url($this->uri->uri_string());
			$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'invoices');
				
			$this->load->view('form',$data);

		}

		else {

			$this->mdl_invoice_statuses->save($this->mdl_invoice_statuses->db_array(), uri_assoc('invoice_status_id'));

			$this->redir->redirect('invoice_statuses');

		}

	}

	function delete() {

		if (uri_assoc('invoice_status_id')) {

			$this->mdl_invoice_statuses->delete(array('invoice_status_id'=>uri_assoc('invoice_status_id')));

		}

		$this->redir->redirect('invoice_statuses');

	}

	function _post_handler() {

		if ($this->input->post('btn_add')) {

			redirect('invoice_statuses/form');

		}

		elseif ($this->input->post('btn_cancel')) {

			redirect('invoice_statuses/index');

		}

	}

}

?>