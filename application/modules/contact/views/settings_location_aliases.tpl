{literal}
<script type="text/javascript">
$(document).ready(function() {
    jQuery("#save_location_aliases").click(function() {        	
		
		form_name = 'form_location_aliases';
        url = "/contact/contact_settings/update/";
        dataType = "html";    	
		type = 'POST';
		action = 'location_aliases';
		form = document.forms[form_name];
		formObj = retrieveForm(form);
		   
        jQuery.ajax({
        	url		: url,
        	dataType: dataType,
        	type	: type,
            data    : {
            			action: action,
                		save: true,
                		form: formObj,
            			},
            error	: errorCallback,                			
        })
        .success(function(){
        	//close the accordion
        	jQuery('#location_accordion').accordion("activate",false);
        	//jQuery('#location_visible_accordion').accordion("activate",$('.laa'));
        });        	     	
    });
});
</script>
{/literal}

<p style="padding-bottom: 15px;">
	{t}For each attribute you can specify an alias that will replace the attribute name in the contact's details and form{/t}.
	{t}Changes are not automatically saved{/t}: {t}you need to press the button{/t}.
</p>

<form name="form_location_aliases">
	{foreach $location_visible_attributes as $key => $attribute_name}
		<dl id="LocationVisibleAttributes_{$attribute_name}" class="box" style="margin-bottom:10px; background-color: #f3f3f3;">
			
			<dt style="padding-top: 10px; width: 60%; color: black;">{$attribute_name}</dt>
			
			<dd>
				{$attr_alias = ""}
			
				{if isset($location_aliases) and isset($attribute_name) and isset($location_aliases.$attribute_name)}
					{$attr_alias = $location_aliases[$attribute_name]}
				{/if}
				{t}Alias{/t}: <input type="text" name="{$attribute_name}" id="{$attribute_name}" value="{$attr_alias}"/>
			</dd>
				
				
			<p style="padding-bottom: 5px; font-style: italic;">
				{if $location_all_attributes[$attribute_name]['desc'] != ""}
					{t}{$location_all_attributes[$attribute_name]['desc']}{/t}
				{else}
					{t}No description available{/t}.
				{/if}
			</p>
		</dl>
	{/foreach}
	<a class="button" id="save_location_aliases" href="#" style="float: right;">{t}Save aliases{/t}</a>		 
</form>       
  