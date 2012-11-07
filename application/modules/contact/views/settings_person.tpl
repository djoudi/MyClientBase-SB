{literal}
<script type="text/javascript">
	$(document).ready(function() {
		var url = "/contact/contact_settings/update/";

		//refreshes the content of div #person_accordion everytime the accordion is clicked
		$('#person_accordion').accordion({}).find('.pva').click(		
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=person_visible'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#person_visible_accordion").html(theResponse);
				});
        });
        		
		//refreshes the content of div #person_order_accordion everytime the accordion is clicked
		$('#person_accordion').accordion({}).find('.poa').click(		
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=person_sort'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#person_order_accordion").html(theResponse);
				});
        });

		//refreshes the content of div #person_aliases_accordion everytime the accordion is clicked
		$('#person_accordion').accordion({}).find('.paa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=person_aliases'; 
				$.post("/contact/contact_settings/update/", input, function(theResponse){
					$("#person_aliases_accordion").html(theResponse);
				});          
        });

        $("#save_person_default_values").click(function() {

        	form_name = 'form_person_default_values';
            url = "/contact/contact_settings/update/";
            dataType = "html";    	
    		type = 'POST';
    		action = 'person_defaultvalues';
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
        });
        
	});
</script>
{/literal}

<div id="person_default_values">
	{*
	<pre style="font-size: 11px;">
	{$mandatory_attributes|print_r}
	</pre>
	*}
	<p style="padding-bottom: 10px;">
		{t}The object{/t} {t}Person{/t} {t}can have some attributes prefilled (totally or partially) so that every newly created contact will have the values specified in this form{/t}.
	</p>
	<form name="form_person_default_values" style="margin-bottom: 10px;">
		<div class="box settings" style="float: right; width: 48%; padding-bottom: 5px; margin-bottom: 5px;">
			<dl>
				{$value=''}{if isset($default_values.homePhone)}{$value=$default_values.homePhone}{/if}
				<dt>{t}Home phone{/t}</dt>
				<dd><input type="text" name="homePhone" value="{$value}" /></dd>
			</dl>
	
			<dl>
				{$value=''}{if isset($default_values.mozillaHomeState)}{$value=$default_values.mozillaHomeState}{/if}
				<dt>{t}State or province{/t}</dt>
				<dd><input type="text" name="mozillaHomeState" value="{$value}" /></dd>
			</dl>
			
		</div>
		
		<div class="box settings" style="width: 48%;  padding-bottom: 5px; margin-bottom: 5px;">
			{*
			<dl>
				<dt>{t}User password{/t}</dt>
				<dd><input type="text" name="userPassword" value="{$default_values.userPassword}"/></dd>
			</dl>
			*}
			
			<dl>
				{$value=''}{if isset($default_values.homeFacsimileTelephoneNumber)}{$value=$default_values.homeFacsimileTelephoneNumber}{/if}
				<dt>{t}Home fax{/t}</dt>
				<dd><input type="text" name="homeFacsimileTelephoneNumber" value="{$value}"/></dd>
			</dl>
			
			<dl>
				{$value=''}{if isset($default_values.mozillaHomeCountryName)}{$value=$default_values.mozillaHomeCountryName}{/if}
				<dt>{t}Country{/t}</dt>
				<dd><input type="text" name="mozillaHomeCountryName" value="{$value}"/></dd>
			</dl>
			
			<dl>
				{$value=''}{if isset($default_values.category)}{$value=$default_values.category}{/if}
				<dt>{t}Category{/t}</dt>
				<dd><input type="text" name="category" value="{$value}"/></dd>
			</dl>			
			{*
			<dl>
				<dt>{t}Preferred language{/t}</dt>
				<dd><input type="text" name="preferredLanguage" /></dd>
			</dl> *}			
		</div>
		
		<div style="clear: both;"></div>
		
		<a class="button" id="save_person_default_values" href="#" style="float: right;">{t}Save{/t}</a>
		
		<div style="clear: both;"></div>
	</form>
</div>

<div id="person_accordion">	
{* persons accordion items *}
	{$obj = "{t}person{/t}"}
	<h3 class="pva"><a href="#">{t}Set visible attributes{/t}</a></h3>
	<div id="person_visible_accordion">	
		{$settings_person}
	</div>
	
	<h3 class="poa"><a href="#">{t}Set attributes order{/t}</a></h3>
	<div id="person_order_accordion">
		{$settings_person_order}
	</div>

	<h3 class="paa"><a href="#">{t}Set attributes aliases{/t}</a></h3>
	<div id="person_aliases_accordion">
		{$settings_person_aliases}
	</div>
</div>