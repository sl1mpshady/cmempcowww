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
<script>
	$(document).ready(function () {
		$('#modules').addClass('active');
		$('#modules_menu').show();
		$('#add-replenishment').addClass('selected');
		$(document).ajaxStart(function(){
			$(this).css('cursor','wait');
		});
		$(document).ajaxSuccess(function(){
			//$(this).css('cursor','auto');
		});
		$('#list').dataTable({
			"scrollY":        "356px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "asc" ]],
			"async": false
		});
		
		function getData(){
			var list = $('#list').dataTable();
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
						s[i][5]
						]);
					} // End For
					if(s.length==0)
						$('.print_info').fadeIn(1000).fadeOut(1500);
				},
				error: function(e){
					console.log(e.responseText);
				}
			});
		}
		shortcut.add('ctrl+p',function() {
			$('#print').click();
		});
		$('#go').click(function() {
			 getData();	
		});
		
		shortcut.add('enter',function() {
			$('#go').click();	
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
		  height:26px;
	}
	
	#list_filter {
		display:none;
	}
</style>
<body>
<div style="display:none" id="parent">modules</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:0px;" id="header2">Replenish Stocks</div>
	<style>
        #list td:nth-child(6),#list td:nth-child(5),#list td:nth-child(7),#list td:nth-child(8) {
			text-align:right;	
		}
		th,#list td:nth-child(4){
			text-align:center !important;
		}
    </style>
    <table width="100%" style="margin:10px 0px">
    	<tr>
            <td width="12%" style="text-align:right">
				<button style="color:navy;font-weight:bolder" class="btn" id="go">{ } Generate List</button>
				<button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Save</button>
			</td>
        </tr>
    </table>
    <table width="100%" id="list">
        <thead>
			<th width="2%">#</th>
			<th width="20%">Product ID</th>
			<th>Item Description</th>
			<th width="8%">Unit</th>
			<th width="12%">ROP</th>
			<th width="15%">On Hand Qty</th>
			<th width="15%">Reorder Qty</th>
		</thead>
        
    </table>
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
		bootbox.dialog({
			message: "Are you sure you want to save this list for replenishment?",
			buttons: {
				main: {
					label: 'Yes',
					className: "btn",
					callback: function(){
						var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
						$("body").append(appendthis);
						$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
						$(".modal-overlay").css('position','fixed');
						var modalBox = 'print_popup';
						$('iframe').attr('src','report/individual_ledger.php?custID='+$('#custID').html()+'&from='+$('#from').val()+'&to='+$('#to').val()+'&custID='+$('#custID').html()+'&accID='+$('#accID').html());
						$('#'+modalBox).fadeIn(1000);
						$('#'+modalBox+' iframe').css('height',$(window).height()-90);
						var view_width = $(window).width();
						$('#'+modalBox).css('height',$(window).height()-90);
						var view_top = $(window).scrollTop() + 10;
						$('#'+modalBox).css("left", (view_width - $('#'+modalBox).width() ) / 2 );
						$('#'+modalBox).css("top", view_top);
					}
				},
				cancel: {
					label: 'No',
					className: "btn"
				}
			}
		});
		
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
