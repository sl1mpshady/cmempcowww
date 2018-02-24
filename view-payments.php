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
<script src="media/js/sales-view.dataTable"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="acceptPayment" style="display:none;visibility:0"><?php echo $row_rsAccount['acceptPayment'];?></span>
<script>
	$(document).ready(function () {
		$('.print_info').fadeIn(1000).fadeOut(2500);
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#view-payment').addClass('selected');
		$(document).ajaxStart(function(){
			$(this).css('cursor','wait');
		});
		$(document).ajaxSuccess(function(){
			//$(this).css('cursor','auto');
		});
		$('#list').dataTable({
			"scrollY":        "250px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/payment_server_processing.php",
			"createdRow": function ( row, data, index ) {
				$('td', row).eq(0).html('<a href="view-payment.php?i='+data[0]+'">'+data[0]+'</a>');
			}
		});
		$('#tools').append('<button class="btn" id="add"><span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span>Add New</button><button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button>');
		document.getElementById("from").valueAsDate = new Date();
		document.getElementById("to").valueAsDate = new Date();		
		if($('#acceptPayment').html()!='True')
			$('#add').remove();
		$('#add').click(function() {
			window.location.assign('payment.php');
			});
		$('#print').attr('data-modal-id',"print_popup");
		$(document).on("click","#list tr", function(){
			if ( $(this).hasClass('active_') ) {
				$(this).removeClass('active_');
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
			}
		});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn")){
				 $('#list tr.active_').removeClass('active_');
			}
		});
		shortcut.add('ctrl+p',function() {
			$('#print').click();
		});
		
	});	
</script>
<style>
	#list a {
	  color: #337ab7 !important;
	}
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
	.modal-body input {
	  text-align: center;
	  font-size: 31px !important;
	  height: auto;
	  border:0;
	}
	.modal-body input:focus {
	  border:none !important;
	  box-shadow:none !important;
	  background-color:none !important;
	  border-color:0 !important;
	}
	.modal-body .form-control {
	  overflow: hidden !important;
	  height: inherit !important;
	}
	.modal-body input[type=date]{
	  margin-left: -20px;
	  padding-left: 4px;
	  height: 75px;
	  margin-top: -15px;
	  width: 326px;
	}
	.modal-body div .form-control {
	  height:60px !important;
	}
	#print_popup .btn {
	  font-size: 14px;
	  padding: 0.75em 1.5em;
	  /* margin-left: 20px; */
	  font-weight: bold;
	}
	@media print {
		#payment2 td:nth-child(1){
		width: 130px;
  		text-align: right;
		}
		#payment2 td:nth-child(2){
			text-align:right;
		}
		
		.modal-overlay {
			display:none;
		}
	}
</style>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Payment</div>
    <div id="box" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
			if($_GET['success']=='add'){
				echo 'Product has been added successfuly.';
			}
			else if($_GET['success']=='delete'){
				echo 'Product has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Product has been updated successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Payments</div>
        <div style="padding:20px">
        	<style>
				#list td:nth-child(2) {
					text-align:right;	
				}
				th,#list td:nth-child(4) {
					text-align:center !important;
				}
			</style>
        	<table width="100%" id="list">
              	<thead>
                	<th width="10%">Payment #</th>
                    <th width="15%">Paid Amount</th>
                    <th >Customer</th>
                    <th width="14%">Date & Time</th>
                    <th >Assign</th>
                </thead>
                
            </table>

        </div>
    </div>
    
    <div id="print_popup" class="modal-box" style="width:20%">
    	<header>
          <h3>Filter Date</h3>
        </header>
        <div class="modal-body" style="text-align:center;padding:15px;">
        	<span style="text-shadow: 1px 0px black;" >FROM</span>
         	<div class="form-control" style="height:60px !important"><input type="date" id="from" /></div>
            <span style="  text-shadow: 1px 0px black;">TO</span>
         	<div class="form-control" style="height:60px !important"><input type="date" id="to" /></div>
            <div style="margin-top:15px;text-align:center;">
            	<a href="#" id="date_button" class="btn js-modal-close" style="text-transform:uppercase">Submit</a>
                <a href="#" id="datecancel_button" class="btn js-modal-close" style="text-transform:uppercase">Cancel</a>
            </div>
        </div>
    </div>
</div>
<div id="print" style="display:none;margin:0px;width:675px;float:none;text-align:center" class="visible-print-block" >
	<div>
        <div id="store-name">Sales & Inventory Management System</div>
        <div id="store-addr">Commercial Center, Mindanao State University, Marawi City</div>
        <div id="store-contact"> +639 1037 27802</div>
    </div>
	<div style="margin-top:15px;font-weight:100">PAYMENT SUMMARY</div>
    <div style="font-weight:100" id="date-summary">FROM 0000-00-00 TO 0000-00-00</div>
    <table width="100%" border="0" style="border-bottom:2px solid black;border-top:2px solid black;font-size:13px">
    	<td style="text-align:left" width="5%">#</td>
    	<td style="text-align:left" width="10%">Payment #</td>
        <td style="text-align:right" width="15%">Paid Amount</td>
        <td style="text-align:center">Customer</td>
        <td style="text-align:center" width="20%">Date & Time</td>
        <td style="text-align: center;">Assign</td>
    </table>
    <style>
		#receipt-list td:nth-child(1),#receipt-list td:nth-child(2){
			text-align:left;
		}
		#receipt-list td:nth-child(1){
			width:5%;
		}
		#receipt-list td:nth-child(2){
			width:10%;
			text-align:left;
		}
		#receipt-list td:nth-child(3){
			width:15%;
			text-align:right
		}
		#receipt-list td:nth-child(4),#receipt-list td:nth-child(6){
			text-align:left
		}
		#receipt-list td:nth-child(5){
			width:20%;
			text-align:center;
		}
		#receipt-list td {
			border-bottom: 1px solid black;
			border-spacing: 0;
			border-collapse: collapse;
			padding-left:5px;
			padding-right:5px;
		}
		#payment2 td, #product td:nth-child(5), #product td:nth-child(4){
			text-align:right
		}
		#account td{
			text-align:center;
			text-transform:uppercase;
		}
	</style>
    <table id="receipt-list" width="100%" border="0" style="font-size:13px">
    <tbody>
    </tbody>
    </table>
    <div style="margin-top:15px;font-weight:400;border-bottom:2px solid black">SUMMARY</div>
    <div style="margin:10px;font-size:13px" id="payment2">
    	<table width="100%" border="0">
          <tr>
          	<td>Total Number of Payments:</td>
            <td id="totPay"></td>
          	<td>&nbsp;</td>
            <td>Total Paid Amount:</td>
            <td id="totPaid"></td>
          </tr>
        </table>
		<table width="100%" border="0" id="account" style="margin-top:20px">
          <tr>
            <td id="custName3" style="text-align:left">&nbsp;Date Printed:<span style="margin-right:5px" id="datetime"></span></td>
            <td width="20%">&nbsp;</td>
            <td width="40%" style="border-bottom:1px solid black" id="accName1"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>VERIFIED BY</td>
          </tr>
        </table>

    </div>
    
    
</div>
<script>
	$(function(){
	var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

	$('button[data-modal-id]').click(function(e) {
		e.preventDefault();
		var check = true;
		
		if(check){
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());	
			//$(".js-modalbox").fadeIn(500);
			var modalBox = $(this).attr('data-modal-id');
			$('#'+modalBox).fadeIn($(this).data());
			var view_width = $(window).width();
			var view_top = $(window).scrollTop() + 150;
			$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
			$('#'+modalBox).css("top", view_top);
		}
		//$(this+' input').focus();
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
	$('#date_button').click(function() {
        if(new Date($('#from').val())>new Date($('#to').val()) || new Date($('#from').val())=="Invalid Date" || new Date($('#to').val())=="Invalid Date"){
			bootbox.dialog({
				message: "Date is invalid.",
				buttons: {
					main: {
						label: 'Ok',
						className: "btn",
					}
				}
			});	
		}
		else{
			$.ajax({
			   url: 'fetch/get-payments.php',
			   type: 'get',
			   data: {from:$('#from').val(),to:$('#to').val(),},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  $('#receipt-list').find('tbody').html('');
				  for(var i = 0; i < s.length-1; i++) {
					  $('#receipt-list').find('tbody').append('<tr><td>'+new Number(new Number(i)+1)+'</td><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td><td>'+s[i][3]+'</td><td>'+s[i][4]+'</td></tr>');
				  }
				  $('#totPay').html(s[s.length-1][0]);
				  $('#totPaid').html(s[s.length-1][1]);
				  $('#datetime').html(s[s.length-1][2]);
				  $('#date-summary').html("From "+$('#from').val()+" To "+$('#to').val())
				  $('#accName1').html($('#accname').html());
				  window.print() ;
			   }
		  });
		}
    });
});
</script>
<script src="media/js/product-popup.js"></script>
<div class="print_info" style="display:none"><h6>Hint!</h6><p>When searching for a Sales Order by date, enter the date in format YYYY-MM-DD or by time in format HH:MM in 24H format</p></div>
</body>
</html>
<?php
}
else
header("Location: index.php");
?>
