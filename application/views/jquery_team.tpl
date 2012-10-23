{* <pre>{$object->team|print_r}</pre> *}


{jdecode team=$object->team}

{* <pre>{$team|print_r}</pre> *} 
{if $team}

	{foreach $team as $key => $colleague}
		<p class="zero">
			<input type="checkbox" id="colleague_{$colleague->uid}" value="{$colleague->uid}" name="colleagues" />
			{$colleague->name}
		</p>
	{/foreach}

{/if}



