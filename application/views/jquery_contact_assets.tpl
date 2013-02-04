{* <pre>{$object->contact_assets|print_r}</pre> *}


{jdecode assets=$object->contact_assets}
 
{* <pre>{$assets|print_r}</pre> *}  
{if $assets}

	{foreach $assets as $key => $asset}
		<p class="zero">
			<input type="checkbox" id="asset_{$asset->id}" value="{$asset->id}" name="asset_{$asset->id}" />
			{$asset->type}: {$asset->brand} {$asset->model}
		</p>
	{/foreach}

{/if}



