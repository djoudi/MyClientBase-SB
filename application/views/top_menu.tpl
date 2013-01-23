{literal}
<script type="text/javascript">
	function shortcuts_top_menu(){
		
		if(language == 'english' || language == 'italian'){
	    	jQuery(document).bind('keydown', 'Shift+c',function (evt){
		    	$('#top_menu_contact').trigger("click");
				return false; 
			});

	    	jQuery(document).bind('keydown', 'Shift+e',function (evt){
		    	$('#top_menu_logout').trigger("click");
				return false; 
			});
		}
		
		if(language == 'english'){
	    	jQuery(document).bind('keydown', 'Shift+s',function (evt){
		    	$('#top_menu_system_settings').trigger("click");
				return false; 
			});
		}
		
		if(language == 'italian'){
	    	jQuery(document).bind('keydown', 'Shift+i',function (evt){
		    	$('#top_menu_system_settings').trigger("click");
				return false; 
			});
		}		
	}

	$(document).ready(function() {
		shortcuts_top_menu();	

		$('#a_shortcuts').bubbletip($('#tip_shortcuts'), { deltaDirection: 'left', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
	});
</script>
{/literal}

<div class="grid_24" style="margin-top: 25px;">

	<div style="float: right; margin-top: 0px; margin-bottom: 2px; margin-right: 2px; font-size: 9px;">
		
		<span>Mcb-Sb v. {$mcbsb_version}</span>
		<a id="a_shortcuts" href="#"><img style="margin-left: 10px;" src="/layout/images/shortcuts.png" /></a>
		
		<div id="tip_shortcuts" style="display:none;">
			<p style="font-weight: bold;">{t}Shortcuts{/t}</p>
			
			<p style="padding-top: 5px;">
				{t}You can speed up your work using keyboard shortcuts{/t}.
				{t}Shortcuts are a replacement for mouse click{/t}.
			</p>
			
			<p style="padding-top: 5px;">
				{t}Some of the tabs or buttons that you can see in this page have an underlined character which indicates the shortcut{/t}.
			</p>
			
			<p style="padding-top: 5px;">
				{t}For the top left menu tabs, hold the SHIFT button and then press the underlined character corrisponding to the tab that you want to click{/t}.
			</p>
			
			<p style="padding-top: 5px;">
				{t}For the blue buttons, just hit the underlined character corrisponding to the button that you want to trigger{/t}
			</p>
		</div>
	</div> 	
	
	<div class="top_menu">
		<ul class="top_menu" id="navigation">
   			{foreach $top_menu as $key => $item}
	   			
	   			{$href = $item['item_link']}
	   			

	   			{if $item['item_name'] == 'Videos'}
					{$additional_class = 'tj_videos'}
	   				{if $language == 'english'} {$href="gydKx6aAXVs"} {/if}
					{if $language == 'italian'} {$href="J-m8Hw4x14o"} {/if}			
				{else}
					{$additional_class = ''}
				{/if} 
	   			
   				<a class="{$additional_class} top_menu" href="{$href}">
   				
   					{assign var=item_id value="top_menu_"|cat:$item['item_name']|strtolower|replace:' ':'_'}
   					
   					{if $item['item_link']=='/logout'}
								
   						{* no shortcut for Logout *}
   						{assign var="label" value="{t}{$item['item_name']}{/t}"}
   						
   					{else}
   						
   						{assign var="label" value="{t u=1}{$item['item_name']}{/t}"}
   						
   					{/if}
   					
   					
   					{if $item['item_selected']}
   						<li class="top_menu" id="{$item_id}" >{$label}</li>
   					{else}
   						<li class="top_menu b_light_blue {$additional_class}" id="{$item_id}">{$label}</li>
   					{/if}
   				</a>
   			{/foreach}
		</ul>
	</div>
	<div style="clear: both;"></div>
</div>