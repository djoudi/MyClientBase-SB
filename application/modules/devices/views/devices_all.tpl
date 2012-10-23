{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign pager_file "{$fcpath}application/views/pager.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}
{include file="$top_file"}
{include file="$top_menu_file"}

<div class="grid_18">
	<div class="box" style="padding: 10px;">
	<h4>{t}All devices{/t}</h4>
	{if $devices}
		{include file=	"{$fcpath}application/modules/devices/views/devices_table.tpl"}
	{else}
		<p style="padding-top: 10px;">{t}No devices found{/t}. {t}You can add one by clicking the "add device" button on the right{/t}.</p>	
	{/if}
	</div>
</div>

<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include file=	"{$fcpath}application/modules/products/views/actions_panel.tpl"}</div>
</div>

{include file="$pager_file"}

{include file="$footer_file"}