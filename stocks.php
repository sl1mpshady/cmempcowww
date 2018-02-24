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
/*mysql_select_db($database_connSIMS, $connSIMS);
$query_rsProduct = "SELECT prodID, prodDesc, prodOHQuantity, prodPrice, prodStatus, getCatName(prodCategory) as category FROM product WHERE prodDelete='False'";
$rsProduct = mysql_query($query_rsProduct, $connSIMS) or die(mysql_error());
//$row_rsProduct = mysql_fetch_assoc($rsProduct);
$totalRows_rsProduct = mysql_num_rows($rsProduct);
$query_rsCategory = "SELECT catID, catName FROM category";
$rsCategory = mysql_query($query_rsCategory, $connSIMS) or die(mysql_error());
$row_rsCategory = mysql_fetch_array($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);*/
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
<script src="media/js/jquery.highlight.js"></script>
<script src="media/js/dataTables.searchHighlight.min.js"></script>
<link href="media/css/dataTables.searchHighlight.css" rel="stylesheet" />
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
</head>
<span id="addProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['addProduct'];?></span>
<span id="editProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['editProduct'];?></span>
<span id="deleteProduct" style="display:none;visibility:0"><?php echo $row_rsAccount['deleteProduct'];?></span>
<script>
	$(document).ready(function () {
		$('#reports').addClass('active');
		$('#reports_menu').show();
		$('#stocks_menu').addClass('selected');
		
		$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false,
			searchHighlight: true,
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/stock_server_processing.php?stocks=true",
			"createdRow": function ( row, data, index ) {
				/*if ( (data[5].replace(/[\$,]/g, '') * 1 - data[4].replace(/[\$,]/g, '') * 1) <= 100) {
					$('td', row).eq(4).css('color','red');
				}*/
				$('td', row).eq(0).html('<a href="view-product-ledger.php?i='+data[0]+'">'+data[0]+'</a>');
				$('td', row).eq(1).html('<a href="view-product-ledger.php?i='+data[0]+'">'+data[1]+'</a>');
				//$('td', row).eq(1).html('<a href="view-customer.php?c='+data[0]+'">'+data[1]+'</a>');
				//$('td', row).eq(0).html('<a href="view-sales_order.php?i='+data[0]+'">'+data[0]+'</a>');
			//	alert(data[5]);
			}
		});
		
		$('#add').remove();
		$('#delete').remove();	
		$('#print').attr('data-modal-id',"print_popup");
		$('#edit').html('<span class="glyphicon glyphicon-folder-open" style="color:navy;margin-right:3px"></span> View Logs').attr('id','view');
		shortcut.add('ctrl+p',function() {
			$('#print').click();
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
		$('#view').click(function(e) {
            if($('#list tr').hasClass('active_'))
				window.location.assign('view-product-ledger.php?i='+$('#list .active_ td').eq(0).text());
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
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Stocks</div>
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
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Stocks List</div>
        <div style="padding:5px">
        	<style>
				#list td:nth-child(6),#list td:nth-child(5),#list td:nth-child(7),#list td:nth-child(4) {
					text-align:right;	
				}
				th, #list td:nth-child(3) {
					text-align:center !important;
				}
			</style>
        	<table width="100%" id="list">
              	<thead>
                	<th width="15%">Product ID</th>
                    <th>Item Description</th>
					<th width="6%">Unit</th>
                    <th width="12%">Stock In</th>
                    <th width="12%">Stock Out</th>
                    <th width="12%">On Hand</th>
                </thead>
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
			$('iframe').attr('src','report/stocks.php');
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
<!--<div class="print_info" style="display:none"><h6>Hint!</h6><p>When searching for a Sales Order by date, enter the date in format YYYY-MM-DD or by time in format HH:MM in 24H format</p></div>-->
<div class="print_info" id="noData" style="display:none"><h6>No data!</h6></div>
</div>
</body>
</html>
<?php
//mysql_free_result($rsProduct);
}
else
header("Location: index.php");
?>
