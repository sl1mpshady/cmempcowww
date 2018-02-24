<?php
    require_once('Connections/connection.php');	
    mysql_connect($db_hostname,$db_username,$db_password);
    mysql_select_db($db_database);
 
    $term=$_GET["term"];
    
    $query=mysql_query("SELECT * FROM product where prodID like '%".$term."%' order by prodID ");
    $json=array();
    
    while($product=mysql_fetch_array($query)){
        $json[]=array(
                'value'=> $product["prodID"],
                'label'=>$product["prodID"]
                    );
    }
    
    echo json_encode($json);
 
?>