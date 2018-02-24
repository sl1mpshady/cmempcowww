<?php require_once('Connections/connSIMS.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
if(isset($_SESSION['MM_Username'])){

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StoreSys</title>
<?php include_once('menu.php'); ?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<title><?php if(isset($_GET['name'])){echo 'asasas';}?></title>

<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<script>
	$(document).ready(function () {
		$('#admin').addClass('active');
		$('#admin_menu').show();
		$('#add-account').addClass('selected');
		
		$('#list').dataTable({
			"scrollY":        "200px",
			"scrollCollapse": false,
			"paging":         false
		});
		$('#edit').attr('data-modal-id',"edit-popup");
		$('#add').attr('data-modal-id',"add-popup");
		$('#add').html('<span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span>Add Category');
		$(document).on("click","#list tr", function(){
			if ( $(this).hasClass('active_') ) {
				$(this).removeClass('active_');
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
			}
			if($('#list .active_ td').eq(0).text() == 'No data available in table')
				$(this).removeClass('active_');
		});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn")){
				 $('#list tr.active_').removeClass('active_');
			}
		});
	});	
</script>
<style>
	.dataTables_scrollHead {
		background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);

	}
	.dataTables_scroll {
		  box-shadow: 0px 0px 2px gray;
	}
	.btn {
		  background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
		  padding: 2px 5px;
		  margin-left:5px;
		  font-size:12px;
	}
	.form-control {
		height:28px;
	}
	fieldset {
	  margin-top: 1em !important;
	  border-radius: 4px 4px 0 0;
	  -moz-border-radius: 4px 4px 0 0;
	  -webkit-border-radius: 4px 4px 0 0;
	   border: 1px solid #ccc;
	  padding: 1.5em;
	  background: white;
	  text-shadow: 1px 1px 2px #fff inset;
	  -moz-box-shadow: 1px 1px 2px #fff inset;
	  -webkit-box-shadow: 1px 1px 2px #fff inset;
	  box-shadow: 1px 1px 2px #fff inset;
	  margin-top: -1em !important;
	  
	}
	fieldset legend {
	  font-weight: bold;
	  color: #444;
	  padding: 0px 10px;
	  border-radius: 2px;
	  -moz-border-radius: 2px;
	  -webkit-border-radius: 2px;
	  border: 1px solid #aaa;
	  background-color: #fff;
	  -moz-box-shadow: 3px 3px 15px #bbb;
	  -webkit-box-shadow: 3px 3px 15px #bbb;
	  box-shadow: 3px 3px 15px #bbb;
	  max-width: 100%;
	  width: auto;
	  font-size:90%;
	  margin-bottom:0px !important;
	}
	fieldset div {
		font-size: 11px;
	}
	fieldset div input {
		width: 12px;
	  vertical-align: sub;
	  margin-right: 3px;
	}
	fieldset fieldset {
		float:left;
		margin:19px;
	}
</style>
<body>
<div style="display:none" id="parent">admin</div>
<div style="padding:25px">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:25px" id="header2">Add Account</div>
    <div id="box" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Please fill all the boxes in red.
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div id="id1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Username already exist. Please user other username.
  		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div id="pass" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Password did not match.
  		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div id="name1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">Account name already exist. Please user other name.
  		<button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-user"></span> Add Account</div>
        <div style="padding:20px;height:350px">
        	<style>
				#list td:nth-child(4),#list td:nth-child(5),#list td:nth-child(6) {
					text-align:right;	
				}
				th, #list td:nth-child(3) {
					text-align:center !important;
				}
				.form-control {
					width:90%;
					margin-left:20px;
				}
				.input td:nth-child(1){
					text-align:left;
					font-weight:bold;
					width:32%
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
				.im_upload:hover {
				  background-color: #efefef;
				}
				.im_upload {
				  border-radius: 4px;
				  color: white;
				  border: 1px solid #cdcdcd;
				  background-color: #fff;
				  display: inline-block;
				  font-weight: bold;
				  text-decoration: none;
				}
				.image-placeholder-show {
				  display: inline-block;
				}
				.image-placeholder {
				  color: #555;
				  position: relative!important;
				  font-size: 12px!important;
				  width: 109px!important;
				  height: 109px!important;
				  margin-top: 10px!important;
				}
				.image-placeholder input[type="file"] {
				  position: absolute;
				  top: 0;
				  right: 0;
				  float: left;
				  margin: 0;
				  opacity: 0;
				  width: 100%;
				  height: 107px;
				  filter: alpha(opacity=0);
				  border: 0;
				  cursor: pointer;
				  cursor: hand;
				  font-size: 13px;
				  z-index: 100;
				}
				.thumb-container {
					position: relative;
					text-align: center;
					cursor: pointer;
					text-align: center;
					vertical-align: middle;
					background-color: transparent;
					background-repeat: no-repeat;
					color: #6d6d6d;
					height: 100%;
					width: 100%;
					margin: 0 auto;
				  }
				 .sprite_ai_camera {
				  width: 57px;
				  height: 47px;
				  background: url("media/images/ai.png?16afd28648844eb1e3bb92444d2e4444c1a475f2") no-repeat -198px -2px;
				}
				.thumb-camera {
				  width: 56px;
				  height: 46px;
				  border-radius: 5px;
				  position: relative;
				  margin: 20px auto 16px auto;
				}
				.thumb-image {
				  width:100%;				
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
       		<div style="width:45%;float:left">		
              <div class="head-title1">Account Information</div>
              <table width="100%" border="0" class="input">
              <tr>
                <td width="50%"><span class='required'>*</span> Username :</td>
                <td><input type="text" id="accUsername" class="form-control" /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Account Name :</td>
                <td><input type="text" id="accName" class="form-control" /></td>
              </tr>
              <tr>
                <td><span class='required'>*</span> Password :</td>
                <td><input type="password" id="password1" class="form-control" /></td>
              </tr>
              <tr>
                 <td><span class='required'>*</span> Confirm Password :</td>
                <td><input type="password" id="password2" class="form-control" /></td>
              </tr>
              <tr>
                 <td>&nbsp;</td>
                <td><button id="submit" class="btn" style="margin-left:20px;font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);font-size: 14px;padding: 12px 20px;">Submit</button></td>
              </tr>
              </table>
			</div>
            <div style="width:48%;float:right">
              <div class="head-title1" style="margin-bottom:10px">Account Previligers</div>
              <fieldset style="  margin-top: 11px !important;">
                  <legend>Global privileges <input type="checkbox" id="checkall" class="checkall_box" title="Check All"> <label for="checkall">Check All</label> </legend>
                  <fieldset style="padding:11px">
                      <legend>Product</legend>
                      <div><input type="checkbox" id="product-add" data-id="check" /><label for="product-add">ADD</label></div>
                      <div><input type="checkbox" id="product-edit" data-id="check" /><label for="product-edit">EDIT</label></div>
                      <div><input type="checkbox" id="product-delete" data-id="check" /><label for="product-delete">DELETE</label></div>
                  </fieldset>
                  <fieldset style="padding:11px">
                      <legend>Purchase</legend>
                      <div><input type="checkbox" id="purchase-add" data-id="check" /><label for="purchase-add">ADD</label></div>
                      <div><input type="checkbox" id="purchase-edit" data-id="check" /><label for="purchase-edit">EDIT</label></div>
                      <div><input type="checkbox" id="purchase-delete" data-id="check" /><label for="purchase-delete">DELETE</label></div>
                  </fieldset>
                  <fieldset style="padding:11px">
                      <legend>Customer</legend>
                      <div><input type="checkbox" id="customer-add" data-id="check" /><label for="customer-add">ADD</label></div>
                      <div><input type="checkbox" id="customer-edit" data-id="check" /><label for="customer-edit">EDIT</label></div>
                      <div><input type="checkbox" id="customer-delete" data-id="check" /><label for="customer-delete">DELETE</label></div>
                  </fieldset>
                  <fieldset style="padding:11px">
                      <legend>Category</legend>
                      <div><input type="checkbox" id="category-add" data-id="check" /><label for="category-add">ADD</label></div>
                      <div><input type="checkbox" id="category-edit" data-id="check" /><label for="category-edit">EDIT</label></div>
                      <div><input type="checkbox" id="category-delete" data-id="check" /><label for="category-delete">DELETE</label></div>
                  </fieldset>
                  <fieldset style="padding:11px">
                      <legend>Administration</legend>
                      <div style="float:left;margin-right:10px;width:109px"><input type="checkbox" id="account-add" data-id="check" /><label for="account-add">ADD ACCOUNT</label></div>
                      <div style="float:left;margin-right:10px"><input type="checkbox" id="account-edit" data-id="check" /><label for="account-edit">EDIT ACCOUNT</label></div>
                      <div style="float:left;margin-right:10px"><input type="checkbox" id="account-delete" data-id="check" /><label for="account-delete">DELETE ACCOUNT</label></div>
                      <div style="float:left;margin-right:10px"><input type="checkbox" id="accept-return" data-id="check" /><label for="accept-return">ACCEPT RETURN</label></div>
                      <div style="float:left;margin-right:10px"><input type="checkbox" id="accept-payment" data-id="check" /><label for="accept-payment">ACCEPT PAYMENT</label></div>
                  </fieldset>
              </fieldset>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
	$('#checkall').click(function() {
		if($(this).prop('checked') == true)
			$('input[data-id=check]').each(function(index, element) {
				$(this).prop('checked',true);
			});
		else
			$('input[data-id=check]').each(function(index, element) {
				$(this).prop('checked',false);
			});
	});
   	$('#name').keypress(function(e){     
		var str = String.fromCharCode(e.keyCode);
		var regx = /^[A-Za-z0-9]+$/;
		if (!regx.test(str) && str!=' ') 
		  return false;
		else
			return true;
	});
	$("#name").keyup(function() {
	  str = $(this).val();
	  force = false;
	  str=force ? str.toLowerCase() : str;  
	  $(this).val(function(index, value) {
		  return str.replace(/(\b)([a-zA-Z])/g,function(firstLetter){return   firstLetter.toUpperCase();});
		});
	});
	function clear(){
		$('#header2').css('margin-bottom','25px');
		$('#id1').hide();
		$('#box').hide();
		$('#name1').hide();
	}
	$('#submit,#submit-edit').click(function() {
		clear();
		var check = true;
		$('input[type=text],input[type=password]').each(function(index, element) {
            if($(this).val()==''){
				$(this).parent().removeClass('has-success');
				$(this).parent().addClass('has-error');
				check = false;
			}
			else{
				$(this).parent().removeClass('has-error');
				$(this).parent().addClass('has-success');
			}
				
        });
		if(!check){
			clear();
			$('#header2').css('margin-bottom','0');
			$('#box').show();
		}
		else{
			if($('#password1').val() != $('#password2').val()){
				clear();
				$('#header2').css('margin-bottom','0');
				$('#pass').show();
				check = false;
			}
			else
				check = true;
		}
		
		if(check){
			$.ajax({
				url: 'check/check.php',
				dataType: 'json',
				data: {accUsername:$('#accUsername').val()},
				success: function(s){
					console.log(s);
					if(s[0]==true){
						clear();
						$('#header2').css('margin-bottom','0');
						$('#id1').show();
					}
					else {
						$.ajax({
							url: 'check/check.php',
							dataType: 'json',
							data: {accName:$('#accName').val()},
							success: function(s){
								console.log(s);
								if(s[0]==true){
									clear();
									$('#header2').css('margin-bottom','0');
									$('#name1').show();
								}
								else{
									$.ajax({
										url: 'save/save.php',
										data: {accUsername:$('#accUsername').val(),accName:$('#accName').val(),accPassword:$('#password1').val(),addProduct:$('#product-add').prop('checked'),editProduct:$('#product-edit').prop('checked'),deleteProduct:$('#product-delete').prop('checked'),addPurchase:$('#purchase-add').prop('checked'),editPurchase:$('#purchase-edit').prop('checked'),deletePurchase:$('#purchase-delete').prop('checked'),addCustomer:$('#customer-add').prop('checked'),editCustomer:$('#customer-edit').prop('checked'),deleteCustomer:$('#customer-delete').prop('checked'),addCategory:$('#category-add').prop('checked'),editCategory:$('#category-edit').prop('checked'),deleteCategory:$('#category-delete').prop('checked'),addAccount:$('#account-add').prop('checked'),editAccount:$('#account-edit').prop('checked'),deleteAccount:$('#account-delete').prop('checked'),acceptReturn:$('#accept-return').prop('checked'),acceptPayment:$('#accept-payment').prop('checked'),accID:$('#accID').html(),account:true},
										dataType: 'json',
										success: function(s){
											console.log(s);
											
										},
										error: function(f){
											window.location.assign('view-accounts.php?success=add');
										}
									});
								}
							}
						});
					}
				}
			});
		}
	});
	$('#edit-popup .close').click(function() {
		$('.modal-body').css('padding-top','2em');
	});
	$('.close').click(function() {
		$('#'+modalBox+' .modal-body').css('padding-top','2em');	
	});
});
</script>
<script src="media/js/product-popup.js"></script>
</body>
</html>
<?php
}else
header("Location: index.php");
?>
