<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ajax extends Ajax_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('products/product','product');
	}
	
	
	public function close_product(){
		
		$post = $this->get_post_values();
		
		if($post){
			if($post['id'] ){
				$this->product->id = $post['id'];
				if($this->product->read()){
					$this->product->endnote = $post['endnote'];
					$a = $this->product;
					if($this->product->close()){
						$this->status = true;
						
						$data = array();
						$data['product'] = $this->product->toJson();
						
						$data['buttons'][] = $this->product->magic_button('edit');
						$data['buttons'][] = $this->product->magic_button('close');
						
						$this->procedure = 'replace_html';
						$this->replace = array();
						$this->replace[] = array(
												'id' => 'box_product_details',
												'html' => $this->load->view('product_details_core.tpl', $data, true, 'smarty', 'products')
						);
						
						$button = $this->product->magic_button('close');
						$this->replace[] = array(
								'id' => 'li_'.$button['id'],
								'html' => '<a class="button" href="' . $button['url'] . '" id="' . $button['id'] . '"  onClick=\''. $button['onclick'] .'\'>'. $button['label'] .'</a>',
						);												
					}  
				}
			}
		} else {
			//TODO do something
		}
	}
	
	public function save_product(){
		
		if($get = $this->input->get()){
			
			if(!$get['product']) {
				//TODO this should return something to js
				//$this->status = false;
				//return;
			}
			
			
			
			foreach ($get as $attribute => $value) {
				if(empty($get['id']) && $attribute == 'id'){
					continue;
				} else {
					$this->product->$attribute = $value;
				}
			}
			
			if(empty($get['id'])) {
				$id = $this->product->create();
				$message = 'product #' . $id . ' successfully created';
			} else {
				$id = $this->product->update();
				$message = 'product #' . $id . ' successfully updated';
			}
			
			if($id){
				$this->mcbsb->system_messages->success = $message;
			} else {
				$product_id = empty($this->product->id) ? '' : '#'.$this->product->id;
				$this->mcbsb->system_messages->error = 'Error while saving the product '.$product_id;
			}
			
		}
		
		redirect('/products/');
	}

}