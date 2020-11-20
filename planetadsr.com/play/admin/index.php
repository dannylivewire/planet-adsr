<?php 
include 'common.php';
if(!isset($_SESSION["admin_id"])) {
	header('Location: login.php');
} else {
	header('Location: welcome.php');
}
?>