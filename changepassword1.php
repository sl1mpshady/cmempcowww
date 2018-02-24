<?php require_once('Connections/connSIMS.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
if(isset($_SESSION['MM_Username'])){
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


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StoreSys</title>
<?php include_once('menu.php'); ?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<title></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
</head>
<script>
	$(document).ready(function () {
		
	});
</script>
<body>
<div style="padding:25px">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:25px" id="header2">Change Password</div>
    <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<span id="blank" style="display:none">Please fill all the boxes in red.</span>
        <span id="pass" style="display:none">Password did not match.</span>
        <span id="id1" style="display:none">Old Password is incorrect.</span>
        <span id="name1" style="display:none">Account name already exist. Please user other name.</span>
        <span id="length" style="display:none">Password must contain six characters above.</span>
		<span id="upper" style="display:none">Password must contain atleast one uppercase character.</span>
		<span id="lower" style="display:none">Password must contain atleast one lowercase character.</span>
		<span id="number" style="display:none">Password must contain atleast one number.</span>
		<span id="error" style="display:none"></span>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
	
    <div style="/*border:1px solid lightgray;border-radius:4px*/">
    	<!--<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-plus-sign"></span> Add Customer</div>-->
        <div style="padding:20px;overflow:auto;padding-top:4px" id="body">
        	<style>
				.form-control {
					width: 50%;
					margin-left: 20px;
				}
				td:nth-child(1) {
					text-align: right;
					font-weight: bold;
					width: 40%;
				}
				td:nth-child(2),td:nth-child(3),td:nth-child(4){
					padding:5px;
				}
				.head-title1 {
					background-color: rgb(150, 150, 150);
					margin-left: -20px;
					font-family: calibri;
					width: 35%;
					padding-left: 15px;
					font-weight: bold;
					color: white;
					box-shadow: 2px 2px 5px 1px gray;
					text-align: center;
					margin-bottom:15px;
				}
				placeholder #yy {
					color:black;
				}
				.error {
					.has-error .form-control {
					border-color: #a94442;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
				  }
				 .form-control .succ{
					border-color: #3c763d;
					-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
					box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
				  }

			</style>
			<table width="50%" style="width:100% !important">
				<tr>
					<td><span class='required'>*</span> Username :</td>
					<td><input type="hidden" value="<?php echo $_SESSION['MM_AccID']; ?>" id="accIDC"><input disabled value="<?php echo $_SESSION['MM_Username']; ?>" type="text" class="form-control" id="uname" placeholder="Username"></td>
				</tr>
				<tr>
					<td><span class='required'>*</span> Old Password :</td>
					<td><input type="password" class="form-control" id="pass0" placeholder="Password"></td>
				</tr>
				<tr>
					<td><span class='required'>*</span> New Password :</td>
					<td><input type="password" class="form-control" id="pass1" placeholder="Password"></td>
				</tr>
				<tr>
					<td><span class='required'>*</span> Confirm New Password :</td>
					<td><input type="password" class="form-control" id="pass2" placeholder="Confirm Password"></td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td>
						<button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Submit</button>
						<button id="clear" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Clear</button>
					</td>
              </tr>
		    </table>
    </div>
</div>
<script>
$(document).ready(function() {
	function clear(){
		$('#blank,#pass,#id1,#name1,#length,#upper,#lower,#number').hide();
	}
	function showError(id){
		$('#header2').css('margin-bottom','0');
		clear();
		$('#box,#'+id).show();
	}

	var check = true;
	$('#submit').click(function() {
		$('.close').click();
		var check = true;
		$('input[type=text],input[type=password]').each(function(index, element) {
			if($(this).val().trim()==''){
				$(this).val('');
				check = false;
				$(this).parent('td').removeClass('has-success');
				$(this).parent('td').addClass('has-error');
			}
			else{
				$(this).parent('td').removeClass('has-error');
				$(this).parent('td').addClass('has-success');
			}
        });
		if(!check)
			showError('blank');
		else{
			if($('#pass1').val() != $('#pass2').val()){
				showError('pass');
				check = false;
			}
			else
				check = true;
		}
		if(check){
			if($('#pass1').val().length < 6)
				showError('length');	
			else{
				if($('#pass1').val().match(/[A-Z]/g) == null)
					showError('upper');
				else{
					if($('#pass1').val().match(/[a-z]/g) == null)
						showError('lower');
					else{
						if($('#pass1').val().match(/[0-9]/g) == null)
							showError('number');
						else{
							$.ajax({
								url: 'check/check.php',
								type: 'post',
								dataType: 'json',
								data: {accUsername:$('#uname').val(),accIDC:$('#accIDC').val(),accPassword:$('#pass0').val(),accPassword1:$('#pass1').val()},
								success: function(s){
									console.log(s);
									if(s[0]==true){
										window.location.assign('logout.php');
									}
									else {
										showError('id1');
									}
								},
								error: function(f){
									$('#error').html(f);
									showError('error');
								}
							});
						}
					}
				}
			}
			
		}
	});
	$('#ok').click(function() {
		window.location.assign('view-accounts1.php');
	});
});
</script>
</body>
<div class="print_info" id="print_info" style="display:none"><h6>New Account Added</h6><button id="ok" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">OK</button></div>
</html>
<?php
}else
	header("Location: index.php?SE");
	?>