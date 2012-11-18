{* CONTACT PROFILE TABS *}
<div style="float: right; line-height: 30px; border-left: 1px solid #ccc; background-color: #f3f3f3; font-size: 18px; padding: 2px; padding-right: 5px; text-align: left;">
	{$contact_ref|truncate:30:"[..]":true}
</div>		
<ul>
	<li><a href="#tab_client">{t}Info{/t}</a></li>

	{if $contact_locs}
	<li><a href="#tab_locations">{t}Locations{/t} ({$contact_locs|count})</a></li>
	{/if}
											
	{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass} and $members}
		<li><a href="#tab_members">{t}Members{/t} ({$members|count})</a></li>
	{/if}
	
	{if {preg_match pattern="dueviPerson" subject=$contact->objectClass} and {$contact_orgs|count} >0}
		{$already_wrote=1}<li><a href="#tab_member_of">{t}Member of{/t} ({$contact_orgs|count})</a></li>
	{/if}	
	
	{if isset($ss_contact_folder_num_items)}				
		<li>
			<a href="#tab_documents">{t}Documents{/t}
			{if $ss_contact_folder_num_items}
				({$ss_contact_folder_num_items})
			{/if}
			</a>
		</li>
	{/if}

	{if isset($tasks)}
		<li><a href="#tab_tasks">{t}Tasks{/t} ({$tasks|count})</a></li>
	{/if}
	
	{if isset($quotes_html) and {$quotes|count} > 0}
		<li><a href="#tab_quotes">{citranslate lang=$language text='quotes'} ({$quotes|count})</a></li>
	{/if}
						
	{if isset($invoices_html) and {$invoices|count} > 0}
		<li><a href="#tab_invoices">{citranslate lang=$language text='invoices'} ({$invoices|count})</a></li>
	{/if}	
</ul>

<div style="clear: both;"></div>