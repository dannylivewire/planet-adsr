<?php
include '../common.php';
if($settingObj->getScheduleVideo() == 1) {
	echo $listObj->getFirstShow($settingObj->getPlaylistStart());
} else {
	echo $listObj->getFirstVideo();
}

?>