<table class="contact-details-left" style="border: 1px solid #e8e8e8; width: 98%; margin-left: 15px; margin-bottom: 3px; padding-bottom: 0px;">
{foreach $ss_contact_folder_content as $index => $item}
	<tr valign="top" style="background-color: {cycle values="#FFF,#e8e8e8"};">
		<td class="field" style="width: 30%;">
		{*
		http://tooljardev:3000/folder/a9ab79f56e1402f016798a6bfa41ec64e8d0ff54?type=file&path=coyote_willy%2Fapache-maven.pdf&hash=625c3f38aa791a274e4ac4799eb85dfb3b2d2d5f&name=apache-maven.pdf
		http://tooljardev:3000/folder/a9ab79f56e1402f016798a6bfa41ec64e8d0ff54?path=coyote_willy%2Fapache-maven.pdf&hash=625c3f38aa791a274e4ac4799eb85dfb3b2d2d5f&name=apache-maven.pdf
		*}
			<a target="_blank" href="{$ss_contacts_doc_folder_url}?type={$item->type}&{$item->url}">{$item->name}</a>
		</td>
	</tr>
{/foreach}										
</table>