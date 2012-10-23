{setlocale locale=$locale}

<script type="text/javascript">
	$(document).ready(function() {
		shortcuts_top_menu();	

		$("[id^=a_task_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_task_'+item_num), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});

		$('#a_info_mark').bubbletip($('#tip_info_mark'), { deltaDirection: 'up', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });

		$('#a_urgent').bubbletip($('#tip_urgent'), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
	});
</script>

{if $tasks}

{* Legend *}
<div style="float: right;">
	<div class="legend_symbol b_gray">&nbsp;</div><div class="legend_label">{t}ordinary{/t}</div>
	<div class="legend_symbol b_orange">&nbsp;</div><div class="legend_label">{t}due today{/t}</div>
	<div class="legend_symbol b_medium_gray">&nbsp;</div><div class="legend_label">{t}completed{/t}</div>
</div>
<div style="clear: both;"></div>

<table style="width: 100%;">

	{* HEADER *}
    <tr>
    	{$class = "first"}

    	{if isset($show_task_selector) && $show_task_selector} {* //TODO what about if $common_list ? *}
			{$class = ""}
			<th class="first">&nbsp;</th>
		{/if}
		
		<th style="width: 20px; text-align: center;"><a id="a_urgent"><img src="/layout/images/question_mark.png" /></a></th>  {* question mark field *}
		<div id="tip_urgent" style="display: none;">
			<p>{t}Urgent tasks display an esclamation mark here below{/t}.</p>
		</div>
		
		<th style="max-width: 310px;">{t}Task{/t}</th>
		
		<th><a id="a_info_mark"><img src="/layout/images/question_mark.png" /></a></th>  {* question mark field *}
		<div id="tip_info_mark" style="display: none;">
			<p>{t}Click on the rounded blue markers below to see a quick detailed view of the task{/t}.</p>
		</div>
		
		<th>{t}People involved{/t}</th>
		
		<th>{t}Time line{/t}</th>
		
		{* <th>{t}Appointments{/t}</th> *}
		
    </tr>
    
    
    {* BODY *}
    
	{foreach $tasks as $task}
	<tr>
		{if isset($show_task_selector) && $show_task_selector}
			<td class="first"><input type="checkbox" class="task_id_check" name="task_id[]" value="{$task.id}"></td>
		{/if}
	
		{* URGENT *}
		<td  style="vertical-align: top; padding-top: 5px;">
			{if $task.urgent}
				<img src="/layout/images/esclamation_mark.png" />
			{else}
				&nbsp;
			{/if}
		</td>
		
		<td style="width: 310px; line-height: 20px; vertical-align: top;">
		{$style=''}
		
		{assign var=today value=$smarty.now|date_format:"%Y-%m-%d"}
		{if $today == $task.due_date|date_format:"%Y-%m-%d" && !$task.complete_date}{$style="color: #ff9c00;"}{/if}
		
		{if $task.complete_date}{$style="color: #acacac; font-style: italic;"}{/if}
			<p style="padding-top: 5px; padding-bottom: 5px; {$style}">
				<a class="button" style="font-size: 11px;" href="/tasks/details/id/{$task.id}">#{$task.id}</a>
				{$task.task}
			</p>
		</td>
		
		{$num={counter}}
		
		{* info mark *}
		<td style=" vertical-align: top; padding-top: 5px;"><a id="a_task_{$num}"><image src="/layout/images/info_mark.png" /></a></td>
		<div id="tip_task_{$num}" style="display: none;">
			<p>{t}Creation date{/t}: {$task.creation_date|date_format:"%a %Y-%m-%d  %H:%m"|default:'--'}</p>
			<p>{t}Created by{/t}: <a href="/contact/details/uid/{$task.created_by}">{$task.creator}</a></p>
			
			
			{if $task.update_date}
				<p style="margin-top: 10px;">{t}Last update{/t}: {$task.update_date|date_format:"%a %Y-%m-%d %H:%m"}</p>
			{/if}
			
			{if $task.editor}
				<p>{t}Updated by{/t}: {$task.editor}</p>
			{/if}

			
			{if $task.complete_date}
				<p style="margin-top: 10px;">{t}Closed on{/t}: {$task.complete_date|date_format:"%a %Y-%m-%d %H:%m"}</p>
			{/if}
			
			{if $task.completionist}
				<p>{t}Closed by{/t}: {$task.completionist}</p>
			{/if}			
			
			<hr style="margin: 10px;"/>
			
			<p><b>{t}Details{/t}</b></p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task.details|default:'--'}</p>
			
			<hr style="margin: 10px;"/>
			
			<p><b>{t}Complete message{/t}</b></p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task.endnote|default:'--'}</p>			
		</div>				
		
		{* People involved *}
		<td style="vertical-align: top; padding-top: 0px; padding-bottom: 3px; height: 100%;">
		
			<table class="zero" style="height: 100%; border-collapse:collapse;">
		
				<tr class="zero">
					{if {preg_match pattern="\/tasks$" subject=$site_url} && isset($task.contact_name)}
						<td class="zero" valign="top" style="vertical-align: top;">			
							<a href="/contact/details/{$task.contact_id_key}/{$task.contact_id}#tab_tasks">{$task.contact_name|truncate:23:"[..]":true}</a>
						</td>
					{/if}
				</tr>
				
				<tr class="zero">
					<td class="zero" valign="top" style="height: 100%; vertical-align: top;">
						{if $task.involved}
						<hr style="margin-right: 15px;"/>
						<ul style="margin: 0px; padding: 0px; list-style: none; line-height: 18px;">
							{foreach $task.involved as $key => $colleague}
								<li style="line-height: 18px; margin: 0px; padding: 0px; font-size: 11px;">
									<img src="/layout/images/hat.png"/><a style="margin-left: 5px;" href="/contact/details/uid/{$colleague.colleague_id}">{$colleague.colleague_name|truncate:23:"[..]":true}</a>
								</li>
							{/foreach}
						</ul>
						{/if}
					</td>
				
				</tr>
				
				<tr class="zero">			
					<td class="zero" valign="bottom">
						{if $task.involve_buttons}
							<ul class="zero" style="list-style: none;">
							{foreach $task.involve_buttons as $key => $button}
								
								<li class="zero" id="li_{$button.id}"><a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
							{/foreach}
							</ul>
						{/if}
					</td>
				</tr>
				
			</table>
		</td>		
				
		{* old time line *}
		{*
		<td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top; padding-left: 5px; padding-right: 5px; padding-top: 7px; width: 150px; font-size: 11px; {$style}">
			<dl style="padding: 0px; margin: 0px;">
				<dt style="padding: 0px; margin: 0px; width: 50px; line-height: 18px;">{t}start{/t} :</dt>
					<dd style="padding: 0px; margin: 0px;  line-height: 18px;">{$task.start_date|date_format:"%a %Y-%m-%d"|default:'--'}</dd>
					
				<dt style="padding: 0px; margin: 0px; width: 50px; line-height: 18px;">{t}end{/t} :</dt>
				 	<dd style="padding: 0px; margin: 0px; line-height: 18px;">{$task.due_date|date_format:"%a %Y-%m-%d"|default:'--'}</dd>
				<hr style="width: 90%;" />
				<dt style="padding: 0px; margin: 0px; width: 50px; line-height: 18px;">{t}closed{/t} :</dt>
					<dd style="padding: 0px; margin: 0px; line-height: 18px;">{$task.complete_date|date_format:"%a %Y-%m-%d"|default:'--'}</dd>
			</dl>
		</td>		
		*}
		
		{* time line *}
		<td class="zero" style="vertical-align: top; padding-top: 3px; padding-bottom: 3px; width: 280px;">
		
			<table class="zero" style="height: 100%; border-collapse:collapse;">
				
				<tr class="zero">
					<td class="zero" valign="top" style="height: 100%; vertical-align: top;">
						
						<ul style="margin: 0px; padding: 0px; list-style: none; line-height: 18px; max-height: 150px; overflow-x: hidden; overflow-y: scroll;">
						
							<li class="zero" style="font-size: 11px; margin-bottom: 5px;">{t}end{/t}: {$task.due_date|date_format:"%a %Y-%m-%d"|default:'--'}</li>						
							
							{if $task.appointments}
								
								{$k={$task.appointments|count}}
								{foreach $task.appointments as $key => $appointment}
									
									<li style="margin: 0px; padding: 0px; padding-top: 10px; padding-bottom: 10px; font-size: 11px;">
									
										{* delete button *}
										{if isset($task.delete_appointment_buttons.$key)}
											{assign var=button value=$task.delete_appointment_buttons.$key}
										
											<a class="button" style="font-size: 11px; float: right; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
										{/if}	
																			
										<p class="zero" style="font-weight: bold;">{$k}) {$appointment.start_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}</p>
										<p class="zero" style="font-weight: bold; margin-left: 17px;">{$appointment.end_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}</p>									
										
										<p><a href="https://maps.google.com/maps?q={$appointment.where|htmlspecialchars}&m=t&z=17" target="_blank"><img src="/layout/images/map.png" style="margin-right: 5px;"/></a>{$appointment.where|default:'--'}</p>
										
										<hr style="margin-left: 15px; margin-right: 75px;"/>
										
										{* appointment involve button *}
										{if isset($task.appointment_involve_buttons.$key)}
											{assign var=button value=$task.appointment_involve_buttons.$key}
										
											<a class="button" style="font-size: 11px; float: right; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
										{/if}										
										
										{* people involved in the appointment *}
										{if isset($task.involved_in_appointment.$key)}
											{foreach $task.involved_in_appointment.$key as $t => $otr}
												<p class="zero" style="margin-left: 5px;"><img src="/layout/images/hat.png"/><a style="margin-left: 5px;" href="/contact/details/uid/{$otr.colleague_id}">{$otr.colleague_name|truncate:23:"[..]":true}</a></p>		
											{/foreach}
										{/if}
										
										<hr style="margin-left: 15px; margin-right: 75px;"/>
									</li>
									{$k=$k-1}
								{/foreach}
								
							{/if}
							<li class="zero" style="font-size: 11px; margin-top: 5px;">{t}start{/t}: {$task.start_date|date_format:"%a %Y-%m-%d"|default:'--'}</li>
						</ul>
						
					</td>
					
				</tr>
				
				<tr class="zero">			
					<td class="zero" valign="bottom" style="padding-top: 5px;">
						{if $task.create_appointment_buttons}
							<ul class="zero" style="list-style: none;">
								
							
								{foreach $task.create_appointment_buttons as $key => $button}
									
									<li class="zero" id="li_{$button.id}"><a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
								
								{/foreach}
								
							</ul>
						{/if}
					</td>
				</tr>
				
			</table>
		</td>
				
		
	</tr>	
	{/foreach}
	
</table>
{/if}