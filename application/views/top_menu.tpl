
<div class="grid_24"> 	
	<div class="top_menu">
		<ul class="top_menu" id="navigation">
   			{foreach $top_menu as $key => $item}
   			{* TODO add class="selected" to the selected tab *}
   				<li class="top_menu b_light_blue"><a class="top_menu" href="{$item['item_link']}">{t}{$item['item_name']}{/t}</a></li>
   			{/foreach}
			<span style="float: right; margin-right: 5px;">MCB-SB {$mcbsb_version}</span>
		</ul>
	</div>
</div>