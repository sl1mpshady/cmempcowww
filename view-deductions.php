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
<span id="acceptReturn" style="display:none;visibility:0"><?php echo $row_rsAccount['acceptReturn'];?></span>
<script>
	$(document).ready(function () {
		$('.print_info').fadeIn(1000).fadeOut(2500);
		$('#reports').addClass('active');
		$('#reports_menu').show();
		$('#view-payroll_deductions').addClass('selected');
		$(document).ajaxStart(function(){
			$(this).css('cursor','wait');
		});
		$(document).ajaxSuccess(function(){
			$(this).css('cursor','auto');
		});
		$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "asc" ]]
		});
		/*$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false,
			"order": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/return_server_processing.php",
			"createdRow": function ( row, data, index ) {
				$('td', row).eq(0).html('<a href="view-return.php?i='+data[0]+'">'+data[0]+'</a>');
				if(data[1]=='PO'){
					data[1]='Purchase Order';
					$('td', row).eq(2).html('<a href="view-purchase_order.php?i='+data[2]+'">'+data[2]+'</a>');
				}
				else{
					data[1]='Sales Order';
					$('td', row).eq(2).html('<a href="view-sales_order.php?i='+data[2]+'">'+data[2]+'</a>');
				}
				$('td', row).eq(1).html(data[1]);
				
			}
		});*/
		$('#tools').append('<button class="btn" id="add"><span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span>Add New</button><button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button>');
		document.getElementById("from").valueAsDate = new Date();
		document.getElementById("to").valueAsDate = new Date();		
		if($('#acceptReturn').html()!='True')
			$('#add').remove();
		$('#add').click(function() {
			window.location.assign('add-return.php');
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
		$('select').change(function(){
			($(this).val()=='PO' || $(this).val()=='SO' || $(this).val()=='all') ? $('#search').val('').attr('disabled',true):$('#search').attr('disabled',false);
		});
		function getData(){
			var list = $('#list').dataTable();
			$.ajax({
				url: 'fetch/get-ded.php',
				dataType: 'json',
				type:'get',
				data: {type:$('select').val(),value:$('#search').val(),from:$('#from').val(),to:$('#to').val()},
				success: function(s){
					console.log(s);
					list.fnClearTable();
					for(var i = 0; i < s.length; i++) {
							
						s[i][0] = '<a href="view-deduction.php?i='+s[i][0]+'">'+s[i][0]+'</a>';
						list.fnAddData([
						s[i][0],
						s[i][1],
						s[i][2],
						s[i][3],
						s[i][4],
						s[i][5]
						]);
					}
					if(s.length==0)
						$('#noData').fadeIn(1000).fadeOut(1500);
					
				},
				error: function(e){
					console.log(e.responseText);
				}
			});
		}
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
		shortcut.add('enter',function(){
			$('#go').click();
		});
	});	
</script>
<style>
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
	input[type=date] {
		height: 28px;
		width: inherit;
	}
	#list_filter {
		display:none;
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
<div style="display:none" id="parent">reports</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Credit Deduction</div>
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
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Credit Deductions</div>
        <div style="padding:5px">
        	<style>
				#list td:nth-child(2),#list td:nth-child(3) {
					text-align:right;	
				}
				th,#list td:nth-child(4) {
					text-align:center !important;
				}
			</style>
			<table width="100%" style="margin:0px 0px 5px 0px">
				<tr>
					<td width="29%">
						<div style="float:left;width:72%" class="input-group"><span class="input-group-addon" style="background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;"><span class="glyphicon glyphicon-search"></span> </span><input type="text" class="form-control" placeholder="Search" style="font-weight:normal" aria-controls="list" id="search" disabled></div>
						<select class="form-control1" style="margin-left:5px;float:right;height: 28px;width:101px">
							<option value="all">-- All --</option>
							<option value="dedID">Deduction ID</option>
							<option value="assign">Assign</option>
						</select>
						
					</td>
					<td style="text-align:right"><input class="form-control1" style="width:143px" type="date" id="from"> <span class="glyphicon glyphicon-chevron-right"></span> <input class="form-control1" style="width:143px" type="date" id="to"></td>
					<td width="8%" style="text-align:right"><button style="color:navy;font-weight:bolder" class="btn" id="go">{ }</button><button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button></td>
				</tr>
			</table>
        	<table width="100%" id="list">
              	<thead>
                	<th width="10%">Deduction #</th>
					<th width="15%">Customers</th>
                    <th width="15%">Deduction Cost</th>
                    <th width="14%">Date & Time</th>
                    <th >Assign</th>
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
		$('iframe').attr('src','report/deductions.php?type='+$('select').val()+'&value='+$('#search').val()+'&from='+$('#from').val()+'&to='+$('#to').val());
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
			   url: 'fetch/get-returns.php',
			   type: 'get',
			   data: {from:$('#from').val(),to:$('#to').val(),},
			   dataType: 'json',
			   success: function(s){
				  console.log(s);
				  $('#receipt-list').find('tbody').html('');
				  for(var i = 0; i < s.length-1; i++) {
					  $('#receipt-list').find('tbody').append('<tr><td>'+new Number(new Number(i)+1)+'</td><td>'+s[i][0]+'</td><td>'+s[i][1]+'</td><td>'+s[i][2]+'</td><td>'+s[i][3]+'</td><td>'+s[i][4]+'</td><td>'+s[i][5]+'</td></tr>');
				  }
				  $('#totRet').html(s[s.length-1][0]);
				  $('#totCost').html(s[s.length-1][1]);
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
