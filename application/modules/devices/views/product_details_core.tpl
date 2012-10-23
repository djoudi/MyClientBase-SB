{jdecode object=$product}

<div id="box_product_details">
	<p style="line-height: 20px; margin-bottom: 15px;">
		{if !$object->salable}
		<span style="padding-left: 5px;"><img src="/layout/images/esclamation_mark.png" /></span>
		{/if}
		<span style="font-size: 15px; padding-left: 5px;">{$object->product}</span>
	</p>
	
	<div class="grid_8">
		<div class="box" style="padding: 10px;">
			
			<table>
				<tr>
					<td style="width: 110px;">{t}Brand{/t}</td>
					<td>{$object->brand|default:'--'}</td>
				</tr>		
				<tr>
					<td style="width: 110px;">{t}Model{/t}</td>
					<td>{$object->model|default:'--'}</td>
				</tr>
				<tr>
					<td style="width: 110px;">{t}Due date{/t}</td>
					<td>{$object->code_number|default:'--'}</td>
				</tr>
			</table>
			
			<table style="margin-top: 10px;">	
				<tr>
					<td style="width: 110px;">{t}Creation date{/t}</td>
					<td>{$object->creation_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Created by{/t}</td>
					<td>{$object->creator}</td>
				</tr>			
	
				{if $object->update_date}
				<tr>
					<td style="width: 110px;">{t}Last update date{/t}</td>
					<td>{$object->update_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
				</tr>
	
				<tr>
					<td style="width: 110px;">{t}Last updated by{/t}</td>
					<td>{$object->editor}</td>
				</tr>			
				{/if}
				
			</table>
		</div>
	</div>
	
	<div class="prefix_1 grid_8">
		<div class="box" style="padding: 10px;">
	
			<p style="font-weight: bold;">{t}Details{/t}</p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$object->details|default:'---'}</p>
	
			<p style="margin-top: 20px; font-weight: bold; line-height: 18px; white-space: pre-wrap;">{t}Note{/t}</p>
			<p style="line-height: 18px; white-space: pre-wrap;">{$object->note|default:'---'}</p>
	
		</div>
	</div>
	<div style="clear: both;"></div>
</div>

{*
<div style="clear: both;"></div>
{foreach $object->_fields as $attribute => $specifics} 	
 	<dl>
	{if isset($object->$attribute)}
		 
		<dt>{$attribute}</dt> 
		<dd>{$object->$attribute}</dd> {/if}
	</dl>
{/foreach}
*} 