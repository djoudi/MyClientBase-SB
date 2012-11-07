{if isset($contact)}
	{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
		{$contact_ref = $contact->cn}
		{$contact_id = $contact->uid}
		{$contact_id_key = "uid"}
		{$object_type = 'person'}
	{/if}		
	
	{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
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
</script>

{literal}
<script type="text/javascript">
	function shortcuts_ap(){
		if(language == 'english' || language == 'italian'){
	    	jQuery(document).bind('keydown', 'p',function (evt){
		    	toggle_animate('add_person','first_name');
		    	$('#first_name').val('');
		    	$('#last_name').val('');
				return false; 
			});
		}

		if(language == 'english' || language == 'italian'){
	    	jQuery(document).bind('keydown', 'o',function (evt){
		    	toggle_animate('add_organization','organization_name');
		    	$('#organization_name').val('');
				return false; 
			});
		}

		if(language == 'english' || language == 'italian'){
	    	jQuery(document).bind('keydown', 'a',function (evt){
	    		toggle_animate('search_organization','input_search');
		    	$('#input_search').val('');
				return false; 
			});
		}		

		//this will be Add Location
		if(language == 'english'){
	    	jQuery(document).bind('keydown', 'l',function (evt){
				return false; 
			});
		}		
	}
	
	$(document).ready(function() {

		shortcuts_ap();	

		$("#add_person_button").click(function(){
			toggle_animate('add_person','first_name');
	    	$('#first_name').val('');
	    	$('#last_name').val('');
		});
		
		$('#add_person_submit').click(function(){
			return submit_person();
		});
		
		$('#last_name').keypress(function(event){
			
			//this intercepts the press enter on the second input box of the person form and performs a submit
			if (event.which == 13)
			{
				return submit_person();
			} else {
			   return true;
			}
		});		

		
		//--------------
		
		
		$("#add_organization_button").click(function(){
			toggle_animate('add_organization','organization_name', '5');
			$('#organization_name').val('');
		});

		$('#add_organization_submit').click(function(){
			return submit_organization();
		});
				
		$('#add_organization_form').submit(function() {
			return submit_organization();
		});			

		
		//--------------
		
		
		$("#associate_organization_button").click(function(){
			toggle_animate('search_organization','input_search');
			$('#input_search').val('');
		});

		$('#associate_organization_submit').click(function(){
			return search_organization();
		});
		
		
		$('#search_organization_form').submit(function() {
			return search_organization();
		});

	});
</script>
{/literal}

	
	<h4>{t}Actions panel{/t}</h4>
	
	<ul class="ap">

		<li class="ap">
			<a class="button" id="add_person_button" href="#">
				{if $language == 'english'}
					{t u=7}Add a person{/t}
				{/if}
				{if $language == 'italian'}
					{t u=14}Add a person{/t}
				{/if}				
			</a>
			<div id="add_person" title="Form" style="display: none;">		
				<form id="add_person_form" style="margin-top: 15px; padding: 0px;">
					<dl style="margin: 0px; padding: 0px; height: 30px;">
						<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px;"><label style="font-size: 10px; margin: 0px; padding: 0px;">{t}First Name{/t}:</label></dt>
						<dd style="margin: 0px; padding: 0px;"><input title="{t}first name{/t}" size="35" type="text" name="firstname" id="first_name" style="width: 170px;" /></dd>
					</dl>
					<dl>
						<dt style="margin: 0px; padding: 0px; padding-right: 5px; height: 30px;"><label style="font-size: 10px; margin: 0px; padding: 0px;">{t}Last Name{/t}:</label></dt>
						<dd style="margin: 0px; padding: 0px;">
							<input title="{t}last name{/t}"  size="35" type="text" name="lastname" id="last_name" style="width: 170px;"/>
							<a class="button" id="add_person_submit" href="#">{t}Ok{/t}</a>
						</dd>
					</dl>
				</form>
			</div>
		</li>
		
		<li class="ap">
			<a class="button" id="add_organization_button" href="#" >
				{if $language == 'english'}
					{t u=8}Add an organization{/t}
				{/if}
				{if $language == 'italian'}
					{t u=14}Add an organization{/t}
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


{if isset($contact_id)}
	<hr>
	<ul class="ap">
		
		{if !$profile_view}
			<li class="ap"><a class="button" id="back_to_profile" href="/contact/details/{$contact_id_key}/{$contact_id}">{t}Back to profile{/t}</a></li>
		{/if}
		
		{if $profile_view && $contact_id != ""}
			<li class="ap"> <a class="button" id="edit_profile" href="/contact/form/{$contact_id_key}/{$contact_id}">{t}Edit profile{/t}</a></li>
		{/if}
		
		{if $contact->enabled=='TRUE'}
			{if $profile_view && $contact_id != ""}
				<li class="ap">
					{assign u 5}
					{if $language == 'italian'} {assign u 10} {/if}
					<a class="button" href="#" onClick="jqueryForm({ 'form_type':'form','object_name':'location','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t u=$u}Add location{/t}</a>
				</li>
			{/if}
			
			{if $object_type == 'person'}
			<li class="ap">
				<a class="button" id="associate_organization_button" href="#">{t u=1}Associate Organization{/t}</a>
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

{if isset($contact_id)}
	{if $tooljar_module_is_enabled && $object_type == 'organization'}
	
		<ul class="ap" >
			<li class="ap"><a class="button" href="#" onClick="set_as_my_tj_organization({ 'oid':'{$contact_id}','hash':'set_here_the_hash' })">{t}This is my organization{/t}</a></li>
		</ul>
	{/if}
{/if}