{jdecode object=$asset}

<div id="box_asset_details">
	
	<div class="grid_17 box" style="margin: 15px; padding-bottom: 10px;"> 
		
		<div class="box_header" style="margin-bottom: 10px;"><h4>{$object->description}</h4></div>
	
		<div class="grid_8">

			<div style="padding: 10px;">
				
				<table>
					<tr>
						<td style="width: 110px;">{t}Category{/t}</td>
						<td>{t}{$object->category|replace:'_':' '|default:'--'}{/t}</td>
					</tr>		
					
					<tr>
						<td style="width: 110px;">{t}Type{/t}</td>
						<td>{$object->type|default:'--'}</td>
					</tr>
					
					<tr>
						<td style="width: 110px;">{t}Owner{/t}</td>
						{assign var="href" value="contact/details/{$object->contact_id_key}/{$object->contact_id}#tab_Assets"}
						<td style="">{anchor("{$href}","{$object->contact_name}",'style="color: #207fca;"')}</td>
					</tr>									
					
				</table>
				
				<table style="margin-top: 40px;">	
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
		
		<div class="grid_8" style="margin-left: 20px;">
			
			<div style="padding: 10px;">

				<table>		
	
					<tr>
						<td style="width: 110px;">{t}Purchased{/t}</td>
						<td>{$object->purchase_date|date_format:"%a %Y-%m-%d"|default:'--'}</td>
					</tr>
					
					<tr>
						<td style="width: 110px;">{t}Price{/t}</td>
						<td style="text-align: right;">{$object->price|default:'--'}</td>
					</tr>					
					
					<tr>
						<td style="width: 110px;">{t}Value{/t}</td>
						<td style="text-align: right;">{$object->value|default:'--'}</td>
					</tr>					
					
				</table>			
				
				<p style="font-weight: bold; margin-top: 20px;">{t}Details{/t}</p>
				<p style="line-height: 18px; white-space: pre-wrap;">{$object->details|default:'---'}</p>

				<p style="font-weight: bold; margin-top: 20px;">{t}Insurance{/t}</p>
				<p style="line-height: 18px; white-space: pre-wrap;">{$object->insurance|default:'---'}</p>				
			</div>
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