{if $object}
	{$max_length = 255}	
	{$max_size = 45}
	{* <pre>{$object|print_r}</pre> *}
 	
 	{* remove this once I can translate js messages: then use dialog title instead of this line *}
	{if isset($form_title)}<h4>{t}{$form_title|ucwords}{/t}</h4>{/if}
	
	<div class="grid_8">

	{* TODO change to default:'GET' after refactoring *}
 	<form class="jquery_form" id="{$form_name|default:'my_jquery_form'}" name="{$form_name|default:'my_jquery_form'}" method="{$form_method|default:'GET'}" action="{$url}">
 	<dl>
 	{foreach $object->_fields as $attribute => $specifics}
 		
 		{if $attribute == 'team'} {continue} {/if}
 		
 		{if isset($object->$attribute)}
 		
	 		{if $specifics->size > $max_size}
				{$size=$max_size}
			{else}
				{$size=$specifics->size}
			{/if}
			
	 		{if $specifics->css_class != ''}
	 			{assign var=class value='class="'|cat:$specifics->css_class|cat:'"'}
	 		{else}
	 			{assign var=class value=''}
	 		{/if}
	 		
	 		{if $specifics->form_type == 'hidden'}
	 			<input type="hidden" name="{$attribute}" value="{$object->$attribute}" />
	 		{else}
		 		
	 			<dt style="width: 30%;">
	 				{if $specifics->alias == ''}
						{t}{$attribute|ucwords}{/t}
					{else}
						{t}{$specifics->alias|ucwords}{/t}
					{/if}
					{if $specifics->mandatory}<span class="dark_red"> *</span>{/if}
	 			</dt>
	 			<dd>
	 				{if $specifics->form_type=='textarea'}
	 					<textarea {$class} id="{$attribute}" name="{$attribute}" cols="43" rows="7">{$object->$attribute}</textarea>
	 				{/if}
	 				
					{if  $specifics->form_type=='checkbox'}
						{$checked=''}
						{if $object->$attribute}{$checked='checked'}{/if}
	 					<input {$class} type="{$specifics->form_type}" id="{$attribute}" name="{$attribute}" value="1" {$checked} />
	 				{/if}
	 					 				
	 				{if  $specifics->form_type=='text'}
	 					<input {$class} type="{$specifics->form_type}" id="{$attribute}" name="{$attribute}" value="{$object->$attribute}"  maxlength="{$specifics->max_length}" size="{$size}" />
	 				{/if}
	 			</dd>
		 		
		 	{/if}
		 	
	 	{/if}
	 	
 	{/foreach}
 	</dl>

 	{if isset($object->contact_assets)}
 		<h4>{t}Assets{/t}</h4>
 		{include file="jquery_contact_assets.tpl"}
 		<div style="height: 10px;"></div>
 	{/if} 	
 	
 	{if isset($object->contact_locs)}
 		<h4>{t}Locations{/t}</h4>
 		{include file="jquery_contact_locs.tpl"}
 		<div style="height: 10px;"></div>
 	{/if}
 	 	
 	{if isset($object->team)}
 		<h4>{t}Involve{/t}</h4>
 		{include file="jquery_team.tpl"}
 	{/if}
 	</form>
 	
 {*<pre>{$object|print_r}</pre>*}	
 	</div>
{/if}