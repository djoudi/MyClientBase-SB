{literal}
<script type="text/javascript">
$(document).ready(function() {
		var url = "/contact/contact_settings/update/";

		//refreshes the content of div #location_visible_accordion everytime the accordion is clicked
		$('#location_accordion').accordion({}).find('.lva').click(		
			function(ev){
				//alert('refresh');
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=location_visible'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#location_visible_accordion").html(theResponse);
				});
        });

		//refreshes the content of div #location_order_accordion everytime the accordion is clicked
		$('#location_accordion').accordion({}).find('.loa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=location_sort'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#location_order_accordion").html(theResponse);
				});          
        });     

		//refreshes the content of div #person_aliases_accordion everytime the accordion is clicked
		$('#location_accordion').accordion({}).find('.laa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=location_aliases'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#location_aliases_accordion").html(theResponse);
				});          
        });  

	});
</script>
{/literal}

<div id="location_accordion">	

{* locations accordion items *}
	{$obj = "{t}location{/t}"}				
	<h3 class="lva"><a href="#">{t}Set visible attributes{/t}</a></h3>
	<div id="location_visible_accordion">
		{* $settings_location *} {* this is necessary only if the accordion is shown open at start *}
	</div>
	
	<h3 class="loa"><a href="#">{t}Set attributes order{/t}</a></h3>
	<div id="location_order_accordion">
		{*$settings_location_order*} {* this is necessary only if the accordion is shown open at start *}
	</div>

	<h3 class="laa"><a href="#">{t}Set attributes aliases{/t}</a></h3>
	<div id="location_aliases_accordion">
		{*$settings_location_aliases*} {* this is necessary only if the accordion is shown open at start *}
	</div>
	
</div>
