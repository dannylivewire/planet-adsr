<?php
include '../common.php';
$show_id=$_GET["show_id"];
$new_time=$_GET["new_time"];
$showObj->saveShow($_GET["show_id"],$_GET["new_time"]);
?>