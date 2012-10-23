<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function uri_assoc($var, $segment = 3) {

	$CI =& get_instance();

	//DAM
	//uid, oid are the MCB client_id, so I need to reconnect to this case looping the segments and looking for uid or oid 
	if($var == 'client_id')
	{
		$segs = $CI->uri->segment_array();
		
		foreach ($segs as $item)
		{
			if($item == 'uid') 
			{
				$var = 'uid';
				break;
			}
			
			if($item == 'oid') 
			{
				$var = 'oid';
				break;
			}
		}
	}
	
	$uri_assoc = $CI->uri->uri_to_assoc($segment);

	if (isset($uri_assoc[$var])) {

		return $uri_assoc[$var];

	}

	else {

		return NULL;

	}

}

//dam
function uri_find($var) {
	
	$CI =& get_instance();	
	$segments = $CI->uri->segment_array();
	foreach ($segments as $key => $value){
		if($value == $var){
			if(isset($segments[$key + 1])) return $segments[$key + 1];	
		}
	}
	
	return null;
}

?>