<?php require_once('Connections/connSIMS.php'); 
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
$_SESSION['MM_Encrypted'] = NULL;
$_SESSION['MM_AccType'] = NULL;
$_SESSION['PrevUrl'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
unset($_SESSION['MM_Encrypted']);
unset($_SESSION['MM_AccType']);
unset($_SESSION['PrevUrl']);
$query = "DELETE FROM logged_in WHERE accID='".$_SESSION['MM_AccID']."'";
mysql_select_db($database_connSIMS, $connSIMS);
mysql_query($query, $connSIMS) or die(mysql_error());
$logoutGoTo = "index.php";
if (isset($_GET['password'])) {
  header("Location: $logoutGoTo"."?password");
  exit;
}
else{
  header("Location: $logoutGoTo");
  exit;
}
?>