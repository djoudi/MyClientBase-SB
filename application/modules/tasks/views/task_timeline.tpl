{* <pre>{$timeline|print_r}</pre> *}

{* FAKE TIMELINE *}
{if !isset($timeline.$task_id)}

	<ul class="time_line" style="overflow-y: hidden;">
	
		<li style="border-top: 1px dashed #ccc;">
			<img style="margin-left: -5px;" src="/layout/images/timeline_end.png" /> 
			<span style="border: 1px solid #ccc; padding: 5px; background-color: white;">{$task->due_date|date_format:"%a %Y-%m-%d"|default:'--'}</span>
		</li>						

		
		<li style="min-height: 35px; max-height: 200px; height: auto;">
			<p class="zero" style="margin-left: 20px; padding-top: 20px; padding-bottom: 20px; font-size: 10px; font-style: italic;">{t}No appointements or activities found for this task{/t}</p>
		</li> 
		
		
		<li style="border-bottom: 1px dashed #ccc;">
			<img style="margin-left: -5px;" src="/layout/images/timeline_start.png" />
			<span style="border: 1px solid #ccc; padding: 5px; background-color: white;">{$task->start_date|date_format:"%a %Y-%m-%d"|default:'--'}</span>
		</li>
		
	</ul>
	
{else}
	
	<ul class="time_line" {if ($timeline.$task_id|count) <= 4} style="overflow-y: hidden;" {/if} >
		
		{foreach $timeline.$task_id as $event}

			{if $event.type == 'start'}
				<li style="border-bottom: 1px dashed #ccc;">
					<img style="margin-left: -5px;" src="/layout/images/timeline_start.png" />
					<span style="border: 1px solid #ccc; padding: 5px; background-color: white;">{$task->start_date|date_format:"%a %Y-%m-%d"|default:'--'}</span>
				</li>
			{/if}						
		
			{if $event.type == 'end'}
				<li style="border-top: 1px dashed #ccc;">
					<img style="margin-left: -5px;" src="/layout/images/timeline_end.png" /> 
					<span style="border: 1px solid #ccc; padding: 5px; background-color: white;">{$task->due_date|date_format:"%a %Y-%m-%d"|default:'--'}</span>
				</li>			
			{/if}
			
			{* <pre>{$event|print_r}</pre> *}
			
			{if $event.type == 'appointment'}
			
				{foreach $task->appointments as $key => $appointment}
				
					{if $appointment->id == $event.id}
						<li>
							
							{if ($task->due_date|date_format:"%s") > ($appointment->start_time|date_format:"%s")}
								<img style="margin-left: -5px;" src="/layout/images/timeline_appointment.png" />
							{else}
								<img style="margin-left: -5px;" src="/layout/images/timeline_appointment_late.png" />
							{/if}
																		

							<table class="event">
								<tr>
									{* <td class="left">&nbsp;</td> *}
									
									<td class="center" colspan="2"  style="background-color: #fffccc;">
										
										{assign var="start_day" value="{$appointment->start_time|date_format:"%a %Y-%m"}"}
										{assign var="end_day" value="{$appointment->end_time|date_format:"%a %Y-%m"}"}
										
										{if $start_day==$end_day}
											<p class="zero" >
												<a id="a_appointment_{$appointment->id}"><image src="/layout/images/info_mark.png" /></a>											
												{$appointment->start_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'} - {$appointment->end_time|date_format:"%H:%M"|default:'--'}
											</p>
										{else}
											<p class="zero" >
												<a id="a_appointment_{$appointment->id}"><image src="/layout/images/info_mark.png" /></a>
												{$appointment->start_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}
											</p>
											<p class="zero" style="padding-left: 25px;">
												{$appointment->end_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}
											</p>
										{/if}
										
										{* bubble *}
										<div id="tip_appointment_{$appointment->id}" style="display: none;">
										
											<p style="font-size: 11px; font-weight: bold;">{t}Appointment ID{/t} #{$appointment->id}</p>
											
											<hr style="margin: 10px;"/>
											<p style="font-size: 11px;">{t}Creation date{/t}: {$appointment->creation_date|date_format:"%a %Y-%m-%d  %H:%m"|default:'--'}</p>
											<p style="font-size: 11px;">{t}Created by{/t}: <a href="/contact/details/uid/{$appointment->created_by}">{$appointment->creator}</a></p>
			
											{if $appointment->update_date}
												<p style="font-size: 11px; margin-top: 10px;">{t}Last update{/t}: {$appointment->update_date|date_format:"%a %Y-%m-%d %H:%m"}</p>
											{/if}
											
											{if $appointment->editor}
												<p style="font-size: 11px;">{t}Updated by{/t}: {$appointment->editor}</p>
											{/if}
											
										</div>											
									</td>
									
									<td class="right" style="background-color: #fffccc;">
									
										{* edit button *}
										{if isset($task->edit_appointment_buttons.$key) && is_null($task->complete_date)}
											{assign var="button" value=$task->edit_appointment_buttons.$key}
											
											<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
									
										{/if}	
																							
										{* delete button *}
										{if isset($task->delete_appointment_buttons.$key) && is_null($task->complete_date)}
											{assign var="button" value=$task->delete_appointment_buttons.$key}
											
											<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
											
										{/if}	
									</td>
								</tr>
								
								{if $appointment->where != ""}
								<tr>
									<td class="left" colspan="3" style="background-color: #fffccc;">
										<p class="zero">
											<a href="https://maps.google.com/maps?q={$task->where|urlencode}" target="_blank">
												<img src="/layout/images/map.png">
												{$appointment->where|default:'--'}
											</a>
										</p>							
									</td>
								</tr>										
								{/if}
								
								<tr>
									{* people involved in the appointment *}
									<td class="left" colspan="2" style="background-color: #fffccc;">
										<ul class="involved_list">

										{foreach $appointment->involved as $t => $otr}
											<li>
												{anchor("/contact/details/uid/{$otr.colleague_id}","{$otr.colleague_name|truncate:23:'[..]':true}")}
											</li>	
										{/foreach}
										</ul>
									</td>
									
									<td class="right" style="background-color: #fffccc;">
										{* appointment involve button *}
										{if isset($task->appointment_involve_buttons.$key) && is_null($task->complete_date)}
											{assign var=button value=$task->appointment_involve_buttons.$key}
										
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
				
				{foreach $task->activities as $key => $activity}
					 
					{if $activity->id == $event.id}
					<li>
						
						{if ($task->due_date|date_format:"%s") > ($activity->action_date|date_format:"%s")}
							<img style="margin-left: -5px;" src="/layout/images/timeline_activity.png" />
						{else}
							<img style="margin-left: -5px;" src="/layout/images/timeline_activity_late.png" />
						{/if}				
											
						<table class="event">
							<tr>
								<td class="center" colspan="2" style="background-color: #eaffda;">
									<p class="zero">{$activity->action_date|date_format:"%a %Y-%m-%d"|default:'--'}</p>
								</td>
								
								<td class="right" style="background-color: #eaffda;">
									
									{* edit button *}
									{if isset($task->edit_activity_buttons.$key) && is_null($task->complete_date)}
										{assign var="button" value=$task->edit_activity_buttons.$key}
										
										<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
										
									{/if}
																					
									{* delete button *}
									{if isset($task->delete_activity_buttons.$key) && is_null($task->complete_date)}
										{assign var="button" value=$task->delete_activity_buttons.$key}
										
										<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
										
									{/if}	
								</td>
							</tr>
							
							<tr>
								<td class="center" colspan="3" style="background-color: #eaffda;">
									<p class="zero">
										
										{assign var="string" value=$activity->activity|truncate:110:" [..]":true}
										
										<a id="a_activity_{$activity->id}"><image style="margin-right: 6px;" src="/layout/images/info_mark.png" /></a> <span>{$string}</span>
									
										{* bubble *}
										<div id="tip_activity_{$activity->id}" style="display: none;">
										
											<p style="font-size: 11px; font-weight: bold;">{t}Activity ID{/t} #{$activity->id}</p>
																		
											<hr style="margin: 10px;"/>
											<p><b>{t}Activity{/t}</b></p>
											<p style="line-height: 18px; white-space: pre-wrap;">{$activity->activity|default:'--'}</p>
											
											<hr style="margin: 10px;"/>
											<p><b>{t}Note{/t}</b></p>
											<p style="line-height: 18px; white-space: pre-wrap;">{$activity->note|default:'--'}</p>
											
											<hr style="margin: 10px;"/>
											<p style="font-size: 11px;">{t}Creation date{/t}: {$activity->creation_date|date_format:"%a %Y-%m-%d  %H:%m"|default:'--'}</p>
											<p style="font-size: 11px;">{t}Created by{/t}: <a href="/contact/details/uid/{$activity->created_by}">{$activity->creator}</a></p>
			
											{if $activity->update_date}
												<p style="font-size: 11px; margin-top: 10px;">{t}Last update{/t}: {$activity->update_date|date_format:"%a %Y-%m-%d %H:%m"}</p>
											{/if}
											
											{if $activity->editor}
												<p style="font-size: 11px;">{t}Updated by{/t}: {$activity->editor}</p>
											{/if}
											
										</div>
														
									</p>
								</td>											
							</tr>

							<tr>
								<td class="left" style="background-color: #eaffda;">
									{if $activity->billable == 1}
										<img src="/layout/images/dollar.png" />
									{else}
										<img src="/layout/images/free.png" />
									{/if}
								</td>
								
								<td class="center" style="background-color: #eaffda;">
									<p class="zero">{t}Duration{/t}: {$activity->duration|default:'0'} {t}Mileage{/t}: {$activity->mileage|default:'0'}</p>
								</td>
								
								<td class="right" style="background-color: #eaffda;">
								
									{anchor("/contact/details/uid/{$activity->created_by}","{$activity->creator|truncate:18:'[..]':true}")}
								</td>													
							</tr>											
							
						</table>
					</li>
					{/if}
				{/foreach}
													
			{/if}							
		{/foreach}
		
	</ul>
	
{/if}
