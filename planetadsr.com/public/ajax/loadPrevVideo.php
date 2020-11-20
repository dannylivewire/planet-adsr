<?php
include '../common.php';

echo $listObj->getPrevShow($_GET["current_video"],$settingObj->getPlaylistStart());

?>