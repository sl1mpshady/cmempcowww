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
	if(!isset($_GET['i']) || isset($_GET['i'])==NULL)
		header("Location: view-payments.php"); 
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
<script src="media/js/sales.dataTable.js"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>

</head>
<script>
	$(document).ready(function () {
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#view-payment').addClass('selected');
		
		var list = $('#list').dataTable({
			"scrollY":        "250px",
			"scrollCollapse": false,
			"paging":         false
		});
		 
		
		var x = window.location.href;
		x = x.split("?");
		x = x[1].split("&");
		x = x[0].split("=");
		$.ajax({
			url: 'fetch/get-payments.php',
			dataType: 'json',
			data: {payID:x[1]},
			success: function(s){
				console.log(s);
				if(s[0][0]==null)
					window.location.assign('view-payments.php');
				list.fnClearTable();
				
				for(var i = 0; i < s.length-1; i++) {
					list.fnAddData([
					i+1,
					'<a href="view-sales_order.php?i='+s[i][0]+'">'+s[i][0]+'</a>',
					s[i][1],
					s[i][2]
					]);
					
				} // End For
				$('#custID').html('<a href="customer-view.php?i='+s[s.length-1][0]+'">'+s[s.length-1][0]+'</a>');
				$('#custID2').html(s[s.length-1][0]);
				$('#custName2,#custName3').html(s[s.length-1][1]);
				$('#custName').html('<a href="customer-view.php?i='+s[s.length-1][0]+'">'+s[s.length-1][1]+'</a>');
				$('#payDateTime,#datetime1').html(s[s.length-1][2]);
				$('#totPaid,#totPaid1').html(s[s.length-1][3]);
				$('#totSO').html(s.length-1);
			},
			error: function(e){
				console.log(e.responseText);
				window.location.assign('view-payments.php');
			}
		});
		$(document).on("click","#list tr", function(){
			if ( $(this).hasClass('active_') ) {
				$(this).removeClass('active_');
				$('#totGross,#totNet,#totDisc,#dateTime,#dueDate,#balance2').html('');
			}
			else {
				$('#list tr.active_').removeClass('active_');
				$(this).addClass('active_');
				$.ajax({
					url: 'fetch/get-sales_orders.php',
					dataType: 'json',
					data: {saleID:$(this).find('td').eq(1).find('a').html(),payment:$('#saleID').html()},
					success: function(s){
						console.log(s);
						$('#totGross').html(s[0][0]);
						$('#totNet').html(s[0][1]);
						$('#totDisc').html(s[0][2]);
						$('#dateTime').html(s[0][3]);
						$('#dueDate').html(s[0][4]);
						$('#balance2').html(s[1][0]);
					} 
				});
			}
		});
		$(document).on("click", function(event){
			if(!$(event.target).parents().andSelf().is("#list") && !$(event.target).parent().andSelf().is("a.btn")){
				 $('#list tr.active_').removeClass('active_');
			}
		});
		shortcut.add('ctrl+p',function() {
			$('#print1').click(); 
		});
		$('#back').click(function() {
			window.history.back()
		});
		shortcut.add('Escape',function() {
			$('.print_info').fadeOut(1000);
		});
		
	});	
</script>
<style>
	.print_info{
		width: 65%;
		left:32%;
		top:40%;
	}
	#history1_wrapper .dataTables_scrollHeadInner {
		width:95.7% !important;
	}
	#summary  a {
	  color: #337ab7 !important;
	}
	#list_filter,.dataTables_info,#history1_filter {
		display:none;
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
	@media print {
		#payment2 td:nth-child(1), td:nth-child(3){
		width: 130px;
  		text-align: right;
		}
		#payment2 td:nth-child(2),td:nth-child(4){
			text-align:right;
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
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-shopping-cart"></span> Payment #<span id="saleID" style="color:navy"><?php echo $_GET['i'];?></span>
        <span style="float:right">
 		<button class="btn" id="back" style="border-left: 3px solid #FFAE00;"><span class="glyphicon glyphicon-arrow-left" style="color:navy;margin-right:3px"></span>Back</button>
        
        <button class="btn" id="print1"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button>
        </span></div>
        <div style="padding:7px;height:397px">
        	<style>
				#list td:nth-child(7),#list td:nth-child(6),#list td:nth-child(5),#list td:nth-child(4) {
					text-align:right;	
				}
				th {
					text-align:center !important;
				}
				#list td:nth-child(3) {
					text-align:right;
				}
				#summary td {
					padding-left:3px;
					padding-right:3px;
				}
				#summary td:nth-child(2),#summary td:nth-child(5){
					text-align:right;
				}
				
			</style>
            
        	<table width="100%" id="list">
              	<thead>
                	<th width="3%" style="text-align:center">#</th>
                	<th width="15%" style="text-align:center">Sales Order #</th>
                    <th width="15%" style="text-align:center">Amount Paid</th>
                    <th width="10%" style="text-align:center">Sales Order Balance</th>
                </thead>
                
            </table>
			<div style="margin-top:6px;font-size:14px" id="summary">
            	<div style="float:left;width:48%;border: 1px solid lightgray;border-radius: 4px;">
                	<div style="color:navy;padding:3px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-info-sign" style="margin-right:3px"></span>Sales Details</div>
                	<table width="100%" border="0">
                      <tr>
                        <td>Total Gross Amount:</td>
                        <td id="totGross"></td>
                        <td>Date Time:</td>
                        <td id="dateTime"></td>
                      </tr>
                      <tr>
                        <td>Total Net Amount:</td>
                        <td id="totNet"></td>
                        <td>Due Date:</td>
                        <td id="dueDate"></td>
                      </tr>
                      <tr>
                        <td>Total Discount:</td>
                        <td id="totDisc"></td>
                        <td>Balance:</td>
                        <td id="balance2"></td>
                      </tr>
                    </table>

                </div>
                <div style="float:right;width:48%;border: 1px solid lightgray;border-radius: 4px;">
                	<div style="color:navy;padding:3px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-info-sign" style="margin-right:3px"></span>Customer<span style="float: right;margin-right: 219px;">Payment</span></div>
                	<table width="100%" border="0">
                      <tr>
                        <td>Customer ID:</td>
                        <td id="custID"></td>
                        <td>&nbsp;</td>
                        <td>Date Time</td>
                        <td id="payDateTime"></td>
                      </tr>
                      <tr>
                        <td>Customer Name:</td>
                        <td id="custName"></td>
                        <td>&nbsp;</td>
                        <td>Total Paid:</td>
                        <td id="totPaid"></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td id="payType"></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td id="balance"></td>
                      </tr>
                    </table>

                </div>
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
    <table width="100%" border="0">
      <tr>
        <td>Customer #:</td>
        <td width="344px" id="custID2" style="text-align:left"></td>
        <td>Payment #:</td>
        <td id="soID" style="text-align:left"><?php echo $_GET['i'];?></td>
      </tr>
      <tr>
        <td>Customer Name:</td>
        <td id="custName2" style="text-align:left"></td>
        <td>&nbsp;</td>
        <td id="payType1" style="text-align:left"></td>
      </tr>
      <tr>
        <td>Date & Time:</td>
        <td id="datetime1" style="text-align:left"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
	<div style="margin-top:15px;font-weight:400">PAYMENT SO ITEMS</div>
    <table width="100%" border="0" style="border-bottom:2px solid black;border-top:2px solid black;font-size:13px">
    	<td style="text-align:left" width="33.33%">Sales Order #</td>
        <td style="text-align:center" width="33.33%">Amount Paid</td>
        <td style="text-align:right" width="33.33%">Sales Order Balance</td>
    </table>
    <style>
		#receipt-list td:nth-child(1){
			text-align:left;
			width:33.33%;
		}
		#receipt-list td:nth-child(2){
			text-align:center;
			width:33.33%;
		}
		#receipt-list td:nth-child(3){
			text-align:right;
			width:33.33%;
		}
		#payment2 td{
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
    <div style="margin-top:15px;font-weight:400;border-bottom:2px solid black">PAYMENT SUMMARY</div>
    <div style="margin:10px;font-size:13px" id="payment2">
    	<table width="100%" border="0">
          <tr>
            <td style="text-align:left">Total No. of SO:</td>
            <td id="totSO"></td>
            <td>&nbsp;</td>
            <td>Total Amount Paid:</td>
            <td id="totPaid1"></td>
          </tr>
        </table>
		<table width="100%" border="0" id="account" style="margin-top:20px">
          <tr>
            <td style="border-bottom:1px solid black;text-align:center" id="custName3"></td>
            <td width="20%">&nbsp;</td>
            <td style="border-bottom:1px solid black;text-align:center" id="accName1"></td>
          </tr>
          <tr>
            <td style="text-align:center">CUSTOMER</td>
            <td>&nbsp;</td>
            <td>VERIFIED BY</td>
          </tr>
        </table>

    </div>
    
    
</div>

<script>
$(document).ready(function() {
	$('#print1').click(function() {
		$.ajax({
			url: 'fetch/get-payments.php',
			dataType: 'json',
			data: {payID:$('#saleID').html()},
			dataType: 'json',
			async:false,
			success: function(s){
			  console.log(s);
			  $('#receipt-list').find('tbody').html('');
			  for(var i = 0; i < s.length-1; i++) {
				  $('#receipt-list').find('tbody').append('<tr><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td></tr>');
			  } // End For
			  $('#accName1').html(s[s.length-1][4]);
			  window.print() ;
			}
		});
	});
});
</script>
<script src="media/js/product-popup.js"></script>

</body>
</html>
<?php
}
else
header("Location: index.php");
?>
