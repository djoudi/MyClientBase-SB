{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign pager_file "{$fcpath}application/views/pager.tpl"}
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
		{include file="tabs_all.tpl"}
	
		<div id="tab_tasks">
			
			{if $tasks}
				{include file=	"{$fcpath}application/modules/tasks/views/tasks_table.tpl"}
			{else}
				<p style="padding-top: 10px;">{t}No tasks found{/t}. {t}You can add one by going on a contact profile and clicking the "add task" button{/t}.</p>	
			{/if}			

		</div>
	</div>


</div>

<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include file=	"{$fcpath}application/modules/tasks/views/actions_panel.tpl"}</div>
</div>

{include file="$pager_file"}

{include file="$footer_file"}