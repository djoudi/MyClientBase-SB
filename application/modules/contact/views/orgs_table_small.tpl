{if count($orgs) gt 0}    

	<h4>{"{t}organizations{/t}"|capitalize}<span style="font-size: 13px; padding-left: 5px;">(  {t}showing{/t} {$contacts.orgs|count} {t}on total of{/t} {$contacts.total_number})</span></h4>

	<table class="contact_search_result">
	
	<tr class="header">
		{* <td class="counter" style="background-color: black;">&nbsp;</td> *}
		<th><a href="?sort_by=sn&flow_order={$next_flow_order}">{t}Name{/t}</a></th>
		<th><a href="?sort_by=l&flow_order={$next_flow_order}">{t}City{/t}</a></th>
		<th>{t}Telephone{/t}</th>
		<th>{t}Mobile{/t}</th>
	</tr>
		
	{foreach $orgs as $key => $organization}
    <tr>
    	{assign 'url' value="/contact/details/oid/{$organization->oid}"}
    	{* <td class="counter">{counter}</td> *}
    	{if $organization->enabled == 'TRUE'}
    		<td>{a url=$url text=$organization->o|ucwords|truncate:20:"[..]":true}</td>
    	{else}
    		<td><strike>{a url=$url text=$organization->o|ucwords|truncate:20:"[..]":true}</strike></td>
    	{/if}
    	<td>{$organization->l|truncate:10:"[..]":true|default:'-'}</td>
    	<td style="font-size: 11px;">{$organization->telephoneNumber|default:'--'}</td>
    	<td style="font-size: 11px;">{$organization->oMobile|default:'--'}</td>
    </tr>
    {/foreach}
    
    </table>    
{else}
	<p style="padding-top: 5px;">{t}No organization found{/t}</p>
{/if}
