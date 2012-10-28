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
	});
</script>
{/literal}

<div class="push_1 grid_7">

	{* <pre>{$errors|print_r}</pre> *}
	<div class="box" style="padding: 10px; padding-left: 25px;">
	
	<h3 style="margin-bottom: 20px;">MCB-SB</h3>
	 
	<form method="post" action="/login" id="form_login">
		<dl>
			<dt><label>{t}Email{/t}</label></dt>
			<dd><input type="text" value="{$form['username']}" id="username" name="username" /></dd>
				
			<dt><label>{t}Password{/t}</label></dt>
			<dd><input type="password" value="{$form['password']}" id="password" name="password" /></dd>
		</dl>
		
		<dl>
			<dt>&nbsp;</dt>
			<dd><span style="margin-left: 3px;">{$captcha['image']}</span></dd>
		</dl>
		
		<dl>
			<dt><label>{t}Write here the code you see on the top. (not case sensitive){/t}</label></dt>
			<dd><input type="text" value="" id="captcha" name="captcha" /></dd>
		</dl>
		
		<a href="#" class="button" style="float: right; margin-right: 50px;" id="button_login">{t}Login{/t}</a>
		<div style="clear: both;"></div>
	</form>
	</div>
</div>
{include file="$footer_file"}