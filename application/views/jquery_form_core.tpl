
 	{foreach $object->_fields as $attribute => $specifics}
 		
 		{* <pre>{$specifics|print_r}</pre> *}
 		
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
	 
	 			<dt style="min-width: 20%; max-width: 25%; clear:left; float: left; padding-top: 10px;">
	 				{if $specifics->alias == ''}
						{t}{$attribute|ucwords}{/t}
					{else}
						{t}{$specifics->alias|ucwords}{/t}
					{/if}
					{if $specifics->mandatory}<span class="dark_red"> *</span>{/if}
	 			</dt>
	 			
	 			
	 			<dd style="float: left; clear: right;">
	 			
	 				{if isset($specifics->default)}
	 					{assign var="def" value="{$specifics->default}"}
	 				{else}
	 					{assign var="def" value=""}
	 				{/if}
	 				
	 				{assign var="style" value=""}
	 			
	 				{if $specifics->mandatory} {assign var="style" value="border-color: orange;"}{/if}
	 				
	 				{if $specifics->form_type=='textarea'}
	 					<textarea {$class} style="{$style}" id="{$attribute}" name="{$attribute}" cols="43" rows="7">{$object->$attribute}</textarea>
	 				{/if}
	 				
					{if  $specifics->form_type=='checkbox'}
						{$checked=''}
						{if $object->$attribute}{$checked='checked'}{/if}
	 					<input {$class} style="{$style}" type="{$specifics->form_type}" id="{$attribute}" name="{$attribute}" value="1" {$checked} />
	 				{/if}
	 					 				
	 				{if  $specifics->form_type=='text'}
	 					<input {$class} style="{$style}" type="{$specifics->form_type}" id="{$attribute}" name="{$attribute}" value="{$object->$attribute|default:$def}"  maxlength="{$specifics->max_length}" size="{$size}" />
	 				{/if}
	 			</dd>
	 			
		 		<div style="clear: both;"></div>
		 		
		 	{/if}
		 	
	 	{/if}
	 	
 	{/foreach}
