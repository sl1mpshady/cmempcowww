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
<title></title>

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
<script src="media/js/dataTables.tableTools.js"></script>
<span id="addCustomer" style="display:none;visibility:0"><?php echo $row_rsAccount['addCustomer'];?></span>
<span id="deleteCustomer" style="display:none;visibility:0"><?php echo $row_rsAccount['deleteCustomer'];?></span>
</head>
<script>
	$(document).ready(function () {
		$('#home').addClass('active');
		$('#home_menu').show();
		$('#customer_menu').addClass('selected');
		$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false
		});
		/*$('#list').dataTable({
			"scrollY":        "300px",
			"scrollCollapse": false,
			"paging":         false,
			searchHighlight: true,
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing/customer_server_processing.php",
			"createdRow": function ( row, data, index ) {
				
				$('td', row).eq(1).html('<a href="customer-view.php?i='+data[0]+'">'+data[1]+'</a>');
				$('td', row).eq(0).html('<a href="customer-view.php?i='+data[0]+'">'+data[0]+'</a>');
			//	alert(data[5]);
			}
		});*/
		/*if($('#addCustomer').html()!='True')
			$('#add').remove();
		if($('#deleteCustomer').html()!='True')
			$('#delete').remove();	
		*/
		if(['AV'].indexOf($('#accType').val())<0){
			$('#delete').remove();	
		}
		$('.input-group input').attr('placeholder','Search');
		$('#edit').html('<span class="glyphicon glyphicon-info-sign" style="color:navy;margin-right:3px"></span>View');
		//$('#add').attr('data-modal-id',"add-popup");
		$('#add').html('<span class="glyphicon glyphicon-plus-sign" style="color:navy;margin-right:3px"></span>Add Customer');
		$('#edit').remove();
		$('#add').click(function() {
			window.location.assign('add-customer.php');	
		});
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
		$('#print').attr('data-modal-id',"print_popup");
		$('select').change(function() {
		 	($(this).val()=='all') ? $('#search').val('').prop('disabled',true) : $('#search').prop('disabled',false);
		});
		$('#go').click(function() {
			var list = $('#list').dataTable();
			$.ajax({
				url: 'fetch/get-customer1.php',
				dataType: 'json',
				type:'get',
				data: {type:$('select').val(),value:$('#search').val()},
				success: function(s){
					console.log(s);
					list.fnClearTable();
					for(var i = 0; i < s.length; i++) {
						s[i][1] = '<a href="customer-view.php?i='+s[i][0]+'">'+s[i][1]+'</a>';
						s[i][0] = '<a href="customer-view.php?i='+s[i][0]+'">'+s[i][0]+'</a>';
						list.fnAddData([
							s[i][0],
							s[i][1],
							s[i][2],
							s[i][3],
							s[i][4],
							s[i][5]
						]);
					} // End For
					if(s.length==0)
						$('#noData').fadeIn(1000).fadeOut(1500);
					
				},
				error: function(e){
					console.log(e.responseText);
				}
			});	
		});
		shortcut.add('enter',function(){
			$('#go').click();
		});
	});	
</script>
<style>
	.dataTables_scrollHead {
		background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);
	}
	#list a {
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
</style>
<body>
<div style="display:none" id="parent">home</div>
<div style="padding:25px" class="hidden-print">
	<div style="font-size: 26px;color: gray;border-bottom: 1px solid lightgray;<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'margin-bottom:0px' : 'margin-bottom:25px')?>" id="header2">Customer</div>
    <div id="box" class="bg-success" style="display:<?php echo (isset($_GET['success']) && $_GET['success']!='' && ($_GET['success']=='add' || $_GET['success'] == 'edit' || $_GET['success'] == 'delete') ? 'block;' : 'none;')?> padding: 0px 10px;margin: 2px 0px 2px 0px;border-radius: 2px;">
    	<?php
			if($_GET['success']=='add'){
				echo 'Customer has been added successfuly.';
			}
			else if($_GET['success']=='delete'){
				echo 'Customer has been deleted successfully.';
			}
			else if($_GET['success']=='edit'){
				echo 'Customer has been deleted successfully.';
			}
		?>
        <button type="button" class="close" aria-label="Close" style="float:right"><span aria-hidden="true">&times;</span></button>
    </div>
    <div style="border:1px solid lightgray;border-radius:4px">
    	<div style="padding:6px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;"><span class="glyphicon glyphicon-list"></span> Customer List</div>
        <div style="padding:5px">
        	<style>
				#list td:nth-child(6),#list td:nth-child(7) {
					text-align:center;	
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
				#list_filter {
					display:none;
				}
			</style>
			<table width="100%" style="margin:0px 0px 5px 0px">
				<tr>
					<td width="40%">
						<div style="float:left;width:75%" class="input-group"><span class="input-group-addon" style="background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;"><span class="glyphicon glyphicon-search"></span> </span><input type="text" class="form-control" placeholder="Search" style="font-weight:normal" aria-controls="list" id="search" disabled></div>
						<select class="form-control1" style="margin-left:5px;float:right;height: 28px;width:120px">
							<option value="all">-- All --</option>
							<option value="custID">Customer ID</option>
							<option value="getCustName(custID)">Customer Name</option>
							<option value="custDesignation">Designation</option>
							<option value="custSection">Section</option>
							<option value="custDepartment">Department</option>
						</select>
						
					</td>
					<td style="text-align:right">&nbsp;</td>
					<td width="15%" style="text-align:right"><button style="color:navy;font-weight:bolder" class="btn" id="go">{ }</button><button class="btn" id="delete" data-modal-id="delete_popup"><span class="glyphicon glyphicon-remove" style="color:maroon;margin-right:3px"></span>Deactivate</button><button class="btn" id="print"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button></td>
				</tr>
			</table>
        	<table width="100%" id="list">
              	<thead>
                	<th width="10%">Customer ID</th>
                    <th  style="text-align:center">Customer Name</th>
                    <th width="15%">Designation</th>
                    <th width="15%">Department</th>
                    <th width="15%">Section</th>
                    <th width="14%">Last Transaction</th>
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
		$('iframe').attr('src','report/customer-list.php?type='+$('select').val()+'&value='+$('#search').val());
		$('#'+modalBox).fadeIn(1000);
		$('#'+modalBox+' iframe').css('height',$(window).height()-90);
		var view_width = $(window).width();
		$('#'+modalBox).css('height',$(window).height()-60);
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
// JavaScript Document
</script>
<script>
$(document).ready(function() {
	shortcut.add('Ctrl+p',function() {
		$('#print').click();
	});
	$('#print').click(function() {
		
	});
	$('#delete').click(function() {
		if($('#list tr').hasClass('active_')){
			var id = $('#list .active_ td').eq(0).text();
			bootbox.dialog({
				message: "Are you sure you want to deactivate "+$('#list .active_ td').eq(1).text()+".",
				buttons: {
					main: {
						label: 'Ok',
						className: "btn",
						callback: function() {
							$.ajax({
								url: 'save/save.php',
								data: {custID:id,update:true,delete:true},
								dataType: 'json',
								success: function(s){
									console.log(s);
								}
							});
							window.location.assign('customer.php?success=delete');
						}
					},
					cancel: {
						label: 'Cancel',
						className: "btn"
					}
				}
			});	
		}
	});
	
	$('.close').click(function() {
		$('#'+modalBox+' .modal-body').css('padding-top','2em');	
	});
});
</script>
<div class="print_info" id="noData" style="display:none"><h6>No data!</h6></div>
</body>
</html>
<?php
}else
header("Location: index.php");
?>
