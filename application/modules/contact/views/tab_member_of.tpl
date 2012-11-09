{if $contact_orgs}
	{foreach $contact_orgs as $key => $org}
		{if isset($org->aliases)} {$aliases = $org->aliases} {/if}	
		<div id="member_of_{$org->oid}" style="margin-bottom: 30px;">	
			<h3>
				<a href="/contact/details/oid/{$org->oid}">{$org->o}</a>
				{if $org->enabled == 'FALSE'}<span class="dark_red" style="font-size: 15px; padding-left: 10px;">{t}This organization is disabled{/t}</span>{/if}

				{if {preg_match pattern=$org->oid subject=$contact->oAdminRDN}}
				<img src="/layout/images/gold_star_20.jpg" style="width: 20px; margin-left: 10px;" />
				<span style="font-size: 12px; margin-left: 3px;">({t}manager{/t})</span>
				{/if}
			</h3>
			<div style="padding: 10px; width: 300px;">
				{if {preg_match pattern=$org->oid subject=$contact->oAdminRDN}} 
					<a class="button" href="#" onClick="jqueryAssociate({ 'procedure':'personAdminOfOrganization','object_name':'organization','object_id':'{$org->oid}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Remove administration{/t}</a>
				{else}
					<a class="button" href="#" onClick="jqueryAssociate({ 'procedure':'personAdminOfOrganization','object_name':'organization','object_id':'{$org->oid}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Make administrator{/t}</a>
				{/if}
					<a class="button" href="#" onClick="jqueryDelete({ 'procedure':'deleteOrganizationMembership','object_name':'organization','object_id':'{$org->oid}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Delete Association{/t}</a>
			</div>
			
			<table>
				{foreach $org->show_fields as $index => $property_name}
					{if $org->$property_name != ""}
					{* style="background-color: {cycle values="#FFF,#e8e8e8"};" *}
					<tr valign="top">
						<td class="field" width="200px;">
							{if isset($aliases) && isset($aliases.$property_name)}
								{t}{$org->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
							{else}
								{t}{$property_name}{/t}
							{/if}
						</td>
						<td class="value">
							{$already_wrote=0}
							
							{* particular cases *}
							{if $property_name=="omail"}
								<a href="mailto:{$org->$property_name}">{$org->$property_name|truncate:70:"[..]":true}</a>
								{$already_wrote=1}
							{/if}
								
							{if $property_name=="oURL"}
								<a href="{$org->$property_name}" target="_blank">{$org->$property_name|truncate:100:"[..]":true}</a>
								{$already_wrote=1}
							{/if}
							{* /particular cases *}
								
							{* default case *}
							{if $already_wrote==0}
								{$org->$property_name|wordwrap:75:" ":true}
							{/if}
						</td>
					</tr>
					{/if}
				{/foreach}										
			</table>
		</div>
	{/foreach}
{/if}
