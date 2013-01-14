{if isset($contact)}
	{if isset($contact->uid)}
		{$contact_ref = $contact->cn}
		{$contact_id = $contact->uid}
		{$contact_id_key = "uid"}
		{$object_type = 'person'}
	{/if}		
	
	{if isset($contact->oid)}
		{$contact_ref = $contact->o}
		{$contact_id = $contact->oid}
		{$contact_id_key = "oid"}
		{$object_type = 'organization'}
	{/if}					
{/if}

<script type="text/javascript">
	//global vars
	contact_id = "{$contact_id}";
	object_type = "{$object_type}";
	
	function shortcuts_ap(){
		if(language == 'english' || language == 'italian'){
	    	jQuery(document).bind('keydown', 'p',function (evt){
		    	toggle_animate('add_person','first_name');
		    	$('#first_name').val('');
		    	$('#last_name').val('');
				return false; 
			});

	    	jQuery(document).bind('keydown', 'o',function (evt){
		    	toggle_animate('add_organization','organization_name');
		    	$('#organization_name').val('');
				return false; 
			});

	    	jQuery(document).bind('keydown', 'a',function (evt){
	    		toggle_animate('search_organization','input_search');
		    	$('#input_search').val('');
				return false; 
			});

	    	jQuery(document).bind('keydown', 'c',function (evt){
	    		$('#create_task')[0].click();
				return false; 
			});
			
		}		

		//this will be Add Location
		if(language == 'english'){
	    	jQuery(document).bind('keydown', 'l',function (evt){
	    		$('#button_add_location').trigger("click");
				return false; 
			});
		}
		if(language == 'italian'){
	    	jQuery(document).bind('keydown', 's',function (evt){
	    		$('#button_add_location').trigger("click");
				return false; 
			});
		}		

		//this will be Edit Profile
		if(language == 'english'){
	    	jQuery(document).bind('keydown', 'e',function (evt){
	    		$('#button_edit_profile')[0].click();
				return false; 
			});
		}
		if(language == 'italian'){
	    	jQuery(document).bind('keydown', 'm',function (evt){
	    		$('#button_edit_profile')[0].click();
				return false; 
			});
		}		

		//this will Back to profile
		if(language == 'english'){
	    	jQuery(document).bind('keydown', 'b',function (evt){
	    		$('#button_back_to_profile')[0].click();
				return false; 
			});
		}		
		if(language == 'italian'){
	    	jQuery(document).bind('keydown', 't',function (evt){
	    		$('#button_back_to_profile')[0].click();
				return false; 
			});
		}		
		
	}
	
	$(document).ready(function() {

		shortcuts_ap();	

		$("#button_toggle_enable").click(function(){
			toggle_enable();
		});



		

		

		$("#button_add_person").click(function(){
			toggle_animate('add_person','first_name');
	    	$('#first_name').val('');
	    	$('#last_name').val('');
		});
		
		$('#add_person_submit').click(function(){
			window.onbeforeunload = null;
			return submit_person();
		});
		
		$('#last_name').keypress(function(event){
			
			if (event.which == 13)
			{
				window.onbeforeunload = null;
				return submit_person();
			} else {
			   return true;
			}
		});		



		
		
	
		$("#button_add_organization").click(function(){
			toggle_animate('add_organization','organization_name', '5');
			$('#organization_name').val('');
		});

		$('#add_organization_submit').click(function(){
			window.onbeforeunload = null;
			return submit_organization();
		});
				
		$('#add_organization_form').submit(function() {
			window.onbeforeunload = null;
			return submit_organization();
		});			

		

		
		
		
		$("#button_associate_organization").click(function(){
			toggle_animate('search_organization','input_search');
			$('#input_search').val('');
		});

		$('#associate_organization_submit').click(function(){
			window.onbeforeunload = null;
			return search_organization();
		});
		
		
		$('#search_organization_form').submit(function() {
			window.onbeforeunload = null;
			return search_organization();
		});





		$('#password').passStrength({
			userid: '#first_name',
		});

		$("#confirm_password").keyup(function(){

			if($('#confirm_password').val() == "") {
				$('#no_match_password').hide();
				$('#match_password').hide();
				return false;
			}
			
			if( $('#password').val() != $('#confirm_password').val()){
				$('#match_password').hide();
				$('#no_match_password').show();
				return false;	
			} else {
				$('#no_match_password').hide();
				$('#match_password').show();
				return false;
			}
		});
		
		$("#button_set_password").click(function(){
			toggle_animate('set_password','password');
	    	$('#password').val('');
	    	$('#confirm_password').val('');
		});
		
		$('#set_password_submit').click(function(){
			window.onbeforeunload = null;
			return submit_password();
		});
		
		$('#confirm_password').keypress(function(event){
			
			if (event.which == 13)
			{
				window.onbeforeunload = null;
				return submit_password();
			} else {
			   return true;
			}
		});				

	});
</script>


	
<div style="background-color: #e8e8e8; margin: 0px; margin-left: -5px; padding-top: 3px; padding-bottom: 5px; padding-left: 10px; border-bottom: 1px solid #ccc;"><h4>{t}Actions panel{/t}</h4></div>

<ul class="ap">

	<li class="ap">
		<a class="button" id="button_add_person" href="#">
			{if $language == 'english'}
				{t u=7}Add a person{/t}
			{/if}
			{if $language == 'italian'}
				{t u=10}Add a person{/t}
			{/if}				
		</a>
		<div id="add_person" title="Form" style="display: none;">		
			<form id="add_person_form" style="margin-top: 15px; padding: 0px;">
				<dl style="margin: 0px; padding: 0px; height: 30px;">
					<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px; width: 60px;">
						<label style="font-size: 10px; margin: 0px; padding: 0px;">{t}First Name{/t}:</label>
					</dt>
					<dd style="margin: 0px; padding: 0px;"><input title="{t}first name{/t}" size="35" type="text" name="firstname" id="first_name" style="width: 170px;" /></dd>
				</dl>
				<dl>
					<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px; width: 60px;">
						<label style="font-size: 10px; margin: 0px; padding: 0px;">{t}Last Name{/t}:</label>
					</dt>
					<dd style="margin: 0px; padding: 0px;">
						<input title="{t}last name{/t}"  size="35" type="text" name="lastname" id="last_name" style="width: 170px;"/>
						<a class="button" id="add_person_submit" href="#">{t}Ok{/t}</a>
					</dd>
				</dl>
			</form>
		</div>
	</li>
	
	<li class="ap">
		<a class="button" id="button_add_organization" href="#" >
			{if $language == 'english'}
				{t u=8}Add an organization{/t}
			{/if}
			{if $language == 'italian'}
				{t u=10}Add an organization{/t}
			{/if}						
		</a>
		<div id="add_organization" title="Form" style="display: none;">
			<form id="add_organization_form" style="margin-top: 15px; padding: 0px;">
				<dl style="margin: 0px; padding: 0px; height: 30px;">
					<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px;"><label style="font-size: 10px; margin: 0px; padding: 0px;">{t}Organization{/t}:</label></dt>
					<dd style="margin: 0px; padding: 0px;">
						<input title="{t}organization name{/t}" size="55" type="text" name="organizationname" id="organization_name" style="width: 150px;"/>
						<a class="button" id="add_organization_submit" href="#">{t}Ok{/t}</a>
					</dd>
				</dl>
			</form>
		</div>				
	</li>
	
	<li class="ap">
		<a class="button" href="/contact/all_people" >{t}Show all people{/t}</a>
	</li>
	
	<li class="ap">
		<a class="button" href="/contact/all_organizations" >{t}Show all organizations{/t}</a>
	</li>
	
	<li class="ap">
		<a class="button" href="/contact/by_location" >{t}Show by location{/t}</a>
	</li>						
	
</ul>


{if isset($contact_id)  && $contact->enabled == 'TRUE'}
	
	<ul class="ap">
		<hr style="margin-top: 10px; margin-bottom: 10px;"/>
			
		{if !$profile_view}
			<li class="ap"><a class="button" id="button_back_to_profile" href="/contact/details/{$contact_id_key}/{$contact_id}">{t u=1}Back to profile{/t}</a></li>
		{/if}
		
		{if $profile_view && $contact_id != ""}
			<li class="ap"> <a class="button" id="button_edit_profile" href="/contact/form/{$contact_id_key}/{$contact_id}">{t u=1}Edit profile{/t}</a></li>
		{/if}
		
		{if $contact->enabled=='TRUE'}
			{if $profile_view && $contact_id != ""}
				<li class="ap">
					{assign u 5}
					{if $language == 'italian'} {assign u 10} {/if}
					<a class="button" id="button_add_location" href="#" onClick="jqueryForm({ 'form_type':'form','object_name':'location','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t u=$u}Add location{/t}</a>
				</li>
			{/if}
			
			{if $object_type == 'person'}
			<li class="ap">
				<a class="button" id="button_associate_organization" href="#">{t u=1}Associate Organization{/t}</a>
				<div id="search_organization" title="Form" style="display: none;">
					<form id="search_organization_form" style="margin-top: 15px; padding: 0px;">
						<dl style="margin: 0px; padding: 0px; height: 30px;">
							<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px;"><label style="font-size: 10px; margin: 0px; padding: 0px;">{t}Organization{/t}:</label></dt>
							<dd style="margin: 0px; padding: 0px;">
								<input title="{t}search for name, vat, phone, email, website{/t}" size="55" type="text" name="input_search" id="input_search" style="width: 150px;"/>
								<a class="button" id="associate_organization_submit" href="#">{t}Ok{/t}</a>
							</dd>
						</dl>
					</form>
				</div>
			</li>
			{/if}
			
			{if $profile_view && $object_type == 'person' && $contact_id != "" && $contact->enabled == 'TRUE'}
				<li class="ap">
					<a class="button" id="button_set_password" href="#">{t}Set password{/t}</a>
					<div id="set_password" title="Form" style="display: none;">		
						<form id="set_password_form" style="margin-top: 15px; padding: 0px;">
						{if $object_type=='person'}
							<input type="hidden" id="contact_uid" name="uid" value="{$contact_id}"/>
							<input type="hidden" id="contact_email" name="email" value="{$contact->mail}"/>
						{/if}
						{if $object_type=='organization'}
							<input type="hidden" id="contact_oid" name="oid" value={$contact_id}/>
							<input type="hidden" id="contact_email" name="email" value="{$contact->omail}"/>
						{/if}
						
							<dl style="margin: 0px; padding: 0px; height: 35px;">
								<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px; width: 50px;">
									<label style="font-size: 10px; margin: 0px; padding: 0px;">{t}Password{/t}:</label>
								</dt>
								<dd style="margin: 0px; padding: 0px;">
									<input title="{t}password{/t}" size="20" type="password" name="userPassword" id="password" style="width: 120px;" />
								</dd>
							</dl>
							<dl style="margin: 0px; padding: 0px; height: 35px;">
								<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px; width: 50px;">
									<label style="font-size: 10px; margin: 0px; padding: 0px;">{t}Confirm{/t}:</label>
								</dt>
								<dd style="margin: 0px; padding: 0px;">
									<input title="{t}last name{/t}"  size="20" type="password" name="confirm_password" id="confirm_password" style="width: 120px;"/>
									<span id="no_match_password" class="pwdtest badPass" style="display: none;"><span>{t}No match{/t}</span></span>
									<span id="match_password" class="pwdtest strongPass" style="display: none;"><span>{t}Matches{/t}</span></span>								
								</dd>
							</dl>
							<dl>
								<a style="float: right; margin-top: 5px;" class="button" id="set_password_submit" href="#">{t}Ok{/t}</a>
								<div style="clear: both;"></div>						
							</dl>
						</form>
					</div>
				</li>
			{/if}				
			
			{if isset($extra_tabs)}
				{foreach $extra_tabs as $key => $extra_tab}
					{if $extra_tab.buttons}
						{foreach $extra_tab.buttons as $key => $button}
							{* {$button.onclick} *}
							<li class="ap"><a class="button" href="{$button.url}" id="{$button.id}"  onClick='{$button.onclick}'>{t u=1}{$button.label}{/t}</a></li>
						{/foreach}
					{/if}
				{/foreach}
			{/if}				
			
			{if $invoice_module_is_enabled}
				<li class="ap"><a class="button" href="/tasks/form/{$contact_id_key}/{$contact_id}?btn_add=true">{t}Create a task{/t}</a></li>
			{/if}
			
			{if $invoice_module_is_enabled}
				<li class="ap"><a class="button" href="/invoices/create/{$contact_id_key}/{$contact_id}/quote/">{t}Create freehand quote{/t}</a></li>
				<li class="ap"><a class="button" href="/invoices/create/{$contact_id_key}/{$contact_id}">{t}Create freehand invoice{/t}</a></li>
			{/if}
			<!--
			<li>
				<form method="post" action="" style="display: inline;">
					<input type="submit" name="btn_edit_client" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='edit_client'}" />
	                <input type="submit" name="btn_add_invoice" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_invoice'}" />
					<input type="submit" name="btn_add_quote" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_quote'}" />
				</form>
			</li>
			 -->
		{/if}	
		
	</ul>
{/if}

{if $contact_id != ""}
	<ul class="ap">
		<hr style="margin-top: 10px; margin-bottom: 10px;"/>
		{if $tooljar_module_is_enabled && $object_type == 'organization' && $contact->enabled == 'TRUE'}
			<li class="ap"><a class="button" href="#" onClick="set_as_my_tj_organization({ 'oid':'{$contact_id}','hash':'set_here_the_hash' })">{t}This is my organization{/t}</a></li>
		{/if}
		
		{if $profile_view}
			<li class="ap">
				{if $contact->enabled == 'TRUE'}
					{assign var="label" value="Disable"}
				{else}
					{assign var="label" value="Enable"}
				{/if}
				<a class="button" id="button_toggle_enable" href="#">{t}{$label}{/t}</a>
			</li>
		{/if}
	</ul>	
{/if}