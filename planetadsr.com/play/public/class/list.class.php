<?php
class lists {	
	public function getShowsList($playlist_start) {
		$arrayShows = Array();
		$showsQry = mysql_query("SELECT s.*,v.video_youtube_id,v.video_title,v.video_description,v.video_thumb,v.video_author,v.video_type,v.video_link FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE s.show_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL ".$playlist_start." DAY) AND s.show_date <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND s.show_active = 1 ORDER BY s.show_date DESC, s.show_time DESC");
		
		
		while($showRow=mysql_fetch_array($showsQry)) {
			if($showRow["show_date"] == date('Y-m-d')) {
				if(str_replace(":","",$showRow["show_time"]) <= date('His')) {
					$arrayShows[$showRow["show_id"]] = Array();
					$arrayShows[$showRow["show_id"]]["video_id"] = $showRow["video_id"];
					$arrayShows[$showRow["show_id"]]["show_date"] = $showRow["show_date"];
					$arrayShows[$showRow["show_id"]]["show_time"] = $showRow["show_time"];
					$arrayShows[$showRow["show_id"]]["video_youtube_id"] = $showRow["video_youtube_id"];
					$arrayShows[$showRow["show_id"]]["video_title"] = stripslashes($showRow["video_title"]);
					$arrayShows[$showRow["show_id"]]["video_description"] = stripslashes($showRow["video_description"]);
					$arrayShows[$showRow["show_id"]]["video_thumb"] = $showRow["video_thumb"];
					$arrayShows[$showRow["show_id"]]["video_author"] = $showRow["video_author"];
					$arrayShows[$showRow["show_id"]]["video_type"] = $showRow["video_type"];
					$arrayShows[$showRow["show_id"]]["video_link"] = $showRow["video_link"];
				}
			} else {
				$arrayShows[$showRow["show_id"]] = Array();
				$arrayShows[$showRow["show_id"]]["video_id"] = $showRow["video_id"];
				$arrayShows[$showRow["show_id"]]["show_date"] = $showRow["show_date"];
				$arrayShows[$showRow["show_id"]]["show_time"] = $showRow["show_time"];
				$arrayShows[$showRow["show_id"]]["video_youtube_id"] = $showRow["video_youtube_id"];
				$arrayShows[$showRow["show_id"]]["video_title"] = stripslashes($showRow["video_title"]);
				$arrayShows[$showRow["show_id"]]["video_description"] = stripslashes($showRow["video_description"]);
				$arrayShows[$showRow["show_id"]]["video_thumb"] = $showRow["video_thumb"];
				$arrayShows[$showRow["show_id"]]["video_author"] = $showRow["video_author"];
				$arrayShows[$showRow["show_id"]]["video_type"] = $showRow["video_type"];
				$arrayShows[$showRow["show_id"]]["video_link"] = $showRow["video_link"];
			}
		}
		return $arrayShows;
	}	
	
	public function getFirstShow($playlist_start) {
		$current_video = '';
		$video_id = "";
		$video_type = "";
		$link_video = "";
		$seconds = "";
		$showQry=mysql_query("SELECT s.*,v.video_youtube_id,v.video_type,v.video_link FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE s.show_date = DATE_FORMAT(NOW(),'%Y-%m-%d') AND REPLACE(s.show_time,':','') = DATE_FORMAT(NOW(),'%H%i00') AND s.show_active=1 ORDER BY s.show_time DESC LIMIT 1");
		
		if(mysql_num_rows($showQry)>0) {
			$video_link = mysql_result($showQry,0,'video_link');
			$current_video = mysql_result($showQry,0,'video_youtube_id');
			$video_id = mysql_result($showQry,0,'show_id');
			$video_type = mysql_result($showQry,0,'video_type');
			$seconds = 0;
		} else {
			//get first in the past
			$showQry=mysql_query("SELECT s.*,v.video_youtube_id,v.video_type,v.video_link, TIMESTAMPDIFF(SECOND,DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:%s'),CONCAT(s.show_date,' ',s.show_time)) as seconds FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE s.show_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL ".$playlist_start." DAY) AND s.show_date <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND s.show_active=1 ORDER BY s.show_date DESC,s.show_time DESC LIMIT 1");
			
			
			if(mysql_num_rows($showQry)>0) {
				if(mysql_result($showQry,0,'show_date') == date('Y-m-d') && str_replace(":","",mysql_result($showQry,0,'show_time'))>date('His')) {
					$showQry=mysql_query("SELECT s.*,v.video_youtube_id,v.video_type,v.video_link, TIMESTAMPDIFF(SECOND,DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:%s'),CONCAT(s.show_date,' ',s.show_time)) as seconds FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE s.show_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL ".$playlist_start." DAY) AND s.show_date <= DATE_FORMAT(NOW(),'%Y-%m-%d') AND s.show_active=1 AND DATE_FORMAT(s.show_time,'%H%i%s') <= DATE_FORMAT(NOW(),'%H%i00') ORDER BY s.show_date DESC,s.show_time DESC LIMIT 1");
					if(mysql_num_rows($showQry)>0) {
						$video_link = mysql_result($showQry,0,'video_link');
						$current_video = mysql_result($showQry,0,'video_youtube_id');
						$video_id = mysql_result($showQry,0,'show_id');
						$video_type = mysql_result($showQry,0,'video_type');
						$seconds =abs(mysql_result($showQry,0,'seconds'));
					}
				} else {
					$video_link = mysql_result($showQry,0,'video_link');
					$current_video = mysql_result($showQry,0,'video_youtube_id');
					$video_id = mysql_result($showQry,0,'show_id');
					$video_type = mysql_result($showQry,0,'video_type');
					$seconds =abs(mysql_result($showQry,0,'seconds'));
				}
			}
		}
		return $current_video."|".$video_id."|".$video_type."|".$seconds;
	}
	
	public function getCurrentShow() {
		$current_video = '';
		$video_id='';
		$video_type='';
		$seconds = "";
		$showQry=mysql_query("SELECT s.*,v.video_youtube_id,v.video_type,v.video_link, TIMESTAMPDIFF(SECOND,DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:%s'),CONCAT(s.show_date,' ',s.show_time)) as seconds FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE s.show_date = DATE_FORMAT(NOW(),'%Y-%m-%d') AND REPLACE(s.show_time,':','') = DATE_FORMAT(NOW(),'%H%i00') AND s.show_active = 1 ORDER BY s.show_time DESC LIMIT 1");
		
		if(mysql_num_rows($showQry)>0) {
			$video_link = mysql_result($showQry,0,'video_link');
			$current_video = mysql_result($showQry,0,'video_youtube_id');
			$video_id = mysql_result($showQry,0,'show_id');
			$video_type = mysql_result($showQry,0,'video_type');
			$seconds =abs(mysql_result($showQry,0,'seconds'));
		} 
		return $current_video."|".$video_id."|".$video_type."|".$seconds;
	}
	
	public function getPrevShow($now_video,$playlist_start) {
		$current_video = '';
		$video_id = "";
		$video_type = "";
		$seconds = 0;
		//get info on current video
		$currentVideoQry = mysql_query("SELECT CONCAT(show_date,' ',show_time) as datetime FROM yt_show WHERE show_id='".mysql_real_escape_string($now_video)."'");
		$current_time = mysql_result($currentVideoQry,0,'datetime');
		//get first in the past
		$showQry=mysql_query("SELECT s.*,v.video_youtube_id,v.video_type,v.video_link, TIMESTAMPDIFF(SECOND,DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:%s'),CONCAT(s.show_date,' ',s.show_time)) as seconds FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE CONCAT(s.show_date,' ',s.show_time) <='".mysql_real_escape_string($current_time)."' AND s.show_date >= DATE_ADD(DATE_FORMAT(NOW(),'%Y-%m-%d'),INTERVAL ".$playlist_start." DAY) AND s.show_id <> '".mysql_real_escape_string($now_video)."' AND s.show_active = 1  ORDER BY s.show_date DESC,s.show_time DESC LIMIT 1");
		
		if(mysql_num_rows($showQry)>0) {
			$video_link = mysql_result($showQry,0,'video_link');
			$current_video = mysql_result($showQry,0,'video_youtube_id');
			$video_id = mysql_result($showQry,0,'show_id');
			$video_type = mysql_result($showQry,0,'video_type');
			$seconds = abs(mysql_result($showQry,0,'seconds'));
		}
		return $current_video."|".$video_id."|".$video_type."|".$seconds;
	}
	
	public function getFirstVideo() {
		$current_video = '';
		$video_id = "";
		$video_type = "";
		$seconds = 0;
		$videoQry=mysql_query("SELECT v.video_id,v.video_youtube_id,v.video_type,v.video_link,v.video_duration FROM yt_videos v WHERE v.video_active=1 ORDER BY v.video_order LIMIT 1");
		
		if(mysql_num_rows($videoQry)>0) {
			$video_link = mysql_result($videoQry,0,'video_link');
			$current_video = mysql_result($videoQry,0,'video_youtube_id');
			$video_id = mysql_result($videoQry,0,'video_id');
			$video_type = mysql_result($videoQry,0,'video_type');
			
		} 
		return $current_video."|".$video_id."|".$video_type."|".$seconds;
	}
	
	public function getNextVideo($current) {
		$current_video = '';
		$video_id = "";
		$video_type = "";
		$seconds = 0;
		$current_order = 0;
		//get current
		$currentQry = mysql_query("SELECT * FROM yt_videos WHERE video_id = '".mysql_real_escape_string($current)."'");
		if(mysql_num_rows($currentQry)>0) {
			$current_order = mysql_result($currentQry,0,'video_order');
		}
		$videoQry=mysql_query("SELECT v.video_id,v.video_youtube_id,v.video_type,v.video_link,v.video_duration FROM yt_videos v WHERE v.video_active=1 AND v.video_order > '".mysql_real_escape_string($current_order)."' ORDER BY v.video_order LIMIT 1");
		
		if(mysql_num_rows($videoQry)>0) {
			$video_link = mysql_result($videoQry,0,'video_link');
			$current_video = mysql_result($videoQry,0,'video_youtube_id');
			$video_id = mysql_result($videoQry,0,'video_id');
			$video_type = mysql_result($videoQry,0,'video_type');
			
		} 
		return $current_video."|".$video_id."|".$video_type."|".$seconds;
	}
	
	public function getVideosList() {
		$arrayVideos = Array();
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE video_active = 1 ORDER BY video_order");
		
		
		while($videoRow=mysql_fetch_array($videosQry)) {
			
			$arrayVideos[$videoRow["video_id"]] = Array();
			$arrayVideos[$videoRow["video_id"]]["video_youtube_id"] = $videoRow["video_youtube_id"];
			$arrayVideos[$videoRow["video_id"]]["video_title"] = stripslashes($videoRow["video_title"]);
			$arrayVideos[$videoRow["video_id"]]["video_description"] = stripslashes($videoRow["video_description"]);
			$arrayVideos[$videoRow["video_id"]]["video_thumb"] = $videoRow["video_thumb"];
			$arrayVideos[$videoRow["video_id"]]["video_author"] = $videoRow["video_author"];
			$arrayVideos[$videoRow["video_id"]]["video_type"] = $videoRow["video_type"];
			$arrayVideos[$videoRow["video_id"]]["video_link"] = $videoRow["video_link"];
			$arrayVideos[$videoRow["video_id"]]["video_id"] = $videoRow["video_id"];
			
		}
		return $arrayVideos;
	}	
	
	public function getVideoData($video_id,$schedule) {
		$filter = "";
		if($schedule == 0) {
			$filter = "AND video_active = 1";
		}
		$arrayVideos = Array();
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE video_youtube_id='".mysql_real_escape_string($video_id)."' ".$filter." ORDER BY video_order");
		
		while($videoRow=mysql_fetch_array($videosQry)) {
			
			$arrayVideos[$videoRow["video_youtube_id"]] = Array();
			$arrayVideos[$videoRow["video_youtube_id"]]["video_youtube_id"] = $videoRow["video_youtube_id"];
			$arrayVideos[$videoRow["video_youtube_id"]]["video_title"] = stripslashes($videoRow["video_title"]);
			$arrayVideos[$videoRow["video_youtube_id"]]["video_description"] = stripslashes($videoRow["video_description"]);
			$arrayVideos[$videoRow["video_youtube_id"]]["video_thumb"] = $videoRow["video_thumb"];
			$arrayVideos[$videoRow["video_youtube_id"]]["video_author"] = $videoRow["video_author"];
			$arrayVideos[$videoRow["video_youtube_id"]]["video_type"] = $videoRow["video_type"];
			$arrayVideos[$videoRow["video_youtube_id"]]["video_id"] = $videoRow["video_id"];
			$arrayVideos[$videoRow["video_youtube_id"]]["video_link"] = $videoRow["video_link"];
			
		}
		return $arrayVideos;
	}	
	
	public function getShowsDisplayList($date,$month = '') {
		$arrayShows = Array();
		if($month == '') {
			$showsQry = mysql_query("SELECT s.*,v.video_title,sc.source_title,sc.source_type,v.video_type FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id LEFT JOIN yt_sources sc ON sc.source_id=v.source_id WHERE s.show_date = '".mysql_real_escape_string($date)."' ORDER BY s.show_time ASC");
		} else {
			$showsQry = mysql_query("SELECT s.*,v.video_title,sc.source_title,sc.source_type,v.video_type FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id LEFT JOIN yt_sources sc ON sc.source_id=v.source_id WHERE s.show_date = '".mysql_real_escape_string($date)."' AND DATE_FORMAT(s.show_date,'%M') = '".mysql_real_escape_string($month)."' ORDER BY s.show_time ASC");
			
		}
		
		while($showRow=mysql_fetch_array($showsQry)) {
			
			$arrayShows[$showRow["show_id"]] = Array();
			$arrayShows[$showRow["show_id"]]["video_id"] = $showRow["video_id"];
			$arrayShows[$showRow["show_id"]]["show_date"] = $showRow["show_date"];
			$arrayShows[$showRow["show_id"]]["show_time"] = $showRow["show_time"];
			$arrayShows[$showRow["show_id"]]["video_type"] = $showRow["video_type"];
			$arrayShows[$showRow["show_id"]]["video_title"] = stripslashes($showRow["video_title"]);
			$source_string = '';
			if($showRow["source_type"]!='') {
				$source_string = $showRow["source_type"].":&nbsp;".stripslashes($showRow["source_title"]);
			}
			$arrayShows[$showRow["show_id"]]["source"] = $source_string;
			
		}
		return $arrayShows;
	}	
	
}

?>
