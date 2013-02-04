{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}
{include file="$top_file"}
{include file="$top_menu_file"}


{* focuses on the tab matching the hash and goes on the top of the page *}
{literal}
<script type="text/javascript">
	$(document).ready(function() {
		
		var currentURL = window.location;
		url_hash = currentURL.hash;
		
		var $tabs = $('#tabs').tabs();
		$tabs.tabs('select', url_hash);
		
		window.location.hash='#top';
{/literal}
		{if isset($tab_index)}
			if(url_hash == '') $('#tabs').tabs({ selected: {$tab_index}});
		{/if}
{literal}
	});
</script>	
{/literal}

<div class="grid_18">

	<div class="box profile" id="tabs">
	
		{* TABS *}
		{include file="tabs.tpl"}
	
	
		<div id="tab_product">
			
			{include file="product_details_core.tpl"}

		</div>
	</div>
</div>

<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include "{$fcpath}application/modules/products/views/actions_panel.tpl"}</div>
</div>

<div style="clear: both;"></div>
{include file="$footer_file"}