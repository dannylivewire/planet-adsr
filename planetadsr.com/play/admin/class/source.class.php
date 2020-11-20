<?php
class source {
	private static $source_id;
	private static $qrySource;
	
	public function setSource($id) {
		
		$rsSource = mysql_query("SELECT * FROM yt_sources WHERE source_id = '".mysql_real_escape_string($id)."'");
		
		$rowSource = mysql_fetch_array($rsSource);
		source::$qrySource = $rowSource;
		source::$source_id=$rowSource["source_id"];
		
	}
	
	public function getSourceId() {
		return source::$source_id;
	}
	
	public function getSourceTitle() {
		return stripslashes(source::$qrySource["source_title"]);
	}
	
	public function getSourceLink() {
		return source::$qrySource["source_link"];
	}
	
	public function getSourceType() {
		return source::$qrySource["source_type"];
	}
	
	public function getSourceActive() {
		return source::$qrySource["source_active"];
	}
	
	public function insertSource($settingObj) {
		//check how many videos there are in table to set right order
		$videoQry= mysql_query("SELECT * FROM yt_videos");
		$totVideos = mysql_num_rows($videoQry);
		$new_order = $totVideos+1;
		
		$result=0;
		//check if source is already in db
		$checkQry = mysql_query("SELECT * FROM yt_sources WHERE source_link = '".mysql_real_escape_string($_POST["link"])."' AND source_source='".mysql_real_escape_string($_POST["source"])."'");
		if(mysql_num_rows($checkQry) == 0) {
			//insert
			
			switch($_POST["type"]) {
				case 'channel':
					switch($_POST["source"]) {
						case 1:
							$id_channel = $_POST["id"];
							if($_POST["name"] != '') {
								$ch = curl_init();
					
								// set url
								
								curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/channels?part=snippet&forUsername=".trim($_POST["name"])."&key=".$settingObj->getYtApiKey());
								curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
								//return the transfer as a string
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						
								// $output contains the output string
								$jsonResponse = curl_exec($ch);
						
								// close curl resource to free up system resources
								curl_close($ch); 
								$responseArray = json_decode($jsonResponse);
								
								$id_channel = $responseArray->items[0]->id; 
							}
							$ch = curl_init();
				
							// set url
							
							curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=snippet%2Cid&channelId=".$id_channel."&type=video&order=date&maxResults=50&key=".$settingObj->getYtApiKey());
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							
							//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							/*var_dump($responseArray->items[1]->snippet);
							exit();*/
							if(isset($responseArray->items[0]->snippet)) {
								
								mysql_query("INSERT INTO yt_sources (source_title, source_link, source_external_link, source_type, source_source, source_active) VALUES('".mysql_real_escape_string($responseArray->items[0]->snippet->channelTitle)."','".mysql_real_escape_string($id_channel)."','http://www.youtube.com/channel/".mysql_real_escape_string($id_channel)."','channel','".mysql_real_escape_string($_POST["source"])."',0)"); // not active, maybe user wants to check content before publish it
								$source_id = mysql_insert_id();
								$arrayEntry = $responseArray->items;
								//$arrayEntry=$responseArray->feed->entry;
							
								for($i=0;$i<count($arrayEntry);$i++) {
									$video_youtube_id = $arrayEntry[$i]->id->videoId;
									//request for video
									$ch = curl_init();
					
									// set url
									
									curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
									curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
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
										mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($source_id)."','".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','http://youtu.be/".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,'".mysql_real_escape_string($_POST["source"])."')");
										$new_order++;
										$result++;
									}
									
								}
							}
							break;
						case 2:
							
							
							//get info
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$_POST["link"]."/info.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							
							//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							$channelName = $responseArray->name;
							//get videos
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$_POST["link"]."/videos.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							
							//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							
								
							mysql_query("INSERT INTO yt_sources (source_title, source_link, source_external_link, source_type, source_source, source_active) VALUES('".mysql_real_escape_string($channelName)."','".mysql_real_escape_string($_POST["link"])."','https://vimeo.com/channels/".mysql_real_escape_string($_POST["link"])."','channel','".mysql_real_escape_string($_POST["source"])."',0)"); // not active, maybe user wants to check content before publish it
							$source_id = mysql_insert_id();
							
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
								//insert in db
								mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($source_id)."','".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','https://vimeo.com/".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,'".mysql_real_escape_string($_POST["source"])."')");
								$new_order++;
								$result++;
							}
							
							
							
							break;
					}
					break;
				case 'playlist':
					switch($_POST["source"]) {
						case 1:
							$playlistArr = explode("?list=",$_POST["link"]);
							if(count($playlistArr)>1) {
								$playlist_id = $playlistArr[1];
								$ch = curl_init();
						
								// set url
								curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet%2Cid%2CcontentDetails&maxResults=50&playlistId=".$playlist_id."&key=".$settingObj->getYtApiKey());
								curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						
								//return the transfer as a string
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						
								// $output contains the output string
								$jsonResponse = curl_exec($ch);
						
								// close curl resource to free up system resources
								curl_close($ch); 
								//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
								//loop video data and inserting in db
								$responseArray=json_decode($jsonResponse);
							
								//request to get playlist title
								// set url
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/playlists?part=snippet%2Cid&id=".$playlist_id."&key=".$settingObj->getYtApiKey());
								curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
								//return the transfer as a string
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						
								// $output contains the output string
								$jsonResponse = curl_exec($ch);
						
								// close curl resource to free up system resources
								curl_close($ch); 
								
								$playlistDetails = json_decode($jsonResponse);
								
								$playlistTitle = $playlistDetails->items[0]->snippet->title;
								mysql_query("INSERT INTO yt_sources (source_title, source_link, source_external_link, source_type,source_source, source_active) VALUES('".mysql_real_escape_string($playlistTitle)."','".mysql_real_escape_string($_POST["link"])."','http://www.youtube.com/playlist?list=".mysql_real_escape_string($playlist_id)."','playlist','".mysql_real_escape_string($_POST["source"])."',0)"); // not active, maybe user wants to check content before publish it
								$source_id = mysql_insert_id();
							
							
								$arrayEntry = $responseArray->items;
								//$arrayEntry=$responseArray->feed->entry;
							
								for($i=0;$i<count($arrayEntry);$i++) {
									$video_youtube_id = $arrayEntry[$i]->contentDetails->videoId;
									//request for video
									$ch = curl_init();
					
									// set url
									
									curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
									curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
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
										mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($source_id)."','".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','http://youtu.be/".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,'".mysql_real_escape_string($_POST["source"])."')");
										$new_order++;
										$result++;
									}
									
								}
							}
							break;
						case 2:
						
							$playlist_id = $_POST["link"];
							//get info
							$ch = curl_init();
					
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/info.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							$playlistTitle = $responseArray->title;
							//get videos
							$ch = curl_init();
					
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/videos.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							
							mysql_query("INSERT INTO yt_sources (source_title, source_link, source_external_link, source_type, source_source, source_active) VALUES('".mysql_real_escape_string($playlistTitle)."','".mysql_real_escape_string($_POST["link"])."','https://vimeo.com/album/".mysql_real_escape_string($_POST["link"])."','playlist','".mysql_real_escape_string($_POST["source"])."',0)"); // not active, maybe user wants to check content before publish it
							$source_id = mysql_insert_id();
							
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
								//insert in db
								mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($source_id)."','".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','https://vimeo.com/".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,'".mysql_real_escape_string($_POST["source"])."')");
								$new_order++;
								$result++;
							}
						
						
							break;
					}
					break;
			}
			
		}
		return $result;
	}
	
	public function delSources($listIds) {
		if($listIds == 'other') {
			//have to delete other videos
			$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = 0");
			while($videoRow = mysql_fetch_array($videosQry)) {
				mysql_query("DELETE FROM yt_show WHERE video_id='".mysql_real_escape_string($videoRow["video_id"])."'");
			}
			mysql_query("DELETE FROM yt_videos WHERE source_id = 0");
		} else {
			//delete also shows
			$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id IN (".$listIds.")");
			while($videoRow = mysql_fetch_array($videosQry)) {
				mysql_query("DELETE FROM yt_show WHERE video_id='".mysql_real_escape_string($videoRow["video_id"])."'");
			}
			mysql_query("DELETE FROM yt_sources WHERE source_id IN (".$listIds.")");
			mysql_query("DELETE FROM yt_videos WHERE source_id IN (".$listIds.") AND source_id <> 0");
		}
		
	}
	
	public function unpublishSources($listIds) {
		mysql_query("UPDATE yt_sources SET source_active = 0 WHERE source_id IN (".$listIds.")");
		//unpublish related shows
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id IN (".$listIds.")");
		while($videoRow = mysql_fetch_array($videosQry)) {
			mysql_query("UPDATE yt_show SET show_active = 0 WHERE video_id='".mysql_real_escape_string($videoRow["video_id"])."'");
		}
	}
	
	public function publishSources($listIds) {
		mysql_query("UPDATE yt_sources SET source_active = 1 WHERE source_id IN (".$listIds.")");
		//publish related shows
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id IN (".$listIds.")");
		while($videoRow = mysql_fetch_array($videosQry)) {
			mysql_query("UPDATE yt_show SET show_active = 1 WHERE video_id='".mysql_real_escape_string($videoRow["video_id"])."'");
		}
	}
	
	public function refreshSources($listIds,$settingObj) {
		//this is meant to update existing videos
		
		//check how many videos there are in table to set right order
		$videoQry= mysql_query("SELECT * FROM yt_videos");
		$totVideos = mysql_num_rows($videoQry);
		$new_order = $totVideos+1;
		
		$sourcesQry = mysql_query("SELECT * FROM yt_sources WHERE source_id IN (".$listIds.")");
		while($sourceRow = mysql_fetch_array($sourcesQry)) {
			$source_id=$sourceRow["source_id"];
			switch($sourceRow["source_source"]) {
				case 1:
					switch($sourceRow["source_type"]) {
						case 'channel':
							$pageToken = "";
							$checkedvideos = 0;
							$numvideos = 0;
							$numdbvideos = mysql_num_rows(mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($source_id)."'"));
							$checkeddbvideos = 0;
							do {
								$ch = curl_init();
					
								// set url
								
								curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=snippet%2Cid&channelId=".$sourceRow["source_link"]."&type=video&maxResults=50&pageToken=".$pageToken."&key=".$settingObj->getYtApiKey());
								curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
								
						
								//return the transfer as a string
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						
								// $output contains the output string
								$jsonResponse = curl_exec($ch);
						
								// close curl resource to free up system resources
								curl_close($ch); 
								
								//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
								//loop video data and inserting in db
								$responseArray=json_decode($jsonResponse);
								
								$numvideos = $responseArray->pageInfo->totalResults;
								if(isset($responseArray->nextPageToken)) {
									$pageToken = $responseArray->nextPageToken;
								} else {
									$pageToken = "";
								}
								$arrayEntry = $responseArray->items;
								
								for($i=0;$i<count($arrayEntry);$i++) {
									$video_youtube_id = $arrayEntry[$i]->id->videoId;
									
									//check if exists
									$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_youtube_id)."'");
									if(mysql_num_rows($videoQry)>0) {
										//request for video
										$ch = curl_init();
						
										// set url
										
										curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
										curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
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
											//update db
											mysql_query("UPDATE yt_videos SET video_title ='".mysql_real_escape_string($video_title)."',video_description='".mysql_real_escape_string($video_description)."',video_thumb='".mysql_real_escape_string($video_thumb)."',video_author='".mysql_real_escape_string($video_author)."', video_upload_date = NOW() WHERE video_youtube_id='".mysql_real_escape_string($video_youtube_id)."'");
											$checkeddbvideos++;
										}
										
									} 
									
									
									
									$checkedvideos++;
									
								}
								
							} while($numvideos>$checkedvideos && $numdbvideos>$checkeddbvideos);
							break;
						case 'playlist':
							$playlistArr = explode("?list=",$sourceRow["source_link"]);
							if(count($playlistArr)>0) {
								$playlist_id = $playlistArr[1];
								$pageToken = "";
								$checkedvideos = 0;
								$numvideos = 0;
								$numdbvideos = mysql_num_rows(mysql_query("SELECT * FROM yt_videos WHERE source_id = ".$source_id));
								$checkeddbvideos = 0;
								do {
									$ch = curl_init();
							
									// set url
									curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet%2Cid%2CcontentDetails&maxResults=50&playlistId=".$playlist_id."&key=".$settingObj->getYtApiKey());
									curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
									
									//return the transfer as a string
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							
									// $output contains the output string
									$jsonResponse = curl_exec($ch);
							
									// close curl resource to free up system resources
									curl_close($ch); 
									//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
									//loop video data and inserting in db
									$responseArray=json_decode($jsonResponse);
								
									//request to get playlist title
									// set url
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/playlists?part=snippet%2Cid&id=".$playlist_id."&key=".$settingObj->getYtApiKey());
									curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
									//return the transfer as a string
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							
									// $output contains the output string
									$jsonResponse = curl_exec($ch);
							
									// close curl resource to free up system resources
									curl_close($ch); 
									
									$playlistDetails = json_decode($jsonResponse);
									
									$playlistTitle = $playlistDetails->items[0]->snippet->title;
									mysql_query("INSERT INTO yt_sources (source_title, source_link, source_external_link, source_type,source_source, source_active) VALUES('".mysql_real_escape_string($playlistTitle)."','".mysql_real_escape_string($_POST["link"])."','http://www.youtube.com/playlist?list=".mysql_real_escape_string($playlist_id)."','playlist','".mysql_real_escape_string($_POST["source"])."',0)"); // not active, maybe user wants to check content before publish it
									$source_id = mysql_insert_id();
								
									$numvideos = $responseArray->pageInfo->totalResults;
									if(isset($responseArray->nextPageToken)) {
										$pageToken = $responseArray->nextPageToken;
									} else {
										$pageToken = "";
									}
									$arrayEntry = $responseArray->items;
									//$arrayEntry=$responseArray->feed->entry;
								
									for($i=0;$i<count($arrayEntry);$i++) {
										$video_youtube_id = $arrayEntry[$i]->contentDetails->videoId;
										
										
										//check if exists
										$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_youtube_id)."'");
										if(mysql_num_rows($videoQry)>0) {	
											//request for video
											$ch = curl_init();
							
											// set url
											
											curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
											curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
									
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
												//update db
												mysql_query("UPDATE yt_videos SET video_title ='".mysql_real_escape_string($video_title)."',video_description = '".mysql_real_escape_string($video_description)."',video_thumb='".mysql_real_escape_string($video_thumb)."',video_author='".mysql_real_escape_string($video_author)."', video_upload_date=NOW() WHERE video_youtube_id='".mysql_real_escape_string($video_youtube_id)."'");
												$checkeddbvideos++;
											}
										} 
										$checkedvideos++;
									}
								} while($numvideos>$checkedvideos && $numdbvideos>$checkeddbvideos);
							}
							break;
					}
					break;
				case 2:
					switch($sourceRow["source_type"]) {
						case 'channel':
						
							//get info
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$sourceRow["source_link"]."/info.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							
							//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							$channelName = $responseArray->name;
							//get videos
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$sourceRow["source_link"]."/videos.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							
							//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							mysql_query("UPDATE yt_sources SET source_title = '".mysql_real_escape_string($channelName)."' WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."'");
								
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
								//insert in db
								//check if exists
								$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
								if(mysql_num_rows($videoQry)>0) {									
									//update db
									mysql_query("UPDATE yt_videos SET video_title ='".mysql_real_escape_string($video_title)."',video_description='".mysql_real_escape_string($video_description)."',video_thumb='".mysql_real_escape_string($video_thumb)."',video_author='".mysql_real_escape_string($video_author)."', video_upload_date = NOW() WHERE video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
									
								} 
								
							}
							
							break;
						case 'playlist':
						
							$playlist_id = $sourceRow["source_link"];
							//get info
							$ch = curl_init();
					
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/info.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							$playlistTitle = $responseArray->title;
							mysql_query("UPDATE yt_sources SET source_title = '".mysql_real_escape_string($playlistTitle)."' WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."'");
							//get videos
							$ch = curl_init();
					
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/videos.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
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
								//insert in db
								//check if exists
								$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
								if(mysql_num_rows($videoQry)>0) {
								
									//update db
									mysql_query("UPDATE yt_videos SET video_title ='".mysql_real_escape_string($video_title)."',video_description='".mysql_real_escape_string($video_description)."',video_thumb='".mysql_real_escape_string($video_thumb)."',video_author='".mysql_real_escape_string($video_author)."', video_upload_date = NOW() WHERE video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
									
								} 
							}
							
							break;
					}
					break;
			}
		}
	}
	
	public function addmoreSources($listIds,$settingObj) {
		//this is meant to update existing videos
		
		//check how many videos there are in table to set right order
		$videoQry= mysql_query("SELECT * FROM yt_videos");
		$totVideos = mysql_num_rows($videoQry);
		$new_order = $totVideos+1;
		
		$sourcesQry = mysql_query("SELECT * FROM yt_sources WHERE source_id IN (".$listIds.")");
		while($sourceRow = mysql_fetch_array($sourcesQry)) {
			$source_id=$sourceRow["source_id"];
			switch($sourceRow["source_source"]) {
				case 1:
					switch($sourceRow["source_type"]) {
						case 'channel':
							$pageToken = "";
							$addedvideos = 0;
							$checkedvideos = 0;
							$numvideos = 0;
							do {
								$ch = curl_init();
					
								// set url
								
								curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=snippet%2Cid&channelId=".$sourceRow["source_link"]."&type=video&maxResults=50&pageToken=".$pageToken."&key=".$settingObj->getYtApiKey());
								curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						
								//return the transfer as a string
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						
								// $output contains the output string
								$jsonResponse = curl_exec($ch);
						
								// close curl resource to free up system resources
								curl_close($ch); 
								
								//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
								//loop video data and inserting in db
								$responseArray=json_decode($jsonResponse);
								
								$numvideos = $responseArray->pageInfo->totalResults;
								if(isset($responseArray->nextPageToken)) {
									$pageToken = $responseArray->nextPageToken;
								} else {
									$pageToken = "";
								}
								$arrayEntry = $responseArray->items;
								
								for($i=0;$i<count($arrayEntry);$i++) {
									$video_youtube_id = $arrayEntry[$i]->id->videoId;
									//request for video
									//check if exists
									$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_youtube_id)."'");
									if(mysql_num_rows($videoQry)==0) {
										$ch = curl_init();
						
										// set url
										
										curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
										curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
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
											mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($source_id)."','".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','http://youtu.be/".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,'".mysql_real_escape_string($sourceRow["source_source"])."')");
											
											$addedvideos++;
											if($addedvideos>=50) {
												break;
											}
										}
										
									} 
									
									
									
									$checkedvideos++;
									
								}
								
							} while($addedvideos<50 && $numvideos>$checkedvideos);
							break;
						case 'playlist':
							$playlistArr = explode("?list=",$sourceRow["source_link"]);
							if(count($playlistArr)>0) {
								$playlist_id = $playlistArr[1];
								$pageToken = "";
								$addedvideos = 0;
								$numvideos = 0;
								$checkedvideos = 0;
								do {
									$ch = curl_init();
							
									// set url
									curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet%2Cid%2CcontentDetails&maxResults=50&playlistId=".$playlist_id."&pageToken=".$pageToken."&key=".$settingObj->getYtApiKey());
									curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							
									//return the transfer as a string
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							
									// $output contains the output string
									$jsonResponse = curl_exec($ch);
							
									// close curl resource to free up system resources
									curl_close($ch); 
									//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
									//loop video data and inserting in db
									$responseArray=json_decode($jsonResponse);
								
								
									$numvideos = $responseArray->pageInfo->totalResults;
									if(isset($responseArray->nextPageToken)) {
										$pageToken = $responseArray->nextPageToken;
									} else {
										$pageToken = "";
									}
									$arrayEntry = $responseArray->items;
									//$arrayEntry=$responseArray->feed->entry;
								
									for($i=0;$i<count($arrayEntry);$i++) {
										$video_youtube_id = $arrayEntry[$i]->contentDetails->videoId;
										//check if exists
										$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_youtube_id)."'");
										if(mysql_num_rows($videoQry)==0) {
											//request for video
											$ch = curl_init();
							
											// set url
											
											curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cid%2CcontentDetails%2Cstatistics&id=".$video_youtube_id."&key=".$settingObj->getYtApiKey());
											curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
									
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
												mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($source_id)."','".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','http://youtu.be/".mysql_real_escape_string($video_youtube_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',0,0,'".mysql_real_escape_string($sourceRow["source_source"])."')");
												$addedvideos++;
												if($addedvideos>=50) {
													break;
												}
											}
										} 
										
										
										
										$checkedvideos++;
									}
								} while($addedvideos<50 && $numvideos>$checkedvideos);
							}
							break;
					}
					break;
				case 2:
					switch($sourceRow["source_type"]) {
						case 'channel':
						
							//get info
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$sourceRow["source_link"]."/info.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							
							//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							$channelName = $responseArray->name;
							//get videos
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$sourceRow["source_link"]."/videos.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							
							//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							mysql_query("UPDATE yt_sources SET source_title = '".mysql_real_escape_string($channelName)."' WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."'");
								
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
								//insert in db
								//check if exists
								$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
								if(mysql_num_rows($videoQry)>0) {
									//if it's the first video do another request for the next 50 videos
									if($i==0) {
										//get number of videos fot this channel
										$videosQry=mysql_query("SELECT * FROM yt_videos WHERE source_id='".mysql_real_escape_string($sourceRow["source_id"])."'");
										$this->refreshNextVideos($sourceRow["source_id"],mysql_num_rows($videosQry));
																			
									} 
								} else {
									//insert in db
									mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($sourceRow["source_id"])."','".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','https://vimeo.com/".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',1,0,2)");
									$new_order++;
								}
								
							}
							
							break;
						case 'playlist':
						
							$playlist_id = $sourceRow["source_link"];
							//get info
							$ch = curl_init();
					
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/info.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
							//loop video data and inserting in db
							$responseArray=json_decode($jsonResponse);
							$playlistTitle = $responseArray->title;
							mysql_query("UPDATE yt_sources SET source_title = '".mysql_real_escape_string($playlistTitle)."' WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."'");
							//get videos
							$ch = curl_init();
					
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/videos.json");
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); 
							//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
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
								//insert in db
								//check if exists
								$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
								if(mysql_num_rows($videoQry)>0) {
									//if it's the first video do another request for the next 50 videos
									if($i==0) {
										//get number of videos fot this channel
										$videosQry=mysql_query("SELECT * FROM yt_videos WHERE source_id='".mysql_real_escape_string($sourceRow["source_id"])."'");
										$this->refreshNextVideos($sourceRow["source_id"],mysql_num_rows($videosQry));
																			
									} 
								} else {
									//insert in db
									mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($sourceRow["source_id"])."','".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".$video_thumb."','https://vimeo.com/".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',1,0,2)");
									$new_order++;
								}
							}
							
							break;
					}
					break;
			}
		}
	}
	
	public function refreshNextVideos($source_id,$start) {
		//check how many videos there are in table to set right order
		$videoQry= mysql_query("SELECT * FROM yt_videos");
		$totVideos = mysql_num_rows($videoQry);
		$new_order = $totVideos+1;
		
		$sourceQry = mysql_query("SELECT * FROM yt_sources WHERE source_id ='".mysql_real_escape_string($source_id)."'");
		$sourceRow = mysql_fetch_array($sourceQry);
		switch($sourceRow["source_source"]) {
			case 2:
				$newstart = $start+1;
				if($newstart <= 20) {
					$newstart = 1;
				} else if($newstart <= 40) {
					$newstart = 2;
				} else if($newstart <= 60) {
					$newstart = 3;
				} else {
					break;
				}
				switch($sourceRow["source_type"]) {
					case 'channel':
					
						//get info
						$ch = curl_init();
						// set url
						curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$sourceRow["source_link"]."/info.json");
						curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						
						//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
						//loop video data and inserting in db
						$responseArray=json_decode($jsonResponse);
						$channelName = $responseArray->name;
						//get videos
						$ch = curl_init();
						// set url
						curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$sourceRow["source_link"]."/videos.json?page=".$newstart);
						curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						
						//$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/videos?author=".$_POST["link"]."&alt=json");
						//loop video data and inserting in db
						$responseArray=json_decode($jsonResponse);
						mysql_query("UPDATE yt_sources SET source_title = '".mysql_real_escape_string($channelName)."' WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."'");
							
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
							//insert in db
							//check if exists
							$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
							if(mysql_num_rows($videoQry)==0) {
								//insert in db
								mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($sourceRow["source_id"])."','".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','https://vimeo.com/".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',1,0,2)");
								$new_order++;
							}
							
						}
						
						break;
					case 'playlist':
					
						$playlist_id = $sourceRow["source_link"];
						//get info
						$ch = curl_init();
				
						// set url
						curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/info.json");
						curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
						//loop video data and inserting in db
						$responseArray=json_decode($jsonResponse);
						$playlistTitle = $responseArray->title;
						mysql_query("UPDATE yt_sources SET source_title = '".mysql_real_escape_string($playlistTitle)."' WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."'");
						//get videos
						$ch = curl_init();
				
						// set url
						curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$playlist_id."/videos.json?page=".$newstart);
						curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
						//return the transfer as a string
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
						// $output contains the output string
						$jsonResponse = curl_exec($ch);
				
						// close curl resource to free up system resources
						curl_close($ch); 
						//@$jsonResponse = file_get_contents("http://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
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
							//insert in db
							//check if exists
							$videoQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($sourceRow["source_id"])."' AND video_youtube_id='".mysql_real_escape_string($video_vimeo_id)."'");
							if(mysql_num_rows($videoQry)==0) {
								//insert in db
								mysql_query("INSERT INTO yt_videos (source_id,video_youtube_id,video_title,video_description,video_thumb,video_link,video_duration,video_author,video_upload_date,video_order,video_active,video_deleted,video_type) VALUES('".mysql_real_escape_string($sourceRow["source_id"])."','".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_title)."','".mysql_real_escape_string($video_description)."','".mysql_real_escape_string($video_thumb)."','https://vimeo.com/".mysql_real_escape_string($video_vimeo_id)."','".mysql_real_escape_string($video_duration)."','".mysql_real_escape_string($video_author)."',NOW(),'".mysql_real_escape_string($new_order)."',1,0,2)");
								$new_order++;
							}
						}
						
						break;
				}
				break;
		}
	}
	
	public function editChannel($channel_id,$name) {
		mysql_query("UPDATE yt_sources SET source_title = '".mysql_real_escape_string($name)."' WHERE source_id='".mysql_real_escape_string($channel_id)."'");
	}
}

?>
