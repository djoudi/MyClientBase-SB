{* video section top of the page *}

<div id="video_section" class="grid_24 box" style="display: none; margin-top: 10px; padding-bottom: 5px; padding-top: 5px; height: 480px; background-color: #f3f3f3;">
	
	<div id="video_menu" class="grid_3" style="padding-left: 12px;">
		
		<ul id="video_menu_carousel" class="jcarousel-skin-tango">
		{if $language=="english"}
			<li style="margin: 0px; padding: 0px;"><a class="tj_videos" href="gydKx6aAXVs"><img src="/layout/images/film_how_to_handle_contacts_en.jpg"/></a></li>
			<li style="margin: 0px; padding: 0px;"><a class="tj_videos" href="ZdVPq6M-WKE"><img src="/layout/images/film_google_contacts_sharing_en.jpg"/></a></li>
			<li style="margin: 0px; padding: 0px;"><a class="tj_videos" href="7CG-piWkHaI"><img src="/layout/images/film_contacts_sharing_showcase_en.jpg"/></a></li>
		{/if}
		
		{if $language=="italian"}	
			<li style="margin: 0px; padding: 0px;"><a class="tj_videos" href="J-m8Hw4x14o"><img width="130" height="116" src="/layout/images/film_how_to_handle_contacts_it.jpg"/></a></li>
			<li style="margin: 0px; padding: 0px;"><a class="tj_videos" href="91wq_8yRne4"><img width="130" height="116" src="/layout/images/film_google_contacts_sharing_it.jpg"/></a></li>
			<li style="margin: 0px; padding: 0px;"><a class="tj_videos" href="fZW0D4z1rNg"><img width="130" height="116" src="/layout/images/film_contacts_sharing_showcase_it.jpg"/></a></li>
		{/if}
	
		</ul>
			
		<div style="margin-top: 10px; text-align: center;">
			<a id="hide_video" href="#" class="button">{t}Hide video{/t}</a>
		</div>
						

	</div>
	    
	{* this div is going to be filled with the video *}	
	<div class="grid_20" id="current_video" style="margin-top: 0px; margin-left: 30px; padding: 0px;"> </div>
	
	
</div>