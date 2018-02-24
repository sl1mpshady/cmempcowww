<?php require_once('Connections/connSIMS.php'); ?>
<?php

//initialize the session

mysql_select_db($database_connSIMS, $connSIMS);
$query_rsAccount = "SELECT * FROM account WHERE accUsername='".$_SESSION['MM_Username']."'";
$rsAccount = mysql_query($query_rsAccount, $connSIMS) or die(mysql_error());
$row_rsAccount = mysql_fetch_assoc($rsAccount);
$totalRows_rsAccount = mysql_num_rows($rsAccount);

/*if($rsAccount['accPassword']!=$_SESSION['MM_Encrypted'])
  header("Location: ". $_SERVER['PHP_SELF']."?doLogout=true");*/
  
$query_rsGeneral1 = "SELECT busName,address,contact,salesReturn FROM general_";
$rsGeneral1 = mysql_query($query_rsGeneral1, $connSIMS) or die(mysql_error());
$row_rsGeneral1 = mysql_fetch_assoc($rsGeneral1);
$totalRows_rsGeneral1 = mysql_num_rows($rsGeneral1);
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
$logoutAction = "logout.php";
/*if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}*/

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['MM_Encrypted'] = NULL;
  $_SESSION['MM_AccType'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['MM_Encrypted']);
  unset($_SESSION['MM_AccType']);
  unset($_SESSION['PrevUrl']);
  $query = "DELETE FROM logged_in WHERE accID='".$_SESSION['MM_AccID']."'";
   mysql_select_db($database_connSIMS, $connSIMS);
   mysql_query($query, $connSIMS) or die(mysql_error());
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
	exit;
  }
}
?>
<script src="media/js/jquery.min.js"></script>
<script src="media/js/bootstrap.min.js"></script>
<link href="media/css/bootstrap.min.css" rel="stylesheet" />
<link href="media/css/bootstrap-theme.min.css" rel="stylesheet">
<script src="media/js/bootbox.min.js"></script>
<script src="media/js/bootstrap-dropdown.js"></script>
<script src="functions.js"></script>
<link href="media/fonts/font.css" type="text/css" rel="stylesheet">
<script src="media/js/jquery.scannerdetection.compatibility.js"></script>
<script src="media/js/jquery.scannerdetection.js"></script>
<script src="media/js/date2.js"></script>
<script>
	$(document).ready(function(e) {
		window.onload = date_time("date_time");
        $('select').css({'background':'url("media/images/arrow_down.png")no-repeat right transparent','background-size':'14px','background-position-x':'98%'});
    });
</script>
</head>
<input type="hidden" id="base641" value="<?php echo $row_rsAccount['accPassword'];?>">
<input type="hidden" id="base642" value="<?php echo $_SESSION['MM_Encrypted'];?>">
<input type="hidden" id="accType" value="<?php echo $row_rsAccount['accType'];?>">
<script>
  $(document).ready(function(e){
    if($('#base641').val()!=$('#base642').val())
      window.location.assign('logout.php?password');
  });
</script>
<style>
/*.glyphicon {-webkit-text-stroke: .25px black;}*/

.modal {
	top: 20%;
}
.modal-footer {
	padding: 6px !important;
}
select{
    -webkit-appearance: none;
}
table a {
  color: #337ab7 !important;
}
body{
  font-family: Tahoma, Geneva, sans-serif !important;
}
#head{
  font-size:15px !important;
  font-family: Tahoma, Geneva, sans-serif !important;
}

/*.modal-box {
  height: 647px !important;
}*/

html,body{
	width:100%;
-webkit-touch-callout: none;
-webkit-user-select: none;
-webkit-tap-highlight-color: rgba(0,0,0,0);
-webkit-tap-highlight-color: transparent;
/*position: auto !important;
position:fixed;*/
}
#list2 td {
	border-bottom: 1px solid #D0CFCF;
}
#header2 {
	font-family: 'Ubuntu';
}
#header {
	font-family: 'Ubuntu';
}
a, a:hover {
	color:inherit;
	text-decoration:none;
}
#logged {
	  background-color: #222;
	  color: white;
	  font-size: 95%;
	  padding: 4px;
		padding: 4px 21px;
}
#navbar ul li {
	padding: 20px;
	padding-bottom: 0px;
	text-transform: uppercase;
	padding-top: 7px;
	font-size:16px;
	cursor:pointer;
}
#navbar li span {
	margin-right:5px !important;
}
.active {
	  background-color: #222;
	  border-top-left-radius: 4px;
	  border-top-right-radius: 4px;
	  color:white;
}
.active span {
	color: #0099ff;
}
#submenu ul {
	  list-style: none;
	  display: -webkit-box;
	  color:white;
	  padding:0
}
#submenu ul li {
	  padding: 5px;
	  margin-right: 8px;
	  margin-left: 8px;
	  font-size: 13px;
	  cursor:pointer;
}
#submenu {
	height:28px !important;
}
.selected {
	background: #0099ff;
}
.required {
	color:red;font-weight: bolder;font-size: 16px;
}
.modal-dialog .btn {
	background-image: linear-gradient(to bottom, #ffffff 0%, gainsboro 158%) !important;
	padding: 2px 5px;
	margin-left: 5px;
	font-size: 12px;
	padding: 6px 12px !important;
	background-image: linear-gradient(to bottom, #ffffff, lightgray 248%) !important;
	font-size: 14px;
	background-color: #fff;
	border: 1px solid #bbb;
	color: #333;
	text-decoration: none;
	display: inline;
	border-radius: 4px;
	font-weight: bold;

}
.print_info {
  position: fixed;
  top: 50%;
  left: 50%;
  width: 400px;
  height: auto;
  margin-left: -200px;
  margin-top: -75px;
  text-align: center;
  color: #333;
  padding: 10px 30px;
  background: #ffffff;
  background: -webkit-linear-gradient(top, #ffffff 0%,#f3f3f3 89%,#f9f9f9 100%);
  background: -moz-linear-gradient(top, #ffffff 0%,#f3f3f3 89%,#f9f9f9 100%);
  background: -ms-linear-gradient(top, #ffffff 0%,#f3f3f3 89%,#f9f9f9 100%);
  background: -o-linear-gradient(top, #ffffff 0%,#f3f3f3 89%,#f9f9f9 100%);
  background: linear-gradient(top, #ffffff 0%,#f3f3f3 89%,#f9f9f9 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f9f9f9',GradientType=0 );
  opacity: 0.95;
  border: 1px solid black;
  border: 1px solid rgba(0, 0, 0, 0.5);
  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  -ms-border-radius: 6px;
  -o-border-radius: 6px;
  border-radius: 6px;
  -webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.5);
  -moz-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.5);
  -ms-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.5);
  -o-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.5);
  box-shadow: 0 3px 7px rgba(0, 0, 0, 0.5);
}
.print_info h6 {
  font-weight: normal;
  font-size: 28px;
  line-height: 28px;
  margin: 1em;
}
.print_info p {
  font-size: 14px;
  line-height: 20px;
}
.dataTables_filter .btn {
  font-family:'Ubuntu';
  font-weight:normal;
}
.btn:hover {
  box-shadow: 0 1px 1px rgba(0, 0, 0, .50);
}
.form-control-date {
	height: 28px;
	padding: 6px 12px;
	font-size: 14px;
	line-height: 1.42857143;
	color: #555;
	background-color: #fff;
	background-image: none;
	border: 1px solid #ccc;
	border-radius: 4px;
	width: 38%;
}
.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none !important;
  float: left;
  min-width: 160px;
  padding: 5px 0;
  margin: 2px 0 0;
  font-size: 14px;
  text-align: left;
  list-style: none;
  background-color: #fff;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  border: 1px solid #ccc;
  border: 1px solid rgba(0,0,0,.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
  box-shadow: 0 6px 12px rgba(0,0,0,.175);
  color:black !important;
}
.popover.right>.arrow {
  top: 14%;
}
.show-dropdown {
  display:inherit !important;
}
.dropdown-menu li:hover {
  background: #0099ff;
  background-color: #0081c2;
  color:white;
  border-radius:1px;
}
.dropdown-menu li{
  width:100%;
  margin: 0 !important;
  padding-left:10px !important;
}
.dropdown-menu>li>a {
  text-align: left;
  padding: 0;
  color:inherit !important;
}
.dropdown-menu>li>a:hover {
  background-color: #0081c2;
  color:inherit !important;
  background:inherit !important;
}

<?php 
if(!strstr($_SERVER["PHP_SELF"],'/sales.php') && !strstr($_SERVER["PHP_SELF"],'/add-payroll-deduction.php') && !strstr($_SERVER["PHP_SELF"],'/add-return.php')){
  //echo '.modal-box{height:647px !important}';
}
?>
</style>
<script>
$(document).ready(function(e) {
  $('#print').click(function(e) {
      $('.print_info').fadeOut('fast');
  });
  if($('#a').val()!=$('#b').val())
	window.location.assign('logout.php');
});
</script>
    <body>
<div id="header" style="background:whitesmoke" class="hidden-print">
	<input type="hidden" id="a" value="<?php echo $row_rsAccount['accPassword']; ?>">
    <input type="hidden" id="b" value="<?php echo $_SESSION['MM_Encrypted']; ?>">
	<div id="logged">
    	You are logged in as <span id="username"><?php echo $_SESSION['MM_Username']; ?></span> (<span id="accname"><?php echo $row_rsAccount['accName']; ?></span>)
        <span style="display:none" id="accID"><?php echo $_SESSION['MM_AccID']; ?></span>
      <span style="float:right"><a href="changepassword1.php" title="Change Password">Change Password</a> | <a title="Logout" href="<?php echo $logoutAction ?>">Logout</a></span>
		
    </div>
    <div id="logo" style="  border-bottom: 1px solid lightgray;">
    	<img src="media/images/logo.png" style="width: 19%;margin-left: 21px;"/>
    	<span id="date_time" style="float:right;margin-right:16px;font-size:14px;"></span>
    </div>
    <div id="navbar" style="padding-left:20px; padding-right:20px;background:whitesmoke">
    	<ul class="nav navbar-nav" style="float:inherit;margin-top:10px">
        	<li id="home"><span class="glyphicon glyphicon-home" aria-hidden="true" style="margin-right: 3px;"></span>Home</li>
            <li id="sell"><span class="glyphicon glyphicon-usd" aria-hidden="true" style="margin-right: 3px;"></span>POS</li>
            <li id="modules" style="<?php if($row_rsAccount['accType']!='IM' && $row_rsAccount['accType']!='AV') echo 'display:none';?>"><span class="glyphicon glyphicon-send" aria-hidden="true" style="margin-right: 3px;"></span>Modules</li>
            <!--<li id="inventory"><span class="glyphicon glyphicon-book" aria-hidden="true" style="margin-right: 3px;"></span>Inventory</li>-->
            <li id="reports" style="<?php if($row_rsAccount['accType']=='CS') echo 'display:none';?>"><span class="glyphicon glyphicon-list" aria-hidden="true" style="margin-right: 3px;"></span>Reports</li>
            <li id="setup" style="<?php if($row_rsAccount['accType']!='AV') echo 'display:none';?>"><span class="glyphicon glyphicon-cog" aria-hidden="true" style="margin-right: 3px;"></span>Setup</li>
			<!--<li id="about"><span class="glyphicon glyphicon-info-sign" aria-hidden="true" style="margin-right: 3px;"></span>About</li> -->
        </ul>
    </div>
    <div style="background:#222;  padding: 0px 16px;" id="submenu">
    	<ul id="home_menu" style="display:none">
         <li id="customer_menu"><a href="customer.php">Customers</a></li>
         <li id="product_menu"><a href="products.php">Products</a></li>
         <li id="measurement_menu"><a href="measurements.php">Measurements</a></li>
      </ul>
      <ul id="sell_menu" style="display:none"><li>&nbsp;</li></ul>
      <ul id="modules_menu" style="display:none">
        <li id="add-product_menu"><a href="add-product.php">Add Product</a></li>
          <li id="add-customer_menu"><a href="add-customer.php">Add Customer</a></li>
          <li id="add-purchase_menu"><a href="add-purchase.php">Add Purchase</a></li>
          <li id="add-payroll_deduction"><a href="add-payroll-deduction.php">Add Credit Deduction</a></li>
          <li id="add-return"><a href="add-return.php">Add Return</a></li>
          
          <!-- PLEASE REVIEW THIS BACK -->
          <!--<li id="import-customer"><a href="import_customer.php">Import Customers</a></li>
          <li id="import-products"><a href="import_products.php">Import Products</a></li>-->
            
        </ul>
        <!--<ul id="inventory_menu" style="display:none"><li id="purchase_menu"><a href="view-purchases.php">Purchase Order</a></li><li>Deliveries</li><li id="stocks_menu"><a href="stocks.php">Stocks</a></li></ul>-->
        <ul id="reports_menu" style="display:none">
        <li id="sales_menu"><a href="view-sales.php">Sales Orders</a></li>
        <!--<li id="inventory_menu"><a href="view-inventory.php">Inventory</li>-->
        <li id="purchase_menu"><a href="view-purchases.php">Purchase Orders</a></li>
        <li id="stocks_menu"><a href="stocks.php">Stocks</a></li>
        <!--<span class="dropdown">
        	<li class="dropdown-toggle" id="inventory_menu" role="button" data-toggle="dropdown" data-target="view-purchases.php" >Inventory</li>
            <ul class="dropdown-menu popover right" style="padding:2px 0px">
              <div class="arrow"></div>	
              <li> <a href="view-purchases.php">Purchase Orders</a></li>
              <li>Deliveries</li>
              <li><a href="stocks.php">Stocks</a></li>
            </ul>
        </span>-->
        <li id="customer-ledger_menu"><a href="view-customer_ledgers.php">Customer Ledger</a></li>
        <li id="stocks-replenishment_menu"><a href="stocks-replenishment.php">Stocks Replenishment</a></li>
        <li id="view-payroll_deductions"><a href="view-deductions.php">Credit Deductions</a></li>
        <li id="return_menu"><a href="view-returns.php">Returns</a></li>
        
        <li id="sales-summary_menu"><a href="sales-summary.php">Sales</a></li>
        <li id="damage_menu"><a href="damages.php">Damages</a></li>
        
        <!-- PLEASE REVIEW THIS BACK -->
        <!--<li id="sales-summary_menu"><a href="sales-summary.php">Summary</a></li>-->
        </ul>
        <ul id="setup_menu" style="display:none">
          <li id="users_menu"><a href="view-accounts1.php">Accounts</a>
          <li id="add-account_menu"><a href="add-account1.php">Add Account</a>
          <li id="account-logs_menu"><a href="view-account_logs.php">Account Logs</a>
          <li id="transaction-logs_menu"><a href="view-transaction_logs.php">Transaction Logs</a>
          </li><li id="general_menu"><a href="general.php">General</a></li>
        </ul>
        <ul id="about_menu" style="display:none">
          <li>CNEMPCO</li><li>Board of Directors</li>
        </ul>
    </div>
    <!--
    <div style="background:#222;  padding: 0px 16px;" id="submenu">
    	<ul id="home_menu" style="display:none"><li id="customer_menu1">Customer</li><li id="product_menu">Products</li></ul>
        <ul id="sales_menu" style="display:none"><li>&nbsp;</li></ul>
    	<ul id="modules_menu" style="display:none">
        	<li id="products"><a href="products.php">Products</a></li>
            <?php if($row_rsAccount['addProduct'] == 'True'){?><li id="add-product"><a href="add-product.php">Add Product</a></li><?php } ?>
            <li id="category"><a href="category.php">Categories</a></li>
            <li id="purchase"><a href="view-purchases.php">Purchase Order</a></li>
             <?php if($row_rsAccount['addPurchase'] == 'True'){?><li id="add-purchase"><a href="add-purchase.php">Add Purchase</a></li><?php } ?>
            <li id="view-sales"><a href="view-sales.php">Sales Order</a></li>
            <li id="view-payment"><a href="view-payments.php">Payments</a></li>
            <?php if($row_rsAccount['acceptPayment'] == 'True'){?><li id="payment"><a href="payment.php">Add Payment</a></li><?php } ?>
            <li id="view-returns"><a href="view-returns.php">Returns</a></li>
            <?php if($row_rsAccount['acceptReturn'] == 'True'){?><li id="add-return"><a href="add-return.php">Add Return</a></li><?php } ?>
        </ul>
        <ul id="customer_menu" style="display:none">
        	<li id="custoners"><a href="customer.php">Customers</a></li>
             <?php if($row_rsAccount['addCustomer'] == 'True'){?><li id="add-customer"><a href="add-customer.php">Add Customer</a></li><?php } ?>
        </ul>
        <ul id="admin_menu" style="display:none">
        	 <li id="view-accounts"><a href="view-accounts.php">Accounts</a></li>
            <?php if($row_rsAccount['addAccount'] == 'True'){?><li id="add-account"><a href="add-account.php">Add Account</a></li><?php } ?>
        </ul>
    </div>-->
</div>