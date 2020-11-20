<?php
include '../common.php';
$container_style="";
if($settingObj->getLayout() == "0") {
	$container_style="float:left";
}
$video_id=$_GET["video"];
$origId = $_GET["video_id"];
$video_type = $_GET["video_type"];
if($settingObj->getManagement() == 0) {
	$arrayVideo = $listObj->getVideoData($video_id,$settingObj->getScheduleVideo());
	
	$video_youtube_id = $arrayVideo[$video_id]["video_youtube_id"];
	$video_title = $arrayVideo[$video_id]["video_title"];
	$video_description = $arrayVideo[$video_id]["video_description"];
	$video_thumb = $arrayVideo[$video_id]["video_thumb"];
	$video_views = 0;
	$video_author = $arrayVideo[$video_id]["video_author"];
	$video_db_id = $arrayVideo[$video_id]["video_id"];
	$video_link = $arrayVideo[$video_id]["video_link"];
	
} else {
	if($video_type==1) {
		$ch = curl_init();
	
		// set url
		curl_setopt($ch, CURLOPT_URL, "https://gdata.youtube.com/feeds/api/videos/".$video_id."?v=2&alt=json");
	
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
		// $output contains the output string
		$jsonResponse = curl_exec($ch);
	
		// close curl resource to free up system resources
		curl_close($ch); 
		//$jsonResponse = file_get_contents("https://gdata.youtube.com/feeds/api/videos/".$video_id."?v=2&alt=json");
		$responseArray=json_decode($jsonResponse);
		$arrayEntry=$responseArray->entry;					
		$video_youtube_id = substr($arrayEntry->{'id'}->{'$t'},-11);
		$video_title = $arrayEntry->title->{'$t'};
		$video_description = $arrayEntry->{'media$group'}->{'media$description'}->{'$t'};
		$video_thumb = "http://i.ytimg.com/vi/".$video_youtube_id."/default.jpg";
		$video_views = $arrayEntry->{'yt$statistics'}->{'viewCount'};
		if(isset($arrayEntry->{'media$group'}->{'media$credit'})) {
			$video_author = $arrayEntry->{'media$group'}->{'media$credit'}[0]->{'yt$display'};
		} else {
			$video_author = $arrayEntry->author[0]->name->{'$t'};
		}
	} else if($video_type == 2) {
		$ch = curl_init();
		// set url
		curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/".$video_id.".json");

		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string
		$jsonResponse = curl_exec($ch);

		// close curl resource to free up system resources
		curl_close($ch); 
		//$jsonResponse = file_get_contents("https://gdata.youtube.com/feeds/api/videos/".substr($arrayCustomVideos[$i],-11)."?v=2&alt=json");
		$responseArray=json_decode($jsonResponse);
		$video_youtube_id = $video_id;
		$video_title = $responseArray[0]->title;
		$video_author = $responseArray[0]->user_name;
		$video_description = $responseArray[0]->description;
		$video_duration = $responseArray[0]->duration;
		$video_thumb = $responseArray[0]->thumbnail_small;
		$video_views = $responseArray[0]->stats_number_of_plays;
		$video_embed = "";
		$video_link = "";
	}
}

?>
    
<div id="video£<?php echo addslashes($video_youtube_id);?>£<?php echo $origId; ?>£<?php echo $video_type; ?>" class="video_container" style="<?php echo $container_style; ?>;cursor:pointer">
	<input type="hidden" id="hidden_title_<?php echo $origId; ?>" value="<?php echo htmlspecialchars($video_title); ?>" />
    <input type="hidden" id="hidden_author_<?php echo $origId; ?>" value="<?php echo htmlspecialchars($video_author); ?>" />
    <input type="hidden" id="hidden_description_<?php echo $origId; ?>" value="<?php echo htmlspecialchars($video_description); ?>" />
    <div class="video">
        <a href="javascript:showVideoFromThumb('<?php echo addslashes($video_youtube_id);?>',<?php echo $origId; ?>,<?php echo $video_type; ?>);"  style="cursor:pointer">
       
            
        
        <?php
        if($settingObj->getDisplayThumb() == '1') {
			$thumb_path = $video_thumb;
			
            ?>
            <div class="video_thumb"><div style="margin-top: -10px;"><img src="<?php echo $thumb_path; ?>" border=0 width="<?php echo $settingObj->getThumbWidth(); ?>"></div></div>
            <?php
        }
        
		
			if($settingObj->getDisplayTitle() == '1' || $settingObj->getDisplayDescription() == '1' || $settingObj->getDisplayViews() == '1') {
			?>
			<div class="video_information">
        	
				<?php
                if($settingObj->getDisplayTitle() == '1') {
                ?>
                 <div class="video_title">
                 <?php
                if(strlen($video_title)>$settingObj->getTitleChars() && $settingObj->getTitleChars() > 0) {
                    echo substr($video_title,0,$settingObj->getTitleChars())."...";
                } else {
                    //default is 20
                    if(strlen($video_title)>20) {
                        echo substr($video_title,0,20)."...";
                    } else {
                        echo $video_title;
                    }
                }
                ?>
                </div>
                <?php
            }
			
			if($settingObj->getDisplayAuthor() == '1') {
				?>
				 <div class="video_author">
				 <?php            
					 echo "by ".$video_author;                
				?>
				</div>
				<?php
			}
                
            
            if($settingObj->getDisplayDescription() == '1' || $settingObj->getDisplayViews() == '1') {
            
                if($settingObj->getDisplayDescription() == '1') {
                    ?>
                    <div class="video_description">
                    <?php
                    if(strlen($video_description)>$settingObj->getDescriptionChars() && $settingObj->getDescriptionChars() > 0) {
                        echo substr($video_description,0,$settingObj->getDescriptionChars())."...";
                    } else {
                        //default is 110
                        if(strlen($video_description)>100) {
                            echo substr($video_description,0,100)."...";
                        } else {
                            echo $video_description;
                        }
                    }
                    ?>
                    </div>
                    <?php
                }
                
                if($settingObj->getDisplayViews() == '1' && $video_views>0) {
                    ?>
                    
                    <div class="video_views">
                    <?php
                    echo "<strong>Views</strong>: ".$video_views;
                    ?>
                    </div>
                    <?php
                }
                
                ?>
             
             <?php
            }
            ?>
            </div>
            <?php
			}
			?>
        <div class="clearboth"></div>
        </a>
    </div>
    <div class="clearboth"></div>
</div>
    
