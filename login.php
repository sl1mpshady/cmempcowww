<?php
require_once('class/base64.php');	
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
function getIP(){
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && eregi("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$",$_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = getenv('REMOTE_ADDR');
	}
	return $ip;
} 

if (!isset($_SESSION)) {
  session_start();
}
$response = array();

if(isset($_POST['username']) && isset($_POST['password'])){
	require_once('Connections/connSIMS.php');
	if($connSIMS){
		if(mysql_select_db($database_connSIMS, $connSIMS)){
			$password = new Password($_POST['password'],$_POST['username']);
			$loginUsername = $_POST['username'];
			$LoginRS__query=sprintf("SELECT accID, accUsername, accPassword,accStatus FROM account WHERE accUsername=%s AND accPassword=%s",
			GetSQLValueString($loginUsername, "text"), GetSQLValueString($password->encrypt(), "text"));
			$LoginRS = mysql_query($LoginRS__query, $connSIMS) or die(mysql_error());
			
			$row_rsAccount = mysql_fetch_array($LoginRS);
			$loginFoundUser = mysql_num_rows($LoginRS);
			if ($loginFoundUser) {
				if($row_rsAccount[3]!='Active'){
					($row_rsAccount[3]=='Delete') ? $response=array(false,'Invalid login details.') :  $response=array(false,'Your account is blocked.');
				}
				else {
					$loginStrGroup = "";
					$query = "SELECT accID,userPC,userIP FROM logged_in WHERE accID='".$row_rsAccount['accID']."'";
					
					$check = mysql_query($query, $connSIMS) or die(mysql_error());
					$row_rsCheck = mysql_fetch_array($check);
					$checkFoundUser = mysql_num_rows($check);
					$status = false;
					($checkFoundUser) ? ($row_rsCheck['userPC'] == gethostbyaddr($_SERVER['REMOTE_ADDR'])) ? $status = true : $response=array(false,'Account has already logged in from another computer.') : $status = true;
					if($status){
						if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
						$_SESSION['MM_Username'] = $_POST['username'];
						$_SESSION['MM_UserGroup'] = $loginStrGroup;
						$query_rsAccount = "SELECT accID, accName, accType, accPassword FROM account WHERE accUsername='$loginUsername'";
						$rsAccount = mysql_query($query_rsAccount, $connSIMS) or die(mysql_error());
						$row_rsAccount = mysql_fetch_assoc($rsAccount);
						$totalRows_rsAccount = mysql_num_rows($rsAccount);
						$_SESSION['MM_AccName'] = $row_rsAccount['accName'];
						$_SESSION['MM_AccID'] = $row_rsAccount['accID'];
						$_SESSION['MM_AccType'] = $row_rsAccount['accType'];
						$_SESSION['MM_Encrypted'] = $row_rsAccount['accPassword'];
						$query = "DELETE FROM logged_in WHERE accID='".$_SESSION["MM_AccID"]."'";
						mysql_query($query, $connSIMS) or die(mysql_error());
						$query = "INSERT INTO logged_in VALUES ('".$_SESSION["MM_AccID"]."','".getIP()."','".gethostbyaddr($_SERVER['REMOTE_ADDR'])."',null,'".session_id()."')";
						mysql_query($query, $connSIMS) or die(mysql_error());
						
						mysql_free_result($rsAccount);
						mysql_free_result($check);
						$response=array(true,'Success.');
					}
				}
			}
			else
				$response=array(false,'Invalid login details.');
		}
		else
			$response=array(false,'Can\'t connect to database.');
	}
	else
		$response=array(false,'Can\'t connect to server.');
}
else 
	$response=array(false,'Invalid login details.');
echo json_encode($response);
?>