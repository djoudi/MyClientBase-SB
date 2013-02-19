{assign header_file "{$fcpath}application/views/header.tpl"}
{assign top_file "{$fcpath}application/views/top.tpl"}
{assign top_menu_file "{$fcpath}application/views/top_menu.tpl"}
{assign footer_file "{$fcpath}application/views/footer.tpl"}

{include file="$header_file"}
{include file="$top_file"}
{include file="$top_menu_file"}

<script type="text/javascript">

	$(window).bind('beforeunload', function() {
		return "You have unsaved changes";
	})
</script>

{literal}
<script type="text/javascript">

	//this is used by autocomplete
	function split( val ) {
	    return val.split( /,\s*/ );
	}
	
	//this is used by autocomplete	
	function extractLast( term ) {
	    return split( term ).pop();
	}

	function show_validation_icon(valid, element_id, message){
		if(valid){
			$('#validation_' + element_id).html('<img src="/layout/images/success.png" />');
		} else {
			$('#validation_' + element_id).html('<img src="/layout/images/error.png" /> ' + message);
		}		
	}

	
	$(document).ready(function() {

		$("[id^=a_autocomplete_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_autocomplete_'+item_num), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});

		$("[id^=a_file_]").each(function(){
			var item_num = this.id.split('_');
			item_num = item_num[2];
			$('#'+this.id).bubbletip($('#tip_file_'+item_num), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
		});
				
		$('.phone').keyup(function() {
			var phone = $(this).val();
			if(phone == '') {
				$('#validation_' + $(this).attr("id")).html('');
				return false;
			}
			valid = validate_phone(phone);
			show_validation_icon(valid, $(this).attr("id"), 'Standard: +{country_code}{prefix}{phone_number} Ex: +3906123456789');
		});

		$('.url').bind("keyup input", function() {
			var url = $(this).val();
			if(url == '') {
				$('#validation_' + $(this).attr("id")).html('');
				return false;
			}
			valid = validate_url(url);
			show_validation_icon(valid, $(this).attr("id"), 'Ex: http://www.website.com');
		});
				
		$('.email').keyup(function() {
			var email = $(this).val();
			if(email == '') {
				$('#validation_' + $(this).attr("id")).html('');
				return false;
			}
			valid = validate_email(email);
			show_validation_icon(valid, $(this).attr("id"), 'Ex: john@website.com');
		});
		
		addAutoComplete('#businessCategory');
		addAutoComplete('#businessActivity');
		addAutoComplete('#businessAudience');		 
		addAutoComplete('#category');
		//addAutoComplete('#preferredLanguage');

		$('#contact_save').live("click", function() {			
			window.onbeforeunload = null;
			$('#form_contact').submit();
		});				
	});

</script>
{/literal}
{* <pre>{$contact|print_r}</pre> *}


{* PREPARING THE FORM CONTENT *}
{foreach $contact->properties as $property => $details}
		
	{$type = ''}
	
	{* don't show fields that are not specified as visible in the configuration file  *}
	{if !in_array($property, $contact->show_fields)} {continue}	{/if}
	
	{* If the field is in the "visible fields" but it's also in the "hidden fields" than do not consider it a form field. Hidden fields are treated later. *}
	{if in_array($property, $contact->hidden_fields)} {continue} {/if}	
	
	{* don't show fields which are not supposed to be modified by the customer  (it's stored in LDAP)  *}
	{if $details['no-user-modification'] == 1} {continue} {/if}		
	

	{* for debugging 
	<h1>{$property}</h1>
	<pre>{$details|print_r}</pre>

	{if $property == 'category'}
		<h4>{$property}</h4>
		<pre style="font-size: 12px;">{$details|print_r}</pre>
	{/if}
	*}
	
	
	{$fields[$property]['desc'] = $details["desc"]}
	{$fields[$property]['syntax'] = $details["syntax"]}
	
	{$fields[$property]['multi-value'] = false} {* default. The multivalue property can be used much more than I do now but I need to figure out how to deal with multivalues in the view perspective *}
	
	{$fields[$property]['autocomplete'] = false} {* default *}
	{if $property == "businessActivity"}{$fields[$property]['autocomplete'] = true}{/if}
	{if $property == "businessAudience"}{$fields[$property]['autocomplete'] = true}{/if}
	{if $property == "businessCategory"}{$fields[$property]['autocomplete'] = true}{/if}
	{if $property == "category"}{$fields[$property]['autocomplete'] = true}{/if}
	{*{if $property == "preferredLanguage"}{$fields[$property]['autocomplete'] = true}{/if}*}
		
	
	{* at the end of the loop the array "fields" will contain all the items and values for the form *}
	{$fields[$property]["value"] = $contact->$property}
	
	{* show an asterisk when a field is required  (it's stored in LDAP) *}
	{if $details["required"]}
		{$fields[$property]["required"] = '<em style="color: red;">*</em> '}
	{else}
		{$fields[$property]["required"] = ' '}
	{/if}
	
	{* FOCUS ON FORM ITEM TYPE: possible types: button,checkbox,file,hidden,image,password,radio,reset,submit,text *}

	{* check if it's a boolean field and render it as a checkbox *}
	{* {if {preg_match pattern="boolean" subject=$details['desc']}} *}
	{if $details['boolean'] == 1}			
		{$type = 'checkbox'}
		{if $fields[$property]['value'] == 'TRUE'}
			{$fields[$property]["checked"] = "checked"}
		{else}
			{$fields[$property]["checked"] = ""}
		{/if}
	{/if}

	{* check if it's a binary field and render it as a checkbox *}
	{if $details['binary'] == 1}							
		{$type = 'file'}
		{$fields[$property]["value"] = "{t}browse{/t}"}
		{$form_addon = 'enctype="multipart/form-data"'}
	{/if}
	
	{if $type == ''}
		{$type = 'text'}
		
		{if $details["max-length"] <= 255}
		
			{$type = 'text'}
			
		{else}
		
			{if $details['syntax'] == '1.3.6.1.4.1.1466.115.121.1.15'}
				{$type = 'textarea'}
			{/if}
			{*
			{if $details['syntax'] == '1.3.6.1.4.1.1466.115.121.1.26'}
				{$property} is a multivalue: what to do? <br/>
			{/if}
			*}
		{/if}
		
	{/if}	
	
	{* particular case *}
	{if $property == 'category'} {$type = 'text'} {/if}
	
	{$fields[$property]["type"] = $type}
	
	{* get the max lenght of the field (it's stored in LDAP) *}
	{if $type == 'text'}
	
		{$max_length = 255}	
		{$max_size = 45}
		{if $details["max-length"]}
			{$fields[$property]["max_length"] = $details["max-length"]} {* max num of character that an input field can contain *}
			
			{if $details["max-length"] > $max_size}
				{$fields[$property]["max_size"] = $max_size}
			{else}
				{$fields[$property]["max_size"] = $details["max-length"]}
			{/if}
						
		{else}
			{$fields[$property]["max_length"] = $max_length}
			{$fields[$property]["max_size"] = $max_size}
		{/if}
		
	{/if}												
	
{/foreach}

{* LEFT COLUMN *}
<div class="grid_18">

	<div class="box">
			
		{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
			{$contact_ref = $contact->cn}
			{$contact_id = $contact->uid}
			{$contact_id_key = "uid"}
			
			{* <h4 style="padding-left: 5px;">{$contact->cn}</h4> *}
		{/if}		
		
		{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
			{$contact_ref = $contact->o}
			{$contact_id = $contact->oid}
			{$contact_id_key = "oid"}
			
			{* <h4 style="padding-left: 5px;"> {$contact->o} </h4> *}
		{/if}
		
							
		<form id="form_contact" method="post" action={$form_url} {$form_addon} style="padding-bottom: 20px; padding-left: 10px; padding-right: 10px; margin-top: 10px;">
	
		{* <pre>{$fields|print_r}</pre> *} 
		
		{* print out hidden fields regardless they are visible or not *}
		{if is_array($contact->hidden_fields)}
			{foreach $contact->hidden_fields as $key => $property}
				<input type="hidden" name="{$property}" id="{$property}" value="{$contact->$property}" /> 
			{/foreach}
		{/if}							
		
		{* outputs the visible fields accordingly to the order provided in the system settings *}
		{foreach $contact->show_fields as $key => $property name="foreach_property"}
			{* output the "tab info" fields => all the fields *}
			{if $fields[$property]}
				{* here some GUI filters *}
				
				{* <dl style="float: left; width: 100%; background-color: {cycle values="#FFF,#f3f3f3"};"> *}
				<dl style="border-bottom: 1px dashed #ccc; min-height: 30px; padding: 0px; margin: 0px; padding-bottom: 7px;">
				
					{* aliases substitution *}
					{if isset($contact->aliases) and isset($property) and isset($contact->aliases.$property)}
						{$fieldname = $contact->aliases.$property}
					{else}
						{$fieldname = $property}
					{/if}					

					<dt style="width: 230px; padding-top: 10px;">{t}{$fieldname|capitalize|regex_replace:"/_/":" "}{/t}{$fields[$property]["required"]}:</dt>
					
					{$checked = ""}
					{if isset($fields[$property]["checked"])} 
						{* $fields[$property]|print_r *}
						{$checked = $fields[$property]["checked"]}
					{/if} 
					
					{if $property == 'sn' || $property == 'givenName' || $property == 'o'}
						{$disabled = 'disabled'}
						<input type="hidden" name="{$property}" id="{$property}" value="{$fields[$property]["value"]}" />
					{else}
						{$disabled = ''}
					{/if}
					
					{if $fields[$property]["type"] == "file"}
						<dd>
							<input title="{t}{$fields[$property]["desc"]}{/t}" type="{$fields[$property]["type"]}" name="{$property}" />
							
							<a id="a_file_{$num}" href="#">
								<img alt="{t}Upload a picture{/t}" src="/layout/images/question_mark.png" />
							</a>
							<div id="tip_file_{$num}" style="display: none;">
								<p>{t}You can upload a picture for your contact having following requirements{/t}</p>
								<p>{t}Max size{/t}: {$upload_settings.max_size} KB</p>
								<p>{t}Max width{/t}: {$upload_settings.max_width} pixel</p>
								<p>{t}Max height{/t}: {$upload_settings.max_height} pixel</p>
							</div>
						</dd>
					{else}
						<dd>
							{$item_button=''}
							{$field_value=$fields[$property]["value"]}
							
							{if $fields[$property]["type"]=="checkbox"}
								{$field_value='TRUE'}
								<input  title="{t}{$fields[$property]["desc"]}{/t}" type="{$fields[$property]["type"]}" name="{$property}" id="{$property}" value="{$field_value}" {$checked} {$disabled} />
							{/if}
							{* <pre>{$fields[$property]|print_r}</pre> *}
							
							{if $fields[$property]["type"]=="text"}
								{assign class ''}
								
								{if $fields[$property]['syntax'] == '1.3.6.1.4.1.1466.115.121.1.40'}
									{$fields[$property]["type"] = 'password'}
								{/if}
								
								{if $property == 'birthDate'}{assign class 'class="datepicker"'}{/if}
								
								{if $fields[$property]["syntax"] == '1.3.6.1.4.1.1466.115.121.1.22' || $fields[$property]["syntax"] == '1.3.6.1.4.1.1466.115.121.1.50' || $fields[$property]["syntax"] == '1.3.6.1.4.1.1466.115.121.1.51' || $fields[$property]["syntax"] == '1.3.6.1.4.1.1466.115.121.1.52'}
									{assign class 'class="phone"'}
									{$fields[$property]["max_length"] = 16}
									{$fields[$property]["max_size"] = 16}
								{/if}
								
								{if $property == 'oURL' || preg_match('/.*(?:URI)$/', $property)}
									{assign class 'class="url"'}
									{$fields[$property]["max_length"] = 254}
									{$fields[$property]["max_size"] = $max_size}
									{if $field_value!=""}
										{$item_button="<a href=\"{$field_value}\" target=\"_blank\" class=\"button\">{t}Go{/t}</a>"}
									{/if}
								{/if}						
								
								{if $property == 'mail' || $property == 'omail'}
									{assign class 'class="email"'}
									{$fields[$property]["max_length"] = 254}
									{$fields[$property]["max_size"] = $max_size}
								{/if}
								{if $property == 'businessCategory'}
								{/if}

								{*{$property} {$class} *}
								
								<input {$class} style="margin: 0px;" title="{t}{$fields[$property]["desc"]}{/t}  ({$fields[$property]["max_length"]} {t}char.{/t})" maxlength="{$fields[$property]["max_length"]}" size="{$fields[$property]["max_size"]}" type="{$fields[$property]["type"]}" name="{$property}" id="{$property}" value="{$field_value}" {$disabled} /> 
								{if $fields[$property]["autocomplete"]}
									{$num={counter}}
									<a id="a_autocomplete_{$num}" href="#">
										<img alt="{t}This field is multivalue and autocomplete{/t}" src="/layout/images/question_mark.png" />
									</a>
									<div id="tip_autocomplete_{$num}" style="display: none;">
										<p style="font-weight: bold;">{t}Autocomplete field{/t}</p>
										<p style="margin-top: 5px;">{t}This field can contain multiple values{/t}: {t}it will use the comma as a separator between values{/t}.</p>
										<p style="margin-top: 5px;">{t}When you type, it will also show you values previously used for this field{/t}.</p>
										<p style="margin-top: 5px;">{t}If you type a new value and then save the form, the new value will be available for all the other contacts{/t}.</p>
									</div>
								{/if}
								<span id="validation_{$property}" style="font-size: 11px;"></span>{* validation message container: do not remove *}
								{$item_button}
								
							{/if}
							
							{if $fields[$property]["type"]=="textarea"}
								
								<textarea rows="7" cols="60" name="{$property}" id="{$property}" placeholder="{t}{$fields[$property]["desc"]}{/t}" {$disabled}>{$field_value}</textarea>
							{/if}
							 
							{* set the first not disabled field as the focused one *}
							{if $disabled == ''}
								{if !isset($focus_set)}
									<script type="text/javascript"> $("#{$property}").focus(); </script>
									{$focus_set = true}
								{/if}
							{/if}
						</dd>
					{/if}
				</dl>										
			{/if}
		{/foreach}

		<dl style="padding-top: 20px;">
			<span style="color: red;">*</span> <span style="text-size: 12px; margin-bottom: 5px;">{t}means mandatory field{/t}</span>
			<input type="hidden" name="contact_save" value="true" />
			<a class="button" href="#" style="float: right;  margin-right: 20px;" id="contact_save">{t}Save{/t}</a>
		</dl>
		
		</form>
	</div>									
</div>
	

{* RIGHT COLUMN *}
<div class="grid_6">
	<div class="box" style="padding-left: 5px;">{include 'actions_panel.tpl'}</div>
</div>		

{include file="$footer_file"}