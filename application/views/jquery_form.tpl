{if $object}
	{$max_length = 255}	
	{$max_size = 45}
	
	{* <pre>{$object->assets|print_r}</pre> *}
	{* <pre>{$object|print_r}</pre> *}
	
	<div class="grid_10">
	
		{* TODO change to default:'GET' after refactoring *}
		<form class="jquery_form" id="{$form_name|default:'my_jquery_form'}" name="{$form_name|default:'my_jquery_form'}" method="{$form_method|default:'GET'}" action="{$url|default:''}">
	
		{* if the for has only hidden fields I do not show the div *}
		{assign var="has_visible_fields" value=false}
		
 		{foreach $object->_fields as $attribute => $specifics}
 			{if isset($object->$attribute)}
 			
 				{if $specifics->form_type != 'hidden'}
	 		
	 				 {assign var="has_visible_fields" value=true}
	 		
	 			{/if}	
	 		
 			{/if}
 		{/foreach}
 		
 		
 		
		{if $has_visible_fields}
			<div class="box" style="padding: 10px; margin-top: 5px; margin-bottom: 5px; background-color: #f3f3f3">
		{/if}

		 	<dl>
				{include file='jquery_form_core.tpl'}
		 	</dl>
		 	
		{if $has_visible_fields}
			</div>
		{/if}
			
			
			
			
			
			
			
	 	{if isset($object->contact_assets)}
	 	
	 		{if $has_visible_fields}
	 			<h4 style="margin-top: 15px;">{t}Assets{/t}</h4>
	 		{/if}
	 		
	 		<div class="box" style="padding: 10px; margin-top: 5px; margin-bottom: 5px; background-color: #f3f3f3">
	 			{include file="jquery_contact_assets.tpl"}
	 		</div>
	 	{/if} 	
	 	
	 	
	 	
	 	
	 	
	 	{if isset($object->contact_locs)}
	 		
	 		{if $has_visible_fields}
	 			<h4 style="margin-top: 15px;">{t}Locations{/t}</h4>
	 		{/if}
	 		
	 		<div class="box" style="padding: 10px; margin-top: 5px; margin-bottom: 5px; background-color: #f3f3f3">
				{include file="jquery_contact_locs.tpl"}
	 		</div>
	 	{/if}
	 	 	
	 	 	
	 	 	
	 	 	
	 	 	
	 	{if isset($object->team)}
	 		
	 		{if $has_visible_fields}
	 			<h4 style="margin-top: 15px;">{t}Involve{/t}</h4>
	 		{/if}
	 		
	 		<div class="box" style="padding: 10px; margin-top: 5px; margin-bottom: 5px; background-color: #f3f3f3">
	 			{include file="jquery_team.tpl"}
	 		</div>
	 	{/if}
	 	
	 	
	 	</form>
 	
 		{*<pre>{$object|print_r}</pre>*}
 			
 	</div>
{/if}