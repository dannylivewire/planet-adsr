<?php 
set_time_limit(0);
include_once dirname(__FILE__).'/../admin/include/db_conn.php';
include_once dirname(__FILE__).'/class/settings.class.php';
$settingObj = new setting();
date_default_timezone_set($settingObj->getTimezone());
$now = new DateTime();
$mins = $now->getOffset() / 60;
$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;
$offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
mysql_query("SET time_zone='$offset';");

include_once dirname(__FILE__).'/class/list.class.php';


$listObj = new lists();


session_start();

?>
