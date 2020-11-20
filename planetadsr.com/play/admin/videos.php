<div id="modal_preview_video" class="modal_video" style="display:none">
	<a href="javascript:hidePreview();" style="background-color:#333;color:#FFF;font-size:30px;text-align:center;line-height:50px;font-weight:bold;width:50px;height:50px;display:block; text-decoration:none;float:right">X</a>
    <div class="cleardiv"></div>
	<div id="preview_video_content">
    </div>
</div>
<div id="modal_edit_video" class="modal_edit_video" style="display:none">
</div>
<div id="action_bar">
    <div id="action"><a onclick="javascript:delItems('manage_videos','videos[]','delVideos','delete')">Delete</a></div>
</div>      
<script>
	function showVideo(video_id) {
		if($('#content_video_'+video_id).css('display')=='none') {
			$('#content_video_'+video_id).slideDown();
		} else {
			$('#content_video_'+video_id).slideUp();
		}
	}
	function editVideo(video_id) {
		$.ajax({
		  url: 'ajax/editVideo.php?video_id='+video_id,
		  success: function(data) {			 
			 $('#modal_edit_video').html(data);
			 $('body').prepend('<div id="div_back" onclick="hideEdit();"></div>');
			 $('#modal_edit_video').fadeIn();
		  }
		});
	}
	function hideEdit() {
		$('#modal_edit_video').fadeOut();
		$('#div_back').remove();
	}
	function previewVideo(video_id,video_youtube_id,video_type,video_link) {
		switch(video_type) {
			case 1:
				$('#preview_video_content').html('<iframe width="560" height="315" src="//www.youtube.com/embed/'+video_youtube_id+'?rel=0" frameborder="0" allowfullscreen></iframe>');
				break;
			case 2:
				$('#preview_video_content').html('<iframe src="//player.vimeo.com/video/'+video_youtube_id+'" width="560" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
				break;
			
		}
		$('body').prepend('<div id="div_back"></div>');
		$('#modal_preview_video').fadeIn();
	}
	function hidePreview() {
		$('#modal_preview_video').fadeOut();
		$('#preview_video_content').html('');
		$('#div_back').remove();
	}
</script>
<form name="manage_videos" action="" method="post">
    <input type="hidden" name="operation" />
    <input type="hidden" name="videos[]" value=0 />
    <div id="table_container">
        <div id="table">
            
            <div class="videos_title_col1">
                <div id="table_cell">#</div>
            </div>
            <div class="videos_title_col2">
                <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_videos','videos[]');" /></div>
            </div>
            <div class="videos_title_col3">
                <div id="table_cell">Title</div>
            </div>
            <div class="videos_title_col4">
                <div id="table_cell">Link</div>
            </div> 
            <div class="videos_title_col5">
                <div id="table_cell"></div>
            </div>
            <div class="videos_title_col6">
                <div id="table_cell"></div>
            </div>
            <div id="empty"></div>
            <?php
			$arrayChannels = $listObj->getSourcesList('channel');
			$arrayPlaylists = $listObj->getSourcesList('playlist');
			
			
			
			foreach($arrayChannels as $channelId => $channel) {		
					
				$i = 1;
				$arrayVideos = $listObj->getVideosList($channelId);
				if(count($arrayVideos)>0) {
					?>
					<div class="sources_divider" style="cursor:pointer" id="channel_opener_<?php echo $channelId;?>" onclick="javascript:showVideos(<?php echo $channelId;?>,'channel_opener_');">
                    	<div class="float_left plus_btn"><img src="images/icons/plus_btn.png" /></div>
                        <div class="float_left source_row">CHANNEL: <?php echo $channel["source_title"]; ?></div>
                    </div>
					<div id="empty"></div>
                    <div id="<?php echo $channelId; ?>" style="display:none">
					<?php
					
					foreach($arrayVideos as $videoId => $video) {												
						if($i % 2) {
							$class="alternate_table_row_white";
						} else {
							$class="alternate_table_row_grey";
						}
						
						?>
						<div id="video_row_<?php echo $videoId; ?>" style="cursor:pointer" onclick="javascript:showVideo(<?php echo $videoId; ?>);">	
						
						
						<div class="videos_row_col1" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $i; ?></div>
						</div>
						<div class="videos_row_col2" class="<?php echo $class; ?>">
							<div id="table_cell"><input type="checkbox" name="videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_videos',this.checked);" /></div>
						</div>
						<div class="videos_row_col3" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_title"];?></div>
						</div>
						<div class="videos_row_col4" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_link"]; ?></div>
						</div> 
                        <div class="videos_row_col5" class="<?php echo $class; ?>">
							<div id="table_cell"><a href="javascript:refreshVideo(<?php echo $videoId; ?>);"><img src="images/icons/refresh.png" border=0 alt="refresh" title="refresh"/></a></div>
						</div>
						<div class="videos_row_col6" class="<?php echo $class; ?>">
							<div id="table_cell"><a href="javascript:delVideo(<?php echo $videoId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete"/></a></div>
						</div>
						<div id="empty"></div>
</div>
                        <div id="content_video_<?php echo $videoId; ?>" style="display:none;">
                            <div style="padding:10px">
                                <?php
                                $thumb = $video["video_thumb"];
                                
                                ?>
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Title</div>
                                <div style="float:left;margin-top:10px"><?php echo $video["video_title"]; ?></div>
                                <div id="empty"></div>
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Thumb</div>
                                <div style="float:left;margin-top:10px"><img class="lazy" data-original="<?php echo $thumb; ?>" border=0 /></div>
                                <div id="empty"></div>
                                
                                    <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Duration</div>
                                    <div style="float:left;margin-top:10px"><?php echo $listObj->milliToTimeString($video["video_duration"]*1000); ?></div>
                                    <div id="empty"></div>
                                    
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Author</div>
                                <div style="float:left;margin-top:10px"><?php echo $video["video_author"]; ?></div>
                                <div id="empty"></div>
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Description</div>
                                <div style="float:left;margin-top:10px"><?php echo $video["video_description"]; ?></div>
                                <div id="empty"></div>
                            </div>
                            <div style="margin-top:5px;border-top:1px dashed #CCC;padding:10px 0px; background-color: #E7E7E7;">
                                <a href="javascript:previewVideo(<?php echo $videoId; ?>,'<?php echo $video["video_youtube_id"]; ?>',<?php echo $video["video_type"]; ?>,'<?php echo $video["video_link"]; ?>');" id="table_cell" style="color:#333;float:left;margin-left:10px;">Preview</a>
                                <a href="javascript:editVideo(<?php echo $videoId; ?>);" id="table_cell" style="color:#333;float:right;margin-right:10px;">Edit</a>
                                 <div id="empty"></div>
                            </div>
                           
                        </div>
						<?php
						$i++;
					}
					?>
                    </div>
                    <?php
				}
			}
			
			foreach($arrayPlaylists as $playlistId => $playlist) {		
					
				$i = 1;
				$arrayVideos = $listObj->getVideosList($playlistId);
				if(count($arrayVideos)>0) {	
					?>
					<div class="sources_divider" style="cursor:pointer" id="playlist_opener_<?php echo $playlistId;?>"  onclick="javascript:showVideos(<?php echo $playlistId;?>,'playlist_opener_');">
                    	<div class="float_left plus_btn"><img src="images/icons/plus_btn.png" /></div>
                        <div class="float_left source_row">PLAYLIST: <?php echo $playlist["source_title"]; ?></div>
                    </div>
					<div id="empty"></div>
                    <div id="<?php echo $playlistId; ?>" style="display:none">
					<?php		
					
					foreach($arrayVideos as $videoId => $video) {											
						if($i % 2) {
							$class="alternate_table_row_white";
						} else {
							$class="alternate_table_row_grey";
						}
						
						?>
						<div id="video_row_<?php echo $videoId; ?>" style="cursor:pointer" onclick="javascript:showVideo(<?php echo $videoId; ?>);">	
						<div class="videos_row_col1" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $i; ?></div>
						</div>
						<div class="videos_row_col2" class="<?php echo $class; ?>">
							<div id="table_cell"><input type="checkbox" name="videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_videos',this.checked);" /></div>
						</div>
						<div class="videos_row_col3" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_title"];?></div>
						</div>
						<div class="videos_row_col4" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_link"]; ?></div>
						</div> 
                        <div class="videos_row_col5" class="<?php echo $class; ?>">
							<div id="table_cell"><a href="javascript:refreshVideo(<?php echo $videoId; ?>);"><img src="images/icons/refresh.png" border=0 alt="refresh" title="refresh" /></a></div>
						</div>
						<div class="videos_row_col6" class="<?php echo $class; ?>">
							<div id="table_cell"><a href="javascript:delVideo(<?php echo $videoId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete" /></a></div>
						</div>
						<div id="empty"></div>
</div>
                        <div id="content_video_<?php echo $videoId; ?>" style="display:none;">
                            <div style="padding:10px">
                                <?php
                                $thumb = $video["video_thumb"];
                                
                                ?>
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Title</div>
                                <div style="float:left;margin-top:10px"><?php echo $video["video_title"]; ?></div>
                                <div id="empty"></div>
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Thumb</div>
                                <div style="float:left;margin-top:10px"><img class="lazy" data-original="<?php echo $thumb; ?>" border=0 /></div>
                                <div id="empty"></div>
                                
                                    <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Duration</div>
                                    <div style="float:left;margin-top:10px"><?php echo $listObj->milliToTimeString($video["video_duration"]*1000); ?></div>
                                    <div id="empty"></div>
                                   
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Author</div>
                                <div style="float:left;margin-top:10px"><?php echo $video["video_author"]; ?></div>
                                <div id="empty"></div>
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Description</div>
                                <div style="float:left;margin-top:10px"><?php echo $video["video_description"]; ?></div>
                                <div id="empty"></div>
                            </div>
                            <div style="margin-top:5px;border-top:1px dashed #CCC;padding-top:10px"></div>
                            <div class="videos_row_col7" style="float:right">
                                <div id="table_cell"><a href="javascript:previewVideo(<?php echo $videoId; ?>,'<?php echo $video["video_youtube_id"]; ?>',<?php echo $video["video_type"]; ?>,'<?php echo $video["video_link"]; ?>');" style="color:#FFF">Preview</a></div>
                            </div>
                            <div class="videos_row_col6" style="float:right;">
                                <div id="table_cell"><a href="javascript:editVideo(<?php echo $videoId; ?>);" style="color:#FFF">Edit</a></div>
                            </div>
                            <div id="empty"></div>
                        </div>
						<?php
						$i++;
					}
					?>
                    </div>
                    <?php
				}
			}
			
            $i = 1;		
			$arrayVideos = $listObj->getVideosList();
			if(count($arrayVideos)>0) {
				?>
				<div class="sources_divider" id="videos_opener_0" style="cursor:pointer" onclick="javascript:showVideos(0,'videos_opener_');">
                	<div class="float_left plus_btn"><img src="images/icons/plus_btn.png" /></div>
                    <div class="float_left source_row">OTHER VIDEOS</div>
                </div>
				<div id="empty"></div>
                <div id="0" style="display:none">
				<?php	
				
				foreach($arrayVideos as $videoId => $video) {											
					if($i % 2) {
						$class="alternate_table_row_white";
					} else {
						$class="alternate_table_row_grey";
					}
					
					?>
					<div id="video_row_<?php echo $videoId; ?>" style="cursor:pointer" onclick="javascript:showVideo(<?php echo $videoId; ?>);">
					<div class="videos_row_col1" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $i; ?></div>
					</div>
					<div class="videos_row_col2" class="<?php echo $class; ?>">
						<div id="table_cell"><input type="checkbox" name="videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_videos',this.checked);" /></div>
					</div>
					<div class="videos_row_col3" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $video["video_title"];?></div>
					</div>
					<div class="videos_row_col4" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $video["video_link"]; ?></div>
					</div> 
                    <div class="videos_row_col5" class="<?php echo $class; ?>">
						<div id="table_cell"><a href="javascript:refreshVideo(<?php echo $videoId; ?>);"><img src="images/icons/refresh.png" border=0 alt="refresh" title="refresh" /></a></div>
					</div>
					<div class="videos_row_col6" class="<?php echo $class; ?>">
						<div id="table_cell"><a href="javascript:delVideo(<?php echo $videoId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete" /></a></div>
					</div>
					<div id="empty"></div>
</div>
                    <div id="content_video_<?php echo $videoId; ?>" style="display:none;">
                        <div style="padding:10px">
                            <?php
                            $thumb = $video["video_thumb"];
                            
                            ?>
                            <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Title</div>
                            <div style="float:left;margin-top:10px"><?php echo $video["video_title"]; ?></div>
                            <div id="empty"></div>
                            <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Thumb</div>
                            <div style="float:left;margin-top:10px"><img class="lazy" data-original="<?php echo $thumb; ?>" border=0 /></div>
                            <div id="empty"></div>
                            
                                <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Duration</div>
                                <div style="float:left;margin-top:10px"><?php echo $listObj->milliToTimeString($video["video_duration"]*1000); ?></div>
                                <div id="empty"></div>
                               
                            <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Author</div>
                            <div style="float:left;margin-top:10px"><?php echo $video["video_author"]; ?></div>
                            <div id="empty"></div>
                            <div style="float:left;width:150px;font-weight:bold;margin-top:10px">Description</div>
                            <div style="float:left;margin-top:10px"><?php echo $video["video_description"]; ?></div>
                            <div id="empty"></div>
                        </div>
                        <div style="margin-top:5px;border-top:1px dashed #CCC;padding-top:10px"></div>
                        <div class="videos_row_col7" style="float:right">
                            <div id="table_cell"><a href="javascript:previewVideo(<?php echo $videoId; ?>,'<?php echo $video["video_youtube_id"]; ?>',<?php echo $video["video_type"]; ?>,'<?php echo $video["video_link"]; ?>');" style="color:#FFF">Preview</a></div>
                        </div>
                        <div class="videos_row_col6" style="float:right;">
                            <div id="table_cell"><a href="javascript:editVideo(<?php echo $videoId; ?>);" style="color:#FFF">Edit</a></div>
                        </div>
                        <div id="empty"></div>
                    </div>
					<?php
					$i++;
				}
				?>
                </div>
                <?php
			}
			?>
        </div>
    </div>
</form>      
