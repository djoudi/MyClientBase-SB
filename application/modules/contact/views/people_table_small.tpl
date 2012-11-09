{if count($people) gt 0}

	<h4>{"{t}people{/t}"|capitalize}<span style="font-size: 13px; padding-left: 5px;">( {t}showing{/t} {$contacts.people|count} {t}on total of{/t} {$contacts.total_number})</span></h4>
	
	<table class="contact_search_result">
	<tr class="header">
		{* <td class="counter" style="background-color: black;">&nbsp;</td> *}
		<th><a href="?sort_by=sn&flow_order={$next_flow_order}">{t}Name{/t}</a></th>
		<th><a href="?sort_by=mozillaHomeLocalityName&flow_order={$next_flow_order}">{t}City{/t}</a></th>
		<th>{t}Telephone{/t}</th>
		<th>{t}Mobile{/t}</th>
	</tr>
	{foreach $people as $key => $person}
    <tr>
    	{assign 'url' value="/contact/details/uid/{$person->uid}"}
    	{* <td class="counter">{counter}</td> *}
    	{if $person->enabled == 'TRUE'}
    		<td>{a url=$url text=$person->cn|ucwords|truncate:20:"[..]":true}</td>
    	{else}
    		<td><strike>{a url=$url text=$person->cn|ucwords|truncate:20:"[..]":true}</strike></td>
    	{/if}
		<td>{$person->mozillaHomeLocalityName|truncate:10:"[..]":true|default:'-'}</td>
		<td style="font-size: 11px;">{$person->homePhone|default:'--'}</td>
		<td style="font-size: 11px;">{$person->mobile|default:'--'}</td>		
    </tr> 
    {/foreach}
    </table>

{else}
	<p style="padding-top: 5px;">{t}No person found{/t}</p>
{/if}
