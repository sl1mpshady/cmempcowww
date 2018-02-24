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
		header("Location: view-purchases.php"); 
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
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<script>
	$(document).ready(function () {
		$('#reports').addClass('active');
		$('#reports_menu').show();
		$('#purchase_menu').addClass('selected');
		
		var list = $('#list').dataTable({
			"scrollY":        "260px",
			"scrollCollapse": false,
			"paging":         false
		});
		var x = window.location.href;
		x = x.split("?");
		x = x[1].split("&");
		x = x[0].split("=");
		$.ajax({
			url: 'fetch/get-purchase_orders.php',
			dataType: 'json',
			data: {purcID:x[1]},
			success: function(s){
				console.log(s);
				if(s.length<=0)
					window.location.assign('view-purchases.php');
				list.fnClearTable();
				for(var i = 0; i < s.length-1; i++) {
					list.fnAddData([
						i+1,
						s[i][0],
						s[i][1],
						s[i][2],
						s[i][3],
						s[i][4],
						s[i][5],
						s[i][6]
					]);
					$('#receipt-list').find('tbody').append('<tr><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][3]+'</td><td>'+s[i][2]+'</td><td>'+s[i][4]+'</td></tr>');
				} // End For*/
				$('#totCost').html(s[s.length-1][0]);
				$('#totQty1').html(s[s.length-1][1]);
				$('#totProd').html(s[s.length-1][2]);
				$('#dateTime,#datetime').html(s[s.length-1][3]);
				$('#accName').html(s[s.length-1][4]);
				$('#suppName,#accName1').html(s[s.length-1][5]);
				$('#suppAddress,#suppAdd1').html(s[s.length-1][6]);
				$('#note,#purcNote1').html(s[s.length-1][7]);		
				$('#charges,#purcCharges1').html(s[s.length-1][8]);
				$('#purcDate,#purcDate1').html(s[s.length-1][9]);	
			},
			error: function(e){
				console.log(e.responseText);
				window.location.assign('view-purchases.php');
			}
		});
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
			$('#print1').click(); 
		});
		$('#back').click(function() {
			window.history.back();
		});
	});	
</script>
<style>
	#summary  a {
	  color: #337ab7 !important;
	}
	#list_filter,.dataTables_info {
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
<div style="display:none" id="parent">reports</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Purchase Order</div>
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
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-shopping-cart"></span> Purchase Order #<span id="purcID" style="color:navy"><?php echo $_GET['i'];?></span>
        <span style="float:right">
 		<button class="btn" id="back" style="border-left: 3px solid #FFAE00;"><span class="glyphicon glyphicon-arrow-left" style="color:navy;margin-right:3px"></span>Back</button>
        <button class="btn" id="print1"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button>
		<button class="btn" id="return"><span class="glyphicon glyphicon-log-in" style="color:navy;margin-right:3px"></span>Returns</button>
        </span></div>
        <div style="padding:7px;height:auto;overflow: auto;">
        	<style>
				#list td:nth-child(7),#list td:nth-child(6),#list td:nth-child(5),#list td:nth-child(8) {
					text-align:right;	
				}
				th {
					text-align:center !important;
				}
				#list td:nth-child(3),#list td:nth-child(4) {
					text-align:left;
				}
				#summary td {
					padding-left:3px;
					padding-right:3px;
				}
				#summary td:nth-child(2),#summary td:nth-child(5){
					text-align:right;
					padding-right:15px;
				}
				#summary table {
					/*table-layout: fixed;*/
					border-collapse: collapse;
				}
				#summary td {
					text-overflow: ellipse;
					overflow:hidden;
					white-space:nowrap;
				}
			</style>
            
        	<table width="100%" id="list">
              	<thead>
                	<th width="3%" style="text-align:center">#</th>
					<th width="6%" style="text-align:center">Unit</th>
                	<th width="15%" style="text-align:center">Product ID</th>
                    <th style="text-align:center">Item Description</th>
                    <th width="12%" style="text-align:center">Unit Cost</th>
                    <th width="10%" style="text-align:center">Quantity</th>
					<th width="12%" style="text-align:center">Freight</th>
                    <th width="12%" style="text-align:center">Total</th>
                </thead>
                
            </table>
			<div style="margin-top:6px;font-size:14px" id="summary">
            	<div style="float:left;width:48%;border: 1px solid lightgray;border-radius: 4px;">
                	<div style="color:navy;padding:3px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-info-sign" style="margin-right:3px"></span>Purchase Details</div>
                	<table width="100%" border="0">
                      <tr>
                        <td>Total Cost Amount:</td>
                        <td id="totCost"></td>
                        <td>Date & Time Added:</td>
                        <td id="dateTime"></td>
                      </tr>
                      <tr>
                        <td>Total No. of Quantity:</td>
                        <td id="totQty1"></td>
                        <td>Added by:</td>
                        <td id="accName" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>Total No. of Products:</td>
                        <td id="totProd"></td>
						<td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <!--<td>Status:</td>
                        <td id="status">Reversed by Administrator(admin)</td>-->
                      </tr>
                    </table>

                </div>
                <div style="float:right;width:48%;border: 1px solid lightgray;border-radius: 4px;">
                	<div style="color:navy;padding:3px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list-alt" style="margin-right:3px"></span>Others</div>
                	<table width="100%" border="0">
                      <tr>
                        <td width="122px">Note/Memo:</td>
                        <td colspan="3" id="note" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>Supplier's Name:</td>
                        <td style="text-align:left;width: 45%;" id="suppName"></td>
                        <td>Invoice Date:</td>
                        <td id="purcDate"></td>
                      </tr>
                      <tr>
                        <td>Supplier's Address:</td>
                        <td style="text-align:left" id="suppAddress"></td>
                        <td>Freight Charges:</td>
                        <td id="charges"></td>
                      </tr>
                    </table>

                </div>
                
            </div>
            </div>
            
        </div>
    </div>
	<div id="print_popup" class="modal-box" style="width:90%;top:10px;">
		<header>
		<h3>Print Preview<span title="Close" class="glyphicon glyphicon-remove-sign js-modal-close" aria-hidden="true" style="float:right;font-size: 17px;cursor: pointer;"></span></h3>
		</header>
		<div class="modal-body" style="text-align:center;padding:0px;display:initial;height:initial">
		<iframe id="report" src="report/purchase_order.php?purcID=<?php echo $_GET['i']; ?>" frameborder="0" style="width:100%;height:100%"></iframe>
		</div>
    </div>
	
</div>
<script>
$(document).ready(function() {
	$('#print1').click(function() {
		printF('purchase');
	});
	$('#return').click(function() {
		printF('return');
	});
	function printF(arg1){
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		modalBox = "print_popup";
		
		if(arg1=='purchase')
			$('iframe').attr('src','report/purchase_order.php?purcID='+$('#purcID').html());
		else
			$('iframe').attr('src','report/return_history.php?type=PO&i='+$('#purcID').html());
		$('#'+modalBox).fadeIn(1000);
		$('#'+modalBox+' iframe').css('height',$(window).height()-90);
		var view_width = $(window).width();
		$('#'+modalBox).css('height',$(window).height()-90);
		var view_top = $(window).scrollTop() + 10;
		$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
		$('#'+modalBox).css("top", view_top);
	}
	$(".js-modal-close, .modal-overlay").click(function() {
		$(".modal-box, .modal-overlay").fadeOut(100, function() {
			$(".modal-overlay").remove();
		});
	});
});
</script>
</body>
</html>
<?php
}
else
header("Location: index.php");
?>
