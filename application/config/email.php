<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//http://codeigniter.com/user_guide/libraries/email.html

$config['user_agent'] = '';
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.host.com';
$config['smtp_port'] = 465;
$config['smtp_timeout']= 10;
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['from'] = 'info@example.com';
$config['reply_to'] = 'info@example.com';
$config['charset'] = 'utf-8';
$config['validate'] = TRUE;
$config['priority'] = 3;
$config['wordwrap'] = TRUE;
$config['mailtype'] = "html";
$config['newline'] = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */
