{literal}
<script type="text/javascript">
	$(document).ready(function(){
		//shows the spinner in the notification area everytime an ajax request is triggered
		$('#spinner').ajaxStart(function() {
			$('#spinner').html('<img src="/layout/images/spinner.gif"/>');
		});
		
		$('#spinner').ajaxStop(function() {
			$(this).html('');
		}); 
	});		
</script>
{/literal}	

<div class="container_24">
	{* top anchor *}
	<div class="grid_9" style="padding-top: 20px;"><img src="/layout/images/mcbsb_logo.png" /><span style="float: right; padding-top: 45px; margin-right: 45px; font-size: 9px;">v.{$mcbsb_version}</span></div>
	<div class="grid_15">
		<div class="box">
		
			<div id="notification_area">
				<div id="spinner" style="float: right; min-height: 100px; min-width: 100px;"></div>
				{* <pre>{$system_messages|print_r}</pre> *}
				<div>
					<ul>
					{foreach $system_messages as $key => $system_message}
						{* <li>{$system_message['time']|strftime '%T'}</li> *}
						{if $system_message['type'] == 'error'}
							{assign type 'dark_red'}
						{/if}
						{if $system_message['type'] == 'warning'}
							{assign type 'blue'}
						{/if}
						{if $system_message['type'] == 'success'}
							{assign type 'dark_green'}
						{/if}
						{* {$system_message['username']|ucwords} *}										 
						<li class="system_message {$type}">{$system_message['time']|date_format:"%T"} - {t}{$system_message['text']}{/t}</li> 
					{/foreach}
					</ul>
				</div>	
			</div>
			
			{* <p style="font-size: 11px; text-align: right;">{t}See all notifications{/t}</p> *} {* //TODO introduce APE and add link here *}
		</div>
	</div>
	<a id="top" name="top"></a>
