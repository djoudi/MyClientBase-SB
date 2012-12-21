{* tax_rates action panel *}

{* MAIN ACTIONS *}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	<h3>{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >

		{* global items: these items are always visible except the page destination of the link *}
		
			<li><a href="/settings">{t}System Settings{/t}</a></li>
			
			{if !{preg_match pattern="\/fields$" subject=$site_url} and !{preg_match pattern="\/fields\/index$" subject=$site_url}}
				<li><a href="/fields/index">{t}Show Custom Fields{/t}</a></li>
			{/if}
						
		{* end global items *}
		
	</ul>
</div>

{if !{preg_match pattern="\/fields\/form" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">
	<h3>{t}Custom Fields Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
			{if {preg_match pattern="\/fields$" subject=$site_url} or {preg_match pattern="\/fields\/index$" subject=$site_url}}
				<li><a href="/fields/form">{t}Create Custom Field{/t}</a></li>
			{/if}
	</ul>
</div>
{/if}