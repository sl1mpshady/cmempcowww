<?php
include "_pdo.php";	
$SQLiteDB = "ServerDB.db";
PDO_Connect("sqlite:$SQLiteDB");
(isset($_GET['e'])) ? PDO_Execute('UPDATE SERVERDB SET SERVERIP="127.0.0.1"') : PDO_Execute('UPDATE SERVERDB SET SERVERIP="192.158.1.1"');

?>