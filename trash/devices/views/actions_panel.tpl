{jdecode object=$product}

<h4>{t}Actions panel{/t}</h4>

{* main action panel *}
{include "{$fcpath}application/views/actions_panel.tpl"}

{* devices action panel *}

<ul class="ap" >

	{if $buttons}
		{foreach $buttons as $key => $button}
			<li class="ap" id="li_{$button.id}"><a class="button" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t}{$button.label}{/t}</a></li>
		{/foreach}
	{/if}
					
</ul>



