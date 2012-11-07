{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
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

{* left column *}
<div class="grid_9">

	<div class="box contact_search">
		<form id="form_contact_search" method="post">
			<span style="margin-left: 5px;">{t}Contact{/t}: </span>
			<input style="width: 200px;" title="{t}Search for name, organization name, vat number, phone, email, website{/t}" type="text" name="search" id="search-box" value="">
			<a href="#" class="button" id="button_search">{t u=1}Search{/t}</a> <a href="#" class="button" id="button_reset">{t u=1}Reset{/t}</a>
		</form>
	</div>
	
	{if $searched_string != ""}
	<div class="contact_search_result box">
		{include 'people_table_small.tpl'}
	</div>
	{/if}
</div>

{* central column *}
<div class="grid_9">
	
	<div class="box" style="min-height: 52px;">
		<p style="text-align: center; padding-top: 18px;">
		{t}People{/t}: {$people_total_number}
		<span style="padding-left: 30px;">{t}Organizations{/t}: {$organizations_total_number}</span>
		<span style="padding-left: 30px;">{t}Total{/t}: {($people_total_number + $organizations_total_number)}</span>
		</p>
	</div>
	
	{if $searched_string != ""}
	<div class="contact_search_result box">
		{include 'orgs_table_small.tpl'}
	</div>
	{/if}
</div>

{* right column *}
<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include 'actions_panel.tpl'}</div>
</div>	

{include 'pager.tpl'}

{include file="$footer_file"}