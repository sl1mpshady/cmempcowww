<?php //require_once('Connections/connSIMS.php'); ?>
<?php
if(!isset($_SESSION['MM_Username'])){
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

?>
<?php
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
  $connSIMS = mysql_pconnect($hostname_connSIMS, $username_connSIMS, $password_connSIMS); 
}
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
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "home.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_connSIMS, $connSIMS);
  $password = str_rot13($password);
  $LoginRS__query=sprintf("SELECT accID, accUsername, accPassword FROM account WHERE accUsername=%s AND accPassword=%s AND accStatus='Active'",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sales and Inventory Management System</title>
<link href="media/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="media/css/bootstrap.min.css" rel="stylesheet">
<script src="media/js/jquery.min.js"></script>
<script src="media/js/bootstrap.min.js"></script>
<script src="media/js/shortcut.js"></script>
<script src="media/js/bootbox.min.js"></script>
<script src="media/js/date.js"></script>
<link href="media/fonts/font.css" type="text/css" rel="stylesheet">
</head>

<style>
body {
	background: url(media/images/bg2.png);
	font-family: "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", sans-serif;
	font-family:calibri;
}
img {
	width: 361px !important;
    height: 55px !important; 
}
#header {
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#CCC));
	background: -webkit-linear-gradient(top, #FFF, #CCC);
	background: -moz-linear-gradient(top, #FFF, #CC);
	background: -ms-linear-gradient(top, #FFF, #CC);
	background: -o-linear-gradient(top, #FFF, #CC);
}
#signup, #header {
   	border-top-left-radius: 5px;
   	border-top-right-radius: 5px;
}
#label {
	  /* text-shadow: 1px 0px black; */
	font-size: 15px;
	font-weight: bold;
	font-family: 'Ubuntu' !important;
}
input {
	font-size:20px !important;
	font-size: 15px !important;
    font-family: ubuntu;
    font-weight: bold;
}
button {
	width: 94%;
	padding: 5px;
	background: #000033;
	color: whitesmoke;
	text-transform: uppercase;
	margin-top: 9px;
}
#error {
	padding: 3px;
	color: #ce4844;
	margin-bottom:10px;
}
#top td {
	width:33%;
}
#signup tr {
	padding:12px;
}
a:hover {
	text-transform:none;
	cursor:pointer;
}
.bootbox {
	margin-top: 125px;
    z-index: 99999;
}
.bootbox button {
	width:inherit;
}
.modal-backdrop {
	z-index:99999;
}
.bootbox {
	z-index:999999;
}
</style>
<script>
$(document).ready(function(e) {
	window.onload = date_time("date_time");
    var view_width = $(window).width();
	var view_top = $(window).scrollTop() + 200;
	$('#signup').css("left", (view_width - $('#signup').width() ) / 2 );
	$('#signup').css("top", view_top);
	
	shortcut.add('alt+ctrl+shift+escape', function(){
		$('.bootbox,.modal-backdrop').remove();
		bootbox.dialog({
			//message: "Please login an admin account below for authentication.",
			message: "Clear Logins",
			buttons: {
				main: {
					label: 'Yes',
					className: "btn",
					callback: function() {
						 
					}
				},
				cancel: {
					label: 'No',
					className: "btn"
				}
			}
		});	
	});
});

</script>

<body onload="startTime()">
<div style="background-color: rgba(95, 95, 95, 0.32);padding: 0px 10px;color: whitesmoke;font-weight: bold" id="top">
	<table width="100%" style="text-transform:uppercase">
  		<tbody>
        	<tr>
    			<td>Client Mode</td>
                <td style="text-align: center;"><span id="date_time">08:04 PM</span></td>
                <td style="text-align: right;"><?php echo $_SESSION['serverID'];?></td>
            </tr>
		</tbody>
	</table>
</div>
<div style="text-align:right"><a href="#" id="server"><?php echo $_SERVER['SERVER_SOFTWARE'];?></a></div>
<div id="signup" style="display: block; position: fixed; opacity: 1; z-index: 11000;/* left: 50%;*/ /*margin-left: -202px;*/ /*top: 200px;*/background:whitesmoke">
    <div id="header" style="text-align:center"><img src="media/images/logo.png" style="width:88%" /></div>
    <div id="form" style="margin-top:12px;padding-bottom:10px;padding-left:22px;padding-right:22px">
      <form ACTION="<?php if($connSIMS)echo $editFormAction; ?>" METHOD="POST" name="login">
        <?php if(isset($error)){?><div id="error" class="bg-danger"><?php echo $error; ?></div><?php }?>
        <?php if(!$connSIMS && isset($_POST['username'])){?><div id="db" class="bg-danger" style="padding:3px;color: #ce4844;margin-bottom:10px"><?php echo "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true' style='margin-right: 3px;'></span>Can't connect to server."; ?></div><?php }?>
        <table width="360px" style="width:360px !important">
          <tr>
            <td style="display:none" colspan="2">For security reasons please enter your username and password to continue using the system. </td>
          </tr>
          <tr>
            <td id="label" style="width:24% !important">Username:</td>
            <td style="width:80% !important"><input required="required" type="text" class="form-control" name="username" autocomplete="off"  /></td>
          </tr>
        </table>
		<table width="360px" style="width:360px !important;margin-top:5px">
          <tr>
            <!--<td id="label" style="width:24% !important"><?php /*echo gethostbyaddr($_SERVER['REMOTE_ADDR']).' '.php_uname('n');*/ echo getenv('COMPUTERNAME');?>:</td>
            <td id="label" style="width:24% !important"><?php echo getIP();?>:</td>-->
			<td id="label" style="width:24% !important">Password:</td>
			<td width="70%" style="width:80% !important"><input required="required" title="Please fill in the password." type="password" name="password" class="form-control"/></td>
          </tr>
        </table>
        
        
       <!-- <div id="label">Password:</div>
        <div><input type="password" name="password" class="form-control"/></div>-->
        <table>
        	<tr>
            	<td width="70%"><div id="btn" style="text-align:center"><button id="submit" class="btn btn-default btn-lg btn-block" style=" font-weight: bold;    text-transform: uppercase;margin-top: 9px; font-size: 15px;    padding: 0.75em 1.5em;       border: 1px solid #bbb;    color: #333;    text-decoration: none;    display: inline;    border-radius: 4px;    -webkit-transition: background-color 0.5s ease;    -moz-transition: background-color 0.5s ease;    transition: background-color 0.5s ease; background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%); width: 100%;    font-size: 15px;    font-weight: bold;    font-family: 'Ubuntu' !important;">Authenticate</button></div>
        		</td>
              	<td><div id="btn" style="text-align:center"><button id="close" onclick="javascript:window.close();" style=" font-weight: bold;    text-transform: uppercase;margin-top: 9px; font-size: 15px;    padding: 0.75em 1.5em;       border: 1px solid #bbb;    color: #333;    text-decoration: none;    display: inline;    border-radius: 4px;    -webkit-transition: background-color 0.5s ease;    -moz-transition: background-color 0.5s ease;    transition: background-color 0.5s ease; background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%); width: 95%; float:right;    font-size: 15px;    font-weight: bold;    font-family: 'Ubuntu' !important;">Exit</button></div></td>
            </tr>
      	</table>
        <div style="font-size:10px;text-align:right">v1.0</div>
      </form>
    </div>	
</div>
</body>
<script>
	$(document).ready(function() {
		$('.close').click(function() {
			$('#error').hide();		
		});
		if($('#db').is(':visible')){
			$('form').attr('action','');
		}
		$('#close').click(function(e) {
			preventDefault(e);
            window.close();
        });
	});
</script>
</html>

<?php } else {
	 header("Location: home.php");
}