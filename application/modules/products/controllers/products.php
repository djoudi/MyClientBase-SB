<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Products extends Admin_Controller {
	
	function __construct() {

		parent::__construct();

		$this->load->helper('date');
		
		$this->load->model('products/product','product');
	
	}

	/**
	 * Shows product details
	 * 
	 * @access		public
	 * @param		none
	 * @return		hmtl
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 26, 2012
	 */	 
	public function details(){
		
		$segments = $this->uri->uri_to_assoc();
		if(!isset($segments['id']) || !is_numeric($segments['id'])) redirect('/'); //TODO in this case would be nice to  roll back to last position
		
		$data = array();
		
		$this->product->id = $segments['id'];
		$this->product->read();
		
		$data['product'] = $this->product->toJson();
		
		$data['buttons'][] = $this->product->magic_button('edit');
		//$data['buttons'][] = $this->product->magic_button('close');
		
		$this->load->view('product_details.tpl', $data, false, 'smarty', 'products');
	}
	
	/**
	 * Retrieves all the products and shows them in a table
	 * 
	 * @access		public
	 * @param		none		
	 * @return		none
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 25, 2012
	 */
	function index(){
		
		$from = (integer) uri_find('from');
		
		$data = array();
		$data['products'] = $this->product->readAll(null,true,$from);
		$data['buttons'][] = $this->product->magic_button('create');
		$data['pager'] = $this->mcbsb->_pagination_links;
		
		$this->load->view('products_all.tpl', $data, false, 'smarty', 'products');
	}
}
?>