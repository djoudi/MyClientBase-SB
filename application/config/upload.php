<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['upload_path'] = 'uploads/';
$config['allowed_types'] = 'jpg|png';
$config['max_size']	= '200';
$config['max_width']  = '200';
$config['max_height']  = '200';
$config['encrypt_name']  = true;  //If set to TRUE the file name will be converted to a random encrypted string