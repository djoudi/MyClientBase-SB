<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">

		<h3 class="title_black">{citranslate lang=$language text='users_accounts'}</h3>

		<div class="content toggle no_padding">

			<table style="width: 100%;" class="hover_links">
				<tr>
					<th scope="col" class="first">{citranslate lang=$language text='name'}</th>
					<th scope="col">{citranslate lang=$language text='email'}</th>
					<th scope="col"  class="last">{citranslate lang=$language text='phone_number'}</th>
				</tr>
				{foreach $users as $key => $user}
				
				<tr class="hoverall">
				{* <pre>{$user|print_r}</pre> *}
					<td class="first"><b><a href="/contact/details/uid/{$user['uid'][0]}" title="{citranslate lang=$language text='edit'}">{$user['givenName'][0]} {$user['sn'][0]}</a></b></td>
					<td><a href="mailto:{$user['mail'][0]}">{$user['mail'][0]}</a></td>
					<td class="last">----</td>
				</tr>
				{/foreach}
			</table>
		</div>
	</div>
</div>
