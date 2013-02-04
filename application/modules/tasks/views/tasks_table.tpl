{setlocale locale=$locale}

<script type="text/javascript">
	$(document).ready(function() {
		shortcuts_top_menu();	

		$("[id^=a_task_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_task_'+item_num), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});

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

<table class="tasks">

	{* HEADER *}
    <tr>
    
    
    	{$class = "first"}
    	{if isset($show_task_selector) && $show_task_selector} {* //TODO what about if $common_list ? *}
			{$class = ""}
			<th class="first">&nbsp;</th>
		{/if}
		
		
		
		{* URGENT *}
		<th class="question_mark">
			<a id="a_urgent"><img src="/layout/images/question_mark.png" /></a>			
		</th>  
		<div id="tip_urgent" style="display: none;">
			<p>{t}Click on the rounded blue markers below to see a quick detailed view of the task{/t}.</p>
			<p>{t}Urgent tasks display an esclamation mark here below{/t}.</p>
		</div>	
		
		
		
		<th class="description">{t}Task{/t}</th>
		

		<th class="involved">{t}People involved{/t}</th>
		
		
		<th class="time_line">{t}Time line{/t}</th>
		
    </tr>
    
    
    {* BODY *}
    
	{foreach $tasks as $task}
	<tr>
		{if isset($show_task_selector) && $show_task_selector}
			<td class="first"><input type="checkbox" class="task_id_check" name="task_id[]" value="{$task.id}"></td>
		{/if}
	
	
	
		{* URGENT *}
		<td class="question_mark">
		
			<a id="a_task_{$num}"><image src="/layout/images/info_mark.png" /></a>
			{if !is_null($task.complete_date)}
				<img style="margin-top: 5px;" src="/layout/images/locked.png" />
			{/if}
			
			{if $task.urgent}
				<img style="margin-top: 5px;" src="/layout/images/esclamation_mark.png" />
			{/if}
		</td>
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

		
		
		
		<td class="description">
		{$style=''}
		
		{assign var=today value=$smarty.now|date_format:"%Y-%m-%d"}
		{if $today == $task.due_date|date_format:"%Y-%m-%d" && !$task.complete_date}{$style="color: #ff9c00;"}{/if}
		
		{if $task.complete_date}{$style="color: #acacac; font-style: italic;"}{/if}

			<p class="zero" style="padding-bottom: 10px; font-weight: bold;">#{$task.id}</p>
			
			<p class="zero" style="min-height: 45px; padding-bottom: 10px; {$style}">
				{$task.task|truncate:80:" [..]":true}
			</p>	

			{if isset($task.assets) && count($task.assets)>0}
			<hr/>
			<ul class="task_assets_list">
				
				{foreach $task.assets as $asset}
					{if $asset.category == "home_appliance"}
						{assign var="string" value="{$asset.brand} {$asset.model} {$asset.serial}"}
						{assign var="string" value=$string|truncate:30:" [..]":true}
						<li>{anchor("assets/details/id/{$asset.id}","{$string}")}</li>	
					{/if}
				{/foreach}
				
			</ul>
			<hr/>
			{/if}
		</td>
		
		{$num={counter}}
		
					
		
		
		
		
		
		{* People involved *}
		<td class="involved">

			<ul class="zero" style="list-style: none;">

				{* this is shown only in the "all tasks" view *}
				{if {preg_match pattern="\/tasks$" subject=$site_url} && isset($task.contact_name)}
					<li class="zero" style="margin-bottom: 10px;">			
						<a class="zero" href="/contact/details/{$task.contact_id_key}/{$task.contact_id}#tab_tasks">{$task.contact_name|truncate:23:"[..]":true}</a>
					</li>
				{/if}
				
				{if $task.involved}

					{foreach $task.involved as $key => $colleague}
						<li style="line-height: 18px; margin: 0px; padding: 0px; font-size: 11px;">
							<img src="/layout/images/hat.png"/><a style="margin-left: 5px;" href="/contact/details/uid/{$colleague.colleague_id}">{$colleague.colleague_name|truncate:23:"[..]":true}</a>
						</li>
					{/foreach}
			
				{/if}
				
			</ul>			

		</td>		
				
				
				
		
		{* time line *}
		<td class="time_line" style="padding-bottom: 15px;">
	
			<div style="background-color: transparent; margin-top: 10px; margin-left: 5px;">
				
				<ul class="time_line" {if ($timeline|count) < 3} style="overflow-y: hidden;" {/if}>
				
					<li>
						<img src="/layout/images/timeline_end.png" />
						{* {t}end{/t}: *} 
						<span style="border: 1px solid #ccc; padding: 5px; background-color: white;">{$task.due_date|date_format:"%a %Y-%m-%d"|default:'--'}</span>
					</li>						

					{if ($task.appointments|count) == 0}
						<li>&nbsp;</li> 
					{/if}
					
					{assign var="task_id" value=$task.id}
					
					{if isset($timeline.$task_id)}
						
						{foreach $timeline.$task_id as $event}
						
							{if $event.type == 'appointment'}
								{foreach $task.appointments as $key => $appointment}
									{if $appointment.id == $event.id}
										<li>
											<img src="/layout/images/timeline_appointment.png" />
											<table class="appointment">
												<tr>
													{* <td class="left">&nbsp;</td> *}
													
													<td class="center" colspan="2">
														{assign var="start_day" value="{$appointment.start_time|date_format:"%a %Y-%m"}"}
														{assign var="end_day" value="{$appointment.end_time|date_format:"%a %Y-%m"}"}
														
														{if $start_day==$end_day}
															<p class="zero" >{$appointment.start_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'} - {$appointment.end_time|date_format:"%H:%M"|default:'--'}</p>
														{else}
															<p class="zero" >{$appointment.start_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}</p>
															<p class="zero" >{$appointment.end_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}</p>
														{/if}	
													</td>
													
													<td class="right">
													
														{* edit button *}
														{if isset($task.edit_appointment_buttons.$key) && is_null($task.complete_date)}
															{assign var="button" value=$task.edit_appointment_buttons.$key}
															
															<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
													
														{/if}	
																											
														{* delete button *}
														{if isset($task.delete_appointment_buttons.$key) && is_null($task.complete_date)}
															{assign var="button" value=$task.delete_appointment_buttons.$key}
															
															<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
															
														{/if}	
													</td>
												</tr>
												
												{if $appointment.where != ""}
												<tr>
													<td class="left">
														<a href="https://maps.google.com/maps?q={$appointment.where|htmlspecialchars}&m=t&z=17" target="_blank"><img src="/layout/images/map.png"></a>
													</td>
													
													<td class="center" colspan="2">
														<p class="zero">{$appointment.where|default:'--'}</p>							
													</td>
												</tr>										
												{/if}
												
												<tr>
													<td class="left">
														{if isset($task.involved_in_appointment.$key)}
															{foreach $task.involved_in_appointment.$key as $t => $otr}
																<p class="zero" style="margin-top: 3px;"><img src="/layout/images/hat.png"/></p>
															{/foreach}
														{/if}
													</td>
													<td class="center">
														{* people involved in the appointment *}
														{if isset($task.involved_in_appointment.$key)}
															{foreach $task.involved_in_appointment.$key as $t => $otr}
																<p class="zero">
																<a href="/contact/details/uid/{$otr.colleague_id}">
																	{$otr.colleague_name|truncate:23:"[..]":true}
																</a>
																</p>		
															{/foreach}
														{/if}									
													</td>
													
													<td class="right">
														{* appointment involve button *}
														{if isset($task.appointment_involve_buttons.$key) && is_null($task.complete_date)}
															{assign var=button value=$task.appointment_involve_buttons.$key}
														
															<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
														{/if}		
													</td>
												</tr>																		
			
											</table>
											
										</li>
										
									{/if}
								{/foreach}
							{/if}
							
							{if $event.type == 'activity'}
								{foreach $task.activities as $activity} 
									{if $activity.id == $event.id}
									<li>
										<div class="zero">
											
											{if ($task.due_date|date_format:"%s") > ($activity.action_date|date_format:"%s")}
												<div style="display: inline-block; vertical-align: top;"><img style="display: inline-block;" src="/layout/images/timeline_activity.png" /></div>
											{else}
												<div style="display: inline-block; vertical-align: top;"><img src="/layout/images/timeline_activity_late.png" /></div>
											{/if}
										
											{assign var="string" value=$activity.activity|truncate:50:" [..]":true}
										
											<div style="display: inline-block; width: 340px; border: 1px solid #ccc; padding: 5px; background-color: white;">
											
												<p class="zero">{$activity.action_date|date_format:"%a %Y-%m-%d"|default:'--'} {anchor("tasks/details/id/{$task.id}","{$string}")}</p> 
												<p class="zero">{t}Duration{/t}: {$activity.duration|default:'0'} {t}Mileage{/t}: {$activity.mileage|default:'0'}</p>
												
											</div>
										</div>
										
									</li>
									{/if}
								{/foreach}									
							{/if}
							
						{/foreach}
							
					{/if}
					
				
					<li>
						<img src="/layout/images/timeline_start.png" />
						{* {t}start{/t}: *}
						<span style="border: 1px solid #ccc; padding: 5px; background-color: white;">{$task.start_date|date_format:"%a %Y-%m-%d"|default:'--'}</span>
					</li>
				</ul>
	
			</div>		
		</td>
				
	</tr>
	
	
	
	
	
	
	
	<tr class="task_buttons">
		
		<td class="task_buttons description" colspan="2">
			<p class="zero" style="vertical-align: bottom;">
				
				<a class="button" style="font-size: 11px;" href="/tasks/details/id/{$task.id}">{t}Details{/t}</a>
				{if isset($task.edit_button) && is_null($task.complete_date)}
					{assign var=button value=$task.edit_button}
				
					<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
				{/if}	

				{if isset($task.close_button) && is_null($task.complete_date)}
					{assign var=button value=$task.close_button}
				
					<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
				{/if}					
			</p>
		</td>	
		
		<td class="task_buttons involved">
			{if $task.involve_buttons  && is_null($task.complete_date)}
				<ul class="zero" style="list-style: none;">
				{foreach $task.involve_buttons as $key => $button}
					
					<li class="zero" id="li_{$button.id}"><a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
				{/foreach}
				</ul>
			{/if}		
		</td>	
		
		<td class="task_buttons time_line" style="background-image: none;">
			{if $task.create_appointment_buttons  && is_null($task.complete_date)}
				
				<ul class="tasks_inline_buttons" >
					
				
					{foreach $task.create_appointment_buttons as $key => $button}
						
						<li id="li_{$button.id}"><a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
					
					{/foreach}
					
					
					{if $task.create_activity_buttons}
							
						{foreach $task.create_activity_buttons as $key => $button}
								
							<li id="li_{$button.id}"><a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
							
						{/foreach}
							
					{/if}						
					
				</ul>
				
			{/if}	
							
		</td>	
		
	</tr>
		
	{/foreach}
	
</table>
{/if}