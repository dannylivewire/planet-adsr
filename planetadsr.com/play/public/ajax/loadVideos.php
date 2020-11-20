<?php
include '../common.php';

$container_style="";
if($settingObj->getLayout() == "0") {
	$container_style="float:left";
}
$arrayVideos = Array();
if($settingObj->getManagement() == 0) {
	if($settingObj->getScheduleVideo() == 1) {
		//get all shows for thumbs, according to what is set in settings
		$arrayCustomVideos = $listObj->getShowsList($settingObj->getPlaylistStart());
	} else {
		$arrayCustomVideos = $listObj->getVideosList();
	}
	$i=0;
	foreach($arrayCustomVideos as $videoId => $video) {
		
		$arrayVideos[$i] = Array();
		$arrayVideos[$i]["show_id"]=$videoId;
		$arrayVideos[$i]["video_youtube_id"]=$video["video_youtube_id"];
		$arrayVideos[$i]["video_id"]=$video["video_id"];
		$arrayVideos[$i]["video_title"] = $video["video_title"];
		$arrayVideos[$i]["video_description"]=$video["video_description"];
		$arrayVideos[$i]["video_thumb"]=$video["video_thumb"];
		$arrayVideos[$i]["video_views"]=0;
		$arrayVideos[$i]["video_author"]=$video["video_author"];
		$arrayVideos[$i]["video_type"]=$video["video_type"];
		$arrayVideos[$i]["video_link"]=$video["video_link"];
		$i++;
	}
	
} else {
	
	switch($settingObj->getSourceType()) {
		case 'channel':
			switch($settingObj->getSourceSource()) {
				case 1:
					//youtube
					
					$max = 25;
					
					$id_channel = $settingObj->getSourceLink();
					$ch = curl_init();
		
					// set url
					
					curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/channels?part=snippet&forUsername=".trim($settingObj->getSourceLink())."&key=".$settingObj->getYtApiKey());
					//return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
					// $output contains the output string
					$jsonResponse = curl_exec($ch);
			
					// close curl resource to free up system resources
					curl_close($ch); 
					$responseArray = json_decode($jsonResponse);
					
					if(isset($responseArray->items[0]->id)) {
						$id_channel = $responseArray->items[0]->id; 
					}
					
					$ch = curl_init();
		
					// set url
					
					curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=snippet%2Cid&channelId=".$id_channel."&type=video&order=date&maxResults=".$max."&key=".$settingObj->getYtApiKey());
					//return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
					// $output contains the output string
					$jsonResponse = curl_exec($ch);
			
					// close curl resource to free up system resources
					curl_close($ch); 
					
					//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
					//loop video data and inserting in db
					$responseArray=json_decode($jsonResponse);
					
					$arrayEntry = $responseArray->items;
					//$arrayEntry=$responseArray->feed->entry;
				
					for($i=0;$i<count($arrayEntry);$i++) {
						$video_youtube_id = $arrayEntry[$i]->id->videoId;
						//request for video
						$ch = curl_init();
		
						// set url
						
						curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						$videoArray = json_decode($jsonResponse);
						if(isset($videoArray->items[0])) {
							$videoData = $videoArray->items[0]->contentDetails;
							
							
							$duration = new DateInterval($videoData->duration);
							$duration = (60 * 60 * $duration->h) + (60 * $duration->i) + $duration->s; 
							
							$video_title = $arrayEntry[$i]->snippet->title;
							$video_author = $arrayEntry[$i]->snippet->channelTitle;
							$video_description = $arrayEntry[$i]->snippet->description;
							$video_thumb = "http://i.ytimg.com/vi/".$video_youtube_id."/1.jpg";
							$video_views = $videoArray->items[0]->statistics->viewCount;
							$video_duration = $duration;
							
							$arrayVideos[$i] = Array();
							$arrayVideos[$i]["video_youtube_id"]=$video_youtube_id;
							$arrayVideos[$i]["video_title"] = $video_title;
							$arrayVideos[$i]["video_description"]=$video_description;
							$arrayVideos[$i]["video_thumb"]=$video_thumb;
							$arrayVideos[$i]["video_views"]=$video_views;
							$arrayVideos[$i]["video_author"]=$video_author;
							$arrayVideos[$i]["video_type"]=1;
							$arrayVideos[$i]["show_id"]=$i;
							$arrayVideos[$i]["video_id"]=$i;
							$arrayVideos[$i]["video_link"]='';
							
						}
						
					}
					break;
				case 2:
				//vimeo
					$max = 25;
					
					
					//get videos
					$ch = curl_init();
					// set url
					curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$settingObj->getSourceLink()."/videos.json");
					//return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
					// $output contains the output string
					$jsonResponse = curl_exec($ch);
			
					// close curl resource to free up system resources
					curl_close($ch); 
					
					//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
					//loop video data and inserting in db
					$responseArray=json_decode($jsonResponse);
					
					for($i=0;$i<count($responseArray);$i++) {
						$video_vimeo_id = $responseArray[$i]->id;
						$video_title = $responseArray[$i]->title;
						$video_author = $responseArray[$i]->user_name;
						$video_description = $responseArray[$i]->description;
						if(isset($responseArray[$i]->stats_number_of_plays)) {
							$video_views = $responseArray[$i]->stats_number_of_plays;
						} else {
							$video_views = 0;
						}
						$video_duration = $responseArray[$i]->duration;
						$video_thumb = $responseArray[$i]->thumbnail_small;
						
						$arrayVideos[$i] = Array();
						$arrayVideos[$i]["video_youtube_id"]=$video_vimeo_id;
						$arrayVideos[$i]["video_title"] = $video_title;
						$arrayVideos[$i]["video_description"]=$video_description;
						$arrayVideos[$i]["video_thumb"]=$video_thumb;
						$arrayVideos[$i]["video_views"]=$video_views;
						$arrayVideos[$i]["video_author"]=$video_author;
						$arrayVideos[$i]["video_type"]=2;
						$arrayVideos[$i]["show_id"]=$i;
						$arrayVideos[$i]["video_id"]=$i;
						
					}
					
					break;
			}
			
			
			break;
		case 'playlist':
			switch($settingObj->getSourceSource()) {
				case 1:
				//youtube
					$max = 25;
					
					$playlistArr = explode("?list=",$settingObj->getSourceLink());
					if(count($playlistArr)>1) {
						$playlist_id = $playlistArr[1];
						$ch = curl_init();
				
						// set url
						curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet%2Cid%2CcontentDetails&maxResults=".$max."&playlistId=".$playlist_id."&key=".$settingObj->getYtApiKey());
						
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
						//loop video data and inserting in db
						$responseArray=json_decode($jsonResponse);
					
						$arrayEntry = $responseArray->items;
						//$arrayEntry=$responseArray->feed->entry;
					
						for($i=0;$i<count($arrayEntry);$i++) {
							$video_youtube_id = $arrayEntry[$i]->contentDetails->videoId;
							//request for video
							$ch = curl_init();
			
							// set url
							
							curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							$videoArray = json_decode($jsonResponse);
							if(isset($videoArray->items[0])) {
								$videoData = $videoArray->items[0]->contentDetails;
								
								
								$duration = new DateInterval($videoData->duration);
								$duration = (60 * 60 * $duration->h) + (60 * $duration->i) + $duration->s; 
								
								$video_title = $arrayEntry[$i]->snippet->title;
								$video_author = $arrayEntry[$i]->snippet->channelTitle;
								$video_description = $arrayEntry[$i]->snippet->description;
								$video_thumb = "http://i.ytimg.com/vi/".$video_youtube_id."/1.jpg";
								$video_views = $videoArray->items[0]->statistics->viewCount;
								$video_duration = $duration;
								
								//insert in db
								$arrayVideos[$i] = Array();
								$arrayVideos[$i]["video_youtube_id"]=$video_youtube_id;
								$arrayVideos[$i]["video_title"] = $video_title;
								$arrayVideos[$i]["video_description"]=$video_description;
								$arrayVideos[$i]["video_thumb"]=$video_thumb;
								$arrayVideos[$i]["video_views"]=$video_views;
								$arrayVideos[$i]["video_author"]=$video_author;
								$arrayVideos[$i]["video_type"]=1;
								$arrayVideos[$i]["show_id"]=$i;
								$arrayVideos[$i]["video_id"]=$i;
								$arrayVideos[$i]["video_link"]='';
							}
							
						}
					}
					
					break;
				case 2:
				//vimeo
					$max = 25;
					
					
					//get videos
					$ch = curl_init();
					// set url
					curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$settingObj->getSourceLink()."/videos.json");
					//return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
					// $output contains the output string
					$jsonResponse = curl_exec($ch);
			
					// close curl resource to free up system resources
					curl_close($ch); 
					
					//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
					//loop video data and inserting in db
					$responseArray=json_decode($jsonResponse);
					
					for($i=0;$i<count($responseArray);$i++) {
						$video_vimeo_id = $responseArray[$i]->id;
						$video_title = $responseArray[$i]->title;
						$video_author = $responseArray[$i]->user_name;
						$video_description = $responseArray[$i]->description;
						if(isset($responseArray[$i]->stats_number_of_plays)) {
							$video_views = $responseArray[$i]->stats_number_of_plays;
						} else {
							$video_views = 0;
						}
						$video_duration = $responseArray[$i]->duration;
						$video_thumb = $responseArray[$i]->thumbnail_small;
						
						$arrayVideos[$i] = Array();
						$arrayVideos[$i]["video_youtube_id"]=$video_vimeo_id;
						$arrayVideos[$i]["video_title"] = $video_title;
						$arrayVideos[$i]["video_description"]=$video_description;
						$arrayVideos[$i]["video_thumb"]=$video_thumb;
						$arrayVideos[$i]["video_views"]=$video_views;
						$arrayVideos[$i]["video_author"]=$video_author;
						$arrayVideos[$i]["video_type"]=2;
						$arrayVideos[$i]["show_id"]=$i;
						$arrayVideos[$i]["video_id"]=$i;
						
					}
					
					break;
			}
			
			break;
	}
}
	
foreach($arrayVideos as $index => $video) {
	
	?>
	
	<div id="video£<?php echo $video["video_youtube_id"];?>£<?php echo $video["show_id"]; ?>£<?php echo $video["video_type"]; ?>" class="video_container" style="<?php echo $container_style; ?>;cursor:pointer">
    	<input type="hidden" id="hidden_title_<?php echo $video["show_id"]; ?>" value="<?php echo htmlspecialchars(str_replace("|","",$video["video_title"])); ?>" />
        <input type="hidden" id="hidden_author_<?php echo $video["show_id"]; ?>" value="<?php echo htmlspecialchars(str_replace("|","",$video["video_author"])); ?>" />
        <input type="hidden" id="hidden_description_<?php echo $video["show_id"]; ?>" value="<?php echo htmlspecialchars(str_replace("|","",$video["video_description"])); ?>" />
		<div class="video">
			<a href="javascript:showVideoFromThumb('<?php echo $video["video_youtube_id"];?>',<?php echo $video["show_id"]; ?>,<?php echo $video["video_type"]; ?>);"  style="cursor:pointer">
		   
				
			
			<?php
			if($settingObj->getDisplayThumb() == '1') {
				$thumb_path = $video["video_thumb"];
				?>
				<div class="video_thumb">
                	<div style="margin-top: -10px;"><img src="<?php echo $thumb_path; ?>" border=0 width="<?php echo $settingObj->getThumbWidth(); ?>"></div>
                </div>
				<?php
			}
			?>
			
            <?php
			if($settingObj->getDisplayTitle() == '1' || $settingObj->getDisplayDescription() == '1' || $settingObj->getDisplayViews() == '1') {
			?>
			<div class="video_information">
			
            <?php
			if($settingObj->getDisplayTitle() == '1') {
				?>
				<div class="video_title">
				 <?php
				if(strlen($video["video_title"])>$settingObj->getTitleChars() && $settingObj->getTitleChars() > 0) {
					echo substr($video["video_title"],0,$settingObj->getTitleChars())."...";
				} else {
					//default is 20
					if(strlen($video["video_title"])>20) {
						echo substr($video["video_title"],0,20)."...";
					} else {
						echo $video["video_title"];
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
					 echo "by ".$video["video_author"];                
				?>
				</div>
				<?php
			}
			
			if($settingObj->getDisplayDescription() == '1' || $settingObj->getDisplayViews() == '1') {
			
				if($settingObj->getDisplayDescription() == '1') {
					?>
					<div class="video_description">
					<?php
					if(strlen($video["video_description"])>$settingObj->getDescriptionChars() && $settingObj->getDescriptionChars() > 0) {
						echo substr($video["video_description"],0,$settingObj->getDescriptionChars())."...";
					} else {
						//default is 110
						if(strlen($video["video_description"])>100) {
							echo substr($video["video_description"],0,100)."...";
						} else {
							echo $video["video_description"];
						}
					}
					?>
					</div>
					<?php
				}
				
				if($settingObj->getDisplayViews() == '1' && $video["video_views"]>0) {
					?>
					
					<div class="video_views">
					<?php
					echo "<strong>Views</strong>: ".$video["video_views"];
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
	<?php
}
echo "|".count($arrayVideos);
?>
