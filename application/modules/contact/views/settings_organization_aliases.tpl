<script type="text/javascript">
    $(document).ready(function() {

        jQuery("#save_organization_aliases").click(function() {		

			form_name = 'form_organization_aliases';
            url = "/contact/contact_settings/update/";
            dataType = "html";    	
    		type = 'POST';
    		action = 'organization_aliases';
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
                console.log('success');
            	//close the accordion
            	jQuery('#organization_accordion').accordion("activate",false);
            	//jQuery('#organization_accordion').accordion("activate",$('.laa'));
            })        	     	
        	
        });
    });
</script>

<p style="padding-bottom: 15px;">
	{t}For each attribute you can specify an alias that will replace the attribute name in the contact's details and form{/t}.
	{t}Changes are not automatically saved{/t}: {t}you need to press the button{/t}.
</p>

<form name="form_organization_aliases">
	{foreach $organization_visible_attributes as $key => $attribute_name}
		<dl id="OrgOrderVisibleAttributes_{$attribute_name}"  class="box" style="margin-bottom:10px; background-color: #f3f3f3;">
			
			<dt style="padding-top: 10px; width: 60%; color: black;">{$attribute_name}</dt>
			
			<dd>
				{$attr_alias = ""}
				
				{if isset($organization_aliases) and isset($attribute_name) and isset($organization_aliases.$attribute_name)}
					{$attr_alias = $organization_aliases[$attribute_name]}
				{/if}
					
				{t}Alias{/t}: <input type="text" name="{$attribute_name}" id="{$attribute_name}" value="{$attr_alias}"/>
			</dd>
			
			<p style="padding-bottom: 5px; font-style: italic;">
				{if $organization_all_attributes[$attribute_name]['desc'] != ""}
					{t}{$organization_all_attributes[$attribute_name]['desc']}{/t}
				{else}
					{t}No description available{/t}.
				{/if}
			</p>
		</dl>
	{/foreach}
	<a class="button" id="save_organization_aliases" href="#" style="float: right;">{t}Save aliases{/t}</a>		 
</form>       
  