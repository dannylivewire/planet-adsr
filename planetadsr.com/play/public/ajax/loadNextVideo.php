<?php
include '../common.php';
if($settingObj->getScheduleVideo() == 1) {
	echo $listObj->getCurrentShow();
} else {
	echo $listObj->getNextVideo($_GET["current_video"]);
}

?>