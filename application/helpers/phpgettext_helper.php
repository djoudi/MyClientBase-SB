<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This helper configures settings for phpgettext to provide a proper content translation via the smarty "t" function 
 * 
 * @access		public
 * @param		None
 * @return		Array containing the localization settings
 * 
 * @author 		Damiano Venturin
 * @since		Oct 25, 2012
 */ 
function setupPhpGettext() {

	$CI =& get_instance();

	$CI->load->config('phpgettext');
	
	// define constants
	if(!defined('PROJECT_DIR')) define('PROJECT_DIR', realpath($CI->config->item('gettextProjectDir')));
	if(!defined('LOCALE_DIR')) define('LOCALE_DIR', realpath($CI->config->item('gettextLocaleDir')));
	if(!defined('DEFAULT_LOCALE')) define('DEFAULT_LOCALE', $CI->config->item('gettextDefaultLocale'));
	
	if(is_file($CI->config->item('gettextInc')))
	{
		if (!function_exists('_gettext')) {
			require_once($CI->config->item('gettextInc'));
			log_message('debug','File '.$CI->config->item('gettextInc').' has been included.');
		}
	} else {
		log_message('debug','File '.$CI->config->item('gettextInc').' can not be found.');
	}
	
	$supported_locales = array_keys($CI->config->item('gettextSupportedLocales'));
	$encoding = $CI->config->item('gettextEncoding');
	
	$locale = $smarty_locale = $CI->mcbsb->_locale;

	return array(
				'locale' => $locale,
				'encoding' => $encoding,
				'supported_locales' => $supported_locales,
				'project_dir' => PROJECT_DIR,
				'locale_dir' => LOCALE_DIR,
				'default_locale' => DEFAULT_LOCALE,
	);
}

/* End of file phpgettext_helper.php */
/* Location: ./application/helpers/phpgettext_helper.php */