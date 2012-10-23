{literal}
<script type="text/javascript">
	$(function() {        			
		//updates the order of the items
    	$( "#OrgOrderVisibleAttributes" ).sortable({
        	opacity: 0.6,
        	cursor: 'move',
        	containment: 'parent',
        	axis: 'y',
        	placeholder: "ui-state-highlight",
        	update: function() {
        		var input = $(this).sortable("serialize") + '&action=organization_sort';
        			$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#org_order_accordion").html(theResponse); 
				});
			}
		}).disableSelection();    		     
	});
</script>
{/literal}
       
<p style="padding-bottom: 15px;">
	{t}Sort the visible attributes as you like by dragging them up or down{/t}. 
	{t}All the changes are automatically saved{/t}.
</p>		

<ul id="OrgOrderVisibleAttributes" class="accordion">
	{foreach $organization_visible_attributes as $key => $attribute_name}
		<li id="OrganizationVisibleAttributes_{$attribute_name}"  class="box enabled" style="cursor: move;">

			<p style="color: black; margin-bottom: 4px; margin-left: 5px; width: 97%;">
				{$attribute_name}
				
				{if isset($organization_aliases) and isset($attribute_name) and isset($organization_aliases.$attribute_name)}
				<span style="font-size: 13px; color: #555555; float: right;">[{t}Alias{/t}: {$organization_aliases.$attribute_name}]</span>
				{/if}	
			</p>
		</li>
	{/foreach}		
</ul>