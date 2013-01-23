<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function application_title() {

    $CI =& get_instance();

    return ($CI->mcbsb->settings->setting('application_title')) ? $CI->mcbsb->settings->setting('application_title') : $CI->lang->line('myclientbase');

}

/**
 * Retrieves uid or oid from the URL
 * 
 * @access		public
 * @param		none	
 * @return		array
 * 
 * @author 		Damiano Venturin
 * @since		Feb 5, 2012 
 */
function retrieve_uid_oid(){
	$CI = &get_instance();
	$uid = uri_assoc('uid');
	$oid = uri_assoc('oid');
	
	if(empty($uid) && empty($oid))
	{
		if(uri_assoc('client_id'))
		{
			$client_id = uri_assoc('client_id');   //retrieving client_id from GET
		} else {
			if($CI->input->get_post('client_id')) $client_id = $this->input->post('client_id'); //retrieving client_id from POST
		}
	}
	 
	if($uid) {
		$params = array(
				'uid' => $uid,
				'client_id' => $uid,
				'client_id_key' => 'uid'
		);
	}
	
	if($oid) {
		$params = array(
				'oid' => $oid,
				'client_id' => $oid,
				'client_id_key' => 'oid'
		);
				
	}
		
	if(isset($client_id) && $client_id) {
		$params = array(
				'client_id' => $client_id
		);
	}
	
	return isset($params) ? $params : null;	
}

function saveUploadedFile()
{
	if($_FILES)
	{	 
		$CI = &get_instance();
		$CI->load->config('upload');

		$config = array();
		$config_items = array('upload_path', 'allowed_types', 'max_size', 'max_width', 'max_height', 'encrypt_name');
		foreach ($config_items as $item) {
			$config[$item] = $CI->config->item($item);
		}
		 
		$CI->load->library('upload', $config);
		 
		$data = array();
		$error = array();
		foreach ($_FILES as $key => $values) {
			if ( ! $CI->upload->do_upload($key))
			{
				$error[$key] = $CI->upload->display_errors();
			}
			else
			{	
				//saves the file locally
				$data[$key] = $CI->upload->data();				
			}
		}
		$output = array('error' => $error, 'data' => $data);
		return $output;
	}	
}

/* End of file mcbsb_helper.php */
/* Location: ./application/helpers/mcbsb_helper.php */