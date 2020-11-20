<?php
class admin {
	private static $user_id;
	private static $qryUser;
	
	public function setAdmin($id) {
		
		$userQry = mysql_query("SELECT * FROM yt_admins WHERE admin_id = '".mysql_real_escape_string($id)."'");
		
		$userRow = mysql_fetch_array($userQry);
		admin::$qryUser = $userRow;
		admin::$user_id=$userRow["admin_id"];
		
	}
	
	public function doLogin($username, $password) {
		$returnvalue = 0;
		$loginQry = mysql_query("SELECT * FROM yt_admins WHERE admin_username = '".mysql_real_escape_string($username)."' AND admin_password = '".mysql_real_escape_string(md5($password))."'");
		if(mysql_num_rows($loginQry)>0) {
			$returnvalue = mysql_result($loginQry,0,'admin_id');
			
		}
		$this->setAdmin($returnvalue);
		
		return $returnvalue;
	}
	
	public function getAdminId() {
		return admin::$user_id;
	}
	
	public function getAdminUsername() {
		return admin::$qryUser["admin_username"];
	}
	
	public function updatePassword() {
		mysql_query("UPDATE yt_admins SET admin_password='".mysql_real_escape_string(md5($_POST["password"]))."' WHERE admin_id='".mysql_real_escape_string($_SESSION["admin_id"])."'");
		echo mysql_error();
	}
	
}

?>
