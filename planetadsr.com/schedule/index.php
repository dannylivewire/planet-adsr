<?php 

$username="codexdru_playlis";
$password="oq-rl6J&Mv3P";
$database="codexdru_scheduler001";

$mysqli = new mysqli("localhost", $username, $password, $database);

$mysqli->select_db($database) or die( "Unable to select database");

?>