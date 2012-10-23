{* this template contains the main action panel, i.e. the part of the action panel in common with most of the pages *}

<script type="text/javascript">
	
	function shortcuts_main_ap(){
		/*
		if(language == 'english' || language == 'italian'){
	    	jQuery(document).bind('keydown', 'p',function (evt){
		    	toggle_animate('add_person','first_name');
		    	$('#first_name').val('');
		    	$('#last_name').val('');
				return false; 
			});
		}
		*/
		return false;				
	}
	
	$(document).ready(function() {

		shortcuts_main_ap();			

	});
</script>


{*
<ul class="ap">

	<li class="ap">
	
	</li>			
	
</ul>
*}