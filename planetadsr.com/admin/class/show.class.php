<?php
class show {
	private static $show_id;
	private static $qryShow;
	
	public function setShow($id) {
		
		$rsShow = mysql_query("SELECT * FROM yt_show WHERE show_id = '".mysql_real_escape_string($id)."'");
		
		$rowShow = mysql_fetch_array($rsShow);
		show::$qryShow = $rowShow;
		show::$show_id=$rowShow["show_id"];
		
	}
	
	public function getShowId() {
		return show::$show_id;
	}
	
	public function getShowVideoId() {
		return show::$qryShow["video_id"];
	}
	
	public function getShowDate() {
		return show::$qryShow["show_date"];
	}
	
	public function getShowTime() {
		return show::$qryShow["show_time"];
	}
	
	public function getShowOrder() {
		return show::$qryShow["show_order"];
	}
	
	public function delShows($listIds) {
		mysql_query("DELETE FROM yt_show WHERE show_id IN (".$listIds.")");
	}
	
	public function insertShow() {
		$arrVideos = $_POST["selected_video"];
		for($z=0;$z<count($arrVideos);$z++) {
			$video_id = $arrVideos[$z];
			//check datetime array
			$showdateArr = $_POST["show_date".$video_id];
			$showtimeArr = $_POST["show_time".$video_id];
			if($showdateArr[0]!='') {
				for($n=0;$n<count($showdateArr);$n++) {
					$show_date = $showdateArr[$n];
					$show_time = $showtimeArr[$n];
				
					if($show_time=='') {
						$show_time='00:00:00';
					}
					//echo $video_id.":".$show_date."-".$show_time."<br>";
					if(isset($_POST["recurrency".$video_id]) && $_POST["recurrency".$video_id] == 1) {
						$end_date = $_POST["end_date".$video_id];
						//get weekdays
						//separate day, month and year
						$arrDateFrom=explode(",",$show_date);
						if($end_date!='') {
							$arrDateTo=explode(",",$end_date);
						} else {
							$arrDateTo=explode(",",$show_date);
						}
						//get an int for the two dates
						$dateFrom=str_replace(",","",$show_date);
						if($end_date!='') {
							$dateTo=str_replace(",","",$end_date);
						} else {
							$dateTo=str_replace(",","",$show_date);
						}
						//loop over weekdays selected
						$resultDate = array();	
						$arrWeekdays = $_POST["show_weekday".$video_id];
						for($i=0;$i<count($arrWeekdays);$i++) {
							
							$newdateFrom=$dateFrom;
							
							
							$year=$arrDateFrom[0];			
							$day = $arrDateFrom[2];
							$mo = $arrDateFrom[1];
							
							$date = strtotime(date('Y-m-d',mktime(0,0,0,$mo,$day,$year)));
							$weekday = date("N", $date);
							
							$j = 1;
							
							while ($weekday != $arrWeekdays[$i] && date("Ymd",$date)<$dateTo) {
								
								$date=strtotime(date("Y-m-d", $date) . "+ 1 day");
								
								$weekday = date("N", $date);
								
							}
							
							if(date("N", $date) == $arrWeekdays[$i]) {
								array_push($resultDate,date('Y-m-d',$date));
							}
							
							while ($newdateFrom <= $dateTo) {
								
								$test =  strtotime(date("Y-m-d", $date) . "+" . $j . " week");
								$j++;
								if(date("Ymd",$test) <= $dateTo) {
									array_push($resultDate,date("Y-m-d", $test));
								}
								
								$newdateFrom = date("Ymd",$test);
							}
							
						}
						
						//use $resultDate for insert
						for($u=0;$u<count($resultDate);$u++) {
							mysql_query("INSERT INTO yt_show(video_id,show_date,show_time,show_active) VALUES('".mysql_real_escape_string($video_id)."','".mysql_real_escape_string($resultDate[$u])."','".mysql_real_escape_string($show_time.":00")."',1)");
						}
					} else {
						
						//insert just one 
						mysql_query("INSERT INTO yt_show(video_id,show_date,show_time,show_active) VALUES('".mysql_real_escape_string($video_id)."','".mysql_real_escape_string($show_date)."','".mysql_real_escape_string($show_time.":00")."',1)");
					}
				}
			}
		}
	}
	
	public function saveShow($show_id,$show_time) {
		mysql_query("UPDATE yt_show SET show_time='".mysql_real_escape_string($show_time.":00")."' WHERE show_id='".mysql_real_escape_string($show_id)."'");
	}

	
}

?>
