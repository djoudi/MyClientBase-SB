{assign header_file "{$fcpath}application/views/header.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}

{literal}
<script type="text/javascript">
	$(document).ready(function(){

		$('#button_login').click(function() {
  			$('#form_login').submit();
		});
			
	});
</script>
{/literal}

<div class="container_24">
	<div class="grid_7 push_1">
	
		<img src="/layout/images/mcbsb_logo.png" />
		
		<div class="box" style="margin-top: 20px; padding: 10px; padding-left: 25px;">
		 
		<p style="margin-bottom: 20px;">{t}You have been recognized both as a Tooljar administrator and a team member{/t}.</p>
		
		<form method="post" action="/login/with_profile" id="form_login">

			<input type="hidden" value="{$email}" name="email" />
					
			<input type="hidden" value="{$password}" name="password" />
			
			<input type="hidden" value="{$remember}" name="remember" />
			
			<input type="hidden" value="{$security_key}" name="security_key" />
			
			<input type="hidden" value="true" name="login_as_tj_admin" />
			
			<dl>
				<dt style="width: 100px; line-height: 40px;"><label>{t}Login as{/t}:</label></dt>
				<dd>
					<input type="radio" name="profile" value="tj_admin">{t}Tooljar administrator{/t}<br>
					<input type="radio" name="profile" value="user" checked>{t}Regular user{/t}<br>
				</dd>				
			</dl>
			
			<a href="#" class="button" style="float: right; margin-right: 10px;" id="button_login">{t}Login{/t}</a>
			<div style="clear: both;"></div>
		</form>
		</div>
	</div>

	{include file="$footer_file"}