<?php
 $data=array();
 require_once('../Connections/connection.php');	
 mysql_connect($db_hostname,$db_username,$db_password);
 mysql_select_db($db_database);
 if(isset($_GET['from']) && isset($_GET['to'])){
	 $query = "SELECT purcID, purcCost, purcDateTime, getAccName(purcAssign), countPurcProducts(purcID), getPurcQty(purcID) FROM purchase WHERE DATEDIFF(purcDateTime, '".$_GET['from']."') >= 0 AND DATEDIFF('".$_GET['to']."',purcDateTime) >= 0" ;
	 //DATEDIFF(purcDateTime,'2015-08-02') >= 0 AND DATEDIFF('2014-08-02',purcDateTime)
	 //echo $query;
	 $query=mysql_query($query);
	 $total=$products=$qty=0;
	 while($so=mysql_fetch_array($query)){
		/*$date=date_create($so[2]);
		$so[2] = date_format($date,"M d, o h:i A");*/
		$data[]=array($so[0],number_format($so[1],2),$so[2],$so[3],number_format($so[4],0),number_format($so[5],0));
		$total += $so[1];
		$products += $so[4];
		$qty += $so[5];
	 }
	 $data[]=array(number_format($total,2),number_format($products,0),number_format($qty,0));
	 mysql_free_result($query);
 }
 else if(isset($_GET['purcID']) && isset($_GET['products'])){
 	 $query = "SELECT purcID,purcDateTime,purcCost,purcQty,getAccName(purcAssign) FROM purchase WHERE purcID='".$_GET['purcID']."'";
	 $query=mysql_query($query);
	 $po=mysql_fetch_array($query);
	 $data[] = array($po[0],$po[1],number_format($po[2],2),number_format($po[3],0),$po[4]);
	 $query = "SELECT getProdCategory(prodID),prodID, getProdName(prodID), prodCost/prodQuantity, prodQuantity, prodCost FROM po_product_list WHERE purcID='".$_GET['purcID']."'";
 	 $query=mysql_query($query);
	 while($po=mysql_fetch_array($query)){
	 	$data[]=array($po[0],$po[1],$po[2],number_format($po[3],2),number_format($po[4],0),number_format($po[5],2));
	 }
	 mysql_free_result($query);
 }
  else if(isset($_GET['purcID']) && isset($_GET['account']) && isset($_GET['check'])){
	  $data1=array('purchase','purchase','purchase');
	  $data2 = array();
	  $query = mysql_query("SELECT retID,retDateTime,retQty FROM _return WHERE retType='PO' AND retSubject='".$_GET['purcID']."'");
	  while($fetch=mysql_fetch_array($query)){
		   $data2[]=array($fetch[0],$fetch[1],number_format($fetch[2],0));
	  }
	  $data[] = array($data1,$data2);
	  mysql_free_result($query);
	 $query = "SELECT prodID, getProdName(prodID), prodCost/prodQuantity, prodQuantity, prodCost,remainStock,unit,getMeasurement(unit) FROM po_product_list WHERE purcID='".$_GET['purcID']."'";
	 //echo $query;
	 $query=mysql_query($query);
	 while($so=mysql_fetch_array($query)){
		/*if($so[6]!='' && $so[6]!=NULL)
			if(number_format(substr($so[3],strpos($so[3],".")+1)) == 0){
				//echo $so[8].') / ('.$so[7].' / '.$so[3].')'.'';	
				//echo ($so[8]/($so[7]/$so[3]));
				$so[3] = (int)($so[8]/($so[7]/$so[3]));
			}*/
		$data[]=array($so[0],$so[1],number_format($so[2],2),number_format($so[3],2),number_format($so[4],2),number_format($so[5],2),$so[6],$so[7]);
	 }	
	 mysql_free_result($query);
	 $data[] = array('purchase','purchase','purchase','purchase','purchase','purchase','purchase','purchase','purchase','purchase','purchase');
	 if(isset($_GET['account'])){
		$query = "SELECT purcCost,getAccName(purcAssign) FROM purchase WHERE purcID='".$_GET['purcID']."'";
		$query=mysql_query($query);
		$so=mysql_fetch_array($query);
		$data[] = array(number_format($so[0],2),$so[1]);
		mysql_free_result($query);
	 }
  }
  else if(isset($_GET['purcID'])){
	 $query = "SELECT unit,prodID, getProdName(prodID), (prodCost-prodFreight)/prodQuantity, prodQuantity, prodFreight, prodCost FROM po_product_list WHERE purcID='".$_GET['purcID']."'";
	 $total = $qty = $prod = 0;
	 $query=mysql_query($query) or die(mysql_error());
	 while($po=mysql_fetch_array($query)){
		$prod +=1;
		$qty +=  $po[4];
		$total += $po[6];
		$data[]=array($po[0],$po[1],$po[2],number_format($po[3],2),number_format($po[4],2),number_format($po[5],2),number_format($po[6],2));
	 }
	 $query = "SELECT purcDateTime, getAccName(purcAssign),suppName,suppAddress,purcNote,purcCharges,purcInvoiceDate FROM purchase WHERE purcID='".$_GET['purcID']."' LIMIT 0,1";
	 $query=mysql_query($query);
	 $po=mysql_fetch_array($query);
	 $date=date_create($po[0]);
	 $po[0] = date_format($date,"M d, o h:i A");
	 $date=date_create($po[6]);
	 $po[6] = date_format($date,"M d, o");
	 $data[]=array(number_format($total,2),number_format($qty,2),number_format($prod,0),$po[0],$po[1],$po[2],$po[3],$po[4],$po[5],$po[6]);
	 mysql_free_result($query);
 }
 else {
 	$term=$_GET["term"];
   	$query=mysql_query("SELECT purcID FROM purchase WHERE purcID like '%".$term."%' order by purcID LIMIT 0,5");
	  while($so=mysql_fetch_array($query)){
		 $data[]=array(
		  'value'=> $so['purcID'],
		  'label'=> $so['purcID']
		 );
	  }
	mysql_free_result($query);
 }
 echo json_encode($data);
 ?>