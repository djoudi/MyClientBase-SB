<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{* dir="rtl" lang="hb" xml:lang="hb" this is for hebrew *}
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{$application_title}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="en" name="language">
		<meta content="width=1200,maximum-scale=1.0" name="viewport">
		<meta name="description" content="MCBSB, your backoffice application" />
		<meta name="keywords" content="invoice, quote, ldap, back-office, contact, business, process, php, gpl" />
		<meta name="author" content="Damiano Venturin" />	

		{* css *}
		{$device = 'computer'}
		<link rel="stylesheet" href="/layout/css/{$device}/mcbsb.css" />		

		{* javascript *}
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 		
		<script type="text/javascript" src="/js/jquery-1.8.2.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.9.1.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="/js/jquery.hotkeys.js"></script> {* provides shortcuts *}
		<script type="text/javascript" src="/js/util.js"></script>
		<script type="text/javascript" src="/js/jquery-bubbletip-1.0.6.js"></script>
		<script type="text/javascript" src="/js/jquery.tubeplayer.js"></script>
		<script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
		<script type="text/javascript" src="/js/password_strength_plugin.js"></script>
		<script type="text/javascript" src="/js/mcbsb.js"></script>
	
		

		{setlocale locale=$locale}
		
		{* global var language *}
		<script type="text/javascript">
			language = "{$language}";
		</script>
		
		{* google maps library *}
		
		<script type="text/javascript" src="/js/gmap3.min.js"></script>			
	
		{literal}
		<script type="text/javascript">
			$(document).ready(function(){

				$("[id^=mapbutton_]").live('click', function (){
					
					var id = $(this).attr("id");
					var parts = id.split('_');
					var map_id = parts[1];
					if(!map_id) return false;
					

					var map_details = $(this).attr("href").replace('#','');
					var parts = map_details.split('_');
					var latitude = parts[0];
					var longitude = parts[1];
					if(!latitude || !longitude) return false;

					$('#map_'+map_id).show();
					
			        $('#map_'+map_id).gmap3({
			            marker:{
				          values:[
              					{latLng:[latitude, longitude], data:""}
			              ],
			              options:{draggable: false}
			            },
			            map:{
			              options:{
			                zoom: 14,
			              }
			            }
			          });
			        
			        return false;
				});
				
				$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

				//videos stuff	
				$("#hide_video").click(function() {
					$("#current_video").tubeplayer("stop");
					$("#video_section").hide();
				});		
			
				$('.tj_videos').live('click', function (){
					var video_id = $(this).attr("href");

					if(!video_id) {
						if(language == 'english') video_id='gydKx6aAXVs';
						if(language == 'italian') video_id='J-m8Hw4x14o';
					} 
					
					$("#current_video").tubeplayer({
						autoPlay: true,
						width: 853, // the width of the player
						height: 480, // the height of the player
						allowFullScreen: "false", // true by default, allow user to go full screen
						initialVideo: video_id, // why tooljar
						preferredQuality: "hd720",// preferred quality: default, small, medium, large, hd720
						modestbranding: false,
						loadSWFObject: false,
						onPlay: function(id){}, // after the play method is called
						onPlayerUnstarted: function(id){}, 
						onPlayerCued: function(id){}, 
						onPause: function(){}, // after the pause method is called
						onStop: function(){}, // after the player is stopped
						onSeek: function(time){}, // after the video has been seeked to a defined point
						onMute: function(){}, // after the player is muted
						onUnMute: function(){}, // after the player is unmuted
						// Player State Change Specific Functionality
						onPlayerUnstarted: function(){}, // when the player returns a state of unstarted
						onPlayerEnded: function(){}, // when the player returns a state of ended
						onPlayerPlaying: function(){}, //when the player returns a state of playing
						onPlayerPaused: function(){}, // when the player returns a state of paused
						onPlayerBuffering: function(){}, // when the player returns a state of buffering
						onPlayerCued: function(){}, // when the player returns a state of cued
						onQualityChange: function(quality){}, // a function callback for when the quality of a video is determined
						// Error State Specific Functionality
						onErrorNotFound: function(){}, // if a video cant be found
						onErrorNotEmbeddable: function(){}, // if a video isnt embeddable
						onErrorInvalidParameter: function(){} // if we've got an invalid param					
					});
					
					$("#video_section").show();
					
					$("#current_video").tubeplayer("stop");
					$("#current_video").tubeplayer("unmute");
					$("#current_video").tubeplayer("cue", video_id);
					$("#current_video").tubeplayer("play");

					window.scrollTo(0,0);
					
					return false;				
				});		

			    $('#video_menu_carousel').jcarousel({
			        vertical: true,
			        scroll: 2
			    });			
			    		 
			});		
		</script>
		{/literal}	
	
		
		{if $environment == 'development'}
		{literal}
		<script type="text/javascript">
			
			//DAM This rewrites all the PHP errors at the bottom of the page and hides the original ones
			$(document).ready(function(){

				var html = '';
				jQuery('.php_error').each(function(index){
					html = html + '<div class="php_error" style="border:1px solid #990000; padding-left:20px; margin:0 0 10px 0;"> [error #' + index + '] '+ jQuery(this).html() + '</div>';
					jQuery(this).replaceWith("");
				});
			
				if(html != "") html = '<br/><div id="php_error_container" style=""><h1>List of PHP errors</h1>' + html + '</div>';
				jQuery('.php_error').remove();
					
				jQuery('#php_error_container').replaceWith(html);
				jQuery('.php_error').css('background-color','yellow');
			});
		
		</script>				
		{/literal}
		{/if}
		


	</head>

	<body>

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	