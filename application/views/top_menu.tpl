<div class="grid_24" style="margin-top: 10px;"> 	
	<div class="top_menu">
		
		<ul class="top_menu" id="navigation">
   			{foreach $top_menu as $key => $item}
	   			
   				<a class="top_menu" href="{$item['item_link']}">
   					{if $item['item_selected']}
   						<li class="top_menu">{t}{$item['item_name']}{/t}</li>
   					{else}
   						<li class="top_menu b_light_blue">{t}{$item['item_name']}{/t}</li>
   					{/if}
   				</a>
   			{/foreach}
		</ul>
	</div>
</div>