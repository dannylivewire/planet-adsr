<?php
include '../common.php';
@$_POST["name"] = $_GET["name"];
@$_POST["id"] = $_GET["id"];
@$_POST["link"] = $_GET["link"];
@$_POST["type"] = $_GET["type"];
@$_POST["source"] = $_GET["source"];
echo $sourceObj->insertSource($settingObj);
?>
