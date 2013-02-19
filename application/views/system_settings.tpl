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
		
		jQuery('#person_accordion').accordion({
			active: false,
			collapsible: true, 
			autoHeight: false
		});

		jQuery('#organization_accordion').accordion({
			active: false,
			collapsible: true, 
			autoHeight: false
		});

		jQuery('#location_accordion').accordion({
			active: false,
			collapsible: true, 
			autoHeight: false
		});				
	});
</script>


<div class="grid_18">

	<div class="box profile" id="tabs">
	
		{* TABS *}
		<ul>
			<li><a href="#tab_application">{t}Application{/t}</a></li>
			{foreach $tabs as $key => $tab}
				<li><a href="#tab_{$tab['title']}">{t}{$tab['title']}{/t}</a></li>
			{/foreach}
		</ul>		
	
			<div id="tab_application" class="settings">
				<form method="post" >
				
					{* right side *}
					<div class="box" style="width: 48%; float: right; padding-bottom: 10px;">	

						<dl>
							<dt>{t}Currency symbol{/t}</dt>
							<dd>
								<input type="text" name="currency_symbol" value="{$currency_symbol}" />
							</dd>
						</dl>	
								
						<dl>
							<dt>{t}Currency symbol placement{/t}</dt>
							<dd>
								<select name="currency_symbol_placement">
									
									{$selected=''}
									{if $currency_symbol_placement=="before"}{$selected='selected="selected"'}{/if}
									<option value="before" {$selected}>{t}before{/t}</option>
									
									{$selected=''}
									{if $currency_symbol_placement=="after"}{$selected='selected="selected"'}{/if}
									<option value="after" {$selected}>{t}after{/t}</option>
									
								</select>
							</dd>
						</dl>
						
						<dl>
							<dt>{t}Decimal symbol{/t}</dt>
							<dd>
								<input type="text" name="decimal_symbol" value="{$decimal_symbol}" />
							</dd>
						</dl>							

						<dl>
							<dt>{t}Thousands separator{/t}</dt>
							<dd>
								<input type="text" name="thousands_separator" value="{$thousands_separator}" />
							</dd>
						</dl>
															
					</div>
				
				
					{* left side *}
					<div class="box" style="width: 48%; padding-bottom: 10px;">
						
						<dl>
							<dt>{t}Default language{/t}</dt>
							<dd>
								<select name="default_language">
								{foreach $languages as $lang => $identifier}
									{$selected=''}
									{if $lang==$default_language|capitalize}{$selected='selected="selected"'}{/if}
									<option value="{$lang}" {$selected}>{t}{$lang}{/t}</option>
								{/foreach}
								</select>
							</dd>
						</dl>

						<dl>
							<dt>{t}Date format{/t}</dt>
							<dd>
								<select name="default_date_format">
								{foreach $date_formats as $key => $item}
									{$selected=''}
									{if $item['key']==$default_date_format}{$selected='selected="selected"'}{/if}
									<option value="{$item['key']}" {$selected}>{t}{$item['dropdown']}{/t}</option>
								{/foreach}
								</select>							
							</dd>
						</dl>
						
						<dl>
							<dt>{t}Results per page{/t}</dt>
							<dd>
								<input type="text" name="results_per_page" value="{$results_per_page}" />
							</dd>
						</dl>
						
						<dl>
							<dt>{t}Database backup{/t}</dt>
							<dd><input class="button" type="submit" name="btn_backup" value="{t}Run{/t}" /></dd>
						</dl>													
					</div>
					
					
					<input  class="button" style="float: right; margin-top: 10px; margin-right: 10px;" type="submit" name="btn_save_settings" value="{t}Save{/t}" />
					
					<div style="clear: both;"></div>
				</form>
			</div>
			

			{foreach $tabs as $key => $tab}
				<div id="tab_{$tab['title']}">{$tab['html']}</div>
			{/foreach}
			
		</div>	
	</div>
</div>


<div class="grid_6" style="width: 293px;">
	<div class="box" style="padding-left: 5px;">{include 'system_settings_actions_panel.tpl'}</div>
</div>

<div style="clear: both;"></div>


{include file="$footer_file"}