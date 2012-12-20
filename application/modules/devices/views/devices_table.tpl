{setlocale locale=$locale}

<script type="text/javascript">
	$(document).ready(function() {
		shortcuts_top_menu();	

		$("[id^=a_product_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_product_'+item_num), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});

		$('#a_info_mark').bubbletip($('#tip_info_mark'), { deltaDirection: 'up', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });

		$('#a_salable').bubbletip($('#tip_salable'), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
	});
</script>

{if $devices}
{* <pre>{$devices|print_r}</pre> *}
<table>
	<tr>
		<th>{t}Category{/t}</th>
		<th>{t}Brand{/t}</th>
		<th>{t}Model{/t}</th>
		<th>{t}SN{/t}</th>
		<th>{t}RN{/t}</th>
		<th>{t}Purchased{/t}</th>
		<th>{t}Warranty{/t}</th>
		<th>{t}Insurance{/t}</th>
	</tr>
	{foreach $devices as $key => $device}
		<tr>
			<td>{$device.category}</td>
			<td>{$device.brand}</td>
			<td>{$device.model}</td>
			<td>{$device.serial}</td>
			<td>{$device.registration_number}</td>
			<td>{$device.purchase_date}</td>
			<td>{$device.under_warranty}</td>
			<td>{$device.insurance|truncate:20:"[..]":true}</td>
		</tr>
	{/foreach}
</table>
{/if}