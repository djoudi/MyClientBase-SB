{literal}
<script type="text/javascript">
$(document).ready(function() {
		var url = "/contact/contact_settings/update/";

		//refreshes the content of div #organization_accordion everytime the accordion is clicked
		$('#organization_accordion').accordion({}).find('.ova').click(		
			function(ev){
				//alert('refresh');
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=organization_visible'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#org_visible_accordion").html(theResponse);
				});
        });
		
		//refreshes the content of div #org_order_accordion everytime the accordion is clicked
		$('#organization_accordion').accordion({}).find('.ooa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=organization_sort'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#org_order_accordion").html(theResponse);
				});          
        });

		//refreshes the content of div #org_aliases_accordion everytime the accordion is clicked
		$('#organization_accordion').accordion({}).find('.oaa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=organization_aliases'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#org_aliases_accordion").html(theResponse);
				});          
        });

        $("#save_organization_default_values").click(function() {

        	form_name = 'form_organization_default_values';
            url = "/contact/contact_settings/update/";
            dataType = "html";    	
    		type = 'POST';
    		action = 'organization_defaultvalues';
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
            })                	   	
        });
        
	});
</script>
{/literal}

{*	
	<pre style="font-size: 11px;">
	{$prop|print_r}
	</pre>
*}

<div id="organization_default_values">	
	<p style="padding-bottom: 10px;">
		The object organization can have some attributes prefilled (totally or partially) so that every newly created contact will have the values
		specified in this form.
	</p>
	<form name="form_organization_default_values" style="margin-bottom: 10px;">
		<div class="box settings" style="float: right; width: 48%; padding-bottom: 5px; margin-bottom: 5px;">
			<dl>
				{$value=''}{if isset($default_values.telephoneNumber)}{$value=$default_values.telephoneNumber}{/if}
				<dt>{t}Phone number{/t}</dt>
				<dd><input type="text" name="telephoneNumber" value="{$value}" /></dd>
			</dl>
	
			<dl>
				{$value=''}{if isset($default_values.st)}{$value=$default_values.st}{/if}
				<dt>{t}State or province{/t}</dt>
				<dd><input type="text" name="st" value="{$value}" /></dd>
			</dl>
			
		</div>
		
		<div class="box settings" style="width: 48%;  padding-bottom: 5px; margin-bottom: 5px;">
			
			<dl>
				{$value=''}{if isset($default_values.facsimileTelephoneNumber)}{$value=$default_values.facsimileTelephoneNumber}{/if}
				<dt>{t}Fax{/t}</dt>
				<dd><input type="text" name="facsimileTelephoneNumber" value="{$value}"/></dd>
			</dl>
			
			<dl>
				{$value=''}{if isset($default_values.c)}{$value=$default_values.c}{/if}
				<dt>{t}Country{/t}</dt>
				<dd><input type="text" name="c" value="{$value}"/></dd>
			</dl>
			
			<dl>
				{$value=''}{if isset($default_values.category)}{$value=$default_values.category}{/if}
				<dt>{t}Category{/t}</dt>
				<dd><input type="text" name="category" value="{$value}"/></dd>
			</dl>			
		
		</div>
		
		<div style="clear: both;"></div>
		
		<a class="button" id="save_organization_default_values" href="#" style="float: right;">{t}Save{/t}</a>
		
		<div style="clear: both;"></div>
	</form>
</div>

	
	
<div id="organization_accordion">	
	
{* organizations accordion items *}
	{$obj = "{t}organization{/t}"}		
	<h3 class="ova"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set visible attributes{/t}</a></h3>
	<div id="org_visible_accordion">
		{* $settings_organization *} {* this is necessary only if the accordion is shown open at start *}
	</div>

	<h3 class="ooa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes order{/t}</a></h3>
	<div id="org_order_accordion">
		{* $settings_organization_order *} {* this is necessary only if the accordion is shown open at start *}
	</div>

	<h3 class="oaa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes aliases{/t}</a></h3>
	<div id="org_aliases_accordion">
		{* $settings_organization_aliases *} {* this is necessary only if the accordion is shown open at start *}
	</div>
	
</div>
