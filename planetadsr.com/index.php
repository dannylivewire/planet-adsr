<?php include 'public/common.php'; ?>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language="javascript" type="text/javascript" src="public/js/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="public/js/jquery.ba-throttle-debounce.min.js"></script>
<script language="javascript" type="text/javascript" src="public/js/jquery.mousewheel.min.js"></script>
<script language="javascript" type="text/javascript" src="public/js/jquery.touchSwipe.min.js"></script>
<script language="javascript" type="text/javascript" src="public/js/jquery.carouFredSel-6.1.0.js"></script>

<link href="public/css/style.css" rel="stylesheet" type="text/css" />

<script src="http://a.vimeocdn.com/js/froogaloop2.min.js"></script>



<style>

<?php
if($settingObj->getDisplayThumb() == '0') {
	if($settingObj->getDisplayTitle() == '1' || $settingObj->getDisplayDescription() == '1' || $settingObj->getDisplayViews() == '1') {
		?>
		.video_information {
			width: <?php echo ($settingObj->getVideolistWidth()-$settingObj->getThumbWidth()-20-5)."px"; ?>
		}
		
		<?php
	}
	
} else if($settingObj->getDisplayTitle() == '1' || $settingObj->getDisplayDescription() == '1' || $settingObj->getDisplayViews() == '1') {
	?>
	
	.video_thumb {
		width:<?php echo $settingObj->getThumbWidth(); ?>px;
		overflow: hidden;
		
	}
	
	.video_information {
		width: <?php echo ($settingObj->getVideolistWidth()-$settingObj->getThumbWidth()-20-5)."px"; ?>
	}
	<?php
} else {
	?>
	
	.video_thumb {
		float:left;
		width:<?php echo $settingObj->getThumbWidth(); ?>px;
		overflow: hidden;
	}
	
	<?php
}

if($settingObj->getLayout() == 0) {
	?>
	.video_container {
		float:left;
	}
	<?php
}
?>

.video_container {
	width:<?php echo $settingObj->getVideolistWidth(); ?>px;
	height: <?php echo $settingObj->getVideolistHeight(); ?>px;
	overflow:hidden;
	background-color:<?php echo $settingObj->getThumbBg();?>;
	padding: <?php echo $settingObj->getVideoPadding(); ?>px;
	<?php
	if($settingObj->getVideolistFontSize()!='') {
		?>
		font-size: <?php echo $settingObj->getVideolistFontSize(); ?>px;
		<?php
	}
	?>

}

.player {
	width:<?php echo $settingObj->getPlayerWidth(); ?>px;
	height:<?php echo $settingObj->getPlayerHeight(); ?>px;
}

/*** videolist buttons ***/
.button_prev_next {
	<?php
	if($settingObj->getButtonFontSize()!='') {
		?>
		font-size: <?php echo $settingObj->getButtonFontSize(); ?>px;
		<?php
	}
	?>
	text-decoration:none;
}
.playlist_prev {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
}

.playlist_prev:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.playlist_prev_separate {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
	width: <?php echo ($settingObj->getVideolistWidth()+($settingObj->getVideoPadding()*2)); ?>px;
}

.playlist_prev_separate:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.playlist_next {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
}

.playlist_next:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.playlist_next_separate {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
	width: <?php echo ($settingObj->getVideolistWidth()+($settingObj->getVideoPadding()*2)); ?>px;
}

.playlist_next_separate:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.playlist_prev_horz {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
}

.playlist_prev_horz:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.playlist_prev_horz_separate {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
}

.playlist_prev_horz_separate:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.playlist_next_horz {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
}

.playlist_next_horz:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.playlist_next_horz_separate {
	background-color: <?php echo $settingObj->getButtonBackColor(); ?>;
	color: <?php echo $settingObj->getButtonColor(); ?>;
}

.playlist_next_horz_separate:hover {
	background-color: <?php echo $settingObj->getButtonBackColorHover(); ?>;
	color: <?php echo $settingObj->getButtonColorHover(); ?>;
}

.video_content_container {
	width: <?php echo $settingObj->getPlayerWidth(); ?>px;
}
.general_container {
	min-height:<?php echo $settingObj->getPlayerHeight(); ?>px;
}

</style>

 
<script>
 
  
	var totslide = 0;
	var slider = '';
	var currentvideo = 0;
	var checktimeout;
	var done = false;
	var seconds = new Date().getSeconds();
	firstRequest = 60-seconds;
	var currentslide;
	
	  var tag = document.createElement('script');

	  tag.src = "https://www.youtube.com/iframe_api";
	  var firstScriptTag = document.getElementsByTagName('script')[0];
	  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	  var player;
	  function onYouTubeIframeAPIReady() {
		  
		  player = new YT.Player('container_all', {
		  height: '<?php echo $settingObj->getPlayerHeight(); ?>',
		  width: '<?php echo $settingObj->getPlayerWidth(); ?>',
		  playerVars: { 'rel': 0, 'modestbranding' :1, 'iv_load_policy' :3  },
		  events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		  }
		});
		
		
	  }
	  
	  
	<?php 
	if($settingObj->getManagement() == 0) {
		?>
	  function onPlayerReady(event) {
		 
		  <?php
		  if($settingObj->getScheduleVideo() == 1) {
			  ?>
			  checktimeout=setTimeout(checkVideo,firstRequest*1000);
			  <?php
		  }
		  ?>
			$.ajax({
				url: 'public/ajax/loadVideos.php',
				success: function(data) {	
					//fill playlist with data
					arrData=data.split("|");
					$('#playlist').html(arrData[0]);	
					$('#playlist_container').fadeIn("slow");
					checkVideo(0);
					$.ajax({
						url: 'public/ajax/loadFirstVideo.php',
						success: function(data) {
							arrDataVideo = data.split("|");
							totslide = arrData[1];
							showVideo(arrDataVideo[0],arrDataVideo[1],arrDataVideo[2],arrDataVideo[3]);
							
							
							
							<?php
							if($settingObj->getShowVideoInfo() == '1') {
								?>
								$('#youtube_title').html($('#hidden_title_'+arrDataVideo[1]).val());
								$('#youtube_author').html($('#hidden_author_'+arrDataVideo[1]).val());
								$('#youtube_description').html($('#hidden_description_'+arrDataVideo[1]).val());
								<?php
							}
							?>
							
							if(arrData[1] > <?php echo $settingObj->getVideoNum(); ?>)  {
								slider = $('#playlist').carouFredSel({ 	
									circular:false,
									infinite:false,
									
									<?php
									if($settingObj->getLayout() == "1") {
										?>
										direction: 'down',
										<?php
									} else {
										?>
										direction: 'left',
										slideWidth: 320,
										<?php
									}
									?>
									items:<?php echo $settingObj->getVideoNum(); ?>,
									scroll:1,
									auto:false,
									prev:$('#go-prev'),
									next:$('#go-next'),
									pagination:false,
									mousewheel:true
								
								});
								
								
								
								$('#go-prev').css({"display":"block"});
										
								$('#go-next').css({"display":"block"});
							} else {
								$('#go-prev').css({"display":"none"});
								
								$('#go-next').css({"display":"none"});
							}
							
						}
					});	
					
				}
			});
		  
	  }
	  function onFinish(id) {
		
		  clearTimeout(checktimeout);
		   done=true;
		   <?php
		  if($settingObj->getScheduleVideo() == 1) {
			  ?>
			  seconds = new Date().getSeconds();
			   firstRequest = 1;
			   checktimeout=setTimeout(checkVideo,firstRequest*1000);
			  <?php
		  }
		  ?>
		   
		   checkVideo(0);
		
	  }
	  function onPlayerStateChange(event) {
		if (event.data == 0) {
		  clearTimeout(checktimeout);
		   done=true;
		   <?php
		  if($settingObj->getScheduleVideo() == 1) {
			  ?>
			  seconds = new Date().getSeconds();
			   firstRequest = 60-seconds;
			   checktimeout=setTimeout(checkVideo,firstRequest*1000);
			  <?php
		  }
		  ?>
		   
		   checkVideo(0);
		 
		} else {
			done=false;
		}
	  }
	  <?php
	} else {
		?>
		ready = 1;
		function onPlayerReady(event) {
			
		  clearTimeout(checktimeout);
		  
			$.ajax({
				url: 'public/ajax/loadVideos.php',
				success: function(data) {	
					//fill playlist with data
					arrData=data.split("|");
					$('#playlist').html(arrData[0]);	
					$('#playlist_container').fadeIn("slow");
					
					//get first video from divs
					var g = 0;
					totslide = arrData[1];
					$('#playlist').find('div').each(function() {
						if(g == 0) {
							//split div id
							arrDataVideo = $(this).attr('id').split('£');			
							
							showVideo(arrDataVideo[1],arrDataVideo[2],arrDataVideo[3],arrDataVideo[4]);
							$('#container_all').fadeIn();
							ready = 0;
							<?php
							if($settingObj->getShowVideoInfo() == '1') {
								?>
								$('#youtube_title').html($('#hidden_title_'+arrDataVideo[2]).val());
								$('#youtube_author').html($('#hidden_author_'+arrDataVideo[2]).val());
								$('#youtube_description').html($('#hidden_description_'+arrDataVideo[2]).val());
								<?php
							}
							?>
							
						}
						g++;
					});
					
					
					if(arrData[1] > <?php echo $settingObj->getVideoNum(); ?>)  {
						slider = $('#playlist').carouFredSel({ 	
							circular:false,
							infinite:false,
							
							<?php
							if($settingObj->getLayout() == "1") {
								?>
								direction: 'down',
								<?php
							} else {
								?>
								direction: 'left',
								slideWidth: 320,
								<?php
							}
							?>
							items:<?php echo $settingObj->getVideoNum(); ?>,
							scroll:1,
							auto:false,
							prev:$('#go-prev'),
							next:$('#go-next'),
							pagination:false,
							mousewheel:true
						
						});
						
						
						$('#go-prev').css({"display":"block"});
								
						$('#go-next').css({"display":"block"});
					} else {
						$('#go-prev').css({"display":"none"});
						
						$('#go-next').css({"display":"none"});
					}
					
					
				}
			});
		  <?php
		  if($settingObj->getAutoplay() == '1') {
			  ?>
			 	var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
				if(!iOS) {
					event.target.playVideo();
				}
				
			  
			<?php
		  }
		  ?>
	  }
	   var currentPosition = 0;
	  function onPlayerStateChange(event) {
		  
		if (event.data == 0) {
		  
		   done=true;
		   //get next video
		  
		  
		   var newvideo;
		   //$('#playlist').trigger("currentPosition",function(num) { currentPosition = num;});
		   
		   newvideo = $('#playlist').triggerHandler('slice',[(currentPosition+1),(currentPosition+2)]);
		   //alert(newvideo.attr('id'));
			//alert(currentPosition);
		  //split div id
			arrDataVideo = newvideo.attr('id').split('£');	
			if(arrDataVideo[2]>0) { 					
				//to prevent loop, if unmanaged no loop is available
				showVideo(arrDataVideo[1],arrDataVideo[2],arrDataVideo[3],arrDataVideo[4]);
				<?php
				if($settingObj->getShowVideoInfo() == '1') {
					?>
					$('#youtube_title').html($('#hidden_title_'+arrDataVideo[2]).val());
					$('#youtube_author').html($('#hidden_author_'+arrDataVideo[2]).val());
					$('#youtube_description').html($('#hidden_description_'+arrDataVideo[2]).val());
					<?php
				}
				?>
			}
				  
			   
			
		 
		} else {
			done=false;
		}
		if(event.data == -1) {
			if(ready == 1) {
				$('#container_all').css("display","none");
			}
		}
	  }
		<?php
	}
	?>
	  
	 
	
	function checkVideo(num) {
		
		if(num!=1) {
			$.ajax({
				url: 'public/ajax/loadNextVideo.php?current_video='+currentvideo,
				success: function(data) {
					
					if(data!='|||') {
						
						var arrdatavideo = data.split("|");
						if($('#video£'+arrdatavideo[0]+"£"+arrdatavideo[1]+"£"+arrdatavideo[2]).length == 0) {
							$.ajax({
								url: 'public/ajax/addThumb.php?video='+arrdatavideo[0]+"&video_id="+arrdatavideo[1]+"&video_type="+arrdatavideo[2],
								success: function(data) {	
									
									totslide++;
									if(totslide><?php echo $settingObj->getVideoNum(); ?> && slider == '') {
										$('#playlist').prepend(data);	
										slider = $('#playlist').carouFredSel({ 	
										circular:false,
										infinite:false,
										
										<?php
										if($settingObj->getLayout() == "1") {
											?>
											direction: 'down',
											<?php
										} else {
											?>
											direction: 'left',
											slideWidth: 320,
											<?php
										}
										?>
										items:<?php echo $settingObj->getVideoNum(); ?>,
										scroll:1,
										auto:false,
										prev:$('#go-prev'),
										next:$('#go-next'),
										pagination:false,
										mousewheel:true
									
									});
									
									
									$('#go-prev').css({"display":"block"});
											
									$('#go-next').css({"display":"block"});
									} else {
										if(slider!='') {
											$('#playlist').trigger('insertItem',[data,0,true]);
										} else {
											$('#playlist').prepend(data);	
										}
									}
									//
									$('#playlist_container').fadeIn("slow");
									
									
										
										//$("#playlist").trigger("synchronise");
									
									showVideo(arrdatavideo[0],arrdatavideo[1],arrdatavideo[2],arrdatavideo[3]);
									<?php
									if($settingObj->getShowVideoInfo() == '1') {
										?>
										$('#youtube_title').html($('#hidden_title_'+arrdatavideo[1]).val());
										$('#youtube_author').html($('#hidden_author_'+arrdatavideo[1]).val());
										$('#youtube_description').html($('#hidden_description_'+arrdatavideo[1]).val());
										<?php
									}
									?>
									
									
									
								}
							});
						} else {
							showVideo(arrdatavideo[0],arrdatavideo[1],arrdatavideo[2],arrdatavideo[3]);
							<?php
							if($settingObj->getShowVideoInfo() == '1') {
								?>
								$('#youtube_title').html($('#hidden_title_'+arrdatavideo[1]).val());
								$('#youtube_author').html($('#hidden_author_'+arrdatavideo[1]).val());
								$('#youtube_description').html($('#hidden_description_'+arrdatavideo[1]).val());
								<?php
							}
							?>
							
							//player.playVideo();
						}
						
					} else {
						<?php
						if($settingObj->getScheduleVideo() == 1) {
							?>
						//get prev video if current has finished playing
						if(done) {
							$.ajax({
								url: 'public/ajax/loadPrevVideo.php?current_video='+currentvideo,//show_id
								success: function(data) {
									if(data!='|||') {
										arrData=data.split("|");
										showVideo(arrData[0],arrData[1],arrData[2],arrData[3]);
										<?php
										if($settingObj->getShowVideoInfo() == '1') {
											?>
											$('#youtube_title').html($('#hidden_title_'+arrData[1]).val());
											$('#youtube_author').html($('#hidden_author_'+arrData[1]).val());
											$('#youtube_description').html($('#hidden_description_'+arrData[1]).val());
											<?php
										}
										?>
									} else {
										//condition according to what set in settings to make it restart from 0
										<?php
										if($settingObj->getLoopVideos() == 1) {
											?>
											
											$.ajax({
												url: 'public/ajax/loadFirstVideo.php',
												success: function(data) {
													arrDataVideo = data.split("|");
													if(slider!='') {
														
														$("#playlist").trigger("synchronise");
													}
													showVideo(arrDataVideo[0],arrDataVideo[1],arrDataVideo[2],arrDataVideo[3]);
													<?php
													if($settingObj->getShowVideoInfo() == '1') {
														?>
														$('#youtube_title').html($('#hidden_title_'+arrDataVideo[1]).val());
														$('#youtube_author').html($('#hidden_author_'+arrDataVideo[1]).val());
														$('#youtube_description').html($('#hidden_description_'+arrDataVideo[1]).val());
														<?php
													}
													?>
												}
											});
											<?php
										} else {
											?>
											player.clearVideo();
											clearTimeout(checktimeout);
											<?php
										}
										?>
										
									}
									
									
								}
							});
						}
						<?php
						} else {
							if($settingObj->getLoopVideos() == 1) {
								//condition according to what set in settings to make it restart from 0
								?>
								$.ajax({
									url: 'public/ajax/loadFirstVideo.php',
									success: function(data) {
										arrDataVideo = data.split("|");
										showVideo(arrDataVideo[0],arrDataVideo[1],arrDataVideo[2],arrDataVideo[3]);
										<?php
										if($settingObj->getShowVideoInfo() == '1') {
											?>
											$('#youtube_title').html($('#hidden_title_'+arrDataVideo[1]).val());
											$('#youtube_author').html($('#hidden_author_'+arrDataVideo[1]).val());
											$('#youtube_description').html($('#hidden_description_'+arrDataVideo[1]).val());
											<?php
										}
										?>
									}
								});
								<?php
							} else {
								?>
								player.clearVideo();
								<?php
							}
						}
						?>
						
					}
					
				}
			});
		} 
		if(num!=0) {
			checktimeout = setTimeout(checkVideo,60000);
		}
	}
	function showVideo(ytvideoid,videoid,video_type,seconds) {
		currentvideo = videoid;
		if(video_type==1) {
			//console.log('youtube');
			$('#vimeo_player').attr('src','//player.vimeo.com/video/');
			$('#container_vimeo_all').fadeOut(0);
			
			$('#container_all').removeAttr('style');
			$('#container_all').css({'position':'relative'});			
			$('#cover_video').fadeOut(0);
			$('#src_player').attr("src",'contents/videos/');
			player.cueVideoById(ytvideoid,seconds);
			var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
			if(!iOS) {
				player.playVideo();
			}
			<?php
			  if($settingObj->getAutoplay() == '0') {
				  ?>
				 player.pauseVideo();
				<?php
			  } 
			  ?>
		} else if(video_type==2) {
			
			//vimeo
			$('#cover_video').fadeIn(0);
			//console.log('vimeo');
			$('#src_player').attr("src",'contents/videos/');
			
			$('#cover_video').fadeOut(0);
			$('#container_all').css({'position':'absolute','z-index':'-1000'});
			player.pauseVideo();
			$('#vimeo_player').attr('src','//player.vimeo.com/video/'+ytvideoid+'?api=1&player_id=vimeo_player&badge=0&byline=0<?php if($settingObj->getAutoplay() == '1') { ?>&autoplay=1<?php } ?>');
			iframeVimeo = $('#vimeo_player')[0];
			playerVimeo = $f(iframeVimeo);
			playerVimeo.addEvent('ready', function(id){
				if($('#vimeo_player').attr('src')!='//player.vimeo.com/video/') {
					$('#cover_video').fadeOut(0);
					
					playerVimeo.api('play');
					setTimeout( loadMiddle, 1000);
					console.log('success');
				}
				
				
				function loadMiddle() {
					
					playerVimeo.api('seekTo', seconds);
					
				}
				
				playerVimeo.addEvent('finish', onFinish);
				//var vimeoVideo = $f(id);
				
				
			});
			
			$('#container_vimeo_all').fadeIn(0);
			
			
		}
		
		$('#loading_container').fadeOut();
		$('#youtube_video').animate({"top":"0","left":"0"},500);
		$('.video_container').each(function() {
			$(this).css({"background-color":"<?php echo $settingObj->getThumbBg(); ?>"});
		});
		//alert('#video£'+ytvideoid+"£"+videoid);
		//$('#video£'+ytvideoid+"£"+videoid).css({"background-color":"<?php echo $settingObj->getThumbBgSel(); ?>"});
		var j=0;
		
		$('.video_container').each(function() {
			$(this).unbind('mouseenter');
			$(this).unbind('mouseleave');
			//console.log($(this).attr('id')+"--"+'video£'+ytvideoid+"£"+videoid+"£"+video_type);
			if($(this).attr('id') != 'video£'+ytvideoid+"£"+videoid+"£"+video_type) {
				console.log($(this).attr('id'));
				$(this).mouseenter(function() {
					$(this).css({"background-color":"<?php echo $settingObj->getThumbBgHover(); ?>"});
				});
				$(this).mouseleave(function() {
					$(this).css({"background-color":"<?php echo $settingObj->getThumbBg(); ?>"});
				});
			
			} else {
				currentPosition = parseInt(videoid);
				if(currentPosition > 0) {
					$(this).css({"background-color":"<?php echo $settingObj->getThumbBgSel(); ?>"});
				} else {
					currentPosition = currentPosition;
					currentslide = currentslide;
				}
			}
			
			if($(this).attr('id') == 'video£'+ytvideoid+"£"+videoid+"£"+video_type) {
				currentPosition = parseInt(videoid);
				if(currentPosition > 0) {
					currentslide = $(this);
				} else {
					currentPosition = currentPosition;
					currentslide = currentslide;
				}
				
				
			}
			
			j++;
			
		});
		
		
		if(parseInt(totslide) > parseInt(<?php echo $settingObj->getVideoNum(); ?>))  {
			setTimeout(function() {
				$("#playlist").trigger("slideTo", currentslide);
			},1000);
			
			
		}
			
		
	}
	
	function removeCover() {
		$('#cover_video').fadeOut(0);
	}
	function showVideoFromThumb(ytvideoid,videoid,video_type) {
		
		showVideo(ytvideoid,videoid,video_type,0);
		<?php
		if($settingObj->getShowVideoInfo() == '1') {
			?>
			$('#youtube_title').html($('#hidden_title_'+videoid).val());
			$('#youtube_author').html($('#hidden_author_'+videoid).val());
			$('#youtube_description').html($('#hidden_description_'+videoid).val());
			<?php
		}
		?>
		
	}
	
</script>

<div class="general_container">
	<?php
    if($settingObj->getLayout() == "1") {
        //vertical layout
        //calculate buttons width according to videolist width and margin between
        $button_width = ceil(($settingObj->getVideolistWidth()-$settingObj->getButtonMarginBetween()+($settingObj->getVideoPadding()*2))/2);
        if($settingObj->getVideolistPosition() == 'left') {
        ?>
        <div class="playlist_container" id="playlist_container" style="margin-right:<?php echo $settingObj->getVideolistMargin(); ?>px">
            <?php
            if($settingObj->getVideoNavigation() == 'top') {
                ?>
                <div class="clearboth"></div>
                <a id="go-prev" href="#" class="playlist_prev button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;width:<?php echo $button_width; ?>;margin-bottom:<?php echo $settingObj->getButtonMarginVideolist(); ?>px;">
                <!--up arrow-->
                prev
                </a>
                <a id="go-next" href="#" class="playlist_next button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-bottom:<?php echo $settingObj->getButtonMarginVideolist(); ?>px;width:<?php echo $button_width; ?>">
                <!--down arrow-->
                next
                </a>
                <div class="clearboth"></div>
                <?php
            }
            
            if($settingObj->getVideoNavigation() == 'separate') {
                ?>
                <div class="clearboth"></div>
                <a id="go-prev" href="#" class="playlist_prev_separate button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-bottom:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                <!--up arrow-->
                prev
                </a>
                <div class="clearboth"></div>
                <?php
            }
            ?>
            <div id="playlist" class="playlist">
            <!--playlist-->
                <img src="public/images/small_loading.gif" />
            </div>
            <?php
            if($settingObj->getVideoNavigation() == 'separate') {
                ?>
                <div class="clearboth"></div>
                <a id="go-next" href="#" class="playlist_next_separate button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-top:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                <!--down arrow-->
                next
                </a>
                <div class="clearboth"></div>
                <?php
            }
            
            if($settingObj->getVideoNavigation() == 'bottom') {
                ?>
                <div class="clearboth"></div>
                <a id="go-prev" href="#" class="playlist_prev button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;margin-top:<?php echo $settingObj->getButtonMarginVideolist(); ?>px;width:<?php echo $button_width; ?>">
                <!--up arrow-->
                prev
                </a>
                <a id="go-next" href="#" class="playlist_next button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;width:<?php echo $button_width; ?>;margin-top:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                <!--down arrow-->
                next
                </a>
                <div class="clearboth"></div>
                <?php
            }
            ?>
        </div>
        <?php
        } 
    } else {
        if($settingObj->getVideolistPosition() == 'top') {
            //calculate buttons height according to videolist height and padding
            $buttons_height = $settingObj->getVideolistHeight()+($settingObj->getVideoPadding()*2);
            
            ?>
            <div class="clearboth"></div>
            <div class="playlist_container_horz" id="playlist_container" style="margin-bottom:<?php echo $settingObj->getVideolistMargin(); ?>px">
                <?php
                if($settingObj->getVideoNavigation() == 'left') {
                    ?>
                    <a id="go-prev" href="#" class="playlist_prev_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px">
                    <!--left arrow-->
                    prev
                    </a>
                    <a id="go-next" href="#" class="playlist_next_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;margin-right:<?php echo $settingObj->getButtonMarginVideolist(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px">
                    <!--right arrow-->
                    next
                    </a>
                    <?php
                }
                if($settingObj->getVideoNavigation() == 'separate') {
                    ?>
                    <a id="go-prev" href="#" class="playlist_prev_horz_separate button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px;margin-right:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                    <!--left arrow-->
                    prev
                    </a>
                    <?php
                }
                ?>
                <div class="playlist_container_horz">
                    <div id="playlist" class="playlist_horz">
                    <!--playlist-->
                        <img src="public/images/small_loading.gif" />
                    </div>
                </div>
                <?php
                if($settingObj->getVideoNavigation() == 'separate') {
                    ?>
                    <a id="go-next" href="#" class="playlist_next_horz_separate button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px;margin-left:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                    <!--right arrow-->
                    next
                    </a>
                    <?php
                }
                
                if($settingObj->getVideoNavigation() == 'right') {
                    ?>
                    <a id="go-prev" href="#" class="playlist_prev_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;margin-left:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                    <!--left arrow-->
                    prev
                    </a>
                    <a id="go-next" href="#" class="playlist_next_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px">
                    <!--right arrow-->
                    next
                    </a>
                    <?php
                }
                ?>
                
            </div>
            <div class="clearboth"></div>
            <?php
            
        }
    }
    ?>
    <div class="video_content_container">
    	<div id="cover_video" style="width:<?php echo $settingObj->getPlayerWidth(); ?>px;height:<?php echo $settingObj->getPlayerHeight(); ?>px;top:0;left:0;position:absolute;z-index:1000;background-color:#DDDDDD" >
        	<img src="public/images/loading.png" border="0" style="margin-left:<?php echo (($settingObj->getPlayerWidth()-128)/2); ?>px;margin-top:<?php echo (($settingObj->getPlayerHeight()-128)/2); ?>px"/>
        </div>
        <div id="container_all" class="player" style="position:relative;">
        	
            <div id="loading_container" style="width:100%;height:100%;top:0;left:0;position:absolute;z-index:20;background-color:#DDDDDD">
                <img src="public/images/loading.png" border="0" style="margin-left:<?php echo (($settingObj->getPlayerWidth()-128)/2); ?>px;margin-top:<?php echo (($settingObj->getPlayerHeight()-128)/2); ?>px"/>
            </div>
        </div>
        <div id="container_vimeo_all" class="player" style="position:relative;display:none">
            <iframe id="vimeo_player" name="vimeo_player" src="//player.vimeo.com/video/" width="<?php echo $settingObj->getPlayerWidth(); ?>px" height="<?php echo $settingObj->getPlayerHeight(); ?>px" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
       
            
        <?php
        if($settingObj->getShowVideoInfo() == '1') {
            if($settingObj->getVideoInfoFontSize()!='') {
                ?>
                <style>
                    .youtube_title {
                        font-size: <?php echo $settingObj->getVideoInfoFontSize(); ?>px;
                    }
                    .youtube_author {
                        font-size: <?php echo $settingObj->getVideoInfoFontSize(); ?>px;
                    }
                    .youtube_description {
                        font-size: <?php echo $settingObj->getVideoInfoFontSize(); ?>px;
                    }
                </style>
                <?php
            }
            ?>
            <div class="youtube_title" id="youtube_title"></div>
            <div class="youtube_author" id="youtube_author"></div>
            <div class="youtube_description" id="youtube_description"></div>
            <?php
        }
        ?>
    </div>
    
    <?php
    if($settingObj->getDisplayVideolist() == '1') {
        if($settingObj->getLayout() == "1") {
            //vertical layout
            //calculate buttons width according to videolist width and margin between
            $button_width = ceil(($settingObj->getVideolistWidth()-$settingObj->getButtonMarginBetween()+($settingObj->getVideoPadding()*2))/2);
            if($settingObj->getVideolistPosition() == 'right') {
                
                ?>
                <div class="playlist_container" id="playlist_container"  style="margin-left:<?php echo $settingObj->getVideolistMargin(); ?>px">
                    <?php
                    if($settingObj->getVideoNavigation() == 'top') {
                        ?>
                        <div class="clearboth"></div>
                        <a id="go-prev" href="#" class="playlist_prev button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;width:<?php echo $button_width; ?>;margin-bottom:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                        <!--up arrow-->
                        prev
                        </a>
                        <a id="go-next" href="#" class="playlist_next button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-bottom:<?php echo $settingObj->getButtonMarginVideolist(); ?>px;width:<?php echo $button_width; ?>">
                        <!--down arrow-->
                        next
                        </a>
                        <div class="clearboth"></div>
                        <?php
                    }
                    if($settingObj->getVideoNavigation() == 'separate') {
                        ?>
                        <div class="clearboth"></div>
                        <a id="go-prev" href="#" class="playlist_prev_separate button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-bottom:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                        <!--up arrow-->
                        prev
                        </a>
                        <div class="clearboth"></div>
                        <?php
                    }
                    ?>
                    <div id="playlist" class="playlist">
                    <!--playlist-->
                        <img src="public/images/small_loading.gif" />
                    </div>
                    <?php
                    if($settingObj->getVideoNavigation() == 'separate') {
                        ?>
                        <div class="clearboth"></div>
                        <a id="go-next" href="#" class="playlist_next_separate button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-top:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                        <!--down arrow-->
                        next
                        </a>
                        <div class="clearboth"></div>
                        <?php
                    }
                    if($settingObj->getVideoNavigation() == 'bottom') {
                        ?>
                        <div class="clearboth"></div>
                        <a id="go-prev" href="#" class="playlist_prev button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;margin-top:<?php echo $settingObj->getButtonMarginVideolist(); ?>px;width:<?php echo $button_width; ?>">
                        <!--up arrow-->
                        prev
                        </a>
                        <a id="go-next" href="#" class="playlist_next button_prev_next" style="display:none;padding: <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px;width:<?php echo $button_width; ?>;margin-top:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                        <!--down arrow-->
                        next
                        </a>
                        <div class="clearboth"></div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } 
        } else {
            if($settingObj->getVideolistPosition() == 'bottom') {
                //calculate buttons height according to videolist height and padding
                $buttons_height = $settingObj->getVideolistHeight()+($settingObj->getVideoPadding()*2);
            
                ?>
                <div class="clearboth"></div>
                <div class="playlist_container_horz" id="playlist_container" style="margin-top:<?php echo $settingObj->getVideolistMargin(); ?>px">
                    <?php
                    if($settingObj->getVideoNavigation() == 'left') {
                        ?>
                        <a id="go-prev" href="#" class="playlist_prev_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px">
                        <!--left arrow-->
                        prev
                        </a>
                        <a id="go-next" href="#" class="playlist_next_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;margin-right:<?php echo $settingObj->getButtonMarginVideolist(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px">
                        <!--right arrow-->
                        next
                        </a>
                        <?php
                    }
                    if($settingObj->getVideoNavigation() == 'separate') {
                        ?>
                        <a id="go-prev" href="#" class="playlist_prev_horz_separate button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px;margin-right:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                        <!--left arrow-->
                        prev
                        </a>
                        <?php
                    }
                    ?>
                    <div class="playlist_container_horz">
                        <div id="playlist" class="playlist_horz">
                        <!--playlist-->
                            <img src="public/images/small_loading.gif" />
                        </div>
                    </div>
                    <?php
                    if($settingObj->getVideoNavigation() == 'separate') {
                        ?>
                        <a id="go-next" href="#" class="playlist_next_horz_separate button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px;margin-left:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                        <!--right arrow-->
                        next
                        </a>
                        <?php
                    }
                    if($settingObj->getVideoNavigation() == 'right') {
                        ?>
                        <a id="go-prev" href="#" class="playlist_prev_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px;margin-right:<?php echo $settingObj->getButtonMarginBetween(); ?>px;margin-left:<?php echo $settingObj->getButtonMarginVideolist(); ?>px">
                        <!--left arrow-->
                        prev
                        </a>
                        <a id="go-next" href="#" class="playlist_next_horz button_prev_next" style="display:none;padding: 0px <?php echo $settingObj->getButtonPadding(); ?>px 0px <?php echo $settingObj->getButtonPadding(); ?>px;height:<?php echo $buttons_height; ?>px;line-height:<?php echo $buttons_height; ?>px">
                        <!--right arrow-->
                        next
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        }
    }
    ?>
    <?php 
    if($settingObj->getShowScheduleList() == 1) {
        $arrayShows = $listObj->getShowsDisplayList(date('Y-m-d'));
        if(count($arrayShows)>0) {
            ?>
            <style>		
                .schedule_list {	
                    margin-top: <?php echo $settingObj->getScheduleListMarginTop(); ?>px;
                    height: <?php echo $settingObj->getScheduleListHeight(); ?>px;
                <?php
                if($settingObj->getScheduleListFontSize()!='') {
                    ?>
                        font-size: <?php echo $settingObj->getScheduleListFontSize(); ?>px;
                    <?php
                }
                ?>
                }
            </style>
            <!-- SCHEDULE
            =============================================================================================================== -->
            <div class="clearboth"></div>
            
            
            <div class="schedule_list" style="overflow:auto">
                <div>TODAY SCHEDULE:</div>
                <div>
                    <ul>
                        
                        <?php
                        $arrayShows = $listObj->getShowsDisplayList(date('Y-m-d'));
                        foreach($arrayShows as $showId => $show) {
                            echo "<li class=\"schedule_row\">".$show["show_time"]."-".$show["video_title"]."</li>";
                        }
                        ?>
                   </ul>
                </div>
            </div>
            
            <div class="clearboth"></div>
                
            
            
            <?php
        }
    }
    ?>
    <div class="clearboth"></div>
</div>

