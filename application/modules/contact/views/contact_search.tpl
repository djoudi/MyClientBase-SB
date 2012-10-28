
{assign header_file "{$fcpath}application/views/header.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}

{literal}
<script type="text/javascript">
	function domo(){
		//console.log('language ' + language);
		if(language == 'english'){
	    	jQuery(document).bind('keydown', 's',function (evt){jQuery('#search-box').focus(); return false; });
		}

		if(language == 'italian'){
	    	jQuery(document).bind('keydown', 'c',function (evt){jQuery('#search-box').focus(); return false; });
		}
	}

	$(document).ready(function() {
		domo();
		$('#search-box').focus();
	});
</script>
{/literal}

<style media="screen" type="text/css">
	#notification_area {
		min-height: 52px;
		max-height: 52px;
		padding: 0px;
		margin: 0px;
	}
</style>


{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'total_number' $contacts.total_number}

	{* left column *}
<div class="grid_9">

	<div class="box contact_search">
		<form id="form_contact_search" method="post" action="">
			<span style="margin-left: 5px;">{t}Contact{/t}: </span><input style="width: 200px;" title="{t}Search for name, organization name, vat number, phone, email, website{/t}" type="text" name="search" id="search-box" value="">
			<a href="#" class="button">{t u=1}Search{/t}</a> <a href="#" class="button">{t u=1}Reset{/t}</a>
			{* <input type="submit" class="button" name="reset" value="{t}Reset{/t}" /> *}
		</form>
	</div>
	
	<div class="contact_search_result box">
	{if count($people) gt 0}

		<h4>{"{t}people{/t}"|capitalize}</h4>
		
		<table class="contact_search_result">
		<tr class="header">
			{* <td class="counter" style="background-color: black;">&nbsp;</td> *}
			<th><a href="">{t}Name{/t}</a></th>
			<th>{t}City{/t}</th>
			<th>{t}Telephone{/t}</th>
			<th>{t}Mobile{/t}</th>
		</tr>
		{foreach $people as $key => $person}
	    <tr>
	    	{assign 'url' value="/contact/details/uid/{$person->uid}"}
	    	{* <td class="counter">{counter}</td> *}
	    	<td>{a url=$url text=$person->cn|ucwords|truncate:25:" [...]":true}</td>
			<td>{$person->mozillaHomeLocalityName|truncate:24:" [...]":true|default:'-'}</td>
			<td>{$person->homePhone|default:'-'}</td>
			<td>{$person->mobile|default:'-'}</td>		
	    </tr> 
	    {/foreach}
	    </table>

	{else}
		{if $made_search}
		<p>{t}No person found{/t}</p>
		{/if}
	{/if}
	</div>
	
</div>

{* central column *}
<div class="grid_9">

	<div class="box"  id="notification_area">
	<p>
		{if $searched_string != ""}
			{t}last search{/t} "{$searched_string}" {t}produced{/t} {$total_number|default:0} {t}results{/t}
		{/if}
		{*<pre>{$system_messages|print_r}</pre>*}
	</p>
	</div>
	
	<div class="contact_search_result box">
	{if count($orgs) gt 0}    
	
		<h4>{"{t}organizations{/t}"|capitalize}</h4>
	
		<table class="contact_search_result">
		
		<tr class="header">
			{* <td class="counter" style="background-color: black;">&nbsp;</td> *}
			<th>{t}Name{/t}</th>
			<th>{t}City{/t}</th>
			<th>{t}Telephone{/t}</th>
			<th>{t}Mobile{/t}</th>
		</tr>
			
		{foreach $orgs as $key => $organization}
	    <tr>
	    	{assign 'url' value="/contact/details/oid/{$organization->oid}"}
	    	{* <td class="counter">{counter}</td> *}
	    	<td>{a url=$url text=$organization->o|ucwords|truncate:30:" [...]":true}</td>
	    	<td>{$organization->l|truncate:24:" [...]":true|default:'-'}</td>
	    	<td>{$organization->telephoneNumber|default:'-'}</td>
	    	<td>{$organization->oMobile|default:'-'}</td>
	    </tr>
	    {/foreach}
	    
	    </table>    
	{else}
		{if $made_search}
		<p>{t}No organization found{/t}</p>
		{/if}
	{/if}
	</div>
	
</div>

{* right column *}
<div class="grid_6">
	<div class="box">{$actions_panel}</div>
</div>	

{*
<div class="grid_5">
	<div class="box right_side" style="max-height: 50px; overflow: auto;">
		<h3>{t}Colleagues{/t}</h3>
		<ul>
		{foreach $colleagues as $key => $colleague}
			<li>{$colleague['name']}</li>
		{/foreach}
		</ul>
	</div>
</div>
*}

<div class="prefix_7 grid_5" style="text-align: center;">		
	{if $pager != ""}
	<div id="pagination">{$pager}</div>
	{/if}
</div>

{include file="$footer_file"}