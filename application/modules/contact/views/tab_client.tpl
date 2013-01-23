
<div class="grid_10 box" style="margin-left: 10px; margin-top: 10px;">
	
	<div class="box_header"><h4>{t}Data{/t}</h4></div>
		
	<table class="contact_profile" style="width: 95%; margin: 10px;">
		{$count = 0}
		{foreach $contact->show_fields as $key => $property_name}
		
			
			{if $contact->$property_name != ""}
			
				{* skip unwilled fields *}
				{if $property_name == "jpegPhoto"} {continue} {/if}
				{if $property_name == "description"} {continue} {/if}
				{if $property_name == "userPassword"} {continue} {/if}
				
				<tr>
					<td class="field">
					
					{if isset($aliases) && isset($aliases.$property_name)}
						{assign 'property_alias' $contact->aliases.$property_name|regex_replace:"/_/":" "}
						{t}{$property_alias|trim}{/t} :
					{else}
						{t}{$property_name}{/t} :
					{/if}
					</td>
	
					
					<td class="value">					
						{$already_wrote=0}
						{* particular cases *}
						
						{* boolean fields *}
						{if $contact->properties[$property_name]['boolean'] == 1}
							{if $contact->$property_name=='TRUE'}
								{t}yes{/t}
							{else}
								{t}no{/t}
							{/if}
							{$already_wrote=1}
						{/if}
						
						{if $property_name == "mail" or $property_name == "omail"}
							<a href="mailto:{$contact->$property_name}">{$contact->$property_name|truncate:45:"[..]":true}</a>
							{$already_wrote=1}
						{/if}	
	
						{if $property_name == 'oURL' || preg_match('/.*(?:URI)$/', $property_name)}
							<a href="{$contact->$property_name}" target="_blank">{$contact->$property_name|truncate:35:"[..]":true}</a>
							{$already_wrote=1}
						{/if}
	
						{if $property_name=="note"}
							<p style="line-height: 18px; white-space: pre-wrap; max-width: 320px; padding-top: 5px; padding-bottom: 5px;">{$contact->$property_name}</p>
							{$already_wrote=1}
						{/if}				
						
						{if $property_name=="jpegPhoto"}
							{* skip the item. I take care of the photo later *}
							{$already_wrote=1}
						{/if}				
	
						{if $property_name=="managerName" && $contact->$property_name != ""}
							<a href="/contact/search/{$contact->$property_name}">{$contact->$property_name|truncate:45:"[..]":true}</a>
							{$already_wrote=1}
						{/if}
																
						{if $property_name=="assistantName" && $contact->$property_name != ""}
							<a href="/contact/search/{$contact->$property_name}">{$contact->$property_name|truncate:45:"[..]":true}</a>
							{$already_wrote=1}
						{/if}	
						
						{* /particular cases *}
								
						{if $already_wrote==0} 
							{$contact->$property_name|truncate:45:"[..]":true}
						{/if}
					</td>						
				</tr>
				{$count = $count + 1}
			{/if}
		{/foreach}
	</table>	
</div>

<div class="grid_8 box" id="profile_summary" style="margin: 10px;">
	
	<div class="box_header"><h4>{t}Summary{/t}</h4></div>	
	
	{if isset($contact->uid)}
				
		{if $contact->jpegPhoto}
			{$src="data:image/jpeg;base64,{$contact->jpegPhoto}"}
		{else}
			{$src="/layout/images/no-face-100.png"}
		{/if}
		
	{else}
		{$src="/layout/images/no-org-100.png"}
	{/if}
	
	<div style="margin-left: 5px; margin-top: 5px;">
		<p>
			<img alt="jpegPhoto" class="box" style="float: right; width: 100px; height: 100px; margin-bottom: 10px; margin-left: 5px;" src="{$src}">
			<h6>{t}Description{/t}:</h6>
			{if $contact->description !=""}
				<p style="line-height: 18px; white-space: pre-wrap;">{$contact->description}</p>
			{else}
				{t}If you add a description for this contact then it will be displayed here{/t}
			{/if}	
		</p>	
	</div>
	
	{* if module invoices is enabled
	<h6>{t}Purchases{/t}:</h6>
	<table class="contact_profile" style="width: 345px;">
		<tr>
			<td class="field">{t}Purchased{/t} :</td>
			<td style="text-align: right;">84397,00 $</td>
		</tr>
		<tr>
			<td class="field">{t}Purchased current year{/t} :</td>
			<td style="text-align: right;">4397,00 $</td>
		</tr>
		<tr>
			<td class="field">{t}Pending{/t} :</td>
			<td style="text-align: right;">3397,00 $</td>
		</tr>
	</table>
	*}
									
</div>	

<div style="clear: both;"></div>	

<p style="font-size: 11px; padding: 5px; margin-top: 10px; margin-left: 5px;">
{t}ID{/t}: {$contact_id}
 
{if isset($contact->entryCreatedBy)}
| <i>{t}Created by{/t}</i>: {$contact->entryCreatedBy|default:'-'}
	{if isset($contact->entryCreationDate)} 
		{t}on {/t} {$contact->entryCreationDate|default:'-'}
	{/if}
{/if}

{if isset($contact->entryUpdatedBy)} 
| <i>{t}Updated by{/t}</i>: {$contact->entryUpdatedBy|default:'-'}
	{if isset($contact->entryUpdateDate)} 
		{t}on {/t} {$contact->entryUpdateDate|default:'-'}
	{/if}
{/if}
</p>	