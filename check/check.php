<?php
if (!isset($_SESSION)) {
  session_start();
}

require('../class/class.php');
$data=array();
if(isset($_GET['prodID'])){
	if(isset($_GET['oldID']))
	$product = new Product();
	$check = $product->check($_GET['prodID']);
	$data = array($check);
}
else if(isset($_GET['catName'])){
	$category = new Category();
	$check = isset($_GET['catID']) ? $category->check($_GET['catName'],$_GET['catID']) : $category->check($_GET['catName']);
	$data = array($check);
}
else if(isset($_GET['measID'])){
	$measurement = new Measurement();
	$check = $measurement->check($_GET['measID']);
	$data = array($check);
}
else if(isset($_GET['custID']) && isset($_GET['custFName']) && isset($_GET['custMName']) && isset($_GET['custLName'])){
	$customer = new Customer;
	$check = $customer->check($_GET['custFName'],$_GET['custMName'],$_GET['custLName'],$_GET['custID']);
	$data = array($check);
}
else if(isset($_GET['custID'])){
	$customer = new Customer();
	$check = $customer->check_id($_GET['custID']);
	$data = array($check);
}
else if(isset($_GET['custFName']) && isset($_GET['custMName']) && isset($_GET['custLName'])){
	$customer = new Customer;
	$check = $customer->check($_GET['custFName'],$_GET['custMName'],$_GET['custLName']);
	$data = array($check);
}
else if(isset($_GET['accName'])){
	$account = new Account;
	$check = isset($_GET['accUsername']) ? $account->connect("SELECT accID FROM account WHERE accStatus='Active' AND accUsername!='".$_GET['accUsername']."' AND accName='".$_GET['accName']."'") : $account->connect("SELECT accID FROM account WHERE accName='".$_GET['accName']."'");
	$num = mysql_num_rows($check);;
	$check = ($num>0 ? true : false);
	$data = array($check);
}
else if(isset($_POST['accUsername']) && isset($_POST['accIDC']) && isset($_POST['accPassword'])){
	$account = new Account;
	$check = $account->checkPassword($_POST['accIDC'],$_POST['accUsername'],$_POST['accPassword']);
	$data = array($check);
	if($check)
		$account->changePassword($_POST['accIDC'],$_POST['accUsername'],$_POST['accPassword1']);
}
else if(isset($_GET['accUsername'])){
	$account = new Account;
	$check = $account->check($_GET['accUsername']);
	$data = array($check);
}

echo json_encode($data);
?>