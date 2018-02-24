<?php
require_once('../Connections/connection.php');	
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);

mysql_query("DELETE FROM alternate_of_measure WHERE prodID=".$_GET['id']);
?>