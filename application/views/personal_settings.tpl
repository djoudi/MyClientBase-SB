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


<div class="grid_18">

	<div class="box profile" id="tabs">
	
		{* TABS *}
		<ul>
			{foreach $tabs as $key => $tab}
				<li><a href="#tab_{$tab['title']}">{t}{$tab['title']}{/t}</a></li>
			{/foreach}
		</ul>		
	
			{foreach $tabs as $key => $tab}
				<div id="tab_{$tab['title']}">{$tab['html']}</div>
			{/foreach}
			
		</div>	
	</div>
</div>


<div class="grid_6" style="width: 293px;">
	{* <div class="box" style="padding-left: 5px;">{include 'system_settings_actions_panel.tpl'}</div>*}
</div>

<div style="clear: both;"></div>


{include file="$footer_file"}