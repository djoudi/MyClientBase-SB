<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="/assets/style/css/styles.css" rel="stylesheet" type="text/css" media="screen" />
		<link type="text/css" href="/assets/jquery/ui-themes/myclientbase/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="/assets/jquery/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="/assets/jquery/jquery-ui-1.8.16.custom.min.js"></script>
	</head>
	<body>
		<div class="container_10" id="center_wrapper">

			<div class="grid_5 push_2" id="content_wrapper">

				<div class="section_wrapper">
				
					<div class="content toggle" style="min-height: 0px;">
						{* <pre>{$errors|print_r}</pre> *} 
						<form method="post" action="/login">
							<dl>
								<dt><label>{t}Email{/t}</label></dt>
								<dd><input type="text" value="{$form['username']}" id="username" name="username" /></dd>
							</dl>
							
							<dl>					
								<dt><label>{t}Password{/t}</label></dt>
								<dd><input type="password" value="{$form['password']}" id="password" name="password" /></dd>
							</dl>
						
							<dl style="border: 0px solid green; height: 120px;">
								<dt><label style="padding-left: 30px;">{$captcha['image']}</label></dt>
								<dd style="height: 100%;">
									<input style="width: 70px; margin-top: -20px; padding-top: -20px;" type="text" value="" id="captcha" name="captcha" />
									<p style="text-align: left; margin: 0px; margin-left: 190px; padding: 0px; padding-top: 0px; padding-bottom: 0px;">{t}Please write here the code you see on the left. (Not case sensitive){/t}</p>
									<input class="uibutton" type="submit" value="{t}Login{/t}" style="float: right; margin: 0px; padding: 0px; margin-top: 10px; margin-right: 25px;" name="btn_submit" id="btn_submit" />
								</dd>
								
							</dl>
						</form>
					</div>				
				
				</div>
				
			</div>
			
		</div>
	</body>
</html>