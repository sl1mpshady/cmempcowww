<?php
if(!isset($_SESSION['MM_Username'])){
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (!isset($_SESSION)) {
  session_start();
  $myfile = fopen("server.txt", "w");
  fwrite($myfile, shell_exec("REG QUERY HKLM\\SOFTWARE\\CMEMPCO /v ServerDB"));
  fclose($myfile);
  if($myfile = fopen("server.txt", "r")) {
	$server = fread($myfile,filesize("server.txt"));
	$server = str_replace('    '," ",$server);
	$server = explode(" ",$server);
	$i = 0;
	foreach($server as $key){
		if($key=='ServerDB'){
			$_SESSION['serverID'] = $server[$i+2];
			break;
		}
		$i++;
	}
	fclose($myfile);
  }
  else
	$_SESSION['serverID']='localhost';
  
  $hostname_connSIMS = $_SESSION['serverID'];
  $database_connSIMS = "sims2";
  $username_connSIMS = "root";
  $password_connSIMS = "";
}

if(isset($_GET['SE']) || isset($_GET['password'])){
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
			mysql_select_db($database_connSIMS, $connSIMS);
			$query_rsAccount = "SELECT accID, accName, accType, accPassword FROM account WHERE accUsername='$loginUsername'";
			$rsAccount = mysql_query($query_rsAccount, $connSIMS) or die(mysql_error());
			$row_rsAccount = mysql_fetch_assoc($rsAccount);
			$totalRows_rsAccount = mysql_num_rows($rsAccount);
			$_SESSION['MM_AccName'] = $row_rsAccount['accName'];
			$_SESSION['MM_AccID'] = $row_rsAccount['accID'];
			$_SESSION['MM_AccType'] = $row_rsAccount['accType'];
			$_SESSION['MM_Encrypted'] = $row_rsAccount['accPassword'];
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
<link href="media/css/popup.css" type="text/css" rel="stylesheet">
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
#error,#error1 {
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

	//try { if(!phpdesktop.GetVersion()) window.location.assign('error'); } catch(err) { window.location.assign('error'); }
	window.onload = date_time("date_time");
    var view_width = $(window).width();
	var view_top = $(window).scrollTop() + 200;
	$('#signup').css("left", (view_width - $('#signup').width() ) / 2 );
	$('#signup').css("top", view_top);
	shortcut.add('enter',function() {
		$('#submit').click();
	});
	$('#submit').click(function(){
		if($('input[name=username]').val()!='' && $('input[type=password]').val()!=''){
			$.ajax({
				url: 'login.php',
				type: 'POST',
				data: {'username':$('input[name=username]').val(),'password':$('input[type=password]').val()},
				dataType: 'json',
				success: function(s){
					console.log(s);
					if(!s[0]){
						$('#error1').hide();
						$('#error-msg').html(s[1]);
						$('#error').show();
						$('input[name=username],input[type=password]').val('');
						$('input[name=username]').focus();
					}
					else
						window.location.assign('home.php');
				},
				error: function(s){
					console.log(s);
					$('#error1').hide();
					$('#error-msg').html('Can\'t connect to server.');
					$('#error').show();
					$('input[name=username],input[type=password]').val('');
					$('input[name=username]').focus();
				}
				
			});
		}
	});
	$('.close').click(function() {
			alert('asasa');
			$('#error').hide();		
	});
	if($('#db').is(':visible')){
		$('form').attr('action','');
	}
	$('#close').click(function(e) {
		preventDefault(e);
		window.close();
	});
	/*$('#changeserver').click(function(){
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		var modalBox = 'serverpopup';
		$('#'+modalBox).fadeIn(1000);
		$('#'+modalBox+' iframe').css('height',$(window).height()+00);
		var view_width = $(window).width();
		//$('#'+modalBox).css('height',$(window).height()-490);
		var view_top = $(window).scrollTop() + 100;
		$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
		$('#'+modalBox).css("top", view_top);
		$('#about_popup .modal-body').html('<marquee scrollamount="2" behavior="pause" direction="up">'+$('#text').html()+'</marquee>');
		$('#about_popup marquee').css('height','100%');
		$('#about_popup marquee').css('white-space','pre-line');
		$('input[name=server]').val($('#serverIP').html());
		
	});*/
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});

	shortcut.add('alt+ctrl+shift+escape', function(){
		$('.bootbox,.modal-backdrop').remove();
		bootbox.dialog({
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
                <td style="text-align: right;" id="serverIP"><?php echo $_SESSION['serverID'];?></td>
            </tr>
		</tbody>
	</table>
</div>
<div style="text-align:right"><a href="javascript:;" id="changeserver">Change Server</a></div>
<div id="signup" style="display: block; position: fixed; opacity: 1; z-index: 11;/* left: 50%;*/ /*margin-left: -202px;*/ /*top: 200px;*/background:whitesmoke">
    <div id="header" style="text-align:center"><img src="media/images/logo.png" style="width:88%" /></div>
    <div id="form" style="margin-top:12px;padding-bottom:10px;padding-left:22px;padding-right:22px">
		<?php if(isset($_GET['password'])){?><div id="error1" class="bg-danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="margin-right: 3px;"></span> Account updated. Please log in again</div><?php }?>
        <?php if(isset($_GET['SE'])){?><div id="error1" class="bg-danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="margin-right: 3px;"></span> Server error. Please log in again.</div><?php }?>
		<div id="error" style="display:none" class="bg-danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="margin-right: 3px;"></span> <span id="error-msg"</span> <button style="width:inherit;margin:0" type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button></div>
        
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
			<td id="label" style="width:24% !important">Password:</td>
			<td width="70%" style="width:80% !important"><input required="required" title="Please fill in the password." type="password" name="password" class="form-control"/></td>
          </tr>
        </table>
        
        
        <table>
        	<tr>
            	<td width="70%"><div id="btn" style="text-align:center"><button id="submit" class="btn btn-default btn-lg btn-block" style=" font-weight: bold;    text-transform: uppercase;margin-top: 9px; font-size: 15px;    padding: 0.75em 1.5em;       border: 1px solid #bbb;    color: #333;    text-decoration: none;    display: inline;    border-radius: 4px;    -webkit-transition: background-color 0.5s ease;    -moz-transition: background-color 0.5s ease;    transition: background-color 0.5s ease; background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%); width: 100%;    font-size: 15px;    font-weight: bold;    font-family: 'Ubuntu' !important;">Authenticate</button></div>
        		</td>
              	<td><div id="btn" style="text-align:center"><button id="close" onclick="javascript:window.close();" style=" font-weight: bold;    text-transform: uppercase;margin-top: 9px; font-size: 15px;    padding: 0.75em 1.5em;       border: 1px solid #bbb;    color: #333;    text-decoration: none;    display: inline;    border-radius: 4px;    -webkit-transition: background-color 0.5s ease;    -moz-transition: background-color 0.5s ease;    transition: background-color 0.5s ease; background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%); width: 95%; float:right;    font-size: 15px;    font-weight: bold;    font-family: 'Ubuntu' !important;">Exit</button></div></td>
            </tr>
      	</table>
        <div style="font-size:10px;text-align:right">v1.0</div>
      <!--</form>-->
    </div>	
</div>
<div id="serverpopup" class="modal-box" style="width: 31%; display: none; left: 237px; top: 150px;z-index:99999999999999">
    	<header>
          <h3>Change Server</h3>
        </header>
        <div class="modal-body">
			<form method="POST">
			<table>
				<tr>
					<td id="label" style="width:24% !important">Server:</td>
					<td style="width:80% !important"><input required="required" type="text" style="font-size:14px !important;height:34px" class="form-control" name="server" autocomplete="off"  /></td>
				</tr>
				
				</table>
				<table>
				<tr>
					<td width="70%"><div id="btn" style="text-align:center"><button id="submitserver" type="submit" class="btn btn-default btn-lg btn-block" style=" font-weight: bold;    text-transform: uppercase;margin-top: 9px; font-size: 15px;    padding: 0.75em 1.5em;       border: 1px solid #bbb;    color: #333;    text-decoration: none;    display: inline;    border-radius: 4px;    -webkit-transition: background-color 0.5s ease;    -moz-transition: background-color 0.5s ease;    transition: background-color 0.5s ease; background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%); width: 100%;    font-size: 15px;    font-weight: bold;    font-family: 'Ubuntu' !important;">Submit</button></div>
					</td>
					<td><div id="btn" style="text-align:center"><button id="close" style=" font-weight: bold;    text-transform: uppercase;margin-top: 9px; font-size: 15px;    padding: 0.75em 1.5em;       border: 1px solid #bbb;    color: #333;    text-decoration: none;    display: inline;    border-radius: 4px;    -webkit-transition: background-color 0.5s ease;    -moz-transition: background-color 0.5s ease;    transition: background-color 0.5s ease; background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%); width: 95%; float:right;    font-size: 15px;    font-weight: bold;    font-family: 'Ubuntu' !important;" class="js-modal-close">Cancel</button></div></td>
				</tr>
			</table>
			</form>
        </div>
    </div>
</body>

</html>

<?php } else {
	 header("Location: home.php");
}