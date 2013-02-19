{* <pre>{$object->team|print_r}</pre> *}


{jdecode team=$object->team}

{if $team && ($team|count)>0}

	{foreach $team as $key => $colleague}
	
		{* marks assets already associated to the task *}
		{assign var="check" value=""}

		{if isset($object->involved)}

			{foreach $object->involved as $key => $colleague_uid}
				{if $colleague->uid == $colleague_uid}
					{assign var="check" value='checked="checked"'}
				{/if}
			{/foreach}
			
		{/if}	
	
		<p class="zero">
			<input style="line-height: 12px; vertical-align: middle;" type="checkbox" id="colleague_{$colleague->uid}" value="{$colleague->uid}" name="colleagues" {$check} />
			{$colleague->name}
		</p>
	{/foreach}
{else}
	{t}No colleague has been defined{/t}
{/if}



