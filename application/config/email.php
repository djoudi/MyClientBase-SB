<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//http://codeigniter.com/user_guide/libraries/email.html

$config['email']['user_agent'] = '';
$config['email']['protocol'] = 'smtp';
$config['email']['smtp_host'] = 'ssl://smtp.host.com';
$config['email']['smtp_port'] = 465;
$config['email']['smtp_timeout']= 10;
$config['email']['smtp_user'] = '';
$config['email']['smtp_pass'] = '';
$config['email']['from'] = 'info@example.com';
$config['email']['reply_to'] = 'info@example.com';
$config['email']['charset'] = 'utf-8';
$config['email']['validate'] = TRUE;
$config['email']['priority'] = 3;
$config['email']['wordwrap'] = TRUE;
$config['email']['mailtype'] = "html";
$config['email']['newline'] = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */
