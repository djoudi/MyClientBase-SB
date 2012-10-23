{jdecode object=$product}

<h4>{t}Actions panel{/t}</h4>

{* main action panel *}
{include "{$fcpath}application/views/actions_panel.tpl"}

{* product action panel *}

<ul class="ap" >

	{* Go back to customer profile *}
	{*
	{if isset($object->contact_id_key) && $object->contact_id_key}
	
		{assign var=url value="/contact/details/{$object->contact_id_key}/{$object->contact_id}/#tab_products"}
		
		<li class="ap"><a class="button" href="{$url}">{t}Back to profile{/t}</a></li>		
		
	{/if}
	*}
	
	{* later on
	{if !{preg_match pattern="\/products\/details" subject=$site_url}}
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
	{if !empty($product->product_id)}
	<li class="ap"><a id="btn_create_activity" href="/activities/form/product_id/{$product->product_id}">{t}Add Activity{/t}</a></li>		
	<li class="ap"><a id="btn_create_mti" href="/products/create_invoice/product_id/{$product->product_id}">{t}Create Invoice{/t}</a></li>
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


