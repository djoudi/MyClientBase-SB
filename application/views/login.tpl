{assign header_file "{$fcpath}application/views/header.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}

{literal}
<script type="text/javascript">
	$(document).ready(function(){

		$('#button_login').click(function() {
  			$('#form_login').submit();
		});
		$('#username').focus();

		$('#captcha').keypress(function(event){
			
			//this intercepts the Enter key
			if (event.which == 13)
			{
				return $('#form_login').submit();
			} else {
			   return true;
			}
		});				
	});
</script>
{/literal}

<div class="container_24">
	<div class="grid_7 push_1">
	
		<img src="/layout/images/mcbsb_logo.png" />
		
		<div class="box" style="margin-top: 20px; padding: 10px; padding-left: 25px;">
		 
		<form method="post" action="/login" id="form_login">
			<dl>
				<dt style="width: 100px;"><label>{t}Email{/t}</label></dt>
				<dd><input type="text" value="{$form['username']}" id="username" name="username" /></dd>
					
				<dt style="width: 100px;"><label>{t}Password{/t}</label></dt>
				<dd><input type="password" value="{$form['password']}" id="password" name="password" /></dd>
			</dl>
			
			<dl>
				<dt style="width: 100px;">&nbsp;</dt>
				<dd><span style="margin-left: 3px;">{$captcha['image']}</span></dd>
			</dl>
			
			<dl>
				<dt style="width: 100px;"><label>{t}Write here the code you see on the top. (not case sensitive){/t}</label></dt>
				<dd><input type="text" value="" id="captcha" name="captcha" /></dd>
			</dl>
			
			<a href="#" class="button" style="float: right; margin-right: 10px;" id="button_login">{t}Login{/t}</a>
			
			<div style="clear: both;"></div>
		</form>
		</div>
		
		{if count($errors) > 0}
		<div class="box b_dark_red" style="color: white; min-height: 25px; margin-top: 5px;text-align: center; padding-top: 5px; font-weight: bold;">
			{foreach $errors as $key => $error}
				<p>{t}{$error}{/t}</p>
			{/foreach}
		</div>
		{/if}
	</div>

	{include file="$footer_file"}