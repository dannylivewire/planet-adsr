<?php
class lists {	
	public function getMeasuresList() {
		$arrayMeasures = Array();
		$measuresQry = mysql_query("SELECT * FROM yt_measures ORDER BY measure_width");
		
		while($measureRow=mysql_fetch_array($measuresQry)) {
			$arrayMeasures[$measureRow["measure_id"]] = Array();
			$arrayMeasures[$measureRow["measure_id"]]["measure_width"] = $measureRow["measure_width"];
			$arrayMeasures[$measureRow["measure_id"]]["measure_height"] = $measureRow["measure_height"];
		}
		return $arrayMeasures;
	}	


	public function getTimezonesList() {
		$arrayTimezones = Array();
		$timezonesQry = mysql_query("SELECT * FROM yt_timezones ORDER BY timezone_name");
		
		while($timezoneRow=mysql_fetch_array($timezonesQry)) {
			$arrayTimezones[$timezoneRow["timezone_id"]] = Array();
			$arrayTimezones[$timezoneRow["timezone_id"]]["timezone_name"] = $timezoneRow["timezone_name"];
			$arrayTimezones[$timezoneRow["timezone_id"]]["timezone_value"] = $timezoneRow["timezone_value"];
		}
		return $arrayTimezones;
	}	
	
	public function getSourcesList($type) {
		$arraySources = Array();
		$sourcesQry = mysql_query("SELECT * FROM yt_sources WHERE source_type = '".mysql_real_escape_string($type)."' ORDER BY source_active DESC,source_title ASC");
		
		while($sourceRow=mysql_fetch_array($sourcesQry)) {
			$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id='".mysql_real_escape_string($sourceRow["source_id"])."' AND video_deleted = 0");
			$tot = mysql_num_rows($videosQry);
			$arraySources[$sourceRow["source_id"]] = Array();
			$arraySources[$sourceRow["source_id"]]["source_title"] = stripslashes($sourceRow["source_title"]);
			$arraySources[$sourceRow["source_id"]]["source_link"] = $sourceRow["source_link"];
			$arraySources[$sourceRow["source_id"]]["source_external_link"] = $sourceRow["source_external_link"];
			$arraySources[$sourceRow["source_id"]]["source_type"] = $sourceRow["source_type"];
			$arraySources[$sourceRow["source_id"]]["source_active"] = $sourceRow["source_active"];
			$arraySources[$sourceRow["source_id"]]["tot_videos"] = $tot;
			
		}
		return $arraySources;
	}	
	
	public function getAllSourcesList() {
		$arraySources = Array();
		$sourcesQry = mysql_query("SELECT * FROM yt_sources ORDER BY source_active DESC,source_title ASC");
		
		while($sourceRow=mysql_fetch_array($sourcesQry)) {
			$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id='".mysql_real_escape_string($sourceRow["source_id"])."' AND video_deleted = 0");
			$tot = mysql_num_rows($videosQry);
			$arraySources[$sourceRow["source_id"]] = Array();
			$arraySources[$sourceRow["source_id"]]["source_title"] = stripslashes($sourceRow["source_title"]);
			$arraySources[$sourceRow["source_id"]]["source_link"] = $sourceRow["source_link"];
			$arraySources[$sourceRow["source_id"]]["source_external_link"] = $sourceRow["source_external_link"];
			$arraySources[$sourceRow["source_id"]]["source_type"] = $sourceRow["source_type"];
			$arraySources[$sourceRow["source_id"]]["source_active"] = $sourceRow["source_active"];
			$arraySources[$sourceRow["source_id"]]["tot_videos"] = $tot;
			
		}
		return $arraySources;
	}	
	
	public function getVideosList($source_id = 0,$orderby="ORDER BY video_active DESC,video_title ASC",$filter="") {
		$arrayVideos = Array();
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($source_id)."' AND video_deleted = 0 ".$filter." ".$orderby);
		
		while($videoRow=mysql_fetch_array($videosQry)) {
			
			$arrayVideos[$videoRow["video_id"]] = Array();
			$arrayVideos[$videoRow["video_id"]]["video_title"] = stripslashes($videoRow["video_title"]);
			$arrayVideos[$videoRow["video_id"]]["video_link"] = $videoRow["video_link"];
			$arrayVideos[$videoRow["video_id"]]["video_duration"] = $videoRow["video_duration"];
			$arrayVideos[$videoRow["video_id"]]["video_thumb"] = $videoRow["video_thumb"];
			$arrayVideos[$videoRow["video_id"]]["video_active"] = $videoRow["video_active"];
			$arrayVideos[$videoRow["video_id"]]["video_upload_date"] = $videoRow["video_upload_date"];
			$arrayVideos[$videoRow["video_id"]]["video_type"] = $videoRow["video_type"];	
			$arrayVideos[$videoRow["video_id"]]["video_author"] = stripslashes($videoRow["video_author"]);	
			$arrayVideos[$videoRow["video_id"]]["video_description"] = stripslashes($videoRow["video_description"]);
			$arrayVideos[$videoRow["video_id"]]["video_youtube_id"] = $videoRow["video_youtube_id"];	
			$arrayVideos[$videoRow["video_id"]]["video_active"] = $videoRow["video_active"];
			$arrayVideos[$videoRow["video_id"]]["video_upload_date"] = $videoRow["video_upload_date"];
			
		}
		return $arrayVideos;
	}	
	
	public function getDeletedVideosList($source_id = 0) {
		$arrayVideos = Array();
		$videosQry = mysql_query("SELECT * FROM yt_videos WHERE source_id = '".mysql_real_escape_string($source_id)."' AND video_deleted = 1 ORDER BY video_title ASC");
		
		while($videoRow=mysql_fetch_array($videosQry)) {
			
			$arrayVideos[$videoRow["video_id"]] = Array();
			$arrayVideos[$videoRow["video_id"]]["video_title"] = stripslashes($videoRow["video_title"]);
			$arrayVideos[$videoRow["video_id"]]["video_link"] = $videoRow["video_link"];
			$arrayVideos[$videoRow["video_id"]]["video_active"] = $videoRow["video_active"];
			
		}
		return $arrayVideos;
	}	
	public function getShowMonthsList($date_from = '',$date_to = '', $select = '') {
		$arrayDates = Array();
		$filter = '';
		if($date_from!='' && $date_to!='') {
			$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') >= '".mysql_real_escape_string(str_replace(",","-",$date_from))."' AND DATE_FORMAT(show_date,'%Y-%m-%d') <= '".mysql_real_escape_string(str_replace(",","-",$date_to))."'";
		} else if($date_from != '') {
			$filter= " AND DATE_FORMAT(show_date,'%Y-%m-%d') = '".mysql_real_escape_string(str_replace(",","-",$date_from))."'";
		} else if($select != '') {
			switch($select) {
				case 'today':
					$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')";
					break;
				case 'tomorrow':
					$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')";
					break;
				case 'next7':
					$filter = " AND DATE_FORMAT(show_date,'%Y%m%d') >= DATE_FORMAT(NOW(),'%Y%m%d') AND DATE_FORMAT(show_date,'%Y%m%d') < DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%Y%m%d')";
					break;
				case 'yesterday':
					$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')";
					break;
				case 'all':
					$filter='';
					break;
					default:
						$filter='';
						break;
			}
		}
		$datesQry = mysql_query("SELECT DISTINCT(DATE_FORMAT(show_date,'%M')) as month  FROM yt_show WHERE 0=0 ".$filter." ORDER BY show_date ASC");
		//echo "SELECT DISTINCT(show_date)  FROM yt_show WHERE 0=0 ".$filter." ORDER BY show_date ASC";
		
		while($dateRow=mysql_fetch_array($datesQry)) {
			
			array_push($arrayDates,$dateRow["month"]);
			
		}
		return $arrayDates;
	}	
	public function getShowDatesList($date_from = '',$date_to = '', $select = '') {
		$arrayDates = Array();
		$filter = '';
		if($date_from!='' && $date_to!='') {
			$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') >= '".mysql_real_escape_string(str_replace(",","-",$date_from))."' AND DATE_FORMAT(show_date,'%Y-%m-%d') <= '".mysql_real_escape_string(str_replace(",","-",$date_to))."'";
		} else if($date_from != '') {
			$filter= " AND DATE_FORMAT(show_date,'%Y-%m-%d') = '".mysql_real_escape_string(str_replace(",","-",$date_from))."'";
		} else if($select != '') {
			switch($select) {
				case 'today':
					$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')";
					break;
				case 'tomorrow':
					$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') = DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')";
					break;
				case 'next7':
					$filter = " AND DATE_FORMAT(show_date,'%Y%m%d') >= DATE_FORMAT(NOW(),'%Y%m%d') AND DATE_FORMAT(show_date,'%Y%m%d') < DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY),'%Y%m%d')";
					break;
				case 'yesterday':
					$filter = " AND DATE_FORMAT(show_date,'%Y-%m-%d') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')";
					break;
				case 'all':
					$filter='';
					break;
					default:
						$filter='';
						break;
			}
		}
		$datesQry = mysql_query("SELECT DISTINCT(show_date)  FROM yt_show WHERE 0=0 ".$filter." ORDER BY show_date ASC");
		//echo "SELECT DISTINCT(show_date)  FROM yt_show WHERE 0=0 ".$filter." ORDER BY show_date ASC";
		
		while($dateRow=mysql_fetch_array($datesQry)) {
			
			array_push($arrayDates,$dateRow["show_date"]);
			
		}
		return $arrayDates;
	}	
	
	public function getShowsList($date,$month = '') {
		$arrayShows = Array();
		if($month == '') {
			$showsQry = mysql_query("SELECT s.*,v.video_title,v.video_type,sc.source_title,sc.source_type FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id LEFT JOIN yt_sources sc ON sc.source_id=v.source_id WHERE s.show_date = '".mysql_real_escape_string($date)."' ORDER BY s.show_time ASC");
		} else {
			$showsQry = mysql_query("SELECT s.*,v.video_title,v.video_type,sc.source_title,sc.source_type FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id LEFT JOIN yt_sources sc ON sc.source_id=v.source_id WHERE s.show_date = '".mysql_real_escape_string($date)."' AND DATE_FORMAT(s.show_date,'%M') = '".mysql_real_escape_string($month)."' ORDER BY s.show_time ASC");
			
		}
		
		while($showRow=mysql_fetch_array($showsQry)) {
			
			$arrayShows[$showRow["show_id"]] = Array();
			$arrayShows[$showRow["show_id"]]["video_id"] = $showRow["video_id"];
			$arrayShows[$showRow["show_id"]]["show_date"] = $showRow["show_date"];
			$arrayShows[$showRow["show_id"]]["show_time"] = $showRow["show_time"];
			$arrayShows[$showRow["show_id"]]["video_title"] = stripslashes($showRow["video_title"]);
			$source_string = '';
			if($showRow["source_type"]!='') {
				$source_string = $showRow["source_type"].":&nbsp;".stripslashes($showRow["source_title"]);
			}
			$arrayShows[$showRow["show_id"]]["source"] = $source_string;
			
		}
		return $arrayShows;
	}	
	
	public function getAllVideosList($order,$filter) {
		$arrayVideos = Array();
		$videosQry = mysql_query("SELECT v.*,s.source_title FROM yt_videos v LEFT JOIN yt_sources s ON v.source_id = s.source_id WHERE v.video_deleted = 0 ".$filter." ".$order);
		
		while($videoRow=mysql_fetch_array($videosQry)) {
			
			$arrayVideos[$videoRow["video_id"]] = Array();
			$arrayVideos[$videoRow["video_id"]]["video_title"] = stripslashes($videoRow["video_title"]);
			$arrayVideos[$videoRow["video_id"]]["video_link"] = $videoRow["video_link"];
			$arrayVideos[$videoRow["video_id"]]["video_duration"] = $videoRow["video_duration"];
			$arrayVideos[$videoRow["video_id"]]["video_source"] = $videoRow["source_title"];
			$arrayVideos[$videoRow["video_id"]]["video_active"] = $videoRow["video_active"];
			$arrayVideos[$videoRow["video_id"]]["video_upload_date"] = $videoRow["video_upload_date"];
			$arrayVideos[$videoRow["video_id"]]["video_type"] = $videoRow["video_type"];
			
		}
		return $arrayVideos;
	}	
	
	public function getOnairVideosList() {
		$arrayVideos = Array();
		$videosQry = mysql_query("SELECT v.*,s.source_title FROM yt_videos v LEFT JOIN yt_sources s ON v.source_id = s.source_id WHERE v.video_deleted = 0 AND v.video_active = 1 ORDER BY v.video_order");
		
		while($videoRow=mysql_fetch_array($videosQry)) {
			
			$arrayVideos[$videoRow["video_id"]] = Array();
			$arrayVideos[$videoRow["video_id"]]["video_title"] = stripslashes($videoRow["video_title"]);
			$arrayVideos[$videoRow["video_id"]]["video_link"] = $videoRow["video_link"];
			$arrayVideos[$videoRow["video_id"]]["video_duration"] = $videoRow["video_duration"];
			$arrayVideos[$videoRow["video_id"]]["video_source"] = $videoRow["source_title"];
			$arrayVideos[$videoRow["video_id"]]["video_order"] = $videoRow["video_order"];
			$arrayVideos[$videoRow["video_id"]]["video_active"] = $videoRow["video_active"];
			$arrayVideos[$videoRow["video_id"]]["video_type"] = $videoRow["video_type"];
			
		}
		return $arrayVideos;
	}	
	
	public function getOnairVideosExceptList($except) {
		$arrayVideos = Array();
		$videosQry = mysql_query("SELECT v.*,s.source_title FROM yt_videos v LEFT JOIN yt_sources s ON v.source_id = s.source_id WHERE v.video_deleted = 0 AND v.video_active = 1 and v.video_id <> ".mysql_real_escape_string($except)." ORDER BY v.video_order");
		
		while($videoRow=mysql_fetch_array($videosQry)) {
			
			$arrayVideos[$videoRow["video_id"]] = Array();
			$arrayVideos[$videoRow["video_id"]]["video_title"] = stripslashes($videoRow["video_title"]);
			$arrayVideos[$videoRow["video_id"]]["video_link"] = $videoRow["video_link"];
			$arrayVideos[$videoRow["video_id"]]["video_duration"] = $videoRow["video_duration"];
			$arrayVideos[$videoRow["video_id"]]["video_source"] = $videoRow["source_title"];
			$arrayVideos[$videoRow["video_id"]]["video_order"] = $videoRow["video_order"];
			$arrayVideos[$videoRow["video_id"]]["video_active"] = $videoRow["video_active"];
			$arrayVideos[$videoRow["video_id"]]["video_type"] = $videoRow["video_type"];
			
		}
		return $arrayVideos;
	}	
	
	public function getTotShowsPerMonth($month) {
		$rsShow = mysql_query("SELECT COUNT(s.show_id) as tot FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE DATE_FORMAT(s.show_date,'%M') = '".mysql_real_escape_string($month)."'");
		return mysql_result($rsShow,0,'tot');
	}
	
	public function getTotShowsPerDay($date) {
		$rsShow = mysql_query("SELECT COUNT(s.show_id) as tot FROM yt_show s INNER JOIN yt_videos v ON v.video_id = s.video_id WHERE DATE_FORMAT(s.show_date,'%Y-%m-%d') = '".mysql_real_escape_string($date)."'");
		return mysql_result($rsShow,0,'tot');
	}
	//utils
	public function timeStringToMilli($timeString) {
		$timeArr = explode(':',$timeString);
		$milli = ($timeArr[2]*1000)+($timeArr[1]*60*1000)+($timeArr[0]*60*60*1000);
		return $milli;
	}
	
	public function milliToTimeString($milli) {
		//var seconds = milli/1000;
		$hours = floor($milli/1000/60/60);
		$minutes = floor(($milli/1000/60))-($hours*60);
		$seconds = floor($milli/1000)-($hours*60*60)-($minutes*60);
		$hoursString = $hours;
		if(strlen($hoursString)==1) {
			$hoursString = "0".$hoursString;
		}
		$minutesString = $minutes;
		if(strlen($minutesString)==1) {
			$minutesString = "0".$minutesString;
		}
		$secondsString = $seconds;
		if(strlen($secondsString)==1) {
			$secondsString = "0".$secondsString;
		}
		
		return $hoursString.":".$minutesString.":".$secondsString;
	}
	
	public function milliToTimeNeutralString($milli) {
		//var seconds = milli/1000;
		$hours = floor($milli/1000/60/60);
		$minutes = floor(($milli-($hours*60*60*1000))/1000/60);
		$seconds = floor($milli/1000)-($hours*60*60)-($minutes*60);
		$hoursString = $hours;
		
		$minutesString = $minutes;
		
		$secondsString = $seconds;
		
		
		return "Available time: ".$hoursString." hours, ".$minutesString." minutes and ".$secondsString." seconds";
	}
	
	}

?>
