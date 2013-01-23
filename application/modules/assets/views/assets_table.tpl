{setlocale locale=$locale}

<script type="text/javascript">
	$(document).ready(function() {
		shortcuts_top_menu();	

		$("[id^=a_asset_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_asset_'+item_num), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});

		$('#a_info_mark').bubbletip($('#tip_info_mark'), { deltaDirection: 'up', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });

		$('#a_salable').bubbletip($('#tip_salable'), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
	});
</script>

{if $assets}
{* <pre>{$assets|print_r}</pre> *}
	{foreach $assets as $category => $items}
		
		{if count($items) > 0}
		<div class="box" style="margin-bottom: 20px;">
			{assign var="category_mod" value="{$category|replace:'_':' '}"}
			{assign var="category_mod" value="{t}{$category_mod}{/t}"}
			
			<div class="box_header"><h4>{t}Category{/t}: {$category_mod|capitalize}</h4></div>
			
			{if $category == "asset"}
				<table style="margin: 10px;" cellspacing="20">
					<tr>
						<th style="width: 130px;">{t}Type{/t}</th>
						<th style="width: 200px;">{t}Description{/t}</th>
						<th style="width: 90px;">{t}Purchased{/t}</th>
						<th style="width: 80px;">{t}Price{/t}</th>
						<th style="width: 80px;">{t}Value{/t}</th>
						<th style="width: 30px;">&nbsp;</th>
					</tr>
					{foreach $items as $key => $item}
						<tr>
						
							<td style="vertical-align: top;">{$item.type|default:'-'}</td>
							<td style="vertical-align: top;">{$item.description|default:'-'}</td>
							<td style="vertical-align: top;">{$item.purchase_date|default:'-'}</td>
							<td style="vertical-align: top;">{$item.price|default:'0.00'}</td>
							<td style="vertical-align: top;">{$item.value|default:'0.00'}</td>
							<td style="vertical-align: middle;">{anchor("assets/details/id/{$item.id}", "{t}Details{/t}",'class="button"')}</td>
							
						</tr>
					{/foreach}
				</table>		
			{/if}
			
			{if $category == "home_appliance"}
				<table style="margin: 10px;" cellspacing="20">
					<tr>
						<th style="width: 130px;">{t}Type{/t}</th>
						<th style="width: 130px;">{t}Brand{/t}</th>
						<th style="width: 130px;">{t}Model{/t}</th>
						<th style="width: 90px;">{t}Purchased{/t}</th>
						<th style="width: 80px;">{t}Price{/t}</th>						
						<th>{t}Description{/t}</th>
						<th style="width: 30px;">&nbsp;</th>
					</tr>
					{foreach $items as $key => $item}
						<tr>
							<td style="vertical-align: top;">{$item.type|default:'-'}</td>
							<td style="vertical-align: top;">{$item.brand|default:'-'}</td>
							<td style="vertical-align: top;">{$item.model|default:'-'}</td>
							<td style="vertical-align: top;">{$item.purchase_date|default:'-'}</td>
							<td style="vertical-align: top;">{$item.price|default:'0.00'}</td>
							<td style="vertical-align: top; font-style: italic;">{$item.description|default:'-'}</td>
							<td style="vertical-align: middle;">{anchor("assets/details/id/{$item.id}", "{t}Details{/t}",'class="button"')}</td>
						</tr>
					{/foreach}
				</table>				
			{/if}
			
			{if $category == "digital_device"}
				<table style="margin: 10px;" cellspacing="20">
					<tr>
						<th style="width: 130px;">{t}Type{/t}</th>
						<th>&nbsp;</th>
						<th style="width: 130px;">{t}Brand{/t}</th>
						<th style="width: 130px;">{t}Model{/t}</th>
						<th style="width: 90px;">{t}Purchased{/t}</th>
						<th style="width: 80px;">{t}Price{/t}</th>						
						<th>{t}Description{/t}</th>
						<th style="width: 30px;">&nbsp;</th>
					</tr>
					{foreach $items as $key => $item}
						<tr>
							<td style="vertical-align: top;">{$item.type|default:'-'}</td>
							<td style="vertical-align: top;">
								{if isset($item.network_device)}
									<img src="/layout/images/network_device.png" width="25px"/>
								{else}
									&nbsp;
								{/if}
							</td>
							<td style="vertical-align: top;">{$item.brand|default:'-'}</td>
							<td style="vertical-align: top;">{$item.model|default:'-'}</td>
							<td style="vertical-align: top;">{$item.purchase_date|default:'-'}</td>
							<td style="vertical-align: top;">{$item.price|default:'0.00'}</td>
							<td style="vertical-align: top; font-style: italic;">{$item.description|default:'-'}</td>
							<td style="vertical-align: middle;">{anchor("assets/details/id/{$item.id}", "{t}Details{/t}",'class="button"')}</td>
						</tr>
					{/foreach}
				</table>				
			{/if}			
			
		</div>
		{/if}
	{/foreach}
{/if}