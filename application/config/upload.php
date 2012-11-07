<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['upload_path'] = 'uploads/';
$config['allowed_types'] = 'gif|jpg|png';
$config['max_size']	= '1000';
$config['max_width']  = '1024';
$config['max_height']  = '768';
$config['encrypt_name']  = true;  //If set to TRUE the file name will be converted to a random encrypted string