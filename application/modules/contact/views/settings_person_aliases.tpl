{literal}
<script type="text/javascript">
    $(document).ready(function() {
        //when the button is clicked the form is retrieved and sent to ajax
        $("#save_person_aliases").click(function() {

        	form_name = 'form_person_aliases';
            url = "/contact/contact_settings/update/";
            dataType = "html";    	
    		type = 'POST';
    		action = 'person_aliases';
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
            	jQuery('#person_accordion').accordion("activate",false);
            	//jQuery('#organization_accordion').accordion("activate",$('.laa'));
            })                	   	
        });
    });
</script>
{/literal}
   
<p style="padding-bottom: 15px;">
	{t}For each attribute you can specify an alias that will replace the attribute name in the contact's details and form{/t}.
	{t}Changes are not automatically saved{/t}: {t}you need to press the button{/t}.
</p>

<form name="form_person_aliases">
	{foreach $person_visible_attributes as $key => $attribute_name}
		<dl id="PersonVisibleAttributes_{$attribute_name}" class="box" style="margin-bottom:10px; background-color: #f3f3f3;">
		{*
			{if $person_all_attributes[$attribute_name]['required'] == 1}
				{$color="red"}
			{else}
				{$color="black"}
			{/if}
		*}
			<dt style="padding-top: 10px; width: 60%; color: black;">{$attribute_name}</dt>
			
			<dd>
				{$attr_alias = ""}
				{if isset($person_aliases) and isset($attribute_name) and isset($person_aliases.$attribute_name)}
					{$attr_alias = $person_aliases[$attribute_name]}
				{/if}	
				{t}Alias{/t}: <input type="text" name="{$attribute_name}" id="{$attribute_name}" value="{$attr_alias}"/>				
			</dd>
			
			<p style="padding-bottom: 5px; font-style: italic;">
				{if $person_all_attributes[$attribute_name]['desc'] != ""}
					{t}{$person_all_attributes[$attribute_name]['desc']}{/t}
				{else}
					{t}No description available{/t}.
				{/if}
			</p>
		</dl>
	{/foreach}
	{* <input class="button" type="button" id="save_person_aliases" style="float: right;" value="{t}Save aliases{/t}"/> *}
	<a class="button" id="save_person_aliases" href="#" style="float: right;">{t}Save aliases{/t}</a>		 
</form>       
  