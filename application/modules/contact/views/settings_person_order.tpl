{literal}
<script type="text/javascript">
	$(function() {        			
		//updates the order of the items
    	$( "#PersonOrderVisibleAttributes" ).sortable({
        	opacity: 0.6,
        	cursor: 'move',
        	containment: 'parent',
        	axis: 'y',
        	placeholder: "ui-state-highlight",
        	update: function() {
        		var input = $(this).sortable("serialize") + '&action=person_sort';
        			$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#person_order_accordion").html(theResponse); 
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
		
<ul id="PersonOrderVisibleAttributes" class="accordion">
	{foreach $person_visible_attributes as $key => $attribute_name}
		<li id="PersonVisibleAttributes_{$attribute_name}" class="box enabled" style="cursor: move;">
		{*
			{if $person_all_attributes[$attribute_name]['required'] == 1}
				{$color="red"}
			{else}
				{$color="black"}
			{/if}
		*}
			<p style="color: black; margin-bottom: 4px; margin-left: 5px; width: 97%;">
				{$attribute_name}
				
				{if isset($person_aliases) and isset($attribute_name) and isset($person_aliases.$attribute_name)}
					<span style="font-size: 13px; color: #555555; float: right;">[{t}Alias{/t}: {$person_aliases.$attribute_name}]</span> 
				{/if}				
			</p>			
		</li>
	{/foreach}		
</ul>          