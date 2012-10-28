<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{* dir="rtl" lang="hb" xml:lang="hb" this is for hebrew *}
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{$application_title}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="en" name="language">
		<meta content="width=1200,maximum-scale=1.0" name="viewport">
		<meta name="description" content="MCBSB, your backoffice application" />
		<meta name="keywords" content="invoice, quote, ldap, back-office, contact, business, process, php, gpl" />
		<meta name="author" content="Damiano Venturin" />	

		{* css *}
		{$device = 'computer'}
		<link rel="stylesheet" href="/layout/css/{$device}/mcbsb.css" />		

		{* javascript *}
				
		<script type="text/javascript" src="/js/jquery-1.8.2.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.9.1.js"></script>
		<script type="text/javascript" src="/js/jquery.hotkeys.js"></script> {* provides shortcuts *}
		
		{* global var language *}
		<script type="text/javascript">
			language = "{$language}";
		</script>
		
		<script type="text/javascript" src="/js/mcbsb.js"></script>
		
		{if $environment == 'development'}
		{literal}
		<script type="text/javascript">
			
			//DAM This rewrites all the PHP errors at the bottom of the page and hides the original ones
			$(document).ready(function(){
			
				var html = '';
				jQuery('.php_error').each(function(index){
					html = html + '<div class="php_error" style="border:1px solid #990000; padding-left:20px; margin:0 0 10px 0;"> [error #' + index + '] '+ jQuery(this).html() + '</div>';
					jQuery(this).replaceWith("");
				});
			
				if(html != "") html = '<br/><div id="php_error_container" style=""><h1>List of PHP errors</h1>' + html + '</div>';
				jQuery('.php_error').remove();
					
				jQuery('#php_error_container').replaceWith(html);
				jQuery('.php_error').css('background-color','yellow');
			});
		
		</script>				
		{/literal}
		{/if}
		
		{* <script src="/assets/jquery/jquery.maskedinput-1.2.2.min.js" type="text/javascript"></script> *}
		{* <script src="/assets/jquery/util.js" type="text/javascript"></script> *}
		{* <script src="/assets/jquery/superfish.js" type="text/javascript"></script> *}
		{* <script src="/assets/jquery/supersubs.js" type="text/javascript"></script> *}
		
		
		{* <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> *}

	</head>
	<body>
	<div class="container_24">
	{* top anchor *}
	<a id="top" name="top"></a>