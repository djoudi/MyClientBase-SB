{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}
{include file="$top_file"}
{include file="$top_menu_file"}

{* focuses on the tab matching the hash and goes on the top of the page *}

<script type="text/javascript">
	$(document).ready(function() {
		
		var currentURL = window.location;
		url_hash = currentURL.hash;
		
		var $tabs = $('#tabs').tabs();
		$tabs.tabs('select', url_hash);
		
		window.location.hash='#top';

		{if isset($tab_index)}
			if(url_hash == '') $('#tabs').tabs({ selected: {$tab_index}});
		{/if}
	});
</script>	

{assign 'contact' $contact}
{assign 'properties' $contact->properties}
{assign 'base_url' $base_url}
{assign 'invoices_html' $invoices_html}

{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
	{$contact_ref = $contact->cn}
	{$contact_id = $contact->uid}
	{$contact_id_key = "uid"}
	{$object_type = 'person'}
	{*
	<h3>{$contact->cn|ucwords}</h3>
	*}
{/if}		

{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
	{$contact_ref = $contact->o}
	{$contact_id = $contact->oid}
	{$contact_id_key = "oid"}
	{$object_type = 'organization'}
	{*
	<h3>{$contact->o|ucwords}<span id="tj_organization" style="display: none; font-size: 13px; margin-left: 20px;">{t}This is your organization{/t}</span></h3>
	*}
{/if}		

{* <pre> $contact|print_r </pre> *}
{if isset($contact->aliases)} {$aliases = $contact->aliases} {/if}

<div class="grid_18">
	<div class="box profile" id="tabs">
	
		{* TABS *}
		{include file="tabs.tpl"}
		
		{* TAB CLIENT *}
		
		{if $contact->enabled == 'TRUE'}
			<div id="tab_client" style="padding: 0px; margin: 0px;">
		{else}
			<div id="tab_client" style="padding: 0px; margin: 0px; background-color: #f3f3f3;">
		{/if}		
			{include 'tab_client.tpl'}
		</div>
		
		{if $contact_locs}
		<div id="tab_locations">
			{include 'tab_locations.tpl'}	
		</div>
		{/if}
		
		{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass} and $members}
		<div id="tab_members" style="padding: 0px; margin: 0px;">	
			{include 'tab_members.tpl'}		
		</div>
		{/if}				
	
		
		{if {preg_match pattern="dueviPerson" subject=$contact->objectClass} and {$contact_orgs|count} >0}
		<div id="tab_member_of">
			{include 'tab_member_of.tpl'}
		</div>				
		{/if}
		
		{if $ss_contact_folder_content}
		<div id="tab_documents">
		 	{include 'tab_documents.tpl'}
		</div>
		{/if}
		
		
	 	{if isset($tasks) and {$tasks|count} > 0}
		<div id="tab_tasks">{$tasks_html}</div>
		{/if}
	 			
	 	{if isset($quotes_html) and {$quotes|count} > 0}
		<div id="tab_quotes">{$quotes_html}</div>
		{/if}
					
	 	{if isset($invoices_html) and {$invoices|count} > 0}
		<div id="tab_invoices">{$invoices_html}</div>
		{/if}
	</div>
</div>

<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include 'actions_panel.tpl'}</div>
</div>

<div class="clear"></div>

{include file="$footer_file"}