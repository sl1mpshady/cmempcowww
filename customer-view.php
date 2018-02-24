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
$query = "SELECT custID,CONCAT(custFName,' ',custMName,' ',custLName),custGender,custBDate,custContactNo,custLimit,custCredit,custCivilStatus,custFName,custMName,custLName,custAddress, DATEDIFF(NOW(),custBDate)/365 as custAge,custElem,custHS,custCol,custVoc,custOthers,custBirthPlace,custDesignation,custSection,custDept,custAccType,custExpire,custLimit,custPicture,custDelete FROM customer WHERE custID='".$_GET['i']."'";
mysql_select_db($database_connSIMS, $connSIMS);
$query = mysql_query($query, $connSIMS) or die(mysql_error());
$customer = mysql_fetch_array($query);
if(mysql_num_rows($query)<=0)
	header("Location: customer.php");

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
<script src="media/js/customer.dataTable.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/dataTables.tableTools.js"></script>
<span id="editCustomer" style="display:none;visibility:0"><?php echo $row_rsAccount['editCustomer'];?></span>
</head>
<script>
	$(document).ready(function () {
		if($('#accType').val()!='AV')
			$('#edit-account,#edit-personal,#edit-work,#edit-membership,#edit-picture').remove();
		$('#home').addClass('active');
		$('#home_menu').show();
		$('#customer_menu').addClass('selected');
		$('#back1').click(function() {
			window.history.back();
			});
		document.getElementById("from").valueAsDate = new Date();
		document.getElementById("to").valueAsDate = new Date();
			
		function sales(){
		  $('#total,#new,#summ-today').html('0');
		  var x = window.location.href;
		  x = x.split("?");
		  x = x[1].split("&");
		  x = x[0].split("=");
		  $('#payment3').hide();
		  $('#return3').hide();	
		  $('#sales2').show();
		  var totsales = 0;
		   $('#sales-table').dataTable({
			  "destroy": true,
			  "scrollY":        "203px",
			  "scrollCollapse": false,
			  "paging":         false,
			  "order": [[ 0, "desc" ]],
			  "bProcessing": true,
			  "bServerSide": true,
			  "sAjaxSource": "customer/sales-order_server_processing.php?i="+x[1]+'&from='+$('#from').val()+'&to='+$('#to').val(),
			  "createdRow": function ( row, data, index ) {
				  $('td', row).eq(0).html('<a href="view-sales_order.php?i='+data[0]+'">'+data[0]+'</a>');
				  if(data[4]=='Credit'){
					  if(new Date() > new Date(data[2]))
						  $('td', row).eq(2).css('color','red');
					  else if(new Date() == new Date(data[2]))
						  $('td', row).eq(2).css('color','darkorange');
				  }
				  if(new Date().getDay() == new Date(data[1]).getDay() && new Date().getFullYear() == new Date(data[1]).getFullYear() && new Date().getMonth() == new Date(data[1]).getMonth()){
					  $('#new').html(new String(new Number($('#new').html().replace(/,/g, ""))+1).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					  $('#summ-today').html(new String(new Number($('#summ-today').html().replace(/,/g, ""))+1).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				  }
				  //totsales = index+1;
				  $('#total').html(new Number(index)+1);
				  $('#summ-total').html(new Number(index)+1);
			  }
		  });
		  
		  //$('#total').html(totsales);
		  $('.input-group input').attr('placeholder','Search');
		}
		function return_(){
		  $('#total,#new').html('0');
		  var x = window.location.href;
		  x = x.split("?");
		  x = x[1].split("&");
		  x = x[0].split("=");
		  $('#payment3').hide();
		  $('#sales2').hide();	
		  $('#return3').show();
		  var totsales = 0;
		   $('#return-table').dataTable({
			  "destroy": true,
			  "scrollY":        "203px",
			  "scrollCollapse": false,
			  "paging":         false,
			  "order": [[ 0, "desc" ]],
			  "bProcessing": true,
			  "bServerSide": true,
			  "sAjaxSource": "customer/return_server_processing.php?i="+x[1]+'&from='+$('#from').val()+'&to='+$('#to').val(),
			  "createdRow": function ( row, data, index ) {
				  $('td', row).eq(0).html('<a href="view-return.php?i='+data[0]+'">'+data[0]+'</a>');
				  if(new Date().getDay() == new Date(data[2]).getDay() && new Date().getFullYear() == new Date(data[2]).getFullYear() && new Date().getMonth() == new Date(data[1]).getMonth()){
					  $('#new').html(new String(new Number($('#new').html().replace(/,/g, ""))+1).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				  }
				  //totsales = index+1;
				  $('#total').html(new Number(index)+1);
			  }
		  });
			 
		  
		  //$('#total').html(totsales);
		  $('.input-group input').attr('placeholder','Search');
		}
		function payment(){;
		  $('#total,#new').html('0');
		  $('#sales2').hide();
		  $('#return3').hide();
		  $('#payment3').show();
		  var x = window.location.href;
		  x = x.split("?");
		  x = x[1].split("&");
		  x = x[0].split("=");
		  $('#payment-table').dataTable({
			  	"destroy": true,
				"scrollY":        "203px",
				"scrollCollapse": false,
				"paging":         false,
				"order": [[ 0, "desc" ]],
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "customer/payment_server_processing.php?i="+x[1]+'&from='+$('#from').val()+'&to='+$('#to').val(),
				"createdRow": function ( row, data, index ) {
					$('td', row).eq(0).html('<a href="view-payment.php?i='+data[0]+'">'+data[0]+'</a>');
					if(new Date().getDay() == new Date(data[1]).getDay() && new Date().getFullYear() == new Date(data[1]).getFullYear() && new Date().getMonth() == new Date(data[1]).getMonth()){
						  $('#new').html(new String(new Number($('#new').html().replace(/,/g, ""))+1).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					  }
				  //totsales = index+1;
				  $('#total').html(new Number(index)+1);
				}
			});
		  $('.input-group input').attr('placeholder','Search');
		}
		sales();
		$('#actType').change(function() {
			if($(this).val() == 'sales'){
				sales();
			}
			else if($(this).val() == 'return'){
				return_();
			}
			else
				payment();
			});
		
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("table")){
				 $('table tr.active_').removeClass('active_');
			}
		});
		$('#back,#add').click(function() {
			if($('#actType').val() == 'sales'){
				window.location.assign('sales.php?i='+$('#custID').html());
			}
			else
				window.location.assign('payment.php?i='+$('#custID').html());
		});
		$('#go').click(function() {
			if($('#actType').val() == 'sales')
				sales();
			else if($('#actType').val() == 'return'){
				return_();
			}
			else
				payment();
		});
			
	});	
</script>
<style>
	.dataTables_scrollHead {
		background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);
	}
	/*.dataTables_info {
		display:none;
	}*/
	table a {
 		color: #337ab7 !important;
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
	#box .box-main {
	  background-color: white;
	  box-shadow: 1px 0px 5px 2px lightgray;
	  border: 1px solid lightgray;
	  border-radius: 2px;
	  height: 200px;
	}
	.img-circle {
	  border-radius: 52%;
	  width: 144px;
	  height: 144px;
	}
	#tools div{
  	  padding: 8px 15px;
	  color: #337A9C !important;
	  cursor:pointer;
	    border-bottom: 1px solid rgb(239, 239, 239);
	}
	#tools .active1 {
		background-color: aliceblue;
		border-left: 3px solid #FFAE00;
	}
	td, th {
	  padding: 0px;
	  padding-right: 22px;
	}
	.summary td:nth-child(2) {
		text-align:right;
	}
	.summary td {
		padding-right:0;
		padding-bottom:3px;
	}
	.activity td {
		padding:16px;
		  color: rgb(150, 150, 150);
	}
	.activity td div{
		font-size:25px;
	}
	#sales-table td:nth-child(2), #payment-table td:nth-child(2) {
		text-align:center;
	}
	
	#sales-table td:nth-child(4),#sales-table td:nth-child(3),#payment-table td:nth-child(3) {
		text-align:right;
	}
	#return-table td:nth-child(2){
		text-align:right
	}
	#return-table td:nth-child(3){
		text-align:center
	}
	.head-title1 {
		  background-color: rgb(150, 150, 150);
		  margin-left: -5px;
		  font-family: calibri;
		  width: 35%;
		  padding-left: 15px;
		  font-weight: bold;
		  color: white;
		  box-shadow: 2px 2px 5px 1px gray;
		  text-align: center;
		  margin-bottom:15px;
	  }
	 .personal-box td, .personal-box th,.work-box td, .work-box th,.membership-box td, .membership-box th {
	  padding: 2px;
	  padding-right: 5px;
	  padding-bottom:10px;
	}
	.personal-box tr,.work-box tr, .membership-box tr {
		padding-bottom:5px;
	}
	.personal-box td:nth-child(1),.work-box td:nth-child(1),.membership-box td:nth-child(1){
		text-align:right;
		font-weight:bold;
		width:21%
	}
	.personal-box .form-control,.work-box .form-control,.membership-box .form-control {
		width:100%;
		margin-left:20px;
		height:32px;
	}
	#jj div {
		text-align:left;
		font-weight:normal;
	}
	.personal-box .btn, .work-box .btn, .membership-box .btn, .picture-box .btn  {
	  font-size: 14px;
	  padding: 0.75em 1.5em;
	  background-color: #fff;
	  border: 1px solid #bbb;
	  color: #333;
	  text-decoration: none;
	  display: inline;
	  border-radius: 4px;
	  -webkit-transition: background-color 1s ease;
	  -moz-transition: background-color 1s ease;
	  transition: background-color 1s ease;
	}
	input[type=date] {
		height: 24px;
		width: inherit;
		margin-top: 7px;
	}
	.form-control1 {
		padding: 3px 7px;
		font-size: 14px;
		line-height: 1.42857143;
		color: #555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #ccc;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
		-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		font-weight: 100;
	}
	.dataTables_filter {
		display:none;
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
	  width: 120px!important;
	  height: 120px!important;
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
	  height: 120px;
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
</style>
<body>
<div style="display:none" id="parent">home</div>
<div style="padding:25px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 497%);" class="hidden-print" >
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Customer's Profile<span style="float:right;display:none;"><button class="btn" id="back1" style="border-left: 3px solid #FFAE00;"><span class="glyphicon glyphicon-arrow-left" style="color:navy;margin-right:3px"></span>Back</button></span></div>
    <div id="boxx" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
		    if($_GET['success']=='delete'){
				echo 'Customer has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Customer has been updated successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="">
    	
    	<table width="100%" border="0" id="box">
          <tr>
            <td rowspan="2" width="20%">
            	<div class="box-main" style="height:445px">
                	<div style="text-align:center;padding:16px">
                    	<?php if($customer['custPicture'] == NULL){?>
							<img src="media/images/default.png" class="img-circle">
						<?php } else echo '<img src="save/fetch.php?i='.$_GET['i'].'" class="img-circle">';?>
                    	<div id="custFullName" style="color: #337A9C !important;font-weight: bold;font-size: 15px;margin-top: 10px;text-align:center"><?php echo $customer[1];?></div>
                        <div id="custID" style="color: #337A9C !important;text-align:center"><?php echo $customer['custID'];?></div>
                    </div>
                    <div id="tools">
                        <div class="active1" id="overview">Overview</div>
                        <div id="personal">Personal Information</div>
                        <div id="work">Work Information</div>
                        <div id="membership">Membership Information</div>
						<div id="picture">Update Picture</div>
                	</div>
                </div>
            </td>
            <td rowspan="2" width="60%" class="overview-box">
            	<div class="box-main" style="height:445px;padding:5px">
                	<div style="color: #337A9C !important;font-size:19px;border-bottom: 1px solid rgb(239, 239, 239);padding-bottom: 5px;"><span class="glyphicon glyphicon-tasks" aria-hidden="true" style="margin-right: 3px;"></span> ACTIVITY<span style="float:right;display:none"><button class="btn" id="back" style="border-left: 3px solid #FFAE00;"><span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span>New</button></span></div>
                    <select id="actType" class="form-control" style="position: absolute;    padding-top: 4px; width: 9%; margin-top: 7px;font-size: 12px;"><option value="sales">Sales Order</option><option value="payment">Deduction</option><option value="return">Return</option></select>
                   
					<table class="activity" style="margin-top:10px">
                    	<tr>
                        	<td style="text-align: right;border-right: 1px solid rgb(239, 239, 239);">Total
                            	<div id="total">0</div>
                            </td>
                            <td>New
                            	<div id="new">0</div>
                            </td>
                        </tr>
                        
                    </table>
					<div style="text-align:right;margin-bottom: 5px;">
						<input class="form-control1" style="width:143px" type="date" id="from"> 
						<span class="glyphicon glyphicon-chevron-right"></span> 
						<input class="form-control1" style="width:143px" type="date" id="to">
						<button style="color:navy;font-weight:bolder;/*font-size: 13px;height: 28px;*/margin-top: -3px;" class="btn" id="go">{ }</button>
						<button class="btn" id="print" style="margin-top:-4px"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button>
					</div>
                    <span id="sales2">
                    <table id="sales-table" width="100%">
                    	<thead>
                        	<th width="11%">SO ID</th>
                            <th style="text-align:center">Date & Time</th>
                            <th style="text-align:center">Net Amount</th>
							<th style="text-align:center">Balance</th>
                            <th style="text-align:center">Type</th>
                        </thead>
                    </table>
                    </span><span id="payment3">
                    <table id="payment-table" width="100%">
                    	<thead>
                        	<th width="5%">ID</th>
                            <th width="25%" style="text-align:center">Date & Time</th>
                            <th width="20%" style="text-align:center">Amount</th>
                            <th style="text-align:center">Added By</th>
                        </thead>
                    </table>
                    </span>
                    </span><span id="return3">
                    <table id="return-table" width="100%">
                    	<thead>
                        	<th width="15%">Return ID</th>
                            <th  width="20%" style="text-align:center">Return Cost</th>
                            <th  width="25%" style="text-align:center">Date & Time</th>
                            <th style="text-align:center">Added By</th>
                        </thead>
                    </table>
                    </span>
                </div>
            </td>
			<td rowspan="2" colspan="2" class="picture-box" style="padding-right:0px;display:none">
            	<div class="box-main" style="height:445px;padding:5px">
                	<div style="color: #337A9C !important;font-size:19px;border-bottom: 1px solid rgb(239, 239, 239);padding-bottom: 5px;margin-bottom: 25px;" id="work-box-header"><span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-right: 3px;"></span> Update Picture<span style="float:right"><button class="btn" id="edit-picture" style="border-left: 3px solid #FFAE00;padding: 2px 5px;font-size: 12px;"><span class="glyphicon glyphicon-pencil" style="color:navy;margin-right:3px"></span>Edit</button></span></div>  
                    <div style="width:75%">
                    <div class="head-title1">Update Picture</div>
                    <center>
						<div class="im_upload nohistory image-placeholder image-placeholder-show" style="margin-left:25px">
							<form id="fileForm" enctype="multipart/form-data">
								<input type="file" size="1" style="display: block; cursor: pointer;" id="photo" />
							</form>
							<div id="preview" src="#"></div>
							<div class="thumb-container thumb-container-empty" id="empty">
								<div id="thumb-camera" class="thumb-camera sprite_ai_camera" style="display: block;"></div>
								
							</div>
						</div>
						<br>
						<div id="buttons" style="display:none">
							<button id="submit3" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Save</button></span><span>
                            <button id="cancel3" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></span>
						</div>
					</center>                  
                    
                    </div>
                </div>
                
            </td>
            <td rowspan="2" colspan="2" class="membership-box" style="padding-right:0px;display:none">
            	<div class="box-main" style="height:445px;padding:5px">
                	<div style="color: #337A9C !important;font-size:19px;border-bottom: 1px solid rgb(239, 239, 239);padding-bottom: 5px;margin-bottom: 25px;" id="membership-box-header"><span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-right: 3px;"></span> Membership Information<span style="float:right"><button class="btn" id="edit-membership" style="border-left: 3px solid #FFAE00;padding: 2px 5px;font-size: 12px;"><span class="glyphicon glyphicon-pencil" style="color:navy;margin-right:3px"></span>Edit</button></span></div>
                    <div style="width:75%">
                	<div class="head-title1">Membership Information</div>
                    <table width="100%" border="0" style="margin-left: 11%;">
                        <tr>
                            <td><span class='required'>*</span> Account Type:</td>
                            <td width="30%">
                                <select id="accType11" class="form-control" style="padding: 6px 3px;"">
                                    <option value="Regular" <?php if($customer['custAccType']=='Regular') echo "selected";?>>Regular</option>
                                    <option value="Casual Skilled" <?php if($customer['custAccType']=='Casual Skilled') echo "selected";?>>Casual Skilled</option>
                                    <option value="Casual Non Skilled" <?php if($customer['custAccType']=='Casual Non Skilled') echo "selected";?>>Casual Non Skilled</option>
                                </select>
                            </td>
                            <td width="20%" style="font-weight: bold;padding-left:26px"> Credit Limit:</td>
                            <td width="15%"><input type="text" class="form-control" id="limit" disabled="disabled" style="cursor:auto" placeholder="<?php echo number_format($customer['custLimit'],2);?>"></td>
                
                        </tr>
						
                        <tr>
                             <td><span class='required'>*</span> Account ID:</td>
                             <td><input type="text" class="form-control" id="accID1" placeholder="<?php echo $customer['custID'];?>"></td>
                             <td width="20%" style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Expirey:</td>   
                             <td width="15%"><input type="date" class="form-control" id="expDate" style="cursor:auto" value="<?php echo $customer['custExpire']; ?>"></td>   
                        </tr>
						<tr>
							<td>Account Status:</td>
							<td width="30%">
                                <select id="accDelete11" class="form-control" style="padding: 6px 3px;"">
                                    <option value="True" <?php if($customer['custDelete']=='True') echo "selected";?>>Deactivate</option>
                                    <option value="False" <?php if($customer['custDelete']!='True') echo "selected";?>>Active</option>
                                </select>
                            </td>
						</tr>
                        <tr id="buttons2" style="display:none;">
                            <td>&nbsp;</td>
                            <td>
                            	<button id="submit2" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Save</button></span><span>
                            <button id="cancel2" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></span>
                            </td>
                            <td width="15%">&nbsp;</td> 
                            <td width="15%">&nbsp;</td>
                        </tr>
                    </table>           
                </div>	
                
            </td>
            <td rowspan="2" colspan="2" class="personal-box" style="padding-right:0px;display:none">
            	<div class="box-main" style="height:445px;padding:5px">
                	<div style="color: #337A9C !important;font-size:19px;border-bottom: 1px solid rgb(239, 239, 239);padding-bottom: 5px;margin-bottom: 25px;" id="personal-box-header"><span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-right: 3px;"></span> Personal Information<span style="float:right"><button class="btn" id="edit-personal" style="border-left: 3px solid #FFAE00;padding: 2px 5px;font-size: 12px;"><span class="glyphicon glyphicon-pencil" style="color:navy;margin-right:3px"></span>Edit</button></span></div>
                    <div id="box1" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
                        <span id="blank1" style="display:none">Please fill all the boxes in red.</span>
                        <span id="id1" style="display:none">Customer under the name has already exist. Please refer to the customer list.</span>
                        <span id="educ1" style="display:none">Please select or enter your educational attainment.</span>
                        <span id="date" style="display:none">Date is invalid.</span>
                        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div style="width:75%">
                    <div class="head-title1">Personal Information</div>
                    <table width="50%" style="width:100% !important;margin-left: 13%;">
                        <tr>
                            <td><span class='required'>*</span> Name :</td>
                            <td><input type="text" class="form-control alpha" id="fname" style="text-transform:capitalize" placeholder="<?php echo $customer['custFName'];?>"/></td>
                            <td><input type="text" class="form-control alpha" id="mname" style="text-transform:capitalize" placeholder="<?php echo $customer['custMName'];?>"/></td>
                            <td><input type="text" class="form-control alpha" id="lname" style="text-transform:capitalize" placeholder="<?php echo $customer['custLName'];?>"/></td>
                      </tr>
                      <tr>
                            <td><span class='required'>*</span> Address :</td>
                            <td colspan="3"><input type="text" class="form-control" id="addr" style="text-transform:capitalize" placeholder="<?php echo $customer['custAddress'];?>"/></td>
                      </tr>
                    </table>
                     <table width="50%" style="width:100% !important;margin-left: 13%;">
                      <tr>
                            <td><span class='required'>*</span> Birthdate :</td>
                            <td colspan="1">
                            	<input class="form-control" placeholder="<?php echo $customer['custBDate'];?>" type="text" onfocus="(this.type='date')" id="bdate" style="cursor:auto;width: 154px;"/>
                                <!--<input type="date" class="form-control" id="bdate" style="cursor:auto;width: 154px;" >-->
                            </td>
                            <td width="25%" style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Gender : <input style="float:right" type="radio" value="Male" name="gender" id="gender1"  /></td>
                            <td><span>Male</span><input  style="margin:10px" type="radio" value="Female" name="gender" id="gender1" /><span>Female</span></td>
                      </tr>
                      <tr>
                      	<td><span class='required'>*</span> Place of Birth :</td>
                        <td colspan="3"><input type="text" class="form-control" id="baddr" style="text-transform:capitalize" placeholder="<?php echo $customer['custBirthPlace'];?>"/></td>
                      </tr>
                      <tr>
                        <td><span class='required'>*</span> Mobile Number :</td>
                        <td colspan="1">
                            <input type="text" class="form-control" id="mobile" style="text-transform:capitalize" placeholder="<?php echo $customer['custContactNo'];?>"/>
                        </td>
                        <td style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Civil Status :</td>
                        <td>
                            <select id="civstat" class="form-control" placeholder="<?php echo $customer['custStatus'];?>">
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Annuled / Divorced">Annuled / Divorced</option>
                                <option value="Widow / er">Widow / er</option>
                                <option value="Legally Separated">Legally Separated</option>
                            </select>
                        </td>
                      </tr>
                    </table>
                    <table style="margin-left:13%">
                    	<tr>
                      	<td style="font-weight: bold;padding-left:26px;"><span class='required'>*</span> Education :</td>
                        <td style="padding-left:21px">
                        	<table id="jj">
                            	<tr>
                                  <td style="width:39%">
                                    <div><input type="checkbox" data-id="educ" id="elem"  style="margin-right:15px"/>  Elementary</div>
                                    <div><input type="checkbox" data-id="educ" id="hs"  style="margin-right:15px"/>  High School</div>
                                    <div><input type="checkbox" data-id="educ" id="coll"  style="margin-right:15px"/>  College</div>
                                    <div><input type="checkbox" data-id="educ" id="voc"  style="margin-right:15px"/>  Vocational</div>
                                  </td>
                                  <td>
       								<input type="text" data-id="educ" id="others" style="width:100%" class="form-control" placeholder="<?php echo ($customer['custOthers']!=' ') ? $customer['custOthers'] : 'Others';?>"/>
                                  </td>
                                </tr>
                            </table>
                        </td>
                        <td width="5%">&nbsp;</td>
                        <td id="buttons" style="display:none"><span>
                        
                            <button id="submit" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Save</button></span><span>
                            <button id="cancel" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></span>
                            </div>
                        </td>
                      </tr>
                    </table>
       				</div>
                
            </td>
            <td rowspan="2" colspan="2" class="work-box" style="padding-right:0px;display:none">
            	<div class="box-main" style="height:445px;padding:5px">
                	<div style="color: #337A9C !important;font-size:19px;border-bottom: 1px solid rgb(239, 239, 239);padding-bottom: 5px;margin-bottom: 25px;" id="work-box-header"><span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-right: 3px;"></span> Work Information<span style="float:right"><button class="btn" id="edit-work" style="border-left: 3px solid #FFAE00;padding: 2px 5px;font-size: 12px;"><span class="glyphicon glyphicon-pencil" style="color:navy;margin-right:3px"></span>Edit</button></span></div>
                    <div id="box2" class="bg-danger" style="display:none;padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
                        <span id="blank2" style="display:none">Please fill all the boxes in red.</span>
                        <span id="id2" style="display:none">Customer under the name has already exist. Please refer to the customer list.</span>
                        <span id="educ2" style="display:none">Please select or enter your educational attainment.</span>
                        <span id="date2" style="display:none">Date is invalid.</span>
                        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
                    </div>
                    
                    <div style="width:75%">
                    <div class="head-title1">Work Information</div>
                      <table width="100%" border="0" style="margin-left: 11%;">
                        <tr>
                          <td><span class='required'>*</span> Designation:</td>
                          <td colspan="3"><input type="text" class="form-control" id="desig" style="text-transform:capitalize" placeholder="<?php echo $customer['custDesignation'];?>"/></td>
                        </tr>
                        <tr>
                          <td width="25%"><span class='required'>*</span> Section:</td>
                          <td width="25%"><input type="text" class="form-control" id="sect" style="text-transform:capitalize" placeholder="<?php echo $customer['custSection'];?>"/></td>
                          <td width="20%" style="font-weight: bold;padding-left:26px"><span class='required'>*</span> Department:</td>
                          <td width="25%"><input type="text" class="form-control" id="dept" style="text-transform:capitalize" placeholder="<?php echo $customer['custDept'];?>"/></td>
                        </tr>
                        <tr id="buttons1" style="display:none;">
                            <td>&nbsp;</td>
                            <td>
                            	<button id="submit1" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Save</button></span><span>
                            <button id="cancel1" class="btn" style="font-weight:bold;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);">Cancel</button></span>
                            </td>
                            <td width="15%">&nbsp;</td> 
                            <td width="15%">&nbsp;</td>
                        </tr>
                      </table>                      
                      
                     
                    </div>
                </div>
                
            </td>
            <td style="padding-right:0" class="overview-box">
            	<div class="box-main" style="width: 100%;margin-top: 0;height:127px;padding:5px">
                	<div style="color: #337A9C !important;"><span style="float:left;font-size:19px">Sales Activity</span><span style="float:right;margin-top:3%"><span class="glyphicon glyphicon-refresh" aria-hidden="true" style="margin-right: 3px;cursor:pointer" title="Refresh"></span></span></div>
                    <table class="summary">
                    	<tr><td width="20%">TODAY</td><td id="summ-today">0</td>
                        <tr><td>WEEKLY</td><td id="summ-week">0</td>
                        <tr><td>TOTAL</td><td id="summ-total">0</td>
                        <tr><td>CREDIT</td><td id="summ-credit"><?php echo number_format($customer['custCredit'],2);?></td>
                    </table>
                </div>
            </td>
          </tr>
          <tr>
            
            <td rowspan="1" style="padding-right:0" class="overview-box">
            	<div class="box-main" style="width: 100%;margin-top: 20px;height:273px;padding:5px">
            		<div style="color: #337A9C !important;"><span style="float:left;font-size:19px">Profile</span><span style="float:right;margin-top:3%"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style="margin-right: 3px;cursor:pointer" title="Edit"></span></span></div>  
                    <input name="educ," type="hidden" id="elem," value="<?php echo $customer['custElem'];?>" />
                    <input name="educ," type="hidden" id="hs,"  value="<?php echo $customer['custHS'];?>"/>
                    <input name="educ," type="hidden" id="coll,"  value="<?php echo $customer['custCol'];?>"/>
                    <input name="educ," type="hidden" id="voc,"  value="<?php echo $customer['custVoc'];?>"/>
                    <input name="type" type="hidden" id="type"  value="<?php 
					if($customer['custAccType']=='Regular')
						echo 'reg';
					else if ($customer['custAccType']=='Casual Skilled')
						echo 'casSki';
					else
						echo 'casNon';?>"/>
                    <table width="100%" border="0" class="summary">
                      <tr>
                        <td width="20%">ID</td>
                        <td id="custID1"><?php echo $customer['custID'];?></td>
                      </tr>
                      <tr>
                        <td rowspan="2">NAME</td>
                        <td rowspan="2" style="width:100%" id="custName1"><?php echo $customer[1];?></td>
                      </tr>
                      <tr>
                      	<td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>GENDER</td>
                        <td id="custGender"><?php echo $customer['custGender'];?></td>
                      </tr>
                      <tr>
                        <td>STATUS</td>
                        <td id="custStatus"><?php echo $customer['custCivilStatus'];?></td>
                      </tr>
                      <tr>
                        <td>BDATE</td>
                        <td id="custBDate"><?php echo $customer['custBDate'];?></td>
                      </tr>
                      <tr>
                        <td>AGE</td>
                        <td id="custBDate"><?php echo number_format($customer['custAge'],0);?></td>
                      </tr>
                      <tr>
                        <td rowspan="2">CONTACT</td>
                        <td rowspan="2" id="custContact"><?php echo $customer['custContactNo'];?></td>
                      </tr>
                       <tr>
                      	<td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr style="display:none">
                        <td>DISCOUNT</td>
                        <td id="custDiscount"><?php echo $customer['custDiscount'];?>%</td>
                      </tr>
                      <tr>
                        <td>LIMIT</td>
                        <td id="custLimit"><?php echo number_format($customer['custLimit'],2);?></td>
                      </tr>
					  <tr>
                        <td>TYPE</td>
                        <td id=""><?php echo ($customer['custDelete'] == 'False') ? 'Active' : 'Deactivated';?></td>
                      </tr>
                    </table>
  	
                </div>
            </td>
          </tr>
         
        </table>

    </div>    
</div>
<div id="print_popup" class="modal-box" style="width:90%;top:10px;">
	<header>
	<h3>Print Preview<span title="Close" class="glyphicon glyphicon-remove-sign js-modal-close" aria-hidden="true" style="float:right;font-size: 17px;cursor: pointer;"></span></h3>
	</header>
	<div class="modal-body" style="text-align:center;padding:0px;display:initial;height:initial">
	<iframe id="report" src="" frameborder="0" style="width:100%;height:100%"></iframe>
	</div>
</div>
<script>
$(document).ready(function() {
	$('#print').click(function() {
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		var modalBox = 'print_popup';
		var $src = '';
		if($('#actType').val()=='sales')
			$src = 'report/sales_orders.php?type=custID'+'&value='+$('#custID').html()+'&from='+$('#from').val()+'&to='+$('#to').val();
		else if($('#actType').val()=='payment')
			$src = 'report/customer_deduction.php?custID='+$('#custID').html()+'&from='+$('#from').val()+'&to='+$('#to').val();
		else
			$src = 'report/customer_return.php?custID='+$('#custID').html()+'&from='+$('#from').val()+'&to='+$('#to').val();
		$('iframe').attr('src',$src);
		$('#'+modalBox).fadeIn(1000);
		$('#'+modalBox+' iframe').css('height',$(window).height()-90);
		var view_width = $(window).width();
		$('#'+modalBox).css('height',$(window).height()-90);
		var view_top = $(window).scrollTop() + 10;
		$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
		$('#'+modalBox).css("top", view_top);
	});
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
});
</script>
<script>
	$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$('button[data-modal-id]').click(function(e) {
		e.preventDefault();
		var modalBox = $(this).attr('data-modal-id');
		var check=true;
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
		}
	});  
	
  
  
$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
});
 
 
});
// JavaScript Document
</script>
<script>
$(document).ready(function() {
	$('#accLimit,#limit').autoNumeric('init',{'vMin':0});
	$('#accDisc').keypress(function(e) {
		if($(this).val().length > 4)
		  return false;
	 });
	 $('#accDisc').priceFormat({
		  prefix: '',
		  thousandsSeparator: '',
		  allowNegative: false
	  });
	$('.alpha').on("keydown", function(event){
  	var arr = [8,9,16,17,20,32,35,36,37,38,39,40,45,46];

	for(var i = 65; i <= 90; i++){
	  arr.push(i);
	}
  // Prevent default if not in array
	  if(jQuery.inArray(event.which, arr) === -1){
		event.preventDefault();
	  }
	});
	$('input[type="text"]').keyup(function(evt){
		if(this.id=='bdate')
			return true;
		var txt = $(this).val();
		$(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
	});
	function clear(){
		$('#blank,#id,#date').hide();
	}
	$('.close').click(function() {
		if($(this).parent('div').attr('id') == 'box1')
			$('#personal-box-header').css('margin-bottom','25px');
		($('#boxx').is(':visible')) ? $('#header2').css('margin-bottom','0px'):$('#header2').css('margin-bottom','25px');
		});
	$('#bdate').on('blur',function() {
		if($(this).val()==''){
			this.type='text';
			this.value = '';
		}
		else
			this.type='date';		
	});
	var check_date = function(input){
		var startDay = new Date(input);
		var endDay = new Date();
		var millisecondsPerDay = 1000 * 60 * 60 * 24;
		var millisBetween = startDay.getTime() - endDay.getTime();
		var days = millisBetween / millisecondsPerDay;
		return Math.ceil(days);
	}
	$('#tools,.overview-box .glyphicon-pencil,#cancel,#cancel1,#cancel2,#cancel3').click(function() {
		$('#tools div').removeClass('active1');
		if($(event.target).parents().andSelf().is("#overview")){
			$('.overview-box').show();
			$(event.target).parents().andSelf().addClass('active1');
		}
		else
			$('.overview-box').hide();
		if($(event.target).parents().andSelf().is("#personal") || $(event.target).parents().andSelf().is(".glyphicon-pencil") || $(event.target).parents().andSelf().is("#cancel")){	
		    $('#tools #personal').addClass('active1');
			$('.personal-box input').each(function(index, element) {
               if($(this).attr('id')!='gender1')
			   		$(this).val('');
            });
			$('input[name=gender]').each(function(index, element) {
				//alert($(this).attr('value') == $('#custGender').html());
                if($(this).attr('value') == $('#custGender').html()){
					$(this).prop('checked',true);
				}
            });
			$('input[type=hidden]').each(function(index, element) {
                ($(this).val() == 'True') ? $('#'+$(this).attr('id').replace(/,/g, "")).prop('checked',true) : $('#'+$(this).attr('id').replace(/,/g, "")).prop('checked',false);
				
            });
			$('.personal-box').show();
			$('.personal-box input,.personal-box select').attr('disabled','disabled').css({'background-color':'transparent','cursor':'auto'});
			$('#buttons').hide();
			$('#civstat').val($('#custStatus').html());
		}
		else
			$('.personal-box').hide();
		if($(event.target).parents().andSelf().is("#work") || $(event.target).parents().andSelf().is("#cancel1")){
			$('.work-box').show();
			$('#tools #work').addClass('active1');
			$('.work-box input').each(function(index, element) {
			   	$(this).val('');
            });
			$('.work-box input').attr('disabled','disabled').css({'background-color':'transparent','cursor':'auto'});
			$('#buttons1').hide();
		}
		else
			$('.work-box').hide();
		if($(event.target).parents().andSelf().is("#membership") || $(event.target).parents().andSelf().is("#cancel2")){
			$('.membership-box').show();
			$('#tools #membership').addClass('active1');
			$('.membership-box select').each(function(index, element) {
			   	$('#accType option[value='+$('#type').val()+']').prop('selected',true);
            });
			$('.membership-box input,.membership-box select').attr('disabled','disabled').css({'background-color':'transparent','cursor':'auto'});
			$('#buttons2').hide();
			$('#accType').val($('#type').val());
			$('.membership-box input[type=text]').val('');
		}
		else
			$('.membership-box').hide();
		if($(event.target).parents().andSelf().is("#picture") || $(event.target).parents().andSelf().is("#cancel3")){
			$('.picture-box').show();
			$('#tools #picture').addClass('active1');
			
			$('.picture-box input').attr('disabled','disabled').css({'background-color':'transparent','cursor':'auto'});
			$('#buttons3').hide();
		}
		else
			$('.picture-box').hide();	
	});
	$('#edit-personal').click(function() {
		$('.personal-box input,.personal-box select').removeAttr('disabled');	
		$('.personal-box #buttons').show();	
	});
	$('#edit-work').click(function() {
		$('.work-box input').removeAttr('disabled');	
		$('.work-box #buttons1').show();
	});
	$('#edit-membership').click(function() {
		$('.membership-box select,.membership-box input').removeAttr('disabled');	
		$('.membership-box #buttons2').show();
		
	});
	$('#edit-picture').click(function() {
		$('.picture-box input').removeAttr('disabled');	
		$('.picture-box #buttons').show();	
	});
	$('#cancel').click(function() {
		$('.personal-box input,.personal-box select').attr('disabled','disabled').css({'background-color':'transparent','cursor':'auto'});
		$('#buttons').hide();
	});
	$('#cancel1').click(function() {
		$('.work-box input,.work-box select').attr('disabled','disabled').css({'background-color':'transparent','cursor':'auto'});
		$('#buttons1').hide();
	});
	$('#cancel2').click(function() {
		$('.membership-box select').attr('disabled','disabled').css({'background-color':'transparent','cursor':'auto'});
		$('#buttons2').hide();
	});
	$('#accType').change(function() {
		if($(this).val() == 'reg'){
			$('#limit').val('10,000.00');
			$('#expDate').val('');
			$('#expDate').attr('disabled','disabled');
			
		}
		else if($(this).val() == 'casSki'){
			$('#limit').val('8,000.00');
			$('#expDate').removeAttr('disabled');
		}
		else{
			$('#limit').val('3,000.00');
			$('#expDate').removeAttr('disabled');
		}
	});
	$("#photo").on('change', function () {
		var objheight = 150;
		var objwidth = 150;
		var imgPath = $(this)[0].value;
		var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
		if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
			if (typeof (FileReader) != "undefined") {
				var image_holder = $("#preview");
				image_holder.empty();
				var reader = new FileReader();
				reader.onload = function (e) {
					$("<img />", {
						"src": e.target.result,
							"class": "thumb-image",
							"style": "width:100%;height:108px !important"
					}).appendTo(image_holder);
					$('#empty').hide();
				}
				image_holder.show();
				reader.readAsDataURL($(this)[0].files[0]);
			} else {
				alert("This browser does not support FileReader.");
			}
		} else {
			alert("Pls select only images");
		}
	});
	shortcut.add('Ctrl+p',function() {
		$('#print').click();
		});
	$('#submit2').click(function() {
		check = false;
		if(check_date($('#expDate').val())>0 && $('#expDate').val()!=''){
			check = true;
			$('#expDate').parent('td').removeClass('has-error');
			$('#expDate').parent('td').addClass('has-success');
		}
		else if($('#expDate').val()!=''){
			$('#membership-box-header').css('margin-bottom','0px');
			$('#expDate').parent('td').removeClass('has-success');
			$('#expDate').parent('td').addClass('has-error');
			$('#box2').show();
			$('#date2').show();
			check = false;
		}
		else if($('#expDate').val()==''){
			check = true;
		}
		if(check){
			$.ajax({
				url: 'save/save.php',
				data: {custAccType:$('#accType11').val(),limit:$('#limit').val().replace(/,/g, ""),accID1:$('#accID1').val(),UPDATE:true,expirey:$('#expDate').val(),custID:$('#custID').html(),delete:$('#accDelete11').val()}, 
				dataType: 'json',
				success: function(s){
					console.log(s);
					window.location.assign('customer-view.php?i='+$('#custID').html());
				},
				error:function(f){
					window.location.assign('customer-view.php?i='+$('#custID').html()+'&success=edit');
				}
			});
		}	
	});
	$('#submit1').click(function() {
		$.ajax({
			url: 'save/save.php',
			data: {designation:$('#desig').val(),section:$('#sect').val(),department:$('#dept').val(),UPDATE:true,custID:$('#custID').html()}, 
			dataType: 'json',
			success: function(s){
				console.log(s);
				window.location.assign('customer-view.php?i='+$('#custID').html());
			},
			error:function(f){
				window.location.assign('customer-view.php?i='+$('#custID').html()+'&success=edit');
			}
		});
	});
	$('#submit3').click(function() {
		$('#photo').each(function() {
			var file =  this.files[0];
			var name = file.name;
			var filePath = $(this)[0].value;
			var extn = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
			
			var file_data = $('#photo').prop('files')[0];   
			var form_data = new FormData();                  
			form_data.append('file', file_data);
			$.ajax({
				url: "save/pic.php?i="+$('#custID').html(), // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function (s) {
					window.location.assign('customer-view.php?i='+$('#custID').html()+'&edit=success');
				}
			});
		});
	});
	$('#submit').click(function() {
		check = false;
		if(check_date($('#bdate').val())<0 && $('#bdate').val()!=''){
			check = true;
			$('#bdate').parent('td').removeClass('has-error');
			$('#bdate').parent('td').addClass('has-success');
		}
		else if($('#bdate').val()!=''){
			$('#personal-box-header').css('margin-bottom','0px');
			$('#bdate').parent('td').removeClass('has-success');
			$('#bdate').parent('td').addClass('has-error');
			$('#box1').show();
			$('#date').show();
			check = false;
		}
		else if($('#bdate').val()==''){
			check = true;
		}
		if(check){
			$.ajax({
			   url: 'check/check.php',
			   type: 'get',
			   data: {'custFName':$('#fname').val(),custMName:$('#mname').val(),custLName:$('#lname').val(),custID:$('#custID').html()},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  if(s[0]==true){
					  $('#personal-box-header').css('margin-bottom','0');
					  clear();
					  $('#id1').show();
					  $('#box1').show();
					  $('#fname').parent('td').removeClass('has-success');
					  $('#fname').parent('td').addClass('has-error');
					  $('#mname').parent('td').removeClass('has-success');
					  $('#mname').parent('td').addClass('has-error');
					  $('#lname').parent('td').removeClass('has-success');
					  $('#lname').parent('td').addClass('has-error');
				  }
				  else {
					  /*$('input[name=gender]').each(function(index, element) {
							if($(this).prop('checked') == true){
								alert($(this).attr('id'));
							}
					  });*/
					 
						$.ajax({
							url: 'save/save.php',
							data: {fname:$('#fname').val(),mname:$('#mname').val(),lname:$('#lname').val(),addr:$('#addr').val(),bdate:$('#bdate').val(),gender:$('#gender1').val(),mobile:$('#mobile').val(),civil_status:$('#civstat').val(),birthPlace:$('#baddr').val(),elem:$('#elem').prop('checked'),hs:$('#hs').prop('checked'),coll:$('#coll').prop('checked'),voc:$('#voc').prop('checked'),others:$('#others').val(),UPDATE:true,custID:$('#custID').html()}, 
							dataType: 'json',
							success: function(s){
								console.log(s);
								//window.location.assign('customer-view.php?i='+$('#custID').html());
							},
							error:function(f){
								window.location.assign('customer-view.php?i='+$('#custID').html()+'&success=edit');
							}
						});
						
				  }
				  
			   },
			   error: function(e){
				  console.log(e);
			   }
		  });
		}
		
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
mysql_free_result($query);
}else
header("Location: index.php");
?>
