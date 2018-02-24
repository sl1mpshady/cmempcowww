<?php
if (!isset($_SESSION)) {
  session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
require('../class/class.php');
$response = array();
if(isset($_POST['update'])){
  $account = new Account;
  $response[] = $account->update($_POST['uname'],$_POST['name'],$_POST['pass'],$_POST['type'],$_POST['status'],$_POST['account']);
}
if(isset($_GET['uname']) && isset($_GET['name']) && isset($_GET['pass']) && isset($_GET['type']) && isset($_GET['status']) && isset($_GET['account'])){
  $account = new Account;
  $response[] = $account->create($_GET['uname'],$_GET['name'],$_GET['pass'],$_GET['type'],$_GET['status'],$_GET['account']);
}
echo json_encode($response);
?>