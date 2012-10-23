{literal}
<script type="text/javascript">
$(document).ready(function() {

	//updates the order of the items
	$( "#LocationOrderVisibleAttributes" ).sortable({
    	opacity: 0.6,
    	cursor: 'move',
    	containment: 'parent',
    	axis: 'y',
    	placeholder: "ui-state-highlight",
    	update: function() {
    		var input = $(this).sortable("serialize") + '&action=location_sort';
    			$.post("/contact/contact_settings/update/", input, function(theResponse){
				$("#location_order_accordion").html(theResponse); 
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
		
<ul id="LocationOrderVisibleAttributes" class="accordion">
	{foreach $location_visible_attributes as $key => $attribute_name}
		<li id="LocationVisibleAttributes_{$attribute_name}" class="box enabled" style="cursor: move;">

			<p style="color: black; margin-bottom: 4px; margin-left: 5px; width: 97%;">
				{$attribute_name}
				
				{if isset($location_aliases) and isset($attribute_name) and isset($location_aliases.$attribute_name)}
					<span style="font-size: 13px; color: #555555; float: right;">[{t}Alias{/t}: {$location_aliases.$attribute_name}]</span>
				{/if}	
			</p>
		</li>
	{/foreach}		
</ul>          