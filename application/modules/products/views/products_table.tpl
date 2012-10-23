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

{if $products}
<table style="width: 100%;">

	{* HEADER *}
    <tr>
    	{$class = "first"}

    	{if isset($show_product_selector) && $show_product_selector} {* //TODO what about if $common_list ? *}
			{$class = ""}
			<th class="first">&nbsp;</th>
		{/if}
		
		
		<th style="width: 20px; text-align: center;"><a id="a_salable"><img src="/layout/images/question_mark.png" /></a></th>  {* question mark field *}
		<div id="tip_salable" style="display: none;">
			<p>{t}Products not salable display an esclamation mark here below{/t}.</p>
		</div>
		
		<th style="max-width: 350px;">{t}product{/t}</th>
		
		<th><a id="a_info_mark"><img src="/layout/images/question_mark.png" /></a></th>  {* question mark field *}
		<div id="tip_info_mark" style="display: none;">
			<p>{t}Click on the rounded blue markers below to see a quick detailed view of the product{/t}.</p>
		</div>
		
		
		<th>{t}Code Number{/t}</th>
		
		<th>{t}Brand{/t}</th>
		
		<th>{t}Model{/t}</th>
		
		<th>{t}Price{/t}</th>
			
    </tr>
    
    
    {* BODY *}
    
	{foreach $products as $product}
	<tr style="vertical-align: top;">
		{if isset($show_product_selector) && $show_product_selector}
			<td class="first"><input type="checkbox" class="product_id_check" name="product_id[]" value="{$product.id}"></td>
		{/if}

		<td>
			{if !$product.salable}
				<img src="/layout/images/esclamation_mark.png" />
			{else}
				&nbsp;
			{/if}
		</td>		
		
		<td style="width: 350px; line-height: 20px;">
		{$style=''}
		{if !$product.salable}{$style="color: #acacac; font-style: italic;"}{/if}
			<p style="padding-top: 5px; padding-bottom: 5px; {$style}">
				<a class="button" style="font-size: 11px;" href="/products/details/id/{$product.id}">#{$product.id}</a>
				{$product.product}
			</p>
		</td>
		
		{$num={counter}}
		
		{* info mark *}
		<td><a id="a_product_{$num}"><image src="/layout/images/info_mark.png" /></a></td>
		<div id="tip_product_{$num}" style="display: none;">
			<p>{t}Creation date{/t}: {$product.creation_date|date_format:"%a %Y-%m-%d"|default:'--'}</p>
			<p>{t}Author{/t}: <a href="/contact/details/uid/{$product.created_by}">{$product.creator}</a></p>
			
			
			{if $product.update_date}
				<p style="margin-top: 10px;">{t}Last update{/t}: {$product.update_date|date_format:"%a %Y-%m-%d"}</p>
			{/if}
			
			{if $product.editor}
				<p>{t}Updated by{/t}: {$product.editor}</p>
			{/if}	
			
			<hr style="margin: 10px;"/>
			
			<p><b>{t}Details{/t}</b></p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$product.details|default:'--'}</p>
			
			<hr style="margin: 10px;"/>
			
			<p><b>{t}Note{/t}</b></p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$product.note|default:'--'}</p>			
		</div>
		
		<td style="font-size: 11px; {$style}">{$product.code_number|default:'--'}</td>
			
		<td style="font-size: 11px; {$style}">{$product.brand|default:'--'}</td>
		
		<td style="font-size: 11px; {$style}">{$product.model|default:'--'}</td>		

		<td style="font-size: 11px; {$style}">{$product.price|money_format:2|default:'--'}</td>			
		
	</tr>	
	{/foreach}
	
</table>
{/if}