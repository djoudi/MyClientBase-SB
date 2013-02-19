{literal}
<script type="text/javascript">
	$(document).ready(function() {
		shortcuts_top_menu();	
	
		$('#a_google_tip').bubbletip($('#tip_google'), { deltaDirection: 'right', calculateOnShow: true });
	});
</script>
{/literal}

<h4>{t}Google Shared Contacts{/t}</h4>

<form method="post" action="/google/save_settings" class="box settings" style="padding: 10px;">
	
	<p>
		{t}By enabling Google Contacts synchronization, all the contacts will be synchronized with you Google Account{/t}
		<a id="a_google_tip" href="#"><img style="margin-left: 10px;" src="/layout/images/question_mark.png" /></a>
	</p>
	
	<div id="tip_google" style="display:none;">
		<p>{t}Google Contacts synchronization will provide{/t}:
		<ul>
			<li>- {t}Email autocomplete when you write a new email{/t}</li>
			<li>- {t}Possibility to import any business contact into your personal contacts. Consequently you'll find all your personal contacts inside you mobile phone address book{/t}</li>
			<li>- {t}Possibility to import any business contact into your Google+ account{/t}</li>
			<li>- {t}Possibility to import any business contact into your LinkedIn account{/t}</li>
			<li>- {t}Capability to look for any business contact from the mobile phone even if it's not in your personal contacts{/t}</li>
			<li>- {t}Possibility to use{/t} <a href="http://support.google.com/a/bin/answer.py?hl=en&answer=115739&ctx=cb&src=cb&cbid=-ye89m9c89gvz&cbrank=2" target="_blank">Google Secure Data Connector</a> (SDC) {t}to connect gadgets, applications, and spreadsheets to your data{/t}</li>
		</ul>
		</p>
		
		{$video_code="ZdVPq6M-WKE"}
		{if $language == 'italian'}{$video_code="91wq_8yRne4"}{/if}
		<p style="float: right; margin-top: 15px;"><a class="tj_videos button" href="{$video_code}">{t}More details{/t}</a></p>
		<div style="clear: both;"></div>	
	</div>	
	
	<dl>
	    <dt>{t}Google domain{/t}:</dt>
	    <dd><input type="text" name="google_domain" value="{$google_domain}" size="100" maxlenght="100" style="width: 500px;"></dd>
	</dl>
	
	<dl>
	    <dt>{t}Admin email{/t}:</dt>
	    <dd><input type="text" name="google_admin_email" value="{$google_admin_email}" size="100" maxlenght="100" style="width: 500px;"></dd>
	</dl>
	
	<dl>
	    <dt>{t}Password{/t}:</dt>
	    <dd><input type="password" name="google_admin_password" value="{$google_admin_password}"  size="100" maxlenght="100" style="width: 500px;"></dd>
	</dl>
	
	<dl>
	    <dt>{t}Confirm password{/t}:</dt>
	    <dd><input type="password" name="google_admin_confirm_password" value="{$google_admin_password}"  size="100" maxlenght="100" style="width: 500px;"></dd>
	</dl>
	
	<dl>
	    <dt>{t}Enable synchronization{/t}?:</dt>
	    {if $google_contact_sync == "true"}
	    	{$checked='checked="checked"'}
	    {else}
			{$checked=''}
	    {/if}
	    <dd><input type="checkbox" name="google_contact_sync" {$checked}></dd>
	</dl>	
	
	<input  class="button" style="float: right; margin-top: 10px; margin-right: 10px;" type="submit" name="save" value="{t}Save{/t}" />
	
	<div style="clear: both;"></div>

</form>

<div style="clear: both"></div>

