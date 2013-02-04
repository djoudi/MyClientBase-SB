{jdecode object=$asset}

<div class="box_header" style="margin-left: -5px;"><h4>{t}Actions panel{/t}</h4></div>

{* main action panel *}
{include "{$fcpath}application/views/actions_panel.tpl"}

{* assets action panel *}

<ul class="ap" >

	{* Go back to customer profile *}
	{if isset($object->contact_id_key) && $object->contact_id_key}
	
		{assign var=url value="/contact/details/{$object->contact_id_key}/{$object->contact_id}/#tab_Assets"}
		
		<li class="ap">{anchor("{$url}","{t}Back to profile{/t}",'class="button"')}</li>		
		
	{/if}

	{if $buttons}
		{foreach $buttons as $key => $button}
			<li class="ap" id="li_{$button.id}"><a class="button" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
		{/foreach}
	{/if}
		
</ul>