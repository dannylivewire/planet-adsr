<script>
	function disableOrder() {
		$('#order_0').attr("disabled","disabled");
	}
	function updateOrder() {
		var formObj = document.forms['manage_onair_videos'];
		var checkboxesL = formObj['onair_videos[]'].length;
		var arrIds = new Array();
		
		for(var i=0; i< checkboxesL; i++) {
			if(formObj['onair_videos[]'][i].checked) {
				arrIds.push(formObj['onair_videos[]'][i].value);
			}
			
		}
		$('.option_order_multiple').each(function() {
			$(this).fadeIn();
		});
		for(var j = 0;j <arrIds.length;j++) {
			$('#option_order_multiple_'+arrIds[j]).fadeOut();
		}
	}
</script>
<form name="manage_onair_videos" action="" method="post">
<div id="action_bar">
    <div id="action"><a onclick="javascript:delItems('manage_onair_videos','onair_videos[]','unpublishVideos','unpublish')">Unpublish</a></div>
    <div id="action">
    	Change order:&nbsp;
        <select name="orderby_total" id="order_0" style="width:190px">
            <option value="">Choose...</option>
            <option value="top">Move to top</option>
            <option value="bottom">Move to bottom</option>
            <optgroup label="Move below...">
                <?php
                $arrayRemaining=$listObj->getOnairVideosExceptList(0);
                if(count($arrayRemaining)>0) {
                    $j = 1;
                    foreach($arrayRemaining as $remaining => $remValue) {
                        
                        ?>
                        <option class="option_order_multiple" id="option_order_multiple_<?php echo $remaining; ?>" value="<?php echo $remValue["video_order"]; ?>"><?php echo $j." - ".$remValue["video_title"]; ?></option>
                        <?php
                        $j++;
                    }
                }
                ?>
            </optgroup>
        </select>&nbsp;
        <input type="button" name="change" value="Save" onclick="javascript:delItems('manage_onair_videos','onair_videos[]','orderVideos','change order');" />
    	
    </div>
</div>      

    <input type="hidden" name="operation" />
    <input type="hidden" name="onair_videos[]" value=0 />
    <div id="table_container">
        <div id="table">
            
            <div class="onair_videos_title_col1">
                <div id="table_cell">#</div>
            </div>
            <div class="onair_videos_title_col2">
                <div id="table_cell"><input type="checkbox" name="selectAll" onclick="javascript:selectCheckbox('manage_onair_videos','onair_videos[]');" /></div>
            </div>
            <div class="onair_videos_title_col3">
                <div id="table_cell">Title</div>
            </div>
            <div class="onair_videos_title_col4">
                <div id="table_cell">Link</div>
            </div> 
            <div class="onair_videos_title_col5">
                <div id="table_cell">Source</div>
            </div> 
            <div class="onair_videos_title_col6">
                <div id="table_cell">Order</div>
            </div>
            <div class="onair_videos_title_col7">
                <div id="table_cell"></div>
            </div>
            <div id="empty"></div>
            <?php
			
					
			$i = 1;
			$arrayVideos = $listObj->getOnairVideosList();
			if(count($arrayVideos)>0) {
				
				
				foreach($arrayVideos as $videoId => $video) {												
					if($i % 2) {
						$class="alternate_table_row_white";
					} else {
						$class="alternate_table_row_grey";
					}
					
					?>
					
					
				
					<div class="onair_videos_row_col1" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $i; ?></div>
					</div>
					<div class="onair_videos_row_col2" class="<?php echo $class; ?>">
						<div id="table_cell"><input type="checkbox" name="onair_videos[]" value="<?php echo $videoId; ?>" onclick="javascript:disableSelectAll('manage_onair_videos',this.checked);updateOrder();" /></div>
					</div>
					<div class="onair_videos_row_col3" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $video["video_title"];?></div>
					</div>
					<div class="onair_videos_row_col4" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $video["video_link"]; ?></div>
					</div> 
                    <div class="onair_videos_row_col5" class="<?php echo $class; ?>">
						<div id="table_cell"><?php echo $video["video_source"]; ?></div>
					</div> 
                    <div class="onair_videos_row_col6" class="<?php echo $class;?>">
                        <div id="table_cell">
                            Change:&nbsp;
                            <select name="order" id="order_<?php echo $videoId; ?>" style="width:190px">
                                <option value="">Choose...</option>
                                <option value="top">Move to top</option>
                                <option value="bottom">Move to bottom</option>
                                <optgroup label="Move below...">
                                    <?php
                                    $arrayRemaining=$listObj->getOnairVideosExceptList($videoId);
                                    if(count($arrayRemaining)>0) {
										$j = 1;
                                        foreach($arrayRemaining as $remaining => $remValue) {
											if($j==$i) {
												$j++;
											}
                                            ?>
                                            <option value="<?php echo $remValue["video_order"]; ?>"><?php echo $j." - ".$remValue["video_title"]; ?></option>
                                            <?php
											$j++;
                                        }
                                    }
                                    ?>
                                </optgroup>
                            </select>&nbsp;
                            <input type="button" name="change" value="Save" onclick="javascript:changeOrder(<?php echo $videoId;?>);" />
                        </div>
                    </div>
					<div class="onair_videos_row_col7" class="<?php echo $class; ?>">
						<div id="table_cell"><a href="javascript:unpublishVideo(<?php echo $videoId; ?>);"><img src="images/icons/published.png" border=0 /></a></div>
					</div>
					<div id="empty"></div>
					<?php
					$i++;
				}
			}
		
		?>
        </div>
    </div>
</form>      