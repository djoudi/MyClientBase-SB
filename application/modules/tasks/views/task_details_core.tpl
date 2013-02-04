
<div id="box_task_details">
	<p style="line-height: 20px; margin-bottom: 15px;">
	
		{if !is_null($task->complete_date)}
			<span style="padding-left: 5px;"><img src="/layout/images/locked.png" /></span>
		{/if}	
	
		{if $task->urgent}
		<span style="padding-left: 5px;"><img src="/layout/images/esclamation_mark.png" /></span>
		{/if}
		<span style="font-size: 15px; padding-left: 5px;">{$task->task}</span>
	</p>
	
	<div class="grid_8">
		<div class="box" style="padding: 10px;">
		
			<table>
				<tr>
					<td style="width: 110px;">{t}Contact{/t}</td>
					<td><a href="/contact/details/{$task->contact_id_key}/{$task->contact_id}/">{$task->contact_name}</a></td>
				</tr>		
				<tr>
					<td style="width: 110px;">{t}Start date{/t}</td>
					<td>{$task->start_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
				<tr>
					<td style="width: 110px;">{t}Due date{/t}</td>
					<td>{$task->due_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
			</table>
				
			<table style="margin-top: 30px;">	
				<tr>
					<td style="width: 110px;">{t}Creation date{/t}</td>
					<td>{$task->creation_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Created by{/t}</td>
					<td>{$task->creator}</td>
				</tr>			
	
				{if $task->update_date}
				<tr>
					<td style="width: 110px;">{t}Last update date{/t}</td>
					<td>{$task->update_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Last updated by{/t}</td>
					<td>{$task->editor}</td>
				</tr>			
				{/if}
				
				{if $task->complete_date}
				<tr>
					<td style="width: 110px;">{t}Closed on{/t}</td>
					<td>{$task->complete_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Closed by{/t}</td>
					<td>{$task->completionist}</td>
				</tr>						
				{/if}
			</table>
		</div>
	</div>
	
	<div class="prefix_1 grid_8">
		<div class="box" style="padding: 10px;">
	
			<p style="font-weight: bold;">{t}Details{/t}</p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task->details|default:'---'}</p>
	
			<p style="margin-top: 20px; font-weight: bold; line-height: 18px; white-space: pre-wrap;">{t}Complete message{/t}</p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task->endnote|default:'---'}</p>
	
		</div>
		
		{if isset($task->assets) && ($task->assets|count) > 0}
			<div class="box" style="padding: 10px; margin-top: 10px;">
				<p style="font-weight: bold;">{t}Assets{/t}</p>
				<ul class="task_assets_list" style="margin-left: 5px;">
				
				
					{foreach $task->assets as $asset} 
						{if $asset.category == "home_appliance"}
							{assign var="string" value="{$asset.brand} {$asset.model} {$asset.serial}"}
							{assign var="string" value=$string|truncate:50:" [..]":true}
							<li>{anchor("assets/details/id/{$asset.id}","{$string}")}</li>	
						{/if}
					{/foreach}
				</ul>
			</div>
		{/if}	

		
		{if isset($task->activities) && ($task->activities|count) > 0}
			<div class="box" style="padding: 10px; margin-top: 10px;">
				<p style="font-weight: bold;">{t}Activities{/t}</p>
				<ul class="task_activities_list" style="margin-left: 5px;">
				
				
					{foreach $task->activities as $activity} 

						{assign var="string" value=$activity.activity|truncate:50:" [..]":true}
						<li>{$activity.action_date}: {anchor("activities/details/id/{$activity.id}","{$string}")} {t}Duration{/t}: {$activity.duration}</li>	

					{/foreach}
				</ul>
			</div>
		{/if}
				
		{if isset($appointments) && ($task->appointments|count) > 0}
			<div class="box" style="padding: 10px; margin-top: 10px;">
		
				<p style="font-weight: bold;">{t}Appointments{/t}</p>
				<ul class="zero" style="list-style: none;">
					{assign var=k value={$appointments|count}}
					{foreach $appointments as $key => $appointment}
					
						{* TODO refactoring, duplicated in tasks_table.tpl *}
						<li style="margin: 0px; padding: 0px; padding-top: 10px; padding-bottom: 10px; font-size: 13px;">
																
							<p class="zero" style="font-weight: bold;">{$k}) {$appointment.start_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}</p>
							<p class="zero" style="font-weight: bold; margin-left: 20px;">{$appointment.end_time|date_format:"%a %Y-%m-%d %H:%M"|default:'--'}</p>									
							
							<p><a href="https://maps.google.com/maps?q={$appointment.where|htmlspecialchars}&m=t&z=17" target="_blank"><img src="/layout/images/map.png" style="margin-right: 5px;"/></a>{$appointment.where|default:'--'}</p>
							
							<hr style="margin-left: 15px; margin-right: 55px;"/>
							
							{* people involved in the appointment *}
							{if isset($involved_in_appointment.$key)}
								{foreach $involved_in_appointment.$key as $t => $otr}
									<p class="zero" style="margin-left: 5px;"><img src="/layout/images/hat.png"/><a style="margin-left: 5px;" href="/contact/details/uid/{$otr.colleague_id}">{$otr.colleague_name|truncate:23:"[..]":true}</a></p>		
								{/foreach}
							{/if}
							
							<hr style="margin-left: 15px; margin-right: 55px;"/>
						</li>
						{$k=$k-1}
		
					{/foreach}
				</ul>
		</div>
	{/if}
	</div>
	<div style="clear: both;"></div>
</div>

{*
<div style="clear: both;"></div>
{foreach $task->_fields as $attribute => $specifics} 	
 	<dl>
	{if isset($task->$attribute)}
		 
		<dt>{$attribute}</dt> 
		<dd>{$task->$attribute}</dd> {/if}
	</dl>
{/foreach}
*} 