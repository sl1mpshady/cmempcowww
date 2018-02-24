<?php
if (!isset($_SESSION)) {
  session_start();
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sales and Inventory Management System</title>
<link href="../media/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="../media/css/bootstrap.min.css" rel="stylesheet">
<script src="../media/js/jquery.min.js"></script>
<script src="../media/js/bootstrap.min.js"></script>
<script src="../media/js/shortcut.js"></script>
<script src="../media/js/bootbox.min.js"></script>
<script src="../media/js/date.js"></script>
<link href="../media/fonts/font.css" type="text/css" rel="stylesheet">
</head>

<style>
body {
	background: url(../media/images/bg2.png);
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
	window.onload = date_time("date_time");
    var view_width = $(window).width();
	var view_top = $(window).scrollTop() + 150;
	$('#error').css('margin-top',view_top);
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
<div id="error" style="text-align: center;color: white;font-size: 52px;font-family:Ubuntu">Running the system on browsers is not supported.<br>Please run the system using the Desktop Application.</div>
</body>

</html>
