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
		$('#reports').addClass('active');
		$('#reports_menu').show();
		$('#sales-summary_menu').addClass('selected');
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
			"order": [[ 0, "desc" ]],
			"async": false
		});
		document.getElementById("from").valueAsDate = new Date();
		document.getElementById("to").valueAsDate = new Date();	
		function getData(){
			var list = $('#list').dataTable();
			
			$.ajax({
				url: 'fetch/get-sales_summary.php',
				dataType: 'json',
				type:'get',
				data: {from:$('#from').val(),to:$('#to').val()},
				success: function(s){
					console.log(s);
					list.fnClearTable();
					$('#receipt-list').find('tbody').html('');
					$('#print-from').html($('#from').val());
					$('#print-to').html($('#to').val());
					var deduc=so=bal=0;
					var totSales = totSold = totProfit = totOthers = new Number();
					for(var i = 0; i < s.length; i++) {
						list.fnAddData([
							s[i][0],
							s[i][1],
							s[i][2],
							s[i][3]
						]);
						
						bal = s[i][3];
						totSales += new Number(s[i][1].replace(/,/g, ""));
						totSold += new Number(s[i][2].replace(/,/g, ""));
						totProfit += new Number(s[i][3].replace(/,/g, ""));
						totOthers = new Number(s[i][4].replace(/,/g, ""));
						
						if(x[0]=='Deduction')
							deduc = new Number(deduc)+1;
						else
							so = new Number(so)+1;
					} // End For
					if(s.length==0)
						$('.print_info').fadeIn(1000).fadeOut(1500);
					else{
						$('#accName1').html($('#accname').html());
						$('#totTRA').html(new String(s.length).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#totSales,#totSales1').html(new String(new Number(totSales).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#totCost,#totCost1').html(new String(new Number(totSold).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#totProfit,#totProfit1').html(new String(new Number(totProfit).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						$('#totOthers').html(new String(new Number(totOthers).toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
						
					}
				},
				error: function(e){
					console.log(e.responseText);
				}
			});
		}
		//getData();
		shortcut.add('ctrl+p',function() {
			$('#print').click();
		});
		$('#go').click(function() {
			var startDay = new Date($('#from').val());
			var endDay = new Date($('#to').val());
			var millisecondsPerDay = 1000 * 60 * 60 * 24;
			var millisBetween = startDay.getTime() - endDay.getTime();
			var days = millisBetween / millisecondsPerDay;
			var x =  Math.ceil(days);
			if(x > 0){
				bootbox.dialog({
				  message: "Date is invalid.",
				  buttons: {
					  main: {
						  label: 'OK',
						  className: "btn"
					  }
				  }
			  });
			  document.getElementById("from").valueAsDate = new Date();
			  document.getElementById("to").valueAsDate = new Date();	
			}
			 else
			 	getData();	
		});
		$('#back').click(function(e) {
            window.location.assign('view-customer_ledgers.php');
        });
		shortcut.add('enter',function() {
			$('#go').click();	
		});
		$('#print').click(function(e) {
			var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
			$("body").append(appendthis);
			$(".modal-overlay").fadeTo(100, 0.7).css('height',$(window).height());
			$(".modal-overlay").css('position','fixed');
			var modalBox = 'print_popup';
			$('iframe').attr('src','report/sales_summary.php?from='+$('#from').val()+'&to='+$('#to').val());
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
<style>
	input[type=date]{
		height:28px;
		width:inherit;
	}
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
		  height:26px;
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
	#list_filter {
		display:none;
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
<div style="display:none" id="parent">reports</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;margin-bottom:0px;" id="header2">Sales Summary</div>
	<style>
        #list td:nth-child(3),#list td:nth-child(4),#list td:nth-child(2) {
            text-align:right;	
        }
		#list td:nth-child(1){
			text-align:left;
		}
        th {
            text-align:center !important;
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
			-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
			-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
			transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		}
    </style>
    <table width="100%" style="margin:10px 0px">
    	<tr>
        	<td width="">Total Sales:</td><td id="totSales" style="font-weight:bold"></td><td>Total Cost:</td><td id="totCost" style="font-weight:bold"></td>	<td>Total Profit:</td> <td id="totProfit" style="font-weight:bold"></td><td>Others:</td> <td id="totOthers" style="font-weight:bold"></td>
            <td width="15%">&nbsp;</td>
            <td style="text-align:right;width:33%;"><input class="form-control1" type="date" id="from"> <span class="glyphicon glyphicon-chevron-right"></span> <input class="form-control1" type="date" id="to"></td>
            <td width="7%" style="text-align:left"><button style="color:navy;font-weight:bolder" class="btn" id="go">{ }<button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button></td>
        </tr>
    </table>
    <table width="100%" id="list">
        <thead>
            <th width="40%">Product</th>
            <th>Total Sales</th>
            <th>Cost of Sold</th>
            <th>Gross Profit</th>
        </thead>
        
    </table>    
</div>
<div id="print_popup" class="modal-box" style="width:90%;top:10px;">
	<header>
	<h3>Print Preview<span title="Close" class="glyphicon glyphicon-remove-sign js-modal-close" aria-hidden="true" style="float:right;font-size: 17px;cursor: pointer;"></span></h3>
	</header>
	<div class="modal-body" style="text-align:center;padding:0px;display:initial;height:initial">
	<iframe id="report" src="" frameborder="0" style="width:100%;height:100%"></iframe>
	</div>
</div>
<div class="print_info" style="display:none"><h6>No data!</h6></div>
</body>
</html>
<?php
}
else
header("Location: index.php");
?>
