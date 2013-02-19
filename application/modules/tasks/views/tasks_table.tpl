{setlocale locale=$locale}

<script type="text/javascript">
	$(document).ready(function() {

		shortcuts_top_menu();	

		$("[id^=a_task_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_task_'+item_num), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});

		$("[id^=a_appointment_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_appointment_'+item_num), { deltaDirection: 'left', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});		
		
		$("[id^=a_activity_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_activity_'+item_num), { deltaDirection: 'left', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});		

		$('#a_urgent').bubbletip($('#tip_urgent'), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
	});
</script>

{* The standard Google Loader script. *}
<script src="http://www.google.com/jsapi"></script>

{if $language == 'english'}
	{literal}
	<script type="text/javascript">
	google.load('picker', '1', {'language':'en'});
	</script>
	{/literal}
{/if}

{if $language == 'italian'}
	{literal}
	<script type="text/javascript">
	google.load('picker', '1', {'language':'it'});
	</script>
	{/literal}
{/if}

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
		
		<th class="files_products">{t}Files & Products{/t}</th>
    </tr>
    
    
    {* BODY *}
    
	{foreach $tasks as $task}
	
	{assign var='task_id' value=$task->id}
	
	<tr id="task_line_{$task_id}">
		{if isset($show_task_selector) && $show_task_selector}
			<td class="first"><input type="checkbox" class="task_id_check" name="task_id[]" value="{$task->id}"></td>
		{/if}
	
	
	
		{* URGENT *}
		<td class="question_mark">
		
			<a id="a_task_{$num}"><image src="/layout/images/info_mark.png" /></a>
			{if !is_null($task->complete_date)}
				<img style="margin-top: 5px;" src="/layout/images/locked.png" />
			{/if}
			
			{if $task->urgent}
				<img style="margin-top: 5px;" src="/layout/images/esclamation_mark.png" />
			{/if}
			
			{if isset($task->where) && $task->where != ""}
				<a href="https://maps.google.com/maps?q={$task->where|urlencode}" target="_blank"><img style="margin-top: 40px;" src="/layout/images/map.png" /></a>
			{/if}
		</td>
		<div id="tip_task_{$num}" style="display: none;">
			<p style="font-size: 11px;">{t}Creation date{/t}: {$task->creation_date|date_format:"%a %Y-%m-%d  %H:%m"|default:'--'}</p>
			<p style="font-size: 11px;">{t}Created by{/t}: <a href="/contact/details/uid/{$task->created_by}">{$task->creator}</a></p>
			
			
			{if $task->update_date}
				<p style="font-size: 11px; margin-top: 10px;">{t}Last update{/t}: {$task->update_date|date_format:"%a %Y-%m-%d %H:%m"}</p>
			{/if}
			
			{if $task->editor}
				<p style="font-size: 11px;">{t}Updated by{/t}: {$task->editor}</p>
			{/if}

			
			{if $task->complete_date}
				<p style="font-size: 11px; margin-top: 10px;">{t}Closed on{/t}: {$task->complete_date|date_format:"%a %Y-%m-%d %H:%m"}</p>
			{/if}
			
			{if $task->completionist}
				<p style="font-size: 11px;">{t}Closed by{/t}: {$task->completionist}</p>
			{/if}			
			
			
			<hr style="margin: 10px;"/>
			<p><b>{t}Task{/t}</b></p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task->task|default:'--'}</p>
			
			<hr style="margin: 10px;"/>
			<p><b>{t}Details{/t}</b></p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task->details|default:'--'}</p>
			
			<hr style="margin: 10px;"/>
			<p><b>{t}Complete message{/t}</b></p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$task->endnote|default:'--'}</p>			
		</div>

		
		
		
		<td class="description">
		
			{$style=''}
			
			{assign var=today value=$smarty.now|date_format:"%Y-%m-%d"}
			{if $today == $task->due_date|date_format:"%Y-%m-%d" && !$task->complete_date}{$style="color: #ff9c00;"}{/if}
			
			{if $task->complete_date}{$style="color: #acacac; font-style: italic;"}{/if}
	
			<p class="zero" style="padding-bottom: 10px; font-weight: bold;">#{$task->id}</p>
			
			<p class="zero" style="min-height: 45px; padding-bottom: 10px; {$style}">
				{$task->task|truncate:80:" [..]":true}
			</p>	
			
			{if isset($task->where)}
			<p class="zero" style="padding-top: 5px; padding-bottom: 10px; font-size: 11px;">
				<a href="https://maps.google.com/maps?q={$task->where|urlencode}" target="_blank">{$task->where|truncate:33:" [..]":true}</a>
			</p>
			{/if}
			
			{if isset($task->assets) && ($task->assets|count)>0}
				<hr style="margin-top: 5px;" />
				<ul class="task_assets_list">
					
					{foreach $task->assets as $asset}
					
						{if $asset.category == "home_appliance" || $asset.category == "digital_device"}
						
							{assign var="string" value="{$asset.type} - {$asset.brand} {$asset.model}"}
							
						{else}
							
							{assign var="string" value="{$asset.type} - {$asset.description}"}
								
						{/if}
						
						{assign var="string" value=$string|truncate:30:" [..]":true}
						<li>{anchor("assets/details/id/{$asset.id}","{$string}")}</li>
						
					{/foreach}
					
				</ul>
				<hr/>
			{/if}

			
			{if (isset($task->budget) && $task->budget != "0.00") || (isset($task->hours_budget) && $task->hours_budget != "0.00") }
			
				<p style="margin-top: 10px; margin-left: -3px; font-size: 11px; font-weight: bold;">{t}Budget{/t}</p>
				<table class="task_summary">
					{if isset($task->budget) && $task->budget != "0.00"}
						<tr>
							<td>{t}Total Budget{/t}</td>
							<td style="text-align: right;">{$currency_symbol} {$task->budget}</td>
						</tr>
					{/if}
				
					{if isset($task->hours_budget) && $task->hours_budget!="0.00"}
						<tr>
							<td>{t}Hours Budget{/t}</td>
							<td style="text-align: right;">{$task->hours_budget}</td>
						</tr>
					{/if}
				</table>
				
			{/if}
						
			{if isset($task->summary)}

				<p style="margin-top: 5px; margin-left: -3px; font-size: 11px; font-weight: bold;">{t}Summary{/t}</p>
				<table class="task_summary">
				{foreach $task->summary as $label => $value}
					<tr>
						<td>{t}{$label|replace:'_':' '|capitalize:true:true}{/t}</td>
						<td style="text-align: right;">{$value}</td>
					</tr>
				{/foreach}
				</table>
				
			{/if}
		</td>
		
		{$num={counter}}
		
					
		
		
		
		
		
		{* People involved *}
		<td class="involved">

			

			{* this is shown only in the "all tasks" view *}
			{if {preg_match pattern="\/tasks$" subject=$site_url} && isset($task->contact_name)}
			<ul class="zero" style="list-style: none;">
				<li class="zero" style="margin-bottom: 10px;">			
					<a class="zero" href="/contact/details/{$task->contact_id_key}/{$task->contact_id}#tab_tasks">{$task->contact_name|truncate:23:"[..]":true}</a>
				</li>
			</ul>
			{/if}
			
			
			<ul class="involved_list">
				{if $task->involved}

					{foreach $task->involved as $key => $colleague}
						<li>
							{anchor("/contact/details/uid/{$colleague.colleague_id}",$colleague.colleague_name|truncate:23:"[..]":true)}</a>
						</li>
					{/foreach}
					
				{else}
				
					<p class="zero" style="font-size: 10px; font-style: italic;">{t}No colleague involved yet{/t}</p>
				
				{/if}
				
			</ul>			

		</td>		
				
				
				
		
		{* time line *}
		<td class="time_line">
	
			<div style="background-color: transparent; margin-top: 10px; margin-left: 5px;">
				
				{include file="task_timeline.tpl"}
				
			</div>		
		</td>
		
		{* goods and files *}
		
		<td class="files_products">
			
			<div style="background-color: transparent; margin-top: 10px; margin-left: 5px;">
			
			<p style="margin-top: 5px; font-size: 11px; font-weight: bold;">{t}Files{/t}</p>
			<hr/>
			{if isset($task->files) && ($task->files|count)>0}
			
				<ul  class="task_files_list" {if ($task->files|count) <= 4} style="overflow-y: hidden;" {/if}>
				
				{foreach $task->files as $key => $gfile}
					
					<li>
						<img src="{$gfile.google_icon_url}" />
						
						<a href="{$gfile.google_url}" target="_blank">{$gfile.google_name|truncate:35:"[..]":true }</a>
						
						<a class="button" style="line-height: 12px; font-size: 11px; margin-top: 2px; margin-right: 3px; float: right;" href="#" onClick='$(this).live("click",detach_file({$gfile.id}))'>{t}Detach{/t}</a>
					</li>
				{/foreach}
				</ul>
				
			{else}
	
				<p class="zero" style="margin-left: 20px; padding-top: 20px; padding-bottom: 20px; font-size: 10px; font-style: italic;">
					{t}No files found for this task{/t}
				</p>
		
			{/if}
			<hr/>
			
			<p style="margin-top: 15px; font-size: 11px; font-weight: bold;">{t}Products{/t}</p>
			<hr/>
				<p class="zero" style="margin-left: 20px; padding-top: 20px; padding-bottom: 20px; font-size: 10px; font-style: italic;">
					{t}No products found for this task{/t}
				</p>
			
			<hr/>
			</div>
		</td>				
	</tr>
	
	
	
	
	
	
	
	<tr class="task_button" id="task_button_line_{$task_id}">
		
		<td class="task_buttons description" colspan="2" style="vertical-align: middle;">
			<p class="zero" style="vertical-align: bottom;">
				
				<a class="button" style="font-size: 11px;" href="/tasks/details/id/{$task->id}">{t}Details{/t}</a>
				{if isset($task->edit_button) && is_null($task->complete_date)}
					{assign var=button value=$task->edit_button}
				
					<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
				{/if}	

				{if isset($task->close_button) && is_null($task->complete_date)}
					{assign var=button value=$task->close_button}
				
					<a class="button" style="font-size: 11px; margin-right: 3px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
				{/if}					
			</p>
		</td>	
		
		<td class="task_buttons involved" style="vertical-align: middle;">
			
			{if $task->involve_button  && is_null($task->complete_date)}
				<ul class="zero" style="list-style: none;">
				
					{assign var=button value=$task->involve_button}
					<a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
					
			{/if}		
		</td>	
		
		<td class="task_buttons time_line" style="vertical-align: middle;">
		
			{if $task->create_appointment_button  && is_null($task->complete_date)}
					
					{assign var=button value=$task->create_appointment_button}
					<a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
					
					{assign var=button value=$task->create_activity_button}
					<a class="button" style="font-size: 11px;" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a>
					
			{/if}	
							
		</td>	
		
		<td class="task_buttons files_products">
			{if $task->show_add_file_button}
				<a class="button" style="font-size: 11px;" href="#"  onClick='$(this).live("click",createPicker({$task->id}))'>{t}Attach file{/t}</a>
			{/if}
		</td>
	</tr>
		
	{/foreach}
	
</table>
{/if}