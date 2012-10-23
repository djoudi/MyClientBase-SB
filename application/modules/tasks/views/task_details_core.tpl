{jdecode object=$task}

<div id="box_task_details">
	<p style="line-height: 20px; margin-bottom: 15px;">
		{if $object->urgent}
		<span style="padding-left: 5px;"><img src="/layout/images/esclamation_mark.png" /></span>
		{/if}
		<span style="font-size: 15px; padding-left: 5px;">{$object->task}</span>
	</p>
	
	<div class="grid_8">
		<div class="box" style="padding: 10px;">
		
			<table>
				<tr>
					<td style="width: 110px;">{t}Contact{/t}</td>
					<td><a href="/contact/details/{$object->contact_id_key}/{$object->contact_id}/">{$object->contact_name}</a></td>
				</tr>		
				<tr>
					<td style="width: 110px;">{t}Start date{/t}</td>
					<td>{$object->start_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
				<tr>
					<td style="width: 110px;">{t}Due date{/t}</td>
					<td>{$object->due_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
			</table>
				
			<table style="margin-top: 10px;">	
				<tr>
					<td style="width: 110px;">{t}Creation date{/t}</td>
					<td>{$object->creation_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Created by{/t}</td>
					<td>{$object->creator}</td>
				</tr>			
	
				{if $object->update_date}
				<tr>
					<td style="width: 110px;">{t}Last update date{/t}</td>
					<td>{$object->update_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Last updated by{/t}</td>
					<td>{$object->editor}</td>
				</tr>			
				{/if}
				
				{if $object->complete_date}
				<tr>
					<td style="width: 110px;">{t}Closed on{/t}</td>
					<td>{$object->complete_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Closed by{/t}</td>
					<td>{$object->completionist}</td>
				</tr>						
				{/if}
			</table>
		</div>
	</div>
	
	<div class="prefix_1 grid_8">
		<div class="box" style="padding: 10px;">
	
			<p style="font-weight: bold;">{t}Details{/t}</p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$object->details|default:'---'}</p>
	
			<p style="margin-top: 20px; font-weight: bold; line-height: 18px; white-space: pre-wrap;">{t}Complete message{/t}</p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$object->endnote|default:'---'}</p>
	
		</div>
		
		<div class="box" style="padding: 10px; margin-top: 10px;">
		{if isset($appointments)}
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
		{/if}
		</div>
	</div>
	<div style="clear: both;"></div>
</div>

{*
<div style="clear: both;"></div>
{foreach $object->_fields as $attribute => $specifics} 	
 	<dl>
	{if isset($object->$attribute)}
		 
		<dt>{$attribute}</dt> 
		<dd>{$object->$attribute}</dd> {/if}
	</dl>
{/foreach}
*} 