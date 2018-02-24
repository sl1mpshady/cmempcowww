<?php
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
if (!isset($_SESSION)) {
  session_start();
}
mysql_query("DELETE FROM pd_temp_list WHERE accID=".$_GET['accID']." AND sessionID='".session_id()."'");
?>