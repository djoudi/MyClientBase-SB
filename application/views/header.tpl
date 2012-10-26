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
		<link href="/assets/style/css/styles.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="/assets/style/fluid_grid.css?column_width=60&amp;column_amount=16&amp;gutter_width=20" media="screen" rel="stylesheet" type="text/css">
		<link href="/assets/style/css/superfish.css" rel="stylesheet" type="text/css" media="screen" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="/assets/style/css/ie6.css" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="/assets/style/css/ie7.css" /><![endif]-->
		<link type="text/css" href="/assets/jquery/ui-themes/myclientbase/jquery-ui-1.8.16.custom.css" rel="stylesheet" />


		{* javascript *}

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
		<script type="text/javascript" src="/assets/jquery/jquery-172.js"></script>
		<script type="text/javascript" src="/assets/jquery/jquery-ui-1.8.16.min.js"></script>
		<script src="/assets/jquery/jquery.maskedinput-1.2.2.min.js" type="text/javascript"></script>
		<script src="/assets/jquery/util.js" type="text/javascript"></script>
		<script src="/assets/jquery/superfish.js" type="text/javascript"></script>
		<script src="/assets/jquery/supersubs.js" type="text/javascript"></script>
		<script src="/assets/jquery/mcbsb.js" type="text/javascript"></script>
		
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	
		{literal}
     <script>
         $(document).ready(function(){
             $("ul.sf-menu").supersubs({
                 minWidth:    12,
                 maxWidth:    38,
                 extraWidth:  1
             }).superfish();

			$( "input:submit.uibutton").button();

         });
     </script>
		{/literal}

	</head>
	<body>
	{* top anchor *}
	<div class="container_16" style="max-width: 1200px;">
	<a id="top" name="top"></a>
 	
		<div id="navigation_wrapper">
			<ul class="sf-menu" id="navigation">

                <?php 
                	echo modules::run('mcb_menu/header_menu/display');
                ?>
			</ul>
		</div>

		<div id="subnavigation_wrapper">
			<?php
				$data = array(); 
				$data['system_messages'] = $this->mcbsb->system_messages->all;
				$this->plenty_parser->parse('system_messages.tpl', $data, false, 'smarty','/views');
				
				$this->load->view('system_messages'); //TODO this should be removed one day 
			?>
		</div>
		
		<div class="container_10" id="center_wrapper">
