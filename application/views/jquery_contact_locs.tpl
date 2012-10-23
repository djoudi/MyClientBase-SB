{* <pre>{$object->team|print_r}</pre> *}


{jdecode locs=$object->contact_locs}

{* <pre>{$locs|print_r}</pre> *} 
{if $locs}

	{foreach $locs as $key => $loc}
		<p class="zero">
			<input type="radio" id="{$loc->label}_{$key}" value="{$loc->address}" name="contact_locs" /> {$loc->label} - {$loc->address}
		</p>
	{/foreach}

{/if}



