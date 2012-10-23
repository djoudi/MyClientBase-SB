{foreach $members as $key => $member}
	
	{if isset($member->aliases)} {$aliases = $member->aliases} {/if}
	<div class="box" style="padding: 5px; margin: 10px;">

		{if $member->jpegPhoto}
			{$src="data:image/jpeg;base64,{$member->jpegPhoto}"}
		{else}
			{$src="/layout/images/no-face-100.png"}
		{/if}
		<img alt="jpegPhoto" class="box" style="float: left; width: 100px; height: 100px; margin: 5px; margin-left: 5px; margin-right: 25px;" src="{$src}">
		
		<div class="zero" style="margin-left: 20px;">
			<h4>				
				<a href="/contact/details/uid/{$member->uid}">
					{if $member->enabled == "FALSE"}
						<strike>{$member->sn} {$member->givenName}</strike>
					{else}
						{$member->sn} {$member->givenName}
					{/if}
				</a>
				{if $member->oAdminRDN == $contact->oid}
				<img src="/layout/images/gold_star_20.jpg" style="width: 20px; margin-left: 10px;" />
				<span style="font-size: 12px; margin-left: 3px;">({t}manager{/t})</span>
				{/if}						
			</h4>					
				
		
			<p style="margin-top: 15px;">
			{foreach name="members" from=$member_fields key=key  item=property_name}
			
			{if $member->$property_name != ""}
				{if in_array($property_name, $member->show_fields)}
					<b style="padding-left: 15px; padding-right: 2px; padding-top: 2px; padding-bottom: 2px;">														
					{if isset($aliases) && isset($aliases.$property_name)}
						{t}{$member->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
					{else}
						{t}{$property_name}{/t}
					{/if}
					:</b>
	
					{$already_wrote=0}
					<!-- particular cases -->
					{if $property_name=="mail"}
						<a href="mailto:{$member->$property_name}">{$member->$property_name|wordwrap:60:"<br/>":true}</a>
						{$already_wrote=1}
					{/if}								
					
					{* default case *}
					{if $already_wrote==0}
						{$member->$property_name|wordwrap:75:" ":true}
					{/if}
	
					{if $smarty.foreach.members.iteration % 3 == 0}</p><p>{/if}
				{/if}
			{/if}																			
			{/foreach}
			</p>
		</div>
		
		<div style="clear: both;"></div>
	</div>
{/foreach}
