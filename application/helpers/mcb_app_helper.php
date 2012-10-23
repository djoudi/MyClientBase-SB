<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function application_title() {

    $CI =& get_instance();

    return ($CI->mcbsb->settings->setting('application_title')) ? $CI->mcbsb->settings->setting('application_title') : $CI->lang->line('myclientbase');

}

?>
