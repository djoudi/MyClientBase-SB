{literal}
<script type="text/javascript">
	$(document).ready(function(){
		//shows the spinner in the notification area everytime an ajax request is triggered
		$('#spinner').ajaxStart(function() {
			$('#spinner').html('<img src="/layout/images/spinner.gif"/>');
		});
		
		$('#spinner').ajaxStop(function() {
			$(this).html('');
		});

		$('#a1_right').bubbletip($('#tip1_right'), { deltaDirection: 'right', calculateOnShow: true, bindShow: 'click', delayHide: 1500 });
	});
</script>
{/literal}	

<div class="container_24">
	{* top anchor *}
	<div class="grid_6" style="padding-top: 5px;">
		
		<img src="/layout/images/mcbsb_logo.png" />
		
		<div class="box" style="margin-top: 15px; height: 30px; padding-top: 14px; padding-left: 5px; padding-right: 5px;">
			<div style="text-align: center;">
				{if $language=="english"}
					{anchor("http://tooljar.biz","{t}Tooljar{/t}",'class="button"')}
					{anchor("http://tooljar.biz/community","{t}Community{/t}",'class="button"')}
				{/if}		
				
				{if $language=="italian"}
					{anchor("http://tooljar.biz?language=it_IT","{t}Tooljar{/t}",'class="button"')}
					{anchor("http://tooljar.biz/community?language=it_IT","{t}Community{/t}",'class="button"')}
				{/if}
				
		   		{if $language == 'english'} {$href="gydKx6aAXVs"} {/if}
				{if $language == 'italian'} {$href="J-m8Hw4x14o"} {/if}				
				
				{* do not use anchor here otherwise videos stop working *}
				<a class="tj_videos button" href="{$href}">{t}Help{/t}</a>
				
				{anchor("logout","{t}Logout{/t}",'class="button"')}
			</div>
		</div>
		
	</div>
	
	<div class="grid_6">
		<div class="box {if in_array('tj_admin',$user->member_of_groups)}b_light_green{/if}" style="padding: 3px; min-height: 54px; max-height: 54px; overflow: auto; font-size: 11px;">
			<p>
				{t}User{/t}:&nbsp;
				{if in_array('tj_admin',$user->member_of_groups)}
					{$user->first_name} {$user->last_name}
				{else}
					<a href="/contact/details/uid/{$user->id}">{$user->first_name} {$user->last_name}</a>
				{/if}
			</p>
			
			<p style="margin-top: 7px;">
				{t}Groups{/t}:&nbsp;
				
				{foreach $user->groups as $key => $group}
					"{$group->description}"&nbsp;
				{/foreach}
			</p>
			
			{if $user->team && $mcbsb_org_oid && !in_array('tj_admin',$user->member_of_groups)}
			<p style="margin-top: 7px;">
				{t}You have{/t} {($user->team|count - 1)} {t}collegues{/t} 
			</p>
			{/if}			
		</div>
		
		<div class="box" style="margin-top: 3px; height: 30px; padding-top: 14px;">
			<div style="text-align: center;">
				{if !$mcbsb_org_oid}
				<span class="dark_red">
					{t}Please set your organization{/t}.						
					<a id="a1_right" href="#"><img src="/layout/images/question_mark.png" /></a>
				{else}
					<a id="a1_right" href="#" style="display: none;"><img src="/layout/images/question_mark.png" /></a>
					<a class="button" href="/contact/details/oid/{$mcbsb_org_oid}">{t}Organization{/t}</a>
					{if $user->team && $mcbsb_org_oid}
						&nbsp;<a class="button" href="/contact/details/oid/{$mcbsb_org_oid}#tab_members">{t}Team{/t}</a>
					{/if}						
				{/if}
					<div id="tip1_right" style="display:none;">
						{t}Create your organization and mark it as "your organization"{/t}.
						{t}Add all the people involved in your organization and associate them to your organization{/t}.
						{* TODO enable in the future *}
						{* <a class="tj_videos" href="J-m8Hw4x14o">{t}Click here for more info{/t}</a> *}
					</div>								
				</span>
			</div>
		</div>
	</div>
	
	<div class="grid_12">
		<div class="box">
		
			<div id="notification_area">
				<div id="spinner" style="float: right; min-height: 100px; min-width: 100px;"></div>
				{* <pre>{$system_messages|print_r}</pre> *}
				<div>
					<ul id="notification_area_messages">
					{foreach $system_messages as $key => $system_message}
						{* <li>{$system_message['time']|strftime '%T'}</li> *}
						{if $system_message['type'] == 'error'}
							{assign type 'dark_red'}
						{/if}
						{if $system_message['type'] == 'warning'}
							{assign type 'blue'}
						{/if}
						{if $system_message['type'] == 'success'}
							{assign type 'dark_green'}
						{/if}
						{* {$system_message['username']|ucwords} *}										 
						<li class="system_message {$type}">{$system_message['time']|date_format:"%T"} - {t}{$system_message['text']}{/t}</li> 
					{/foreach}
					</ul>
				</div>	
			</div>
			
			{* <p style="font-size: 11px; text-align: right;">{t}See all notifications{/t}</p> *} {* //TODO introduce APE and add link here *}
		</div>
	</div>
	<a id="top" name="top"></a>
	
	{assign videos_file "{$fcpath}application/views/videos.tpl"}
	{include file="$videos_file"}
