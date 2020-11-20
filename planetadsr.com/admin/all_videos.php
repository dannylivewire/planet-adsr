<script>
	function submitFilters() {
		var formObj = document.forms["filters"];
		formObj.submit();
	}
	
</script>

<div id="action_bar">
    <div id="action"><a onclick="javascript:delItems('manage_all_videos','all_videos[]','unpublishVideos','unpublish')">Unpublish</a></div>
    <div id="action"><a onclick="javascript:delItems('manage_all_videos','all_videos[]','publishVideos','publish')">On air</a></div>
    <div id="filter">
    	<form name="filters" method="post" action="">
            
            Last updated:&nbsp;<input type="text" id="date_to_select" name="date_to_select" value="<?php if(isset($_POST["date_to_select"])) { echo $_POST["date_to_select"]; } ?>" size=10 /><input type="hidden" name="video_upload_date" id="video_upload_date" value="<?php if(isset($_POST["video_upload_date"])) { echo $_POST["video_upload_date"];} ?>" />
            
        </form>
    </div>
    <div id="filter_submit"><a href="javascript:submitFilters();">Search</a></div>
</div>      
<form name="manage_all_videos" action="" method="post">
    <input type="hidden" name="operation" />
    <input type="hidden" name="all_videos[]" value=0 />
    <div id="table_container">
        <div id="table">
            
            <div class="all_videos_title_col1">
                <div id="table_cell">#</div>
            </div>
            <div class="all_videos_title_col2">
                <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_all_videos','all_videos[]');" /></div>
            </div>
            <div class="all_videos_title_col3">
                <div id="table_cell">Title&nbsp;<a href="?orderby=title&type=<?php echo $_SESSION["allVideosTitleOrder"]; ?>"><img src="images/orderby_<?php echo $_SESSION["allVideosTitleOrder"]; ?>.gif" border=0 /></a></div>
            </div>
            <div class="all_videos_title_col4">
                <div id="table_cell">Link</div>
            </div> 
            <div class="all_videos_title_col5">
                <div id="table_cell">Updated&nbsp;<a href="?orderby=date&type=<?php echo $_SESSION["allVideosUpdatedOrder"]; ?>"><img src="images/orderby_<?php echo $_SESSION["allVideosUpdatedOrder"]; ?>.gif" border=0 /></a></div>
            </div> 
            <div class="all_videos_title_col6">
                <div id="table_cell">On air&nbsp;<a href="?orderby=onair&type=<?php echo $_SESSION["allVideosOnairOrder"]; ?>"><img src="images/orderby_<?php echo $_SESSION["allVideosOnairOrder"]; ?>.gif" border=0 /></a></div>
            </div>
            <div class="all_videos_title_col7">
                <div id="table_cell"></div>
            </div>
            <div id="empty"></div>
            <?php
			$arrayChannels = $listObj->getSourcesList('channel');
			$arrayPlaylists = $listObj->getSourcesList('playlist');
			
			foreach($arrayChannels as $channelId => $channel) {		
					
				$i = 1;
				$arrayVideos = $listObj->getVideosList($channelId,$_SESSION["allVideosOrder"],$filterCondition);
				if(count($arrayVideos)>0) {
					?>
					<div class="sources_divider" style="cursor:pointer" id="channel_opener_<?php echo $channelId;?>" onclick="javascript:showVideos(<?php echo $channelId;?>,'channel_opener_');">
                    	<div class="float_left"><img src="images/icons/plus.png" /></div>
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
						
						
						
						<div class="all_videos_row_col1" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $i; ?></div>
						</div>
						<div class="all_videos_row_col2" class="<?php echo $class; ?>">
							<div id="table_cell"><input type="checkbox" name="all_videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_all_videos',this.checked);" /></div>
						</div>
						<div class="all_videos_row_col3" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_title"];?></div>
						</div>
						<div class="all_videos_row_col4" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_link"]; ?></div>
						</div> 
                        <div class="all_videos_row_col5" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo date("d/m/Y H:i",strtotime($video["video_upload_date"])); ?></div>
						</div> 
                        
                        <div class="all_videos_row_col6" class="<?php echo $class; ?>">
							<div id="table_cell">
                            
                            	<?php
								if($video["video_active"] == 1) {
									?>
                                    <a href="javascript:unpublishVideo(<?php echo $videoId; ?>);"><img src="images/icons/published.png" border=0 alt="unpublish" title="unpublish"/></a>
                                    <?php
								} else {
									?>
                                    <a href="javascript:publishVideo(<?php echo $videoId; ?>);"><img src="images/icons/unpublished.png" border=0 alt="publish" title="publish"/></a>
                                    <?php
								}
								?>
                            	
                             </div>
						</div>
						<div class="all_videos_row_col7" class="<?php echo $class; ?>">
							<div id="table_cell"><a href="javascript:delVideo(<?php echo $videoId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete"/></a></div>
						</div>
						<div id="empty"></div>
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
				$arrayVideos = $listObj->getVideosList($playlistId,$_SESSION["allVideosOrder"],$filterCondition);
				if(count($arrayVideos)>0) {	
					?>
					<div class="sources_divider" style="cursor:pointer" id="playlist_opener_<?php echo $playlistId;?>"  onclick="javascript:showVideos(<?php echo $playlistId;?>,'playlist_opener_');">
                    	<div class="float_left"><img src="images/icons/plus.png" /></div>
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
						
						<div class="all_videos_row_col1" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $i; ?></div>
						</div>
						<div class="all_videos_row_col2" class="<?php echo $class; ?>">
							<div id="table_cell"><input type="checkbox" name="all_videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_all_videos',this.checked);" /></div>
						</div>
						<div class="all_videos_row_col3" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_title"];?></div>
						</div>
						<div class="all_videos_row_col4" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo $video["video_link"]; ?></div>
						</div> 
                        <div class="all_videos_row_col5" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo date("d/m/Y H:i",strtotime($video["video_upload_date"])); ?></div>
						</div> 
                        <div class="all_videos_row_col6" class="<?php echo $class; ?>">
							<div id="table_cell">
                            	<?php
								if($video["video_active"] == 1) {
									?>
                                    <a href="javascript:unpublishVideo(<?php echo $videoId; ?>);"><img src="images/icons/published.png" border=0 alt="unpublish" title="unpublish"/></a>
                                    <?php
								} else {
									?>
                                    <a href="javascript:publishVideo(<?php echo $videoId; ?>);"><img src="images/icons/unpublished.png" border=0 alt="publish" title="publish"/></a>
                                    <?php
								}
								?>
                            </div>
						</div>
						<div class="all_videos_row_col7" class="<?php echo $class; ?>">
							<div id="table_cell"><a href="javascript:delVideo(<?php echo $videoId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete" /></a></div>
						</div>
						<div id="empty"></div>
						<?php
						$i++;
					}
					?>
                    </div>
                    <?php
				}
			}
			
            $i = 1;		
			$arrayVideos = $listObj->getVideosList(0,$_SESSION["allVideosOrder"],$filterCondition);
			if(count($arrayVideos)>0) {
				?>
				<div class="sources_divider" style="cursor:pointer" onclick="javascript:showVideos(0);">OTHER VIDEOS</div>
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
					
					<div class="all_videos_row_col1" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $i; ?></div>
					</div>
					<div class="all_videos_row_col2" class="<?php echo $class; ?>">
						<div id="table_cell"><input type="checkbox" name="videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_videos',this.checked);" /></div>
					</div>
					<div class="all_videos_row_col3" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $video["video_title"];?></div>
					</div>
					<div class="all_videos_row_col4" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $video["video_link"]; ?></div>
					</div> 
                    <div class="all_videos_row_col5" class="<?php echo $class; ?>">
							<div id="table_cell"><?php echo date("d/m/Y H:i",strtotime($video["video_upload_date"])); ?></div>
						</div> 
                    <div class="all_videos_row_col6" class="<?php echo $class; ?>">
						<div id="table_cell">
                        	<?php
							if($video["video_active"] == 1) {
								?>
								<a href="javascript:unpublishVideo(<?php echo $videoId; ?>);"><img src="images/icons/published.png" border=0 alt="unpublish" title="unpublish"/></a>
								<?php
							} else {
								?>
								<a href="javascript:publishVideo(<?php echo $videoId; ?>);"><img src="images/icons/unpublished.png" border=0 alt="publish" title="publish"/></a>
								<?php
							}
							?>
                        </div>
					</div>
                    
					<div class="all_videos_row_col7" class="<?php echo $class; ?>">
						<div id="table_cell"><a href="javascript:delVideo(<?php echo $videoId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete" /></a></div>
					</div>
					<div id="empty"></div>
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