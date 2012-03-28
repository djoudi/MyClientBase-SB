<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {a url='http://www.google.com' text='link'} function plugin
 *
 * Type:     function<br>
 * Name:     citranslate<br>
 * Purpose:  print out a translated sentence using the common CI method (just a workaround)
 * @author Damiano Venturin 
 * @param array parameters
 * @param Smarty
 * @param object $template template object
 * @return string|null
 */
function smarty_function_a($params, $template)
{
	$class = '';
	$url = '';
	$title = '';
	$text = '';
	$target = '';
	if(isset($params['class'])) $class = 'class="'.$params['class'].'"';
	if(isset($params['url'])) $url = $params['url'];
	if(isset($params['title'])) $title = $params['title'];
	if(isset($params['target'])) $target = 'target="'.$params['target'].'"';
	if(isset($params['text'])) $text = $params['text'];
	if(empty($text)) $text = 'text is missing';
	return '<a '.$class.' title="'.$title.'" href="'.$url.'" '.$target.'>'.$text.'</a>';    
}

?>