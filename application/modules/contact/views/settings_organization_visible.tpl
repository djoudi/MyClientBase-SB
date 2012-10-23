{literal}
<script type="text/javascript">
	$(function() {

        	$("#OrgAvailableAttributes li, #OrgVisibleAttributes li").draggable({ 
                axis: "x",
                cursor: 'move',
                distance: 30,
                containment: 'document',
                grid: [400, 0],
                opacity: 0.6,
                revert: true,
                revertDuration: 300,
                delay: 1,
        	}).disableSelection();
        		
            $("#OrgAvailableAttributes li").draggable({ 
               	stop: function(event, ui){
                    // When dragging stops, call the php function	
        			var input = '&item=' + $(this).attr('id') + '&action=organization_addToVisible'; 
        			$.post("/contact/contact_settings/update/", input, function(theResponse){
        				$("#org_visible_accordion").html(theResponse);  
        			}); 	
                },
            }).disableSelection();

            $("#OrgVisibleAttributes li").draggable({ 
               	stop: function(event, ui){
                    // When dragging stops, call the php function	
        			var input = '&item=' + $(this).attr('id') + '&action=organization_removeFromVisible'; 
        			$.post("/contact/contact_settings/update/", input, function(theResponse){
        				$("#org_visible_accordion").html(theResponse); 
        			}); 	
                },
            }).disableSelection();    
        });
</script>  
{/literal}

<p style="padding-bottom: 15px;">
	{t}Drag the gray box from the left to the right to make the attribute visible or from the right to the left to hide it{/t}. 
	{t}All the changes are automatically saved{/t}.
</p>
        
{* list of all the visible attributes *}
<div id="OrgVisibleAttributes" class="box" style="float:right; width: 48%; padding: 3px;">
	<h4 style="padding-left: 5px;">{t}Visible Attributes{/t}<span style="font-size: 13px;"> ({t}found{/t} {$organization_visible_attributes|@count})</span></h4>
	<ul id="OrgVisibleAttributes" class="accordion">
	{foreach $organization_visible_attributes as $key => $attribute_name}
		<li id="OrganizationVisibleAttributes_{$attribute_name}" class="box enabled" style="cursor: move;">

			<p style="color: black; margin-bottom: 4px; margin-left: 5px;">
				{$attribute_name}
				
				{if isset($organization_aliases) and isset($attribute_name) and isset($organization_aliases.$attribute_name)}
					<span style="font-size: 13px; padding-left: 5px; color: #555555;">[{t}Alias{/t}: {$organization_aliases.$attribute_name}]</span>
				{/if}	
			</p>			
			 
			<p style="margin-left: 15px; margin-bottom: 0px; font-style: italic; font-size: 12px;">
				{if $organization_all_attributes[$attribute_name]['desc'] != ""}
					{t}{$organization_all_attributes[$attribute_name]['desc']}{/t}
				{else}
					{t}No description available{/t}.
				{/if}
			</p>
		</li>
	{/foreach}		
	</ul>
</div>

{* list of all the available attributes *}
<div class="box" style="width: 48%; padding: 3px;">
	<h4 style="padding-left: 5px;">{t}Available Attributes{/t}<span style="font-size: 13px;"> ({t}found{/t} {$organization_available_attributes|@count})</span></h4>
	<ul id="OrgAvailableAttributes" class="accordion">
	{foreach $organization_available_attributes as $attribute_name => $attribute_features}
		<li id="OrgAvailableAttributes_{$attribute_name}" class="box" style="cursor: move;">

			<p style="color: black; margin-bottom: 4px; margin-left: 5px;">
				{$attribute_name}
			</p> 
		
			<p style="margin-left: 15px; margin-bottom: 0px; font-style: italic; font-size: 12px;">
				{if $attribute_features['desc'] != ""}
					{t}{$attribute_features['desc']}{/t}
				{else}
					{t}No description available{/t}.
				{/if}
			</p>	
		</li>
	{/foreach}
	</ul>
</div>
