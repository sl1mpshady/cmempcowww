<?php
if(!isset($_SESSION['MM_Username'])){


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
  $myfile = fopen("server.txt", "r") or $_SESSION['serverID']='localhost';
  if($myfile)
  	$_SESSION['serverID'] = fgets($myfile);
  fclose($myfile);
  //$_SESSION['serverID'];
  require_once('Connections/connection.php');	
  $hostname_connSIMS = $_SESSION['serverID'];
  $database_connSIMS = $db_database;
  $username_connSIMS = $db_username;
  $password_connSIMS = $db_password;
  /*$connSIMS = mysql_pconnect($hostname_connSIMS, $username_connSIMS, $password_connSIMS); */
}
if(isset($_GET['SE'])){
	$_SESSION['MM_Username'] = NULL;
	$_SESSION['MM_UserGroup'] = NULL;
	$_SESSION['PrevUrl'] = NULL;
	unset($_SESSION['MM_Username']);
	unset($_SESSION['MM_UserGroup']);
	unset($_SESSION['PrevUrl']);
}
else {
	if(isset($_SESSION['MM_Username'])){
		header("Location: home.php");
	}
	$loginFormAction = $_SERVER['PHP_SELF'];
	if (isset($_GET['accesscheck'])) {
	$_SESSION['PrevUrl'] = $_GET['accesscheck'];
	}
	
	function getIP(){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && eregi("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$",$_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = getenv('REMOTE_ADDR');
		}
		return $ip;
	} 
		
	if (isset($_POST['username']) && $connSIMS) {
	require_once('class/base64.php');	
	$loginUsername=$_POST['username'];
	$password=$_POST['password'];
	$MM_fldUserAuthorization = "";
	$MM_redirectLoginSuccess = "home.php";
	$MM_redirectLoginFailed = "index.php";
	$MM_redirecttoReferrer = false;
	mysql_select_db($database_connSIMS, $connSIMS);
	$password = new Password($password,$uname);
	$LoginRS__query=sprintf("SELECT accID, accUsername, accPassword FROM account WHERE accUsername=%s AND accPassword=%s AND accStatus='Active'",
		GetSQLValueString($loginUsername, "text"), GetSQLValueString($password->encrypt(), "text")); 
	
	$LoginRS = mysql_query($LoginRS__query, $connSIMS) or die(mysql_error());
	$row_rsAccount = mysql_fetch_array($LoginRS);
	$loginFoundUser = mysql_num_rows($LoginRS);
	if ($loginFoundUser) {
		$loginStrGroup = "";
		
		$query = "SELECT accID,userPC,userIP FROM logged_in WHERE accID='".$row_rsAccount['accID']."'";
		$check = mysql_query($query, $connSIMS) or die(mysql_error());
		$row_rsCheck = mysql_fetch_array($check);
		$checkFoundUser = mysql_num_rows($check);
		$status = false;
		if($checkFoundUser){
			if($row_rsCheck['userPC'] == gethostbyaddr($_SERVER['REMOTE_ADDR']))
				$status = true;
			else 
				$error = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="
		margin-right: 3px;
	"></span>Account has already logged in from another computer.<button style="width:inherit;margin:0" type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>';
			
		}
		else
			$status = true;
		if($status){
			
			if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
			//declare two session variables and assign them
			$_SESSION['MM_Username'] = $loginUsername;
			$_SESSION['MM_UserGroup'] = $loginStrGroup;
			mysql_select_db($database_connSIMS, $connSIMS);
			$query_rsAccount = "SELECT accID, accName FROM account WHERE accUsername='$loginUsername'";
			$rsAccount = mysql_query($query_rsAccount, $connSIMS) or die(mysql_error());
			$row_rsAccount = mysql_fetch_assoc($rsAccount);
			$totalRows_rsAccount = mysql_num_rows($rsAccount);
			$_SESSION['MM_AccName'] = $row_rsAccount['accName'];
			$_SESSION['MM_AccID'] = $row_rsAccount['accID'];
			$query = "DELETE FROM logged_in WHERE accID='".$_SESSION["MM_AccID"]."'";
			mysql_select_db($database_connSIMS, $connSIMS);
			mysql_query($query, $connSIMS) or die(mysql_error());
			$query = "INSERT INTO logged_in VALUES ('".$_SESSION["MM_AccID"]."','".getIP()."','".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',null,'".session_id()."')";
			mysql_select_db($database_connSIMS, $connSIMS);
			mysql_query($query, $connSIMS) or die(mysql_error());
			if (isset($_SESSION['PrevUrl']) && false) {
			$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
			}
			mysql_free_result($rsAccount);
			mysql_free_result($check);
			header("Location: " . $MM_redirectLoginSuccess );
		}
	}
	else {
		$error = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="
		margin-right: 3px;
	"></span>Invalid login details.<button style="width:inherit;margin:0" type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>';
	}
	}
}
?>