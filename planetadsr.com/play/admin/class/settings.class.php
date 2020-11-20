<?php

class setting {
	
	private function doSettingQuery($setting) {
		$settingQry = mysql_query("SELECT * FROM yt_config WHERE config_name='".mysql_real_escape_string($setting)."'");
		return mysql_result($settingQry,0,'config_value');
	}
	
	public function getDisplayViews() {
		return setting::doSettingQuery('display_views');
	}
	
	public function getDisplayAuthor() {
		return setting::doSettingQuery('display_author');
	}
	
	public function getTitleChars() {
		return setting::doSettingQuery('title_chars');
	}
	
	public function getDescriptionChars() {
		return setting::doSettingQuery('description_chars');
	}
	
	public function getMeasureId() {
		return setting::doSettingQuery('measure_id');
	}
	
	public function getTimezone() {
		return setting::doSettingQuery('timezone');
	}
	
	public function getCustomWidth() {
		return setting::doSettingQuery('custom_width');
	}
	
	public function getCustomHeight() {
		return setting::doSettingQuery('custom_height');
	}
	
	public function getThumbWidth() {
		return setting::doSettingQuery('thumb_width');
	}
	
	public function getLayout() {
		return setting::doSettingQuery('layout');
	}
	
	public function getAutoplay() {
		return setting::doSettingQuery('autoplay');
	}
	
	public function getVideoNum() {
		return setting::doSettingQuery('video_num');
	}
	
	public function getDisplayTitle() {
		return setting::doSettingQuery('display_title');
	}
	
	public function getDisplayDescription() {
		return setting::doSettingQuery('display_description');
	}
	
	public function getDisplayThumb() {
		return setting::doSettingQuery('display_thumb');
	}
	public function getDisplayVideolist() {
		return setting::doSettingQuery('display_videolist');
	}
	
	public function getPlaylistStart() {
		return setting::doSettingQuery('playlist_start');
	}
	
	public function getScheduleVideo() {
		return setting::doSettingQuery('schedule_video');
	}
	
	public function getManagement() {
		return setting::doSettingQuery('management');
	}
	
	public function getSourceType() {
		return setting::doSettingQuery('source_type');
	}
	
	public function getSourceSource() {
		return setting::doSettingQuery('source_source');
	}
	
	public function getSourceLink() {
		return setting::doSettingQuery('source_link');
	}
	
	public function getSourceNumVideos() {
		return setting::doSettingQuery('source_num_videos');
	}
	
	public function getLoopVideos() {
		return setting::doSettingQuery('loop_videos');
	}
	
	public function getVideolistWidth() {
		return setting::doSettingQuery('videolist_width');
	}
	
	public function getVideolistHeight() {
		return setting::doSettingQuery('videolist_height');
	}
	
	public function getVideolistMargin() {
		return setting::doSettingQuery('videolist_margin');
	}
	
	public function getVideolistPosition() {
		return setting::doSettingQuery('videolist_position');
	}
	
	public function getVideoNavigation() {
		return setting::doSettingQuery('video_navigation');
	}
	
	public function getThumbBg() {
		return setting::doSettingQuery('thumb_bg');
	}
	
	public function getThumbBgHover() {
		return setting::doSettingQuery('thumb_bg_hover');
	}
	
	public function getThumbBgSel() {
		return setting::doSettingQuery('thumb_bg_sel');
	}
	
	public function getShowVideoInfo() {
		return setting::doSettingQuery('show_video_info');
	}
	
	public function getButtonBackColor() {
		return setting::doSettingQuery('button_back_color');
	}
	
	public function getButtonBackColorHover() {
		return setting::doSettingQuery('button_back_color_hover');
	}
	
	public function getButtonColor() {
		return setting::doSettingQuery('button_color');
	}
	
	public function getButtonColorHover() {
		return setting::doSettingQuery('button_color_hover');
	}
	
	public function getButtonPadding() {
		return setting::doSettingQuery('button_padding');
	}
	
	public function getButtonMarginVideolist() {
		return setting::doSettingQuery('button_margin_videolist');
	}
	
	public function getButtonMarginBetween() {
		return setting::doSettingQuery('button_margin_between');
	}
	
	public function getVideoPadding() {
		return setting::doSettingQuery('video_padding');
	}
	
	public function getShowScheduleList() {
		return setting::doSettingQuery('show_schedule_list');
	}
	
	public function getButtonFontSize() {
		return setting::doSettingQuery('button_font_size');
	}
	
	public function getVideoInfoFontSize() {
		return setting::doSettingQuery('video_info_font_size');
	}
	
	public function getVideolistFontSize() {
		return setting::doSettingQuery('videolist_font_size');
	}
	
	public function getScheduleListFontSize() {
		return setting::doSettingQuery('schedule_list_font_size');
	}
	
	public function getScheduleListHeight() {
		return setting::doSettingQuery('schedule_list_height');
	}
	
	public function getScheduleListMarginTop() {
		return setting::doSettingQuery('schedule_list_margin_top');
	}
	
	public function getYtApiKey() {
		return setting::doSettingQuery('yt_api_key');
	}
	public function updateSettings() {
		$global_result = 1;
		mysql_query("UPDATE yt_config
					 SET config_value='0'
					 WHERE config_name IN ('display_title','display_description','display_thumb','display_views','display_author')");
		for($i=0;$i<count($_POST["display_options"]); $i++) {
			switch($_POST["display_options"][$i]) {
				case 'title':
					mysql_query("UPDATE yt_config
								 SET config_value='1'
								 WHERE config_name='display_title'");
					break;
				case 'description':
					mysql_query("UPDATE yt_config
								 SET config_value='1'
								 WHERE config_name='display_description'");
					break;
				case 'thumb':
					mysql_query("UPDATE yt_config
								 SET config_value='1'
								 WHERE config_name='display_thumb'");
					break;
				case 'views':					
					mysql_query("UPDATE yt_config
							 SET config_value='1'
							 WHERE config_name='display_views'");
					break;
				case 'author':					
					mysql_query("UPDATE yt_config
							 SET config_value='1'
							 WHERE config_name='display_author'");
					break;
			}
			
		}
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["display_videolist"])."'
					 WHERE config_name='display_videolist'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["title_chars"])."'
					 WHERE config_name='title_chars'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["description_chars"])."'
					 WHERE config_name='description_chars'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["timezone"])."'
					 WHERE config_name='timezone'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["measure_id"])."'
					 WHERE config_name='measure_id'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["custom_width"])."'
					 WHERE config_name='custom_width'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["custom_height"])."'
					 WHERE config_name='custom_height'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["thumb_width"])."'
					 WHERE config_name='thumb_width'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["layout"])."'
					 WHERE config_name='layout'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["autoplay"])."'
					 WHERE config_name='autoplay'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["video_num"])."'
					 WHERE config_name='video_num'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["playlist_start"])."'
					 WHERE config_name='playlist_start'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["schedule_video"])."'
					 WHERE config_name='schedule_video'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["management"])."'
					 WHERE config_name='management'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["source_type"])."'
					 WHERE config_name='source_type'");
		if(isset($_POST["source_source"])) {
			mysql_query("UPDATE yt_config
						 SET config_value='".mysql_real_escape_string($_POST["source_source"])."'
						 WHERE config_name='source_source'");
		}
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["source_num_videos"])."'
					 WHERE config_name='source_num_videos'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["loop_videos"])."'
					 WHERE config_name='loop_videos'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["videolist_width"])."'
					 WHERE config_name='videolist_width'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["videolist_height"])."'
					 WHERE config_name='videolist_height'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["videolist_margin"])."'
					 WHERE config_name='videolist_margin'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["videolist_position"])."'
					 WHERE config_name='videolist_position'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["video_navigation"])."'
					 WHERE config_name='video_navigation'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["thumb_bg"])."'
					 WHERE config_name='thumb_bg'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["thumb_bg_hover"])."'
					 WHERE config_name='thumb_bg_hover'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["thumb_bg_sel"])."'
					 WHERE config_name='thumb_bg_sel'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["show_video_info"])."'
					 WHERE config_name='show_video_info'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_back_color"])."'
					 WHERE config_name='button_back_color'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_back_color_hover"])."'
					 WHERE config_name='button_back_color_hover'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_color"])."'
					 WHERE config_name='button_color'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_color_hover"])."'
					 WHERE config_name='button_color_hover'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_padding"])."'
					 WHERE config_name='button_padding'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_margin_videolist"])."'
					 WHERE config_name='button_margin_videolist'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_margin_between"])."'
					 WHERE config_name='button_margin_between'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["video_padding"])."'
					 WHERE config_name='video_padding'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["show_schedule_list"])."'
					 WHERE config_name='show_schedule_list'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["button_font_size"])."'
					 WHERE config_name='button_font_size'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["video_info_font_size"])."'
					 WHERE config_name='video_info_font_size'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["videolist_font_size"])."'
					 WHERE config_name='videolist_font_size'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["schedule_list_font_size"])."'
					 WHERE config_name='schedule_list_font_size'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["schedule_list_height"])."'
					 WHERE config_name='schedule_list_height'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["schedule_list_margin_top"])."'
					 WHERE config_name='schedule_list_margin_top'");
		mysql_query("UPDATE yt_config
					 SET config_value='".mysql_real_escape_string($_POST["yt_api_key"])."'
					 WHERE config_name='yt_api_key'");
		//check at youtube if the link exists
		if($_POST["management"] == 1) {
			$result = 0;
			switch($_POST["source_type"]) {
				
				case 'channel':
					switch($_POST["source_source"]) {
						case 1:
						//youtube
							$id_channel = $_POST["source_link"];
							$ch = curl_init();
				
							// set url
							
							curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/channels?part=snippet&forUsername=".trim($_POST["source_link"])."&key=".$this->getYtApiKey());
							curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
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
							
							curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=snippet%2Cid&channelId=".$id_channel."&type=video&order=date&maxResults=50&key=".$this->getYtApiKey());
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
							
							/*$ch = curl_init();
					
							// set url
							curl_setopt($ch, CURLOPT_URL, "https://gdata.youtube.com/feeds/api/videos?author=".$_POST["source_link"]."&alt=json");
					
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch); */     
							
							
							//$jsonResponse = file_get_contents("https://gdata.youtube.com/feeds/api/videos?author=".$_POST["source_link"]."&alt=json");
							//loop video data and inserting in db
							//$responseArray=json_decode($jsonResponse);
							if(isset($responseArray->items)) {
								
								$arrayEntry = $responseArray->items;
							
								for($i=0;$i<count($arrayEntry);$i++) {						
									$result++;
								}
							}
							break;
						case 2:
						//vimeo	
							//get videos
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/channel/".$_POST["source_link"]."/videos.json");
							//return the transfer as a string
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					
							// $output contains the output string
							$jsonResponse = curl_exec($ch);
					
							// close curl resource to free up system resources
							curl_close($ch);
							
							$responseArray=json_decode($jsonResponse);
					
							for($i=0;$i<count($responseArray);$i++) {
								$result++;
							}
							break;
					}
					
					break;
				case 'playlist':
					switch($_POST["source_source"]) {
						case 1:
						//youtube
							$playlistArr = explode("?list=",$_POST["source_link"]);
							if(count($playlistArr)>1) {
								$playlist_id = $playlistArr[1];
								$ch = curl_init();
						
								// set url
								curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet%2Cid%2CcontentDetails&maxResults=50&playlistId=".$playlist_id."&key=".$this->getYtApiKey());
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
								
								$arrayEntry = $responseArray->items;
								for($i=0;$i<count($arrayEntry);$i++) {
									
									$result++;
								}
								
							}
							/*$playlistArr = explode("?list=PL",$_POST["source_link"]);
							if(count($playlistArr)>1) {
								$playlist_id = $playlistArr[1];
								
								$ch = curl_init();
						
								// set url
								curl_setopt($ch, CURLOPT_URL, "https://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
						
								//return the transfer as a string
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						
								// $output contains the output string
								$jsonResponse = curl_exec($ch);
						
								// close curl resource to free up system resources
								curl_close($ch); 
								//@$jsonResponse = file_get_contents("https://gdata.youtube.com/feeds/api/playlists/".$playlist_id."?v=2&alt=json");
								//loop video data and inserting in db
								$responseArray=json_decode($jsonResponse);
							
								$playlistTitle = $responseArray->feed->title->{'$t'};
								
								$arrayEntry=$responseArray->feed->entry;
								for($i=0;$i<count($arrayEntry);$i++) {
									
									$result++;
								}
							}*/
							break;
						case 2:
						//vimeo	
							$ch = curl_init();
							// set url
							curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/album/".$_POST["source_link"]."/videos.json");
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
								$result++;
							}
							break;
					}
					
					break;
			}
			if($result>0) {
				mysql_query("UPDATE yt_config
							 SET config_value='".mysql_real_escape_string($_POST["source_link"])."'
							 WHERE config_name='source_link'");
			
			} else {
				$global_result=0;
			}
		}
		
		return $global_result;
		
	}
	

}

?>
