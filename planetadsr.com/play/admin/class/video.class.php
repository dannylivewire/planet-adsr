<?php
class video {
	private static $video_id;
	private static $qryVideo;
	
	public function setVideo($id) {
		
		$rsVideo = mysql_query("SELECT * FROM yt_videos WHERE video_id = '".mysql_real_escape_string($id)."'");
		
		$rowVideo = mysql_fetch_array($rsVideo);
		video::$qryVideo = $rowVideo;
		video::$video_id=$rowVideo["video_id"];
		
	}
	
	public function getVideoId() {
		return video::$video_id;
	}
	
	public function getVideoYoutubeId() {
		return video::$qryVideo["video_youtube_id"];
	}
	
	public function getVideoTitle() {
		return stripslashes(video::$qryVideo["video_title"]);
	}
	
	public function getVideoDescription() {
		return stripslashes(video::$qryVideo["video_description"]);
	}
	
	public function getVideoLink() {
		return video::$qryVideo["video_link"];
	}
	public function getVideoType() {
		return video::$qryVideo["video_type"];
	}
	public function getVideoDuration() {
		return video::$qryVideo["video_duration"];
	}
	public function getVideoThumb() {
		return video::$qryVideo["video_thumb"];
	}
	public function getVideoAuthor() {
		return video::$qryVideo["video_author"];
	}

	public function getVideoActive() {
		return video::$qryVideo["video_deleted"];
	}
	public function getVideoDeleted() {
		return video::$qryVideo["video_deleted"];
	}
	
	public function insertVideos($settingObj) {
		
		//check how many videos there are in table to set right order
		$videoQry= mysql_query("SELECT * FROM yt_videos");
		$totVideos = mysql_num_rows($videoQry);
		$new_order = $totVideos+1;
		$result = 0;
		$arrayCustomVideos=$_POST["video_link"];
		$arryVideosTypes= $_POST["video_type"];
		
		for($i=0;$i<count($arrayCustomVideos);$i++) {
			$videoArr=explode("/",$arrayCustomVideos[$i]);
			$video_id = $videoArr[count($videoArr)-1];
			
			
			//check if video is already in db
			$checkQry = mysql_query("SELECT * FROM yt_videos WHERE video_youtube_id = '".mysql_real_escape_string($video_id)."'");
			if(mysql_num_rows($checkQry) == 0) {
				switch($arryVideosTypes[$i]) {
					case 1:
						$ch = curl_init();
						// set url
						curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_id."&key=".$settingObj->getYtApiKey());
						curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						
						$videoArray = json_decode($jsonResponse);
										
						$videoData = $videoArray->items[0]->contentDetails;
						
						
						$duration = new DateInterval($videoData->duration);
						$duration = (60 * 60 * $duration->h) + (60 * $duration->i) + $duration->s; 
						
						$video_title = $videoArray->items[0]->snippet->title;
						$video_author = $videoArray->items[0]->snippet->channelTitle;
						$video_description = $videoArray->items[0]->snippet->description;
						$video_thumb = "http://i.ytimg.com/vi/".$video_id."/1.jpg";
						$video_views = $videoArray->items[0]->statistics->viewCount;
						$video_duration = $duration;
						//insert in db
						mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES(0,'".mysql_real_escape_string($video_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','http://youtu.be/".mysql_real_escape_string($video_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,1)");
						$new_order++;
						$result++;
						break;
					case 2:
						$ch = curl_init();
		
						// set url
						curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/".$video_id.".json");
						curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".substr($arrayCustomVideos[$i],-11)."?v=2&alt=json");
						$responseArray=json_decode($jsonResponse);
						$video_vimeo_id = $video_id;
						$video_title = $responseArray[0]->title;
						$video_author = $responseArray[0]->user_name;
						$video_description = $responseArray[0]->description;
						$video_duration = $responseArray[0]->duration;
						$video_thumb = $responseArray[0]->thumbnail_small;
						if(isset($responseArray[0]->stats_number_of_plays)) {
							$video_views = $responseArray[0]->stats_number_of_plays;
						}
						
						
						//insert in db
						mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES(0,'".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','https://vimeo.com/".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,'".mysql_real_escape_string($arryVideosTypes[$i])."')");
						$new_order++;
						$result++;
						break;
				}
				
			}
		}
		return $result;
		
	}
	
	public function delVideos($listIds) {
		//loop through videos, if thery're part of channel or playlist, set deleted flag, otherwise, delete phisically
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE video_id IN (".$listIds.")");
		while($videoRow = mysql_fetch_array($videosQry)) {
			if($videoRow["source_id"] >0) {
				mysql_query("UPDATE yt_videos SET video_deleted = 1 WHERE video_id = '".mysql_real_escape_string($videoRow["video_id"])."'");
				
			} else {
				mysql_query("DELETE FROM yt_videos WHERE video_id ='".mysql_real_escape_string($videoRow["video_id"])."'");
			}
		}
		
	}
	
	public function delVideosPermanently($listIds) {
		//loop through videos, if thery're part of channel or playlist, set deleted flag, otherwise, delete phisically
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE video_id IN (".$listIds.")");
		while($videoRow = mysql_fetch_array($videosQry)) {
			
			mysql_query("DELETE FROM yt_videos WHERE video_id ='".mysql_real_escape_string($videoRow["video_id"])."'");
			
		}
		
	}
	
	public function unpublishVideos($listIds) {
		mysql_query("UPDATE yt_videos SET video_active = 0 WHERE video_id IN (".$listIds.")");
	}
	
	public function publishVideos($listIds) {
		mysql_query("UPDATE yt_videos SET video_active = 1 WHERE video_id IN (".$listIds.")");
	}
	
	public function restoreVideos($listIds) {
		mysql_query("UPDATE yt_videos SET video_deleted = 0 WHERE video_id IN (".$listIds.")");
	}
	
	public function refreshVideo($video_id,$settingObj) {
		//retrieve video id
		$videoQry = mysql_query("SELECT * FROM yt_videos WHERE video_id = '".mysql_real_escape_string($video_id)."'");
		$videoRow = mysql_fetch_array($videoQry);
		switch($videoRow["video_type"]) {
			case 1:
				$youtube_video_id = $videoRow["video_youtube_id"];
				$ch = curl_init();
						// set url
				curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$youtube_video_id."&key=".$settingObj->getYtApiKey());
				curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
				//return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
				// $output contains the output string
				$jsonResponse = curl_exec($ch);
		
				// close curl resource to free up system resources
				curl_close($ch); 
				
				$videoArray = json_decode($jsonResponse);
								
				$videoData = $videoArray->items[0]->contentDetails;
				
				
				$duration = new DateInterval($videoData->duration);
				$duration = (60 * 60 * $duration->h) + (60 * $duration->i) + $duration->s; 
				
				$video_title = $arrayEntry[$i]->snippet->title;
				$video_author = $arrayEntry[$i]->snippet->channelTitle;
				$video_description = $arrayEntry[$i]->snippet->description;
				$video_thumb = "http://i.ytimg.com/vi/".$video_youtube_id."/1.jpg";
				$video_views = $videoArray->items[0]->statistics->viewCount;
				$video_duration = $duration;
				
		
				//update db
				mysql_query("UPDATE yt_videos SET video_title = '".mysql_real_escape_string($video_title)."',video_description='".mysql_real_escape_string($video_description)."',video_thumb='".mysql_real_escape_string($video_thumb)."',video_duration = '".mysql_real_escape_string($video_duration)."',video_author='".mysql_real_escape_string($video_author)."', video_upload_date = NOW() WHERE video_id = '".mysql_real_escape_string($videoRow["video_id"])."'");
				break;
			case 2:
				$vimeo_video_id = $videoRow["video_youtube_id"];
				$ch = curl_init();

				// set url
				curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/".$vimeo_video_id.".json");
				curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
				//return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
				// $output contains the output string
				$jsonResponse = curl_exec($ch);
		
				// close curl resource to free up system resources
				curl_close($ch); 
				//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".substr($arrayCustomVideos[$i],-11)."?v=2&alt=json");
				$responseArray=json_decode($jsonResponse);
				$video_vimeo_id = $video_id;
				$video_title = $responseArray[0]->title;
				$video_author = $responseArray[0]->user_name;
				$video_description = $responseArray[0]->description;
				$video_duration = $responseArray[0]->duration;
				$video_thumb = $responseArray[0]->thumbnail_small;
				if(isset($responseArray[0]->stats_number_of_plays)) {
					$video_views = $responseArray[0]->stats_number_of_plays;
				}
				
				//update db
				mysql_query("UPDATE yt_videos SET video_title = '".mysql_real_escape_string($video_title)."',video_description='".mysql_real_escape_string($video_description)."',video_thumb='".mysql_real_escape_string($video_thumb)."',video_duration = '".mysql_real_escape_string($video_duration)."',video_author='".mysql_real_escape_string($video_author)."', video_upload_date = NOW() WHERE video_id = '".mysql_real_escape_string($videoRow["video_id"])."'");
				
				
				break;
		}
	}
	
	public function orderVideos($listIds,$value) {
		$arrIds = explode(",",$listIds);
		var_dump($arrIds); 
		if($value=='top') {
			$arrIds=array_reverse($arrIds);
		}
		
		for($i=0;$i<count($arrIds);$i++) {
			$video = $arrIds[$i];
			if($value != '') {
				$videoQry = mysql_query("SELECT * FROM yt_videos WHERE video_id='".mysql_real_escape_string($video)."'");
				$videoRow = mysql_fetch_array($videoQry);
				$current_value = $videoRow["video_order"];
				switch($value) {
					case "top":
						mysql_query("UPDATE yt_videos SET video_order = 1 WHERE video_id='".mysql_real_escape_string($video)."'");
						mysql_query("UPDATE yt_videos SET video_order = video_order +1  WHERE video_id<>'".mysql_real_escape_string($video)."' AND video_order < '".mysql_real_escape_string($current_value)."'");
						break;
					case "bottom":
						//check how many rows in table
						$totRows = mysql_num_rows(mysql_query("SELECT * FROM yt_videos"));
						mysql_query("UPDATE yt_videos SET video_order = ".$totRows." WHERE video_id='".mysql_real_escape_string($video)."'");
						mysql_query("UPDATE yt_videos SET video_order = video_order - 1  WHERE video_id<>".mysql_real_escape_string($video)." AND video_order > '".mysql_real_escape_string($current_value)."'");
						break;
					default:
						if($current_value<$value) {
							mysql_query("UPDATE yt_videos SET video_order = '".(mysql_real_escape_string($value))."' WHERE video_id='".mysql_real_escape_string($video)."'");
							mysql_query("UPDATE yt_videos SET video_order = video_order - 1  WHERE video_id<>'".mysql_real_escape_string($video)."' AND video_order <= '".mysql_real_escape_string($value)."' AND video_order > '".mysql_real_escape_string($current_value)."'");
						}
						if($current_value>$value) {
							mysql_query("UPDATE yt_videos SET video_order = ".($value+1)." WHERE video_id='".mysql_real_escape_string($video)."'");
							mysql_query("UPDATE yt_videos SET video_order = video_order + 1  WHERE video_id<>'".mysql_real_escape_string($video)."' AND video_order > '".mysql_real_escape_string($value)."' AND video_order < '".mysql_real_escape_string($current_value)."'");
						}
						break;
				}
			}
		}
		
		
		


	}
	
	public function updateVideo() {
		$video_id = $_POST["video_id"];
		$video_duration = "video_duration";
		if(isset($_POST["video_duration"])) {
			$video_duration = $_POST["video_duration"];
		}
		mysql_query("UPDATE yt_videos SET video_title = '".mysql_real_escape_string($_POST["video_title"])."',video_description = '".mysql_real_escape_string($_POST["video_description"])."',video_duration = ".$video_duration.", video_author = '".mysql_real_escape_string($_POST["video_author"])."' WHERE video_id =".$video_id);
		
		
	}
}

?>
