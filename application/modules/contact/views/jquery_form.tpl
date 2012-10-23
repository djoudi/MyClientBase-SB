{*
<pre style="font-size: 11px;">
{$object|print_r}
</pre>
*}

<script type="text/javascript">
	formname = "{$form_name}";
	$(document).ready(function() {
	
		$('#'+formname).live('keyup', function(e){
			
			if (e.keyCode == 13) {
			    $('#mydialog').dialog("close");
			  }
		});
	});
</script>

	
{if isset($object->aliases)} {$aliases = $object->aliases} {/if}

<div style="width: 500px;" id="{$div_id}" title="Form">
	<p><span class="dark_red">*</span> {t}means mandatory field{/t}</p>

	<form name="{$form_name}" id="{$form_name}">

		{* hidden fields *}
		{foreach $object->hidden_fields as $key => $property_name}
			<input type="hidden" name="{$property_name}" id="{$property_name}" value="{$object->$property_name}" />
		{/foreach}
		
		{* visible fields *}
		{foreach $object->show_fields as $key => $property_name}
			{if {preg_match pattern="^loc" subject=$property_name} and {$property_name != "locId"}}
				<dl>
					<dt style="width: 100px;">
						<label for="{$property_name}">
						{if isset($aliases) && isset($aliases.$property_name)}
							{t}{$object->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
						{else}
							{t}{$property_name}{/t}
						{/if}
						{if $object->properties[$property_name]['required'] == 1}
						<span class="dark_red">*</span>
						{/if}
						</label>
					</dt>
					<dd><input style="margin-right: 5px; width: 250px;" type="text" name="{$property_name}" id="{$property_name}" {if $object->$property_name != ''} value="{$object->$property_name}"{/if} /></dd>
				</dl>
			{/if}
		{/foreach}		
	</form>
</div>	