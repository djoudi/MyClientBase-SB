{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}
{include file="$top_file"}
{include file="$top_menu_file"}

{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}

{literal}
<script type="text/javascript">
	function domo(){
		//console.log('language ' + language);
		if(language == 'english' || language == 'italian'){
	    	jQuery(document).bind('keydown', 'c',function (evt){jQuery('#search-city').focus(); return false; });
		}
	}

	$(document).ready(function() {
		domo();
		$('#search-city').focus();

		$('#button_search').click(function(event) {
		    $('#search-by-location').submit();
		});

		$('#button_reset').click(function(event) {
		    $('#search-by-location').append('<input type="hidden" value="reset" name="reset" />');
		    $('#search-by-location').submit();
		});				

		$('.city').click(function(event) {
			event.preventDefault();
			city = $(this).attr('href');
			console.log('city ' + city);
			$('#search-city').val('');
			$('#search-by-location').append('<input type="hidden" value="' + city + '" name="city" />');
		    $('#search-by-location').submit();
		});

		$('#search-city,#search-state,#search-country').keypress(function(event){
			
			//this intercepts the press enter on any form field 
			if (event.which == 13)
			{
				$('#search-by-location').submit();
				return false;
			} else {
			   return true;
			}
		});			
	});
</script>
{/literal}

{* left column *}
<div class="grid_9">
	<div class="box contact_search" style="margin-bottom: 10px; padding-bottom: 0px;">
		<form id="search-by-location" method="post">				
			<dl>	
				<dt style="width: 80px; font-size: 15px;"><span style="margin-left: 5px;">{"{t u=1}city{/t}"|capitalize}:</span></dt>
				<dd><input style="width: 300px;" title="{t}Set a city{/t}" type="text" name="city" id="search-city" value=""></dd>
			</dl>
			
			<dl>
				<dt style="width: 80px; font-size: 15px;"><span style="margin-left: 5px;">{"{t}state{/t}"|capitalize}:</span></dt>
				<dd><input style="width: 300px;" title="{t}Set a state{/t}" type="text" name="state" id="search-state" value=""></dd>
			</dl>
			
			<dl>
				<dt style="width: 80px; font-size: 15px;"><span style="margin-left: 5px;">{"{t}country{/t}"|capitalize}:</span></dt>
				<dd><input style="width: 300px;" title="{t}Set a country{/t}" type="text" name="country" id="search-country" value=""></dd>
			</dl>
						
			<dl>
				<dt style="width: 80px;">&nbsp;</dt>
				<dd>
					<span>
					<a href="#" class="button" id="button_search">{t u=1}Search{/t}</a> 
					<a href="#" class="button" id="button_reset">{t u=1}Reset{/t}</a>
					</span>
				</dd>
			</dl>
		</form>
	</div>

	{if $searched_string}
	
		{$width=350}
		<div class="box" style="overflow: auto;">
			{assign hl 'en'}
			{if $language == 'italian'}
				{assign hl 'it'}
			{/if}			
			<iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q={$searched_string}&amp;hl={$hl}&amp;ie=UTF8&amp;output=embed"></iframe>
			<p style="float: right; margin: 10px;"><a class="button" href="http://maps.google.com/maps?q={$searched_string}&hl={$hl}" target="_blank">{t}See on map{/t}</a></p>
		</div>
		
		<div class="contact_search_result box">
			{include 'people_table_small.tpl'}
		</div>
		
	{/if}	
</div>

{* central column *}
<div class="grid_9">
	
{if $searched_string}

	<div class="box" style="padding-left: 5px; padding-right: 5px; min-height: 594px; max-height: 594px; overflow: auto;">
		<h4>{"{t}statistics{/t}"|capitalize} <span style="font-size: 11px;">{t}for{/t}: {$searched_string_extended}</span></h4>
		<table style="margin-top: 10px;">
		<tr class="header">
	    	<th>{t}City{/t}</th>
	    	<th>{t}People{/t}</th>
	    	<th>{t}Orgs{/t}</th>
	    	<th>{t}Total{/t}</th>		
		</tr>		
		
		<tr style="line-height: 10px; border: 0px;">
			<td colspan="4">&nbsp;</td>
		</tr>
					
		<tr style="font-style: italic; border: 0px;">
			<td>{t}Summary{/t}</td>
			<td>{$summary['total_people']}</td>
			<td>{$summary['total_organizations']}</td>
			<td>{$summary['total_number']}</td>
		</tr>
		
		<tr style="line-height: 10px;">
			<td colspan="4">&nbsp;</td>
		</tr>
		
		{foreach $statistics as $city => $stats}
		    <tr>
		    	<td><a class="city" href="{$city}">{$city|truncate:30:"[..]":true}</a></td>
		    	<td>{$stats.people}</td>
		    	<td>{$stats.organizations}</td>
		    	<td>{$stats.total}</td>		
		    </tr> 
		{/foreach}
		</table>
	</div>
	
	<div class="contact_search_result box">
		{include 'orgs_table_small.tpl'}
	</div>
{else}
	<div class="box" style="padding: 5px; min-height: 185px; max-height: 185px; overflow: auto;">
	<p style="padding-bottom: 10px;">{t}Use this tool to retrieve statistics usefull for your marketing strategy{/t}.</p>
	<p style="padding-bottom: 10px;">{t}You can search by city, state and country filling one or more fields in the left box{/t}.</p>
	<p style="padding-bottom: 10px;">{t}You can even search providing partial names, ex. country = ital to get all the contacts in Italy or in Italia{/t}.</p>
	</div>
{/if}	
</div>

{* right column *}
<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include 'actions_panel.tpl'}</div>
</div>	


{include 'pager.tpl'}

{include file="$footer_file"}