<div id="action_bar">
    <div id="action"><a onclick="javascript:delItems('manage_sources','sources[]','delSources','delete')">Delete</a></div>
    <div id="action"><a onclick="javascript:delItems('manage_sources','sources[]','refreshSources','refresh')">Refresh</a></div>
    <div id="action"><a onclick="javascript:delItems('manage_sources','sources[]','addmoreSources','add more videos for')">Add more</a></div>
</div>  
<script>
	function editSource(channel_id) {
		if($('#edit_button_'+channel_id).html() == 'Edit') {
			//edit
			$('#display_label_'+channel_id).fadeOut();
			$('#edit_label_'+channel_id).fadeIn();
			$('#edit_button_'+channel_id).html('Save');
			$('#edit_button_container_'+channel_id).css('background-color','#FC3');
		} else {
			//save
			$.ajax({
			  url: 'ajax/editChannel.php?channel_id='+channel_id+'&name='+$('#label_input_'+channel_id).val(),
			  success: function(data) {
				 $('#display_label_'+channel_id).html($('#label_input_'+channel_id).val());
				 $('#edit_label_'+channel_id).fadeOut();
				 $('#display_label_'+channel_id).fadeIn();				
				 $('#edit_button_'+channel_id).html('Edit');
				 $('#edit_button_container_'+channel_id).css('background-color','#F66');
			  }
			});
		}
		
	}
</script>    
<form name="manage_sources" action="" method="post">
    <input type="hidden" name="operation" />
    <input type="hidden" name="sources[]" value=0 />
    <div id="table_container">
        <div id="table">
            
            <div class="sources_title_col1">
                <div id="table_cell">#</div>
            </div>
            <div class="sources_title_col2">
                <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_sources','sources[]');" /></div>
            </div>
            <div class="sources_title_col3">
                <div id="table_cell">Title</div>
            </div>
            <div class="sources_title_col4">
                <div id="table_cell">Videos</div>
            </div> 
            <div class="sources_title_col5">
                <div id="table_cell">Link</div>
            </div> 
            <div class="sources_title_col6">
                <div id="table_cell"></div>
            </div>
            <div class="sources_title_col7">
                <div id="table_cell"></div>
            </div>
            <div class="sources_title_col8">
                <div id="table_cell"></div>
            </div>
            <div class="sources_title_col9">
                <div id="table_cell"></div>
            </div>
            <div id="empty"></div>
            <?php
			$arrayChannels = $listObj->getSourcesList('channel');
			$arrayPlaylists = $listObj->getSourcesList('playlist');
			$arrayVideos = $listObj->getVideosList();
			if(count($arrayChannels)>0) {
				if(count($arrayPlaylists)>0 || count($arrayVideos)>0) {
					//show divider
					?>
                    <div class="sources_divider" id="channels_opener" style="cursor:pointer" onclick="javascript:showSource('channels_list','channels_opener');">
                    	<div class="float_left plus_btn"><img src="images/icons/minus_btn.png" /></div>
                        <div class="float_left source_row">CHANNELS</div>
                    </div>
                    <div id="empty"></div>
                    <?php
				}
			}
			$i=1;
			?>
            <div id="channels_list">
            <?php
			foreach($arrayChannels as $channelId => $channel) {		
																	
				if($i % 2) {
					$class="alternate_table_row_white";
				} else {
					$class="alternate_table_row_grey";
				}
				
				?>
				<div class="sources_row_col1 <?php echo $class; ?>">
					<div id="table_cell"><?php echo $i; ?></div>
				</div>
				<div class="sources_row_col2 <?php echo $class; ?>">
					<div id="table_cell"><input type="checkbox" name="sources[]" value="<?php echo $channelId; ?>" onclick="javascript:disableSelectAll('manage_sources',this.checked);" /></div>
				</div>
				<div class="sources_row_col3 <?php echo $class; ?>">
					<div id="table_cell">
					<span id="display_label_<?php echo $channelId; ?>"><?php echo $channel["source_title"];?></span>
                    <span id="edit_label_<?php echo $channelId; ?>" style="display:none"><input type="text" style="width:100%" id="label_input_<?php echo $channelId; ?>" value="<?php echo $channel["source_title"];?>" /></span>
					</div>
				</div>
				<div class="sources_row_col4 <?php echo $class; ?>">
					<div id="table_cell"><?php echo $channel["tot_videos"]; ?></div>
				</div> 
				<div class="sources_row_col5 <?php echo $class; ?>">
					<div id="table_cell"><?php echo $channel["source_external_link"];?></div>
				</div> 
                <div class="sources_row_col6 <?php echo $class; ?>" id="edit_button_container_<?php echo $channelId; ?>">
					<div id="table_cell"><a href="javascript:editSource(<?php echo $channelId; ?>);" style="color:#FFF" id="edit_button_<?php echo $channelId; ?>">Edit</a></div>
				</div>
				<div class="sources_row_col7 <?php echo $class; ?>">
					<div id="table_cell"><a href="javascript:refreshSource(<?php echo $channelId; ?>);"><img src="images/icons/refresh.png" border=0 alt="refresh" title="refresh" /></a></div>
				</div>
                <div class="sources_row_col8 <?php echo $class; ?>">
					<div id="table_cell"><a href="javascript:addmoreSource(<?php echo $channelId; ?>);"><img src="images/icons/addmore.png" border=0 alt="add more" title="add more" /></a></div>
				</div>
				<div class="sources_row_col9 <?php echo $class; ?>">
					<div id="table_cell"><a href="javascript:delSource(<?php echo $channelId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete" /></a></div>
				</div>
				<div id="empty"></div>
				<?php
				$i++;
			}
			?>
            </div>
            <?php
			
			if(count($arrayPlaylists)>0) {
				if(count($arrayChannels)>0 || count($arrayVideos)>0) {
					//show divider
					?>
                    <div class="sources_divider" id="playlists_opener" style="cursor:pointer" onclick="javascript:showSource('playlists_list','playlists_opener');">
                    	<div class="float_left plus_btn"><img src="images/icons/minus_btn.png" /></div>
                        <div class="float_left source_row">PLAYLISTS</div>
                    </div>
                    <?php
				}
			}
			$i=1;
			?>
            <div id="playlists_list">
            <?php
			foreach($arrayPlaylists as $playlistId => $playlist) {		
																	
				if($i % 2) {
					$class="alternate_table_row_white";
				} else {
					$class="alternate_table_row_grey";
				}
				
				?>
				<div class="sources_row_col1 <?php echo $class; ?>">
					<div id="table_cell"><?php echo $i; ?></div>
				</div>
				<div class="sources_row_col2 <?php echo $class; ?>">
					<div id="table_cell"><input type="checkbox" name="sources[]" value="<?php echo $playlistId; ?>" onclick="javascript:disableSelectAll('manage_sources',this.checked);" /></div>
				</div>
				<div class="sources_row_col3 <?php echo $class; ?>">
					<div id="table_cell"><?php echo $playlist["source_title"];?></div>
				</div>
				<div class="sources_row_col4 <?php echo $class; ?>">
					<div id="table_cell"><?php echo $playlist["tot_videos"]; ?></div>
				</div> 
				<div class="sources_row_col5 <?php echo $class; ?>">
					<div id="table_cell"><?php echo $playlist["source_external_link"];?></div>
				</div> 
                <div class="sources_row_col6 <?php echo $class; ?>" style="background:none">
						<div id="table_cell" class="<?php echo $class; ?>"></div>
				</div>
				<div class="sources_row_col7 <?php echo $class; ?>">
					<div id="table_cell"><a href="javascript:refreshSource(<?php echo $playlistId; ?>);"><img src="images/icons/refresh.png" border=0 alt="refresh" title="refresh" /></a></div>
				</div>
                <div class="sources_row_col8 <?php echo $class; ?>">
					<div id="table_cell"><a href="javascript:addmoreSource(<?php echo $playlistId; ?>);"><img src="images/icons/addmore.png" border=0 alt="add more" title="add more" /></a></div>
				</div>
				<div class="sources_row_col9 <?php echo $class; ?>">
					<div id="table_cell"><a href="javascript:delSource(<?php echo $playlistId; ?>);"><img src="images/icons/delete.png" border=0 alt="delete" title="delete" /></a></div>
				</div>
				<div id="empty"></div>
				<?php
				$i++;
			}
			?>
            </div>
            <?php
			
			if(count($arrayVideos)>0) {
				if(count($arrayChannels)>0 || count($arrayPlaylists)>0) {
					//show divider
					?>
                    <div class="sources_divider" id="videos_opener" style="cursor:pointer" onclick="javascript:showSource('videos_list','videos_opener');">
                    	<div class="float_left plus_btn"><img src="images/icons/minus_btn.png" /></div>
                        <div class="float_left source_row">OTHERS</div>
                    </div>
                    <?php
				}
			
			$i=1;
			
				
			?>
            <div id="videos_list">
                <div class="sources_row_col1 <?php echo $class; ?>">
                    <div id="table_cell"><?php echo $i; ?></div>
                </div>
                <div class="sources_row_col2 <?php echo $class; ?>">
                    <div id="table_cell"><input type="checkbox" name="sources[]" value="'other'" onclick="javascript:disableSelectAll('manage_sources',this.checked);" /></div>
                </div>
                <div class="sources_row_col3 <?php echo $class; ?>">
                    <div id="table_cell">Other videos</div>
                </div>
                <div class="sources_row_col4 <?php echo $class; ?>">
                    <div id="table_cell"><?php echo count($arrayVideos); ?></div>
                </div> 
                <div class="sources_row_col5 <?php echo $class; ?>">
                    <div id="table_cell"></div>
                </div> 
                <div class="sources_row_col6 <?php echo $class; ?>" style="background:none">
                    <div id="table_cell"></div>
                </div>            
                <div class="sources_row_col7 <?php echo $class; ?>" style="background:none">
                    <div id="table_cell" class="<?php echo $class; ?>"></div>
                </div>
                <div class="sources_row_col8 <?php echo $class; ?>" style="background:none">
                    <div id="table_cell" class="<?php echo $class; ?>"></div>
                </div>
                <div class="sources_row_col9 <?php echo $class; ?>">
                    <div id="table_cell"><a href="javascript:delSource('other');"><img src="images/icons/delete.png" border=0 alt="delete" title="delete" /></a></div>
                </div>
            <div id="empty"></div>
            </div>
			<?php
$i++;
			}
			
			?>
        </div>
    </div>
</form>      
