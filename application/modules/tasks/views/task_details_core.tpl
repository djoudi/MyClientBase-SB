
{assign var='task_id' value=$task->id}

<div id="box_task_details">
	
	<div class="grid_9 box">
		
		<div class="box_header"><h4>{t}Task specifics{/t}</h4></div>
		
		<div class="zero" style="padding: 10px;">
		
			<p style="font-weight: bold;">{t}Task{/t}</p>
			<hr style="margin-bottom: 5px;" />
			<p style="line-height: 20px; margin-bottom: 15px;">
			
				{if !is_null($task->complete_date)}
					<span style="padding-left: 5px;"><img src="/layout/images/locked.png" /></span>
				{/if}	
			
				{if $task->urgent}
				<span style="padding-left: 5px;"><img src="/layout/images/esclamation_mark.png" /></span>
				{/if}
				<span style="font-size: 15px; padding-left: 5px;">{$task->task}</span>
			</p>
			
			
			<p style="font-weight: bold; margin-top: 20px;">{t}Details{/t}</p>
			<hr style="margin-bottom: 5px;" />
			<p style="line-height: 18px; white-space: pre-wrap;">{$task->details|default:'---'}</p>
		
			{if isset($task->assets) && ($task->assets|count) > 0}
				
				<p style="font-weight: bold; margin-top: 20px;">{t}Assets involved{/t}</p>
				<hr style="margin-bottom: 5px;" />
				<ul class="task_assets_list" style="margin-left: 5px;">
				
				
					{foreach $task->assets as $asset} 
						
						{if $asset.category == "home_appliance" || $asset.category == "digital_device"}
						
							{assign var="string" value="{$asset.type} - {$asset.brand} {$asset.model} {$asset.serial}"}
							
						{else}
							
							{assign var="string" value="{$asset.type} - {$asset.description}"}
								
						{/if}
						
						{assign var="string" value=$string|truncate:60:" [..]":true}
						
						<li>{anchor("assets/details/id/{$asset.id}","{$string}")}</li>							
					{/foreach}
				</ul>
				
			{/if}	
					
			<table style="margin-top: 20px;">
				<tr>
					<td style="width: 110px;">{t}Contact{/t}</td>
					<td><a href="/contact/details/{$task->contact_id_key}/{$task->contact_id}/">{$task->contact_name}</a></td>
				</tr>
				
				{if isset($task->where)}
					<tr style="line-height: 18px;">
						<td style="width: 110px;">{t}Where{/t}</td>
						<td><a href="https://maps.google.com/maps?q={$task->where|urlencode}" target="_blank">{$task->where}</a></td>
					</tr>				
				{/if}		
				<tr>
					<td style="width: 110px;">{t}Start date{/t}</td>
					<td>{$task->start_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
				<tr>
					<td style="width: 110px;">{t}Due date{/t}</td>
					<td>{$task->due_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
			</table>
			
			{if isset($task->involved) && ($task->involved|count) > 0}
				<p style="font-weight: bold; margin-top: 20px;">{t}Colleagues involved{/t}</p>
				<hr style="margin-bottom: 5px;" />			
				
				<ul class="involved_list" style="margin-left: 5px;">
					{foreach $task->involved as $key => $colleague}
						<li>{anchor("contact/details/uid/{$colleague.colleague_id}",$colleague.colleague_name)}</li>
					{/foreach}
				</ul>
				
			{/if}
			
			{if isset($task->summary)}

				<p style="font-weight: bold; margin-top: 20px;">{t}Summary{/t}</p>
				<table>
				{foreach $task->summary as $label => $value}
					<tr>
						<td>{t}{$label|replace:'_':' '|capitalize:true:true}{/t}</td>
						<td style="text-align: right;">{$value}</td>
					</tr>
				{/foreach}
				</table>
			{/if}	
			
			<p style="margin-top: 20px; font-weight: bold; line-height: 18px; white-space: pre-wrap;">{t}Complete message{/t}</p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task->endnote|default:'---'}</p>
		
		
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
	
	<div class="grid_8 box" style="margin-left: 10px;">
		<div class="box_header"><h4>{t}Related information{/t}</h4></div>
		
		<div class="zero" style="padding: 10px;">
			
			<p style="font-weight: bold;">{t}Timeline{/t}</p>
			<hr style="margin-bottom: 5px;" />
			<div style="background-color: transparent; margin-left: 5px;">
				{include file="task_timeline.tpl"}
			</div>

			<p style="font-weight: bold; margin-top: 20px;">{t}Files{/t}</p>
			<hr style="margin-bottom: 5px;" />
			{if isset($task->files) && ($task->files|count)>0}
			
				<ul  class="task_files_list" {if ($task->files|count) <= 4} style="overflow-y: hidden;" {/if}>
				
				{foreach $task->files as $key => $gfile}
					
					<li>
						<img src="{$gfile.google_icon_url}" />
						
						<a href="{$gfile.google_url}" target="_blank">{$gfile.google_name|truncate:50:"[..]":true }</a>
						
						<a class="button" style="line-height: 12px; font-size: 11px; margin-top: 2px; margin-right: 3px; float: right;" href="#" onClick='$(this).live("click",detach_file({$gfile.id}))'>{t}Detach{/t}</a>
					</li>
				{/foreach}
				</ul>
				
			{else}
	
				<p class="zero" style="margin-left: 35px; padding-top: 10px; padding-bottom: 10px; font-size: 10px; font-style: italic;">
					{t}No files found for this task{/t}
				</p>
		
			{/if}			
			
			<p style="font-weight: bold; margin-top: 20px;">{t}Products{/t}</p>
			<hr style="margin-bottom: 5px;" />
			<p class="zero" style="margin-left: 35px; padding-top: 10px; padding-bottom: 10px; font-size: 10px; font-style: italic;">
				{t}No products found for this task{/t}
			</p>			
			
		</div>
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