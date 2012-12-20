{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign pager_file "{$fcpath}application/views/pager.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}
{include file="$top_file"}
{include file="$top_menu_file"}

{literal}
<script type="text/javascript">
	function domo(){
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

		$('#button_search').click(function(event) {
		    $form = $(this).parent("form");
		    $('#form_contact_search').submit();
		});

		$('#button_reset').click(function(event) {
		    $('#form_contact_search').append('<input type="hidden" value="reset" name="reset" />');
		    $('#form_contact_search').submit();
		    //$.post($form.attr("action"), $form.serialize() + "&reset=reset", function(data) { $(this).html(data); });
		});				
		
	});
</script>
{/literal}


<style media="screen" type="text/css">
	#search_notification_area {
		min-height: 52px;
		max-height: 52px;
		padding: 0px;
		margin: 0px;
	}
</style>

{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'total_number' $contacts.total_number}

{* <pre>{$people|print_r}</pre> *}

{* left column *}

<div class="grid_18">
	
	<div class="box" style="padding: 5px;">
	
	{if isset($people) && count($people) gt 0}
		<h4>{t}All people{/t}<span style="padding-left: 5px; font-size: 11px;">({$people_total_number})</span></h4>
		
		<table class="contact_search_result">
		<tr class="header">
			{* <td class="counter" style="background-color: black;">&nbsp;</td> *}
			<th><a href="?order_by=cn&flow_order={$next_flow_order}">{t}Name{/t}</a></th>
			<th><a href="?order_by=mozillaHomeLocalityName&flow_order={$next_flow_order}">{t}City{/t}</a></th>
			<th>{t}Telephone{/t}</th>
			<th>{t}Mobile{/t}</th>
			<th>{t}E-mail{/t}</th>			
		</tr>
		{foreach $people as $key => $person}
	    <tr>
	    	{* 'cn','o','mail','omail','mobile','oMobile','homePhone','telephoneNumber','labeledURI','oURL' *}
	    	{assign 'url' value="/contact/details/uid/{$person->uid}"}
	    	{* <td class="counter">{counter}</td> *}
	    	{if $person->enabled == 'TRUE'}
	    		<td>{a url=$url text=$person->cn|ucwords|truncate:25:"[..]":true}</td>
	    	{else}
	    		<td><strike>{a url=$url text=$person->cn|ucwords|truncate:25:"[..]":true}</strike></td>	
	    	{/if}
			<td>{$person->mozillaHomeLocalityName|truncate:25:"[..]":true|default:'-'}</td>
			<td style="font-size: 11px;">{$person->homePhone|default:'-'}</td>
			<td style="font-size: 11px;">{$person->mobile|default:'-'}</td>
			<td><a href="mailto:{$person->mail}">{$person->mail|truncate:25:"[..]"|default:'-'}</a></td>
	    </tr> 
	    {/foreach}
	    </table>
	{/if}
	
	
	{if isset($orgs) && count($orgs) gt 0}    
	
		<h4>{t}All organizations{/t}<span style="padding-left: 5px; font-size: 11px;">({$organizations_total_number})</span></h4>
	
		<table class="contact_search_result">
		
		<tr class="header">
			{* <td class="counter" style="background-color: black;">&nbsp;</td> *}
			<th><a href="?order_by=o&flow_order={$next_flow_order}">{t}Name{/t}</a></th>
			<th><a href="?order_by=l&flow_order={$next_flow_order}">{t}City{/t}</a></th>
			<th>{t}Telephone{/t}</th>
			<th>{t}Mobile{/t}</th>
			<th>{t}E-mail{/t}</th>
		</tr>
			
		{foreach $orgs as $key => $organization}
	    <tr>
	    	{assign 'url' value="/contact/details/oid/{$organization->oid}"}
	    	{* <td class="counter">{counter}</td> *}
	    	{if $organization->enabled == 'TRUE'}
	    		<td>{a url=$url text=$organization->o|ucwords|truncate:25:"[..]":true}</td>
	    	{else}
	    		<td><strike>{a url=$url text=$organization->o|ucwords|truncate:25:"[..]":true}</strike></td>
	    	{/if}
	    	<td>{$organization->l|truncate:25:"[..]":true|default:'-'}</td>
	    	<td style="font-size: 11px;">{$organization->telephoneNumber|default:'-'}</td>
	    	<td style="font-size: 11px;">{$organization->oMobile|default:'-'}</td>
	    	<td><a href="mailto:{$organization->omail}">{$organization->omail|truncate:25:"[..]"|default:'-'}</a></td>
	    </tr>
	    {/foreach}
	    
	    </table>    
	{/if}
	
	{if count($people) == 0 && count($orgs) == 0}
		<p style="padding-top: 5px;">{t}No contact found{/t}</p>
	{/if}
	</div>
</div>

{* right column *}
<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include 'actions_panel.tpl'}</div>
</div>	

{include file="$pager_file"}

{include file="$footer_file"}