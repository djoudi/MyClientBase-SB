{* tax_rates action panel *}

{* MAIN ACTIONS *}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	<h3>{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >

		{* global items: these items are always visible except the page destination of the link *}
		
			<li><a href="/settings">{t}System Settings{/t}</a></li>
			
			{if !{preg_match pattern="\/tax_rates$" subject=$site_url} and !{preg_match pattern="\/tax_rates\/index$" subject=$site_url}}
				<li><a href="/tax_rates/index">{t}Show Tax Rates{/t}</a></li>
			{/if}
						
		{* end global items *}
		
	</ul>
</div>

{if !{preg_match pattern="\/tax_rates\/form" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">
	<h3>{t}Tax Rate Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
			{if {preg_match pattern="\/tax_rates$" subject=$site_url} or {preg_match pattern="\/tax_rates\/index$" subject=$site_url}}
				<li><a href="/tax_rates/form">{t}Create Tax Rate{/t}</a></li>
			{/if}
	</ul>
</div>
{/if}