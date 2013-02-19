{* <pre>{$object->contact_assets|print_r}</pre> *}

{jdecode assets=$object->contact_assets}



{if $assets}

	{foreach $assets as $key => $asset}
	
		{* marks assets already associated to the task *}
		{assign var="check" value=""}

		{if isset($object->assets)}

			{foreach $object->assets as $key => $asset_id}
				{if $asset->id == $asset_id}
					{assign var="check" value='checked="checked"'}
				{/if}
			{/foreach}
			
		{/if}
				
		<p class="zero">
			<input style="line-height: 12px; vertical-align: middle;" type="checkbox" id="asset_{$asset->id}" value="{$asset->id}" name="asset_{$asset->id}" {$check}/>
			{$asset->type}: {$asset->brand} {$asset->model}
		</p>
	{/foreach}

{/if}



