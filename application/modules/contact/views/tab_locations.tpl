{foreach $contact_locs as $key => $loc}		
	{if isset($loc->aliases)} {$aliases = $loc->aliases} {/if}
	
	{assign var=k value={counter}}
	
	<div id="loc_{$loc->locId}" style="margin-bottom: 30px;">
		<h3 style="margin-left: -15px;">{t}{$loc->locDescription}{/t}</h3>
		<div style="padding: 5px;">

			{if $loc->locDescription|lower != 'home' && $loc->locDescription|lower != 'registered address'}
				<a class="button" href="#" onClick="jqueryForm({ 'form_type':'form','object_name':'location','object_id':'{$loc->locId}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Edit{/t}</a>
			{else}
				{* send back to the info tab *}
				<a class="button" href="/contact/form/{$contact_id_key}/{$contact_id}">{t}Edit{/t}</a>
			{/if}
			{if $loc->locDescription|lower != 'home' && $loc->locDescription|lower != 'registered address'}				
				<a class="button" href="#" onClick="jqueryDelete({ 'procedure':'deleteLocation','object_name':'location','object_id':'{$loc->locId}','hash':'set_here_the_hash' })">{t}Delete{/t}</a>
			{/if}
			
			{if $loc->locLatitude}
				<a class="button" href="#{$loc->locLatitude}_{$loc->locLongitude}_{$description}" id="mapbutton_{$k}">{t}Display map{/t}</a>
				<a class="button" href="http://maps.google.com/maps?q={$loc->locLatitude},+{$loc->locLongitude}+({$description})&amp;hl=en&amp;ie=UTF8&amp;t=h&amp;vpsrc=6&amp;ll={$loc->locLatitude},{$loc->locLongitude}&amp;spn=0.020352,0.025835&amp;z=14&amp;iwloc=A&amp;source=embed" target="_blank">{t}Larger Map{/t}</a>
			{/if}
		</div>
										
		<table style="width: 540px; float: left;">
			{foreach $loc->show_fields as $key => $property_name}
				{if $loc->$property_name != ""}
				<tr>
					<td class="field" style="width: 30%;">
						{if isset($aliases) && isset($aliases.$property_name)}
							{t}{$loc->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
						{else}
							{t}{$property_name}{/t}
						{/if}
					</td>
					<td class="value"> 
						{if $property_name == "locDescription"}
							{t}{$loc->$property_name|wordwrap:75:" ":true}{/t}
						{else}
							{$loc->$property_name|wordwrap:75:" ":true}
						{/if}													
					</td>
				</tr>
				{/if}
			{/foreach}										
		</table>
			
		<div style="float: right;">
		{if $loc->locLatitude}
			{$desc = $loc->locDescription}
			{$description = "$contact_ref - $desc"}
			{assign hl 'en'}
			{if $language=='italian'}{assign hl 'it'}{/if}
			
			<div id="map_{$k}" class="box gmap" style="display: none;"></div>

		{else}
				<img class="box" src="/layout/images/empty_map.png" width="300px" height="300px"/>
		{/if}
		</div>
		
		<div style="clear: both;"></div>
		<p style="font-size: 11px; padding: 5px;">
			{t}ID{/t}: {$loc->locId}
			 
			{if isset($loc->entryCreatedBy)}
			| <i>{t}Created by{/t}</i>: {$loc->entryCreatedBy|default:'-'}
				{if isset($loc->entryCreationDate)} 
					{t}on {/t} {$loc->entryCreationDate|default:'-'}
				{/if}
			{/if}
			
			{if isset($loc->entryUpdatedBy)} 
			| <i>{t}Updated by{/t}</i>: {$loc->entryUpdatedBy|default:'-'}
				{if isset($loc->entryUpdateDate)} 
					{t}on {/t} {$loc->entryUpdateDate|default:'-'}
				{/if}
			{/if}
		</p>		
		
	</div>						
{/foreach}

