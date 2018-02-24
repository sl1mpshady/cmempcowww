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
$totalRows_rsCategory = mysql_num_rows($rsCategory);
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>StoreSys</title>
<?php include_once('menu.php'); ?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />


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
	$('#stocks-replenishment_menu').addClass('selected');
	var list = $('#list').dataTable({
		"scrollY":        "300px",
		"scrollCollapse": false,
		"paging":         false
	});
	$.ajax({
		url: 'fetch/get-products.php?replenishment',
		dataType: 'json',
		success: function(s){
			console.log(s);
			list.fnClearTable();
			for(var i = 0; i < s.length; i++) {
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
			} // End For
		},
		error: function(e){
			console.log(e.responseText);
		}
	});
	
	$('#add').html('<span class="glyphicon glyphicon-file" style="color:navy;margin-right:3px"></span>Excel');
	$('#edit').remove();
	$('#delete').remove();	
	$('#print').attr('data-modal-id',"print_popup");
	$('#add').click(function() {
		window.location.assign('excel/replenishment.php');
	});
	shortcut.add('ctrl+p',function() {
		$('#print').click();
	});
});	
</script>
<script>
$(document).ready(function(e) {
 shortcut.add('Ctrl+p',function(){
	  $('#print').click();
	 });
  function printFunction(){
  	$('#print').click();
  }
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
</style>
<body>
<div style="display:none" id="parent">reports</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:25px" id="header2">Stocks Replenishment</div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Stocks List</div>
        <div style="padding:5px">
        	<style>
				#list td:nth-child(6),#list td:nth-child(5),#list td:nth-child(7),#list td:nth-child(8) {
					text-align:right;	
				}
				th,#list td:nth-child(4){
					text-align:center !important;
				}
			</style>
        	<table width="100%" id="list">
              	<thead>
                	<th width="2%">#</th>
                    <th width="12%">Product ID</th>
                    <th>Item Description</th>
					<th width="5%">Unit</th>
					<th width="12%">OHB</th>
					<th width="12%">SOLD DAILY</th>
					<th width="12%">ROP</th>
					<th width="15%">Reorder Qty</th>
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
</div>
<script>
$(document).ready(function() {
	$('#print').click(function() {
		var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
		$("body").append(appendthis);
		$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
		$(".modal-overlay").css('position','fixed');
		var modalBox = 'print_popup';
		$('iframe').attr('src','report/replenishment.php?accID='+$('#accID').html());
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
<div class="print_info" style="display:none"><h6>No data!</h6></div>
</body>
</html>
<?php
}
else
header("Location: index.php");
?>
