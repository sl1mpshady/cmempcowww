<?php
 require_once('../Connections/connection.php');	
 mysql_connect($db_hostname,$db_username,$db_password);
 mysql_select_db($db_database);
 $data=array();
 if(isset($_GET['prodID'])){
 	include('../class/class.php');
	isset($_GET['type']) ? $product = new Product($_GET['prodID'],true) : $product = new Product($_GET['prodID']);
	if(isset($_GET['type']))
		$data = array($product->prodName,$product->prodCategory,$product->prodID,$product->prodStock,$product->prodPrice,$product->prodStatus,$product->prodReorderQty,$product->prodMaximumQty,$product->prodLeadtime,$product->prodMeasurement,$product->prodMeasurement1);
	else
		$data = array($product->prodPrice,$product->prodName, $product->prodStock,$product->discount,$product->sub_prodID,$product->sub_prodQty,$product->sub_prodName,$product->prodMeasurement,$product->unit);
 }
 else if(isset($_GET['home'])){
	 $query = "SELECT *, getProdCategory(prodID) as Cat,getProdName(prodID) as prodName,NOW() as now FROM product WHERE prodDelete='False' AND prodOHQuantity <= prodReorderQty order by Cat";
	 $query=mysql_query($query);
	 $qty = $tot = 0;
	 $date = '';
	 while($product=mysql_fetch_array($query)){
		$data[]=array($product['Cat'],$product['prodID'],$product['prodDesc'],number_format($product['prodOHQuantity'],0),number_format($product['prodPrice'],2),number_format($product['prodPrice']*$product['prodOHQuantity'],2));
		$qty += $product['prodOHQuantity'];
		$tot += $product['prodPrice']*$product['prodOHQuantity'];
		$date = $product['now'];
	 }
	 $data[]=array(number_format($qty,0),number_format($tot,2),$date);

	 mysql_free_result($query);
 }
 else{
	 if(isset($_GET['catID'])){
		 $query = $_GET['catID'] == '0' ? "SELECT *, getProdCategory(prodID) as Cat,getProdName(prodID) as prodName FROM product WHERE prodDelete='False' order by Cat" : "SELECT *, getProdCategory(prodID) as Cat,getProdName(prodID) as prodName FROM product WHERE prodCategory='".$_GET['catID']."' AND prodDelete='False' order by Cat";
		 $query=mysql_query($query);
		 $qty = $tot = 0;
		 while($product=mysql_fetch_array($query)){
		 	$data[]=array($product['Cat'],$product['prodID'],$product['prodDesc'],number_format($product['prodOHQuantity'],0),number_format($product['prodPrice'],2),number_format($product['prodPrice']*$product['prodOHQuantity'],2));
			$qty += $product['prodOHQuantity'];
			$tot += $product['prodPrice']*$product['prodOHQuantity'];
		 }
		 mysql_free_result($query);
		$query=mysql_query("SELECT NOW()");
		$fetch = mysql_fetch_array($query);
		 $data[]=array(number_format($qty,0),number_format($tot,2),$fetch[0]);
	 }
	 else{
		 $term=$_GET["term"];
		 $query = (isset($_GET['purchase'])) ? mysql_query("SELECT * FROM product where prodID like '%".$term."%' and prodDelete='False' order by prodID LIMIT 0,5") : mysql_query("SELECT * FROM product where prodID like '%".$term."%' and prodStatus='Active' and prodDelete='False' order by prodID LIMIT 0,5");
			while($product=mysql_fetch_array($query)){
				 $data[]=array(
							'value'=> $product["prodID"],
							'label'=>$product["prodID"]
								);
			}
	 }
	mysql_free_result($query);
 }
 echo json_encode($data);
?>