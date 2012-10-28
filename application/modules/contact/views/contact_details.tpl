{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}
{include file="$top_menu_file"}

{* focuses on the tab matching the hash and goes on the top of the page *}

<script type="text/javascript">
	$(document).ready(function() {
		
		var currentURL = window.location;
		url_hash = currentURL.hash;
		
		var $tabs = $('#tabs').tabs();
		$tabs.tabs('select', url_hash);
		
		window.location.hash='#top';
	});
</script>	

{assign 'contact' $contact}
{assign 'properties' $contact->properties}
{assign 'language' 'en'}
{assign 'baseurl' $baseurl}
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

<div class="grid_18">
	<div class="box profile" id="tabs">
	
		{* TABS *}		
		<ul>
			<li><a href="#tab_client">{t}Info{/t}</a></li>
	
			{if $contact_locs}
			<li><a href="#tab_locations">{t}Locations{/t} ({$contact_locs|count})</a></li>
			{/if}
													
			{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass} and $members}
				<li><a href="#tab_members">{t}Members{/t} ({$members|count})</a></li>
			{/if}
			
			{if {preg_match pattern="dueviPerson" subject=$contact->objectClass} and {$contact_orgs|count} >0}
				{$already_wrote=1}<li><a href="#tab_memberOf">{t}Member of{/t} ({$contact_orgs|count})</a></li>
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
	
		{* TAB CLIENT *}
		{if isset($contact->aliases)} {$aliases = $contact->aliases} {/if}
		<div id="tab_client" style="padding: 0px; margin: 0px;">
			<div id="profile_summary">
				<h4>Summary</h4>	
				<p style="margin-top: 13px;">		
					{if isset($contact->uid)}
						{if $contact->jpegPhoto}
							{$src="data:image/jpeg;base64,{$contact->jpegPhoto}"}
						{else}
							{$src="/images/no-face-100.png"}
						{/if}
					{else}
						{$src="/images/no-org-100.png"}
					{/if}	
					<img alt="jpegPhoto" style="float: right; border: 1px solid #ccc; width: 100px; height: 100px; margin-top: 0px; margin-bottom: 10px; margin-left: 5px;" src="{$src}">
				</p>
				<p>
					<h6>{t}Note{/t}:</h6>
					{t}If you add a note for this contact then it will be displayed here{/t}	
				</p>
				<table class="contact_profile" style="width: 345px;">
					<tr>
						<td class="field">{t}Purchased{/t} :</td>
						<td style="text-align: right;">84397,00 $</td>
					</tr>
					<tr>
						<td class="field">{t}Purchased current year{/t} :</td>
						<td style="text-align: right;">4397,00 $</td>
					</tr>
					<tr>
						<td class="field">{t}Pending{/t} :</td>
						<td style="text-align: right;">3397,00 $</td>
					</tr>
				</table>											
			</div>				
			
			<div style="padding-left: 10px; padding-top: 5px;">
			<h4>{t}Data{/t}</h4>		
				<table class="contact_profile" style="width: 500px;">
					{$count = 0}
					{foreach $contact->show_fields as $key => $property_name}
					
						{if $contact->$property_name != ""}
						{* style="height: 5px; background-color: {cycle values="#FFF,#FFF"};" *}
						<tr>
							<td class="field">
							
							{if isset($aliases) && isset($aliases.$property_name)}
								{t}{$contact->aliases.$property_name|regex_replace:"/_/":" "}{/t} :
							{else}
								{t}{$property_name}{/t} :
							{/if}
							</td>
		
							
							<td class="value">					
								{$already_wrote=0}
								{* particular cases *}
								
								{* boolean fields *}
								{if $contact->properties[$property_name]['boolean'] == 1}
									{if $contact->$property_name=='TRUE'}
										{t}yes{/t}
									{else}
										{t}no{/t}
									{/if}
									{$already_wrote=1}
								{/if}
								
								{if $property_name == "mail" or $property_name == "omail"}
									<a href="mailto:{$contact->$property_name}">{$contact->$property_name|wordwrap:60:"<br/>":true}</a>
									{$already_wrote=1}
								{/if}	
		
								{if $property_name == "labeledURI" or $property_name == "oURL"}
									<a href="{$contact->$property_name}" target="_blank">{$contact->$property_name|wordwrap:60:"<br/>":true}</a>
									{$already_wrote=1}
								{/if}
								
								{if $property_name=="jpegPhoto"}
									{* skip the item. I take care of the photo later *}
									{$already_wrote=1}
								{/if}				
		
								{if $property_name=="managerName" && $contact->$property_name != ""}
									<a href="/contact/search/{$contact->$property_name}">{$contact->$property_name}</a>
									{$already_wrote=1}
								{/if}
																		
								{if $property_name=="assistantName" && $contact->$property_name != ""}
									<a href="/contact/search/{$contact->$property_name}">{$contact->$property_name}</a>
									{$already_wrote=1}
								{/if}	
								
								{* /particular cases *}
										
								<!-- default case -->
								{if $already_wrote==0} 
									{$contact->$property_name|wordwrap:60:"<br/>":true}
								{/if}
							</td>						
						</tr>
						{$count = $count + 1}
						{/if}
					{/foreach}
				</table>
			</div>
		
			<div style="clear: both;"></div>
			<p>
			{t}ID{/t}: {$contact_id}
			 
			{if isset($contact->entryCreatedBy)}
			| <i>{t}Created by{/t}</i>: {$contact->entryCreatedBy|default:'-'}
				{if isset($contact->entryCreationDate)} 
					on {$contact->entryCreationDate|default:'-'}
				{/if}
			{/if}
			
			{if isset($contact->entryUpdatedBy)} 
			| <i>{t}Updated by{/t}</i>: {$contact->entryUpdatedBy|default:'-'}
				{if isset($contact->entryUpdateDate)} 
					on {$contact->entryUpdateDate|default:'-'}
				{/if}
			{/if}
			</p>		
		</div>
	
		{* <div id="tab_locations">sneuoa</div> *}
		
		{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass} and $members}
		<div id="tab_members" style="padding: 0px; margin: 0px;">	
		
			{foreach $members as $key => $member}
				
				{if isset($member->aliases)} {$aliases = $member->aliases} {/if}
				<div class="box" style="padding: 5px; margin: 10px;">
					{if $key == 0}
						<h4>
					{else}
						<h4>
					{/if}
				
					<a href="/index.php/contact/details/uid/{$member->uid}">
						{if $member->enabled == "FALSE"}
							<strike>{$member->sn} {$member->givenName}</strike>
						{else}
							{$member->sn} {$member->givenName}
						{/if}
					</a>
					{if $member->oAdminRDN == $contact->oid}
					<img src="/images/gold_star_20.jpg" style="width: 20px; margin-left: 10px;" />
					<span style="font-size: 12px; margin-left: 3px;">({t}manager{/t})</span>
					{/if}						
					</h4>					
				
				
					<p>
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
			{/foreach}
		</div>
		{/if}				
	
		
		{*
		<div id="tab_member_of"></div>
		<div id="tab_documents">asoniuh</div>		
		*}
		
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
	<div class="box right_side">{$actions_panel}</div>
</div>

<div class="clear"></div>

{* <pre>{$contact_locs|print_r}</pre> *}
{include file="$footer_file"}