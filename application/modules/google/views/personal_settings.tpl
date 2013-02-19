{literal}
<script type="text/javascript">
	$(document).ready(function() {
		shortcuts_top_menu();	
	
		$('#a_google_tip').bubbletip($('#tip_google'), { deltaDirection: 'right', calculateOnShow: true });
	});
</script>
{/literal}

<h4 style="margin-top: 20px;">{t}Google Resources Access{/t}</h4>



<div class="grid_6 box" style="padding: 10px; height: 160px;">
	<p class="zero" style="line-height: 20px;">
		{t}MCBSB can work with your Google Apps for Business but it needs access to some of your Google resources like{/t}:
		<ul>	
			<li>Calendar</li>
			<li>Drive</li>
			<li>Tasks</li>
			<li>{t}and general information{/t}</li>
		</ul> 	
	</p> 

</div>

<div class="grid_10 box" style="margin-left: 22px; padding: 10px; height: 160px;">

	<p class="zero" style="line-height: 20px;">
	{t}By granting access to your Google resources, MCBSB will:{/t}
		<ul>	
			<li>save the appointments to your team members Google Calendar</li>
		</ul> 			
	</p>
	
	<p class="zero" style="margin-top: 20px; line-height: 20px;">
	{if isset($authUrl)}
	
		<p class="zero" style="font-weight: bold;">{t}Access is not granted{/t}</p>
		<p class="zero" style="margin-top: 15px;"><a class="button" href="{$authUrl}">{t}Grant access{/t}</a></p>
		
	{else}
	
		<p class="zero" style="font-weight: bold;">{t}Access is currently granted{/t}</p>
		<p class="zero" style="margin-top: 15px;"><a class="button" href="{$authUrl}">{t}Revoke access{/t}</a></p>
		
	{/if}
	</p>
</div>

<div style="clear: both"></div>

