<?php require_once('Connections/connSIMS.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

if(isset($_SESSION['MM_Username'])){
mysql_select_db($database_connSIMS, $connSIMS);
$query_rsHome = "SELECT countSOToday(), countPOToday(), countCToday()";
$rsHome = mysql_query($query_rsHome, $connSIMS) or die(mysql_error());
$row_rsHome = mysql_fetch_array($rsHome);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SIMS</title>

<?php include_once('menu.php'); ?>
<script src="media/js/jquery-ui.js"></script>
<link href="media/css/jquery-ui.css" rel="stylesheet" />
<script src="media/js/shortcut.js"></script>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/sales-view.dataTable"></script>
<link href="media/css/jquery.dataTables.css" rel="stylesheet"/>
<link href="media/css/popup.css" rel="stylesheet" />
<script src="media/js/jquery.price_format.2.0.min.js"></script>
<script src="media/js/autoNumeric.js"></script>
<script src="media/js/sales.dataTable.js"></script>
<link href="fontAwesome/font-awesome-2.min.css" rel="stylesheet" />
<link href="fontAwesome/sb-admin-2.css" rel="stylesheet" />
<script src="morris/raphael-min.js"></script>
<script src="morris/morris.min.js"></script>
<link href="morris/morris.css" rel="stylesheet" />
<!--<script src="morris/morris-data.js"></script>-->
<?php include('morris/morris-data.php');?>
<script>
$(document).ready(function () {
    if($('#accType').val()=="CS")
        window.location.assign('sales.php?cashier');
    $('#home').addClass('active');
    $('#home_menu').show();
    var list = $('#list').dataTable({
        "scrollY":        "250px",
        "scrollCollapse": false,
        "paging":         false
    });
    $.ajax({
        url: 'fetch/get-product.php?home',
        dataType: 'json',
        success: function(s){
            console.log(s);
            list.fnClearTable();
            for(var i = 0; i < s.length-1; i++) {
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
        },
        error: function(e){
            console.log(e.responseText);
        }
    });
    $.ajax({
		url: 'fetch/get-products.php?replenishment',
		dataType: 'json',
		success: function(s){
			console.log(s);
	        $('#stocks').html(s.length);
		},
		error: function(e){
			console.log(e.responseText);
		}
	});
    $('#tools').append('<button class="btn" id="print1"><span class="glyphicon glyphicon-print" style="color:navy;margin-right:3px"></span>Print</button>');
});
</script>
<style>
	
	#list td:nth-child(5),#list td:nth-child(6),#list td:nth-child(7){
		text-align:right;
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
		  font-size: 14px;
		  font-family: 'Ubuntu';
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
	.panel-primary a {
        color: #3175B0 !important;
    }
   
</style>
<style>
th {
	text-align:center
}
.btn {
  background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
  padding: 2px 5px;
  margin-left: 5px;
  border:1px solid lightgray;
}
.btn:hover {
  background-color: #ddd;
  -webkit-transition: background-color 1s ease;
  -moz-transition: background-color 1s ease;
  transition: background-color 1s ease;
}
</style>
<div style="display:none" id="parent">home</div>
<div style="padding:20px" class="hidden-print">
    <div style="width:50%;float:left">
    	<img src="media/images/logo.png" style="width:335px" />
    </div>
    <div style="float:left;width: 50%;">
        <div style="float:left;width:48%;border: 1px solid lightgray;border-radius: 4px;;width:100%">
            <div style="color:navy;padding:3px 10px;background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%);border-bottom: 1px solid lightgray;">Quick Task</div>
            <div style="padding:10px">
            <table>
            	<tr>
                	<td><span class="btn"><a href="sales.php">Add Sales Order</a></span></td><td><span class="btn"><a href="add-customer.php">Add Customer</a></span></td><td><span class="btn"><a href="add-product.php">Add Product</a></span></td><td><span class="btn"><a href="add-purchase.php">Add Purchase</a></span></td><td><span class="btn"><a href="payment.php">Add Payment</a></span></td>
                </tr>
            </table>
            </div>
        </div>
    </div>
    <br><br><br><br><br>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-dollar fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $row_rsHome[0];?></div>
                            <div>New Sales Orders!</div>
                        </div>
                    </div>
                </div>
                <a href="view-sales.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Sales Orders</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $row_rsHome[1];?></div>
                            <div>New Purchase Orders!</div>
                        </div>
                    </div>
                </div>
                <a href="view-purchases.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Purchase Orders</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user-plus fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $row_rsHome[2];?></div>
                            <div>New Customers!</div>
                        </div>
                    </div>
                </div>
                <a href="customer.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Customers</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-warning fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge" id="stocks">0</div>
                            <div>Stocks Replenishments!</div>
                        </div>
                    </div>
                </div>
                <a href="stocks-replenishment.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Stocks Replenishments</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
     <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i>
            
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="morris-area-chart"></div>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
</div>
</body>
</html>
<?php }else{
	header("Location: index.php");
	exit;
}
	?>
