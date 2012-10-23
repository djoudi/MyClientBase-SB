{* <pre> {$object|print_r} </pre> *} 

{if count($orgs) gt 0}
<script type="text/javascript">
	formname = "{$form_name}";
	$(document).ready(function() {

		$('#'+formname).live('keyup', function(e){		
			if (e.keyCode == 13) {
			    $('#mydialog').dialog("close");
			  }
		});
	
	    $('[type=radio]').live("click", function() {
	    	selected_radio = $(this).val(); 
	    });
	});
</script>

<div id="{$div_id}" title="Form">
	<div class="box" style="padding: 5px;">
		<form name="{$form_name}">
			<p style="padding-top: 10px; padding-bottom: 10px;">{t}Displaying{/t} {$results_got_number} {if $results_got_number == 1}{t}organization{/t}{else}{t}organizations{/t}{/if} {t}on{/t} {$results_number} {t}found{/t}.</p>

			{if $results_number > $results_got_number}
			<p style="margin-bottom: 20px;">
				{t}Your research produced too many results{/t}: <a class="button" title="{t}Get more results{/t}" href="/contact/search/{$searched_value}">{t}click here{/t}</a> {t}to see all the results.{/t}
			</p>
			{/if}
			<input type="hidden" name="searched_value" value="{$searched_value}" />
			
			
			<table>
				<tr class="header">
					{if $add_radio}
					<th>&nbsp;</th>
					{/if}
					<th>{t}Name{/t}</th>
					<th>{t}City{/t}</th> 
{* 
					<th>{t}Telephone{/t}</th>
					<th>{t}Mobile{/t}</th> 
*}
					<th>{t}Vat Number{/t}</th>
				</tr>
				{foreach $orgs as $key => $organization}
				<tr class="hoverall">
					{if $add_radio}
					<td class="counter" valign="middle"><input type="radio" name="radiogroup" id="radio_{$organization->oid}" value="{$organization->oid}"></td>
					{/if} 
					{assign 'url' value="$base_url/contact/details/oid/{$organization->oid}"} 
					{assign 'urltitle' value="See {$organization->o}'s profile"}
					
					<td>{a url=$url title=$urltitle target="_blank" text=$organization->o|truncate:20:"[..]":true}</td>
					<td>{$organization->l|truncate:18:"[..]":true|default:'-'}</td> 
{*
					<td>{$organization->telephoneNumber|truncate:16:"[..]":true|default:'--'}</td>
					<td>{$organization->oMobile|truncate:12:"[..]":true|default:'--'}</td>
*}
					<td>{$organization->vatNumber|truncate:11:"[..]":true|default:'--'}</td>
				</tr>
				{/foreach}
			</table>

		</form>
		
		{if !$add_radio && $results_number > 0}
			<p style="margin-top: 10px; margin-bottom: 0px;">
				{t}If you are sure that this contact is not yet in the system, please click{/t} OK
			</p>
		{/if}
			
	</div>
</div>
{/if}
