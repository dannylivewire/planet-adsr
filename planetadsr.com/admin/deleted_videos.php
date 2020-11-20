<?php
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
}


if(isset($_POST["operation"]) && $_POST["operation"] != '' && isset($_POST["videos"])) {
	$arrVideos=$_POST["videos"];
	$qryString = "0";
	for($i=0;$i<count($arrVideos); $i++) {
		$qryString .= ",".$arrVideos[$i];
	}
		
	switch($_POST["operation"]) {
		
			
		case "restoreVideos":
			$videoObj->restoreVideos($qryString);
			header('Location: video_archive.php?tab=2');
			break;
		case "delVideos":
			$videoObj->delVideosPermanently($qryString);
			header('Location: video_archive.php?tab=2');
			break;
		
		
	}                
	
}

include 'include/header.php';
?>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script language="javascript" type="text/javascript">
	
	
	function goToByScroll(id){
	      $('html,body').animate({scrollTop: $("#"+id).offset().top},'slow');
	}
	
	
	
	
	function restoreVideo(video_id) {
		$.ajax({
		  url: 'ajax/restoreVideos.php?video_id='+video_id,
		  success: function(data) {
			document.location.href="video_archive.php?tab=2";
		  }
		});
	}
	
	function deleteVideos(form_name,check_name) {
		var formObj = document.forms[form_name];
		if(checkBoxesSel(form_name,check_name)) {
			if(confirm("If you delete these videos, when you refresh the sources they belong to, they could be re-downloaded again. Are you sure?")) {
				formObj.operation.value='delVideos';
				formObj.submit();
			}
		}
	}
	
	function showVideos(divid) {
		if($('#'+divid).css("display") == "none") {
			$('#'+divid).fadeIn();
		} else {
			$('#'+divid).fadeOut();
		}
	}
</script>
<div id="top_bg_container_all">
    <div id="container_all">
        <div id="container_content">
        <?php
        include 'include/menu.php'; 
        ?>
        <div id="action_bar">
        	
            <div id="action"><a onclick="javascript:delItems('manage_videos','videos[]','restoreVideos','restore')">Restore</a></div>
            <div id="action"><a onclick="javascript:deleteVideos('manage_videos','videos[]')">Delete</a></div>
        </div>      
        <form name="manage_videos" action="" method="post">
            <input type="hidden" name="operation" />
            <input type="hidden" name="videos[]" value=0 />
            <div id="table_container">
                <div id="table">
                    
                    <div class="del_videos_title_col1">
                        <div id="table_cell">#</div>
                    </div>
                    <div class="del_videos_title_col2">
                        <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_videos','videos[]');" /></div>
                    </div>
                    <div class="del_videos_title_col3">
                        <div id="table_cell">Title</div>
                    </div>
                    <div class="del_videos_title_col4">
                        <div id="table_cell">Link</div>
                    </div> 
                    <div class="del_videos_title_col5">
                        <div id="table_cell"></div>
                    </div>
                    
                    <div id="empty"></div>
                    <?php
                    $arrayChannels = $listObj->getSourcesList('channel');
                    $arrayPlaylists = $listObj->getSourcesList('playlist');
                    
                    
                    
                    foreach($arrayChannels as $channelId => $channel) {		
                            
                        $i = 1;
                        $arrayVideos = $listObj->getDeletedVideosList($channelId);
                        if(count($arrayVideos)>0) {
                            ?>
                            <div class="sources_divider" style="cursor:pointer" onclick="javascript:showVideos(<?php echo $channelId; ?>);">
                            	<div class="source_row">CHANNEL: <?php echo $channel["source_title"]; ?></div>
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
                                
                                
                            	
                                <div class="del_videos_row_col1" class="<?php echo $class; ?>">
                                    <div id="table_cell"><?php echo $i; ?></div>
                                </div>
                                <div class="del_videos_row_col2" class="<?php echo $class; ?>">
                                    <div id="table_cell"><input type="checkbox" name="videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_videos',this.checked);" /></div>
                                </div>
                                <div class="del_videos_row_col3" class="<?php echo $class; ?>">
                                    <div id="table_cell"><?php echo $video["video_title"];?></div>
                                </div>
                                <div class="del_videos_row_col4" class="<?php echo $class; ?>">
                                    <div id="table_cell"><?php echo $video["video_link"]; ?></div>
                                </div> 
                               
                                <div class="del_videos_row_col5" class="<?php echo $class; ?>">
                                    <div id="table_cell"><a href="javascript:restoreVideo(<?php echo $videoId; ?>);"><img src="images/icons/restore.png" border=0 alt="restore" title="restore" /></a></div>
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
                        $arrayVideos = $listObj->getDeletedVideosList($playlistId);
                        if(count($arrayVideos)>0) {	
                            ?>
                            <div class="sources_divider" style="cursor:pointer" onclick="javascript:showVideos(<?php echo $playlistId; ?>);">PLAYLIST: <?php echo $playlist["source_title"]; ?></div>
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
                                
                                <div class="del_videos_row_col1" class="<?php echo $class; ?>">
                                    <div id="table_cell"><?php echo $i; ?></div>
                                </div>
                                <div class="del_videos_row_col2" class="<?php echo $class; ?>">
                                    <div id="table_cell"><input type="checkbox" name="videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_videos',this.checked);" /></div>
                                </div>
                                <div class="del_videos_row_col3" class="<?php echo $class; ?>">
                                    <div id="table_cell"><?php echo $video["video_title"];?></div>
                                </div>
                                <div class="del_videos_row_col4" class="<?php echo $class; ?>">
                                    <div id="table_cell"><?php echo $video["video_link"]; ?></div>
                                </div> 
                                
                                <div class="del_videos_row_col5" class="<?php echo $class; ?>">
                                    <div id="table_cell"><a href="javascript:restoreVideo(<?php echo $videoId; ?>);"><img src="images/icons/restore.png" border=0 alt="restore" title="restore" /></a></div>
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
                    $arrayVideos = $listObj->getDeletedVideosList();
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
                            
                            <div class="del_videos_row_col1" class="<?php echo $class; ?>">
                                <div id="table_cell"><?php echo $i; ?></div>
                            </div>
                            <div class="del_videos_row_col2" class="<?php echo $class; ?>">
                                <div id="table_cell"><input type="checkbox" name="videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_videos',this.checked);" /></div>
                            </div>
                            <div class="del_videos_row_col3" class="<?php echo $class; ?>">
                                <div id="table_cell"><?php echo $video["video_title"];?></div>
                            </div>
                            <div class="del_videos_row_col4" class="<?php echo $class; ?>">
                                <div id="table_cell"><?php echo $video["video_link"]; ?></div>
                            </div> 
                            
                            <div class="del_videos_row_col5" class="<?php echo $class; ?>">
                                <div id="table_cell"><a href="javascript:restoreVideo(<?php echo $videoId; ?>);"><img src="images/icons/restore.png" border=0  alt="restore" title="restore"/></a></div>
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
                    <div id="empty"></div>
                </div>
                <div id="rowspace"></div>
            </div>
        </form>      
     </div>
   </div>
</div>

<?php 
include 'include/footer.php';
?>