<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['gettextProjectDir'] = FCPATH.APPPATH.'third_party/php-gettext-1.0.11';
$config['gettextLocaleDir'] = FCPATH.APPPATH.'third_party/php-gettext-1.0.11/locale';
$config['gettextInc'] = FCPATH.APPPATH.'third_party/php-gettext-1.0.11/gettext.inc';
$config['gettextSupportedLocales'] = array(
										'english' => 'en_US',
										'italian' => 'it_IT', 
										'russian' => 'ru_RU'
);
$config['gettextEncoding'] = 'UTF-8';

/* End of file phpgettext.php */
/* Location: ./application/config/phpgettext.php */