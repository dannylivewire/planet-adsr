<?php
include '../common.php';
if(isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] > 0) {
	$video = $_GET["id"];
	$value = $_GET["value"];
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
				mysql_query("UPDATE yt_videos SET video_order = video_order - 1  WHERE video_id<>'".mysql_real_escape_string($video)."' AND video_order > '".mysql_real_escape_string($current_value)."'");
				break;
			default:
				if($current_value<$value) {
					mysql_query("UPDATE yt_videos SET video_order = '".(mysql_real_escape_string($value))."' WHERE video_id='".mysql_real_escape_string($video)."'");
					mysql_query("UPDATE yt_videos SET video_order = video_order - 1  WHERE video_id<>'".mysql_real_escape_string($video)."' AND video_order <= '".mysql_real_escape_string($value)."' AND video_order > '".mysql_real_escape_string($current_value)."'");
				}
				if($current_value>$value) {
					mysql_query("UPDATE yt_videos SET video_order = '".($value+1)."' WHERE video_id='".mysql_real_escape_string($video)."'");
					mysql_query("UPDATE yt_videos SET video_order = video_order + 1  WHERE video_id<>'".mysql_real_escape_string($video)."' AND video_order > '".mysql_real_escape_string($value)."' AND video_order < '".mysql_real_escape_string($current_value)."'");
				}
				break;
		}
	}
}
?>
