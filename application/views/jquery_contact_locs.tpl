
{jdecode locs=$object->contact_locs}

{* <pre>{$locs|print_r}</pre> *}

{if $locs && ($locs|count)>0}

	{foreach $locs as $key => $loc}
		<p class="zero">
			{if isset($object->contact_locs_input_type) && $object->contact_locs_input_type == 'checkbox'}
				{assign var="check" value=""}
				
				<input style="line-height: 12px; vertical-align: middle;" type="checkbox" id="contact_locs_{$key}" value="{$loc->address}" name="contact_locs" {$check} /> {t}{$loc->label}{/t} - {$loc->address}
			
			{else}
			
				<input style="line-height: 12px; vertical-align: middle;" type="radio" id="contact_locs_{$key}" value="{$loc->city}:{$loc->address}" name="contact_locs" /> {t}{$loc->label}{/t} - {$loc->address}
				
			{/if}
			
		</p>
	{/foreach}

{else}

	{t}No location has been for the contact{/t}
	
{/if}



