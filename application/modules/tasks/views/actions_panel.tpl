{jdecode object=$task_json}

<div class="box_header" style="margin-left: -5px; padding-left: 10px;"><h4>{t}Actions panel{/t}</h4></div>

{* main action panel *}
{include "{$fcpath}application/views/actions_panel.tpl"}

{* task action panel *}

<ul class="ap" >

	{* Go back to customer profile *}
	{if isset($object->contact_id_key) && $object->contact_id_key}
	
		{assign var=url value="/contact/details/{$object->contact_id_key}/{$object->contact_id}/#tab_Tasks"}
		
		<li class="ap">{anchor("{$url}","{t}Back to profile{/t}",'class="button"')}</li>		
		
	{/if}
	
	{* later on
	{if !{preg_match pattern="\/tasks\/details" subject=$site_url}}
		<li class="ap"><a class="button" href="#">{t}Show Calendar View{/t}</a></li>
	{/if}
	*}
	
	{if $buttons}
		{foreach $buttons as $key => $button}
			<li class="ap" id="li_{$button.id}"><a class="button" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
		{/foreach}
	{/if}
					
</ul>

<ul class="ap" >
	{if !empty($task->task_id)}
	<li class="ap"><a id="btn_create_activity" href="/activities/form/task_id/{$task->task_id}">{t}Add Activity{/t}</a></li>		
	<li class="ap"><a id="btn_create_mti" href="/tasks/create_invoice/task_id/{$task->task_id}">{t}Create Invoice{/t}</a></li>
	{/if}
</ul>
{* {/if} *}

{if {preg_match pattern="\/calendar" subject=$site_url}}
	{if isset($base_url)}
		<div class="box" style="clear:right; float:right; display:inline; width: 235px;">
			<h4 style="margin-left: -25px;">{t}Calendar Legend{/t}</h4>
			<div style="" ><img src="{$base_url}assets/style/img/red.png" style="margin-top: 3px;"/> {t}overdue{/t}</div>
			<div style="" ><img src="{$base_url}assets/style/img/blue.png" style="margin-top: 3px;"/> {t}open{/t}</div>
			<div style="" ><img src="{$base_url}assets/style/img/green.png" style="margin-top: 3px;"/> {t}quotes{/t}</div> 
		</div>
	{/if}
{/if}


