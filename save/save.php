<?php
if (!isset($_SESSION)) {
  session_start();
}

require('../class/class.php');
if(isset($_GET['temp_list'])){
	if(isset($_GET['purchase'])){
		if($_GET['temp_list'] == 'DELETE')
			new temp_list($_GET["prodID"],'','','',$_GET["accID"],$_GET['temp_list'],'purchase');
		else
			new temp_list($_GET['prodID'],$_GET['prodQty'],$_GET['cost'],'',$_GET['accID'],$_GET['temp_list'],'purchase',$_GET['unit'],$_GET['freight']);
	}
	else if(isset($_GET['payroll'])){
		new Deduction($_GET['custID'],$_GET['grossCredit'],$_GET['deduction'],$_GET['netCredit'],$_GET['accID'],$_GET['temp_list']);
	}
	else {
		if($_GET['temp_list'] == 'DELETE')
			new temp_list($_GET["prodID"],'','','',$_GET["accID"],$_GET['temp_list'],NULL,$_GET['unit']);
		else
			new temp_list($_GET["prodID"],$_GET["prodQty"],$_GET["grossPrice"],$_GET["netPrice"],$_GET["accID"],$_GET['temp_list'],NULL,$_GET['unit'],$_GET['conversion']);
	}
}
else if(isset($_POST['measID']) && isset($_POST['type'])){
	$measurement = new Measurement;
	if($_POST['type'] == 'INSERT')
		return $measurement->New_($_POST['measID'],$_POST['measDesc'],$_POST['measMeasurement']);
	else if($_POST['type'] == 'UPDATE')
		return $measurement->update($_POST['measU'],$_POST['measID'],$_POST['measDesc'],$_POST['measMeasurement']);
}
else if(isset($_POST['data'])){
	$conversion = new Database;
	$data=array();

	$myData = json_decode($_POST['data']);
	foreach ($myData as $obj) {
		$conversion->connect("INSERT INTO alternate_of_measure VALUES ('".$_POST['prodID']."','".$obj->measID."','".$obj->conversion."')");
	}
	$data[]=array(true);
	echo json_encode($data);
}
else if(isset($_GET['save']) && isset($_GET['payroll'])){
	$deduction =  new Deduction;
	$deduction->_New($_GET['total'],$_GET['customerCredit'],$_GET['customer'],$_GET['accID']);
	echo json_encode($deduction->dedID);
}
else if(isset($_GET['accID']) && isset($_GET['update']) && isset($_GET['delete']) && isset($_GET['loc'])){
	$account = new Database;
	$account->connect("UPDATE account SET accStatus='Delete' WHERE accID='".$_GET['accID']."'");
}
else if(isset($_GET['account']) && isset($_GET['update']) && isset($_GET['edit'])){
	$values=' ';
	isset($_GET['accName']) ? $values.='accName="'.$_GET['accName'].'", ' : '';
	isset($_GET['accPassword']) && $_GET['accPassword']!='' ? $values.='accPassword="'.$_GET['accPassword'].'", ' : '';
	$col = array('addProduct','editProduct','deleteProduct','addPurchase','editPurchase','deletePurchase','addCustomer','editCustomer','deleteCustomer','addCategory','editCategory','deleteCategory','addAccount','editAccount','deleteAccount','acceptReturn','acceptPayment');
	for($i=0;$i<count($col);$i++){
		$values .= $col[$i]."='".ucfirst($_GET[$col[$i]])."', ";
	}
	$values = substr_replace( $values, "", -2 );
	$values .=" WHERE accUsername='".$_GET['accUsername']."'";
	//echo "UPDATE account SET $values";
	$customer = new Database;
	//echo "UPDATE account SET ".$values;
	$query = $customer->connect("UPDATE account SET ".$values);
	return $query;
}
else if(isset($_GET['fname']) && isset($_GET['new_'])){
		$customer = new Customer;
		$query = $customer->register($_GET['custID'],$_GET['fname'],$_GET['mname'],$_GET['lname'],$_GET['address'],$_GET['bdate'],$_GET['gender'],$_GET['baddress'],$_GET['mobile'],$_GET['civil_status'],$_GET['photo'],$_GET['elem'],$_GET['hs'],$_GET['coll'],$_GET['voc'],$_GET['others'],$_GET['designation'],$_GET['section'],$_GET['dept'],$_GET['accType'],$_GET['limit'],$_GET['expDate']);
		$data[]=array(true);
		echo json_encode($data);
}
else if(isset($_GET['custID']) && isset($_GET['update']) && isset($_GET['delete'])){
	$customer = new Database;
	$customer->connect("UPDATE customer SET custDelete='True' WHERE custID='".$_GET['custID']."'");
}
else if(isset($_GET['save'])){
	$so = new sales_order($_GET['custID'],$_GET['net'],$_GET['gross'],$_GET['type'],$_GET['accID'],$_GET['due'],$_GET['cash'],$_GET['entrusted'],$_GET['credit']);
	$saleID = $so->saleID;
	$data=array();
	$data[]=array($saleID);
	echo json_encode($data);
}
/*else if(isset($_GET['save'])){
	$so = new sales_order($_GET['custID'],$_GET['net'],$_GET['gross'],$_GET['type'],$_GET['accID'],$_GET['due']);
	$saleID = $so->saleID;
	$date = $so->saleDateTime;
	 mysql_connect("localhost","root","");
	 mysql_select_db("sims2");
	 $data=array();
	 $data[]=array($saleID,$date);
	 $query=mysql_query("SELECT *, getProdCategory(prodID) as Cat,getProdName(prodID) as prodName FROM so_product_list where saleID='$saleID'");
		while($product=mysql_fetch_array($query)){
			 $data[]=array($product["Cat"],$product["prodName"],$product["prodQty"],$product["grossPrice"]/$product["prodQty"],$product["grossPrice"],$product["netPrice"],number_format($product["grossPrice"]-$product["netPrice"],2));
		}
	mysql_free_result($query);
	echo json_encode($data);
}*/
else if(isset($_POST['prodID']) && isset($_POST['delete']) && isset($_POST['loc']) && isset($_POST['unit'])){
	$product = new Product();
	if($product->delete_temp_list($_POST['prodID'],$_POST['loc'],$_POST['unit']))
		echo true;
	else
		echo false;
}
else if(isset($_GET['prodID'])){
	$data=array();
	$product = new Product();
	if(isset($_GET['update'])){
		if(isset($_GET['delete']))
			$product->delete($_GET['prodID'],$_GET['loc']);
		else
			$product->update($_GET['oldID'],$_GET['prodID'],$_GET['prodName'],$_GET['prodMeasurement'],$_GET['prodStock'],$_GET['prodPrice'],$_GET['prodStatus'],$_GET['reorderQty'],$_GET['maxQuantity'],$_GET['leadtime']);
	}
	else
		$product->register($_GET['prodID'],$_GET['prodName'],$_GET['prodMeasurement'],$_GET['prodStock'],$_GET['prodPrice'],$_GET['prodStatus'],$_GET['reorderQty'],$_GET['maxQuantity'],$_GET['leadtime']);
	$data = array(true);
	echo json_encode($data);
}
else if(isset($_GET['catID'])){
	if(isset($_GET['delete'])){
		$category = new Category;
		$query = $category->delete($_GET['catID']);
		return $query;
	}
	else
		new Category($_GET['catID'],$_GET['type'],$_GET['catName'],$_GET['catDiscount'],$_GET['catStatus'],$_GET['catMeasurement'],$_GET['catSubProd']);
	//echo $a;
	//echo true;
}
else if(isset($_GET['payReceive'])){
	if(isset($_GET['main'])){
		$so = new sales_order();
		$so->payment($_GET['custID'],$_GET['payReceive'],$_GET['change'],$_GET['accID']);
		$payID = $so->payID;
		$payDateTime = $so->payDateTime;
		$data=array();
		require_once('../Connections/connection.php');	
		mysql_connect($db_hostname,$db_username,$db_password);
		mysql_select_db($db_database);
		$query=mysql_query("SELECT custID, CONCAT(custFName,' ',custMName,' ',custLName), custCredit FROM customer WHERE custID='".$_GET['custID']."' LIMIT 0,1");
		$customer=mysql_fetch_array($query);
		$data[]=array($payID,$payDateTime,$customer[0],$customer[1],number_format($customer[2],2));
		mysql_free_result($query);
		$net = 0;
		//echo "SELECT s.saleID, s.saleDateTime, s.saleNetAmount, p.payReceive, p.saleBalance FROM sales_order s INNER JOIN payment_so_list p ON p.saleID=s.saleID WHERE p.payID='$payID'";
		$query=mysql_query("SELECT s.saleID, s.saleDateTime, s.saleNetAmount, p.payReceive, p.saleBalance FROM sales_order s INNER JOIN payment_so_list p ON p.saleID=s.saleID WHERE p.payID='$payID'");
		while($product=mysql_fetch_array($query)){
				$net += $product[2];
				$data[]=array($product[0],$product[1],number_format($product[2],2),number_format($product[3],2),number_format($product[4],2));
		}
		mysql_free_result($query);
		$data[]=array(number_format($net,2),number_format($_GET['payReceive'],2),$payID);
		echo json_encode($data);
	}else{
		$payment = new payment;	
		$payment->temp_list($_GET['saleID'],$_GET['payReceive'],$_GET['saleBalance'],$_GET['accID']);
	}
}
else if(isset($_GET['purcCost'])){
	$po = new Database;
	$po->connect("INSERT INTO purchase VALUES(null,'".$_GET['purcCost']."','".$_GET['qty']."',null,'".$_GET['accID']."','False','".session_id()."','".$_GET['suppName']."','".$_GET['suppAddress']."','".$_GET['note']."','".$_GET['charges']."','".$_GET['date']."','".$_GET['invoiceNo']."',0)");
	$query = $po->connect("SELECT getPurcID('".$_GET['purcCost']."','".$_GET['qty']."','".$_GET['accID']."','".session_id()."') as purcID");
	
	$fetch = mysql_fetch_array($query);
	$data=array();
	$data[]=array($fetch[0]);
	$po->__close();
	echo json_encode($data);
}
else if(isset($_GET['general'])){
	$values = ' ';
	isset($_GET['busName']) && $_GET['busName'] != '' ? $values.=" busName='".$_GET['busName']."', " : '';
	isset($_GET['regNum']) ? $values.=" regNum='".$_GET['regNum']."', " : '';
	isset($_GET['address']) ? $values.=" address='".$_GET['address']."', " : '';
	isset($_GET['contact']) ? $values.=" contact='".$_GET['contact']."', " : '';
	isset($_GET['logo']) ? $values.=" logo='".$_GET['logo']."', " : '';
	isset($_GET['salesTax']) ? $values.=" salesTax='".$_GET['salesTax']."', " : '';
	isset($_GET['printReceipt']) ? $values.=" printReceipt='".$_GET['printReceipt']."', " : '';
	isset($_GET['cashDuplicate']) ? $values.=" cashDuplicate='".$_GET['cashDuplicate']."', " : '';
	isset($_GET['creditDuplicate']) ? $values.=" creditDuplicate='".$_GET['creditDuplicate']."', " : '';
	isset($_GET['entrusted']) ? $values.=" askEntrusted='".$_GET['entrusted']."', " : '';
	isset($_GET['note']) ? $values.=" note='".$_GET['note']."', " : '';
	isset($_GET['noteAlign']) ? $values.=" noteAlign='".$_GET['noteAlign']."', " : '';
	isset($_GET['noteFontStyle']) ? $values.=" noteFontStyle='".$_GET['noteFontStyle']."', " : '';
	isset($_GET['foot']) ? $values.=" foot='".$_GET['foot']."', " : '';
	isset($_GET['footAlign']) ? $values.=" footAlign='".$_GET['footAlign']."', " : '';
	isset($_GET['footFontStyle']) ? $values.=" footFontStyle='".$_GET['footFontStyle']."', " : '';
	isset($_GET['salesReturn']) ? $values.=" salesReturn='".$_GET['salesReturn']."', " : '';
	isset($_GET['president']) && $_GET['president'] != ''? $values.=" president='".$_GET['president']."', " : '';
	isset($_GET['manager']) && $_GET['manager'] != ''? $values.=" manager='".$_GET['manager']."', " : '';
	isset($_GET['operationManager']) && $_GET['operationManager'] != ''? $values.=" operationManager='".$_GET['operationManager']."', " : '';
	$values = substr_replace( $values, "", -2 );
	new General('UPDATE',"UPDATE general_ SET ".$values);
}
else if(isset($_GET['UPDATE'])){
	$values = ' ';
	isset($_GET['fname']) && $_GET['fname'] != '' ? $values.=" custFName='".$_GET['fname']."', " : '';
	isset($_GET['mname']) && $_GET['mname'] != '' ? $values.=" custMName='".$_GET['mname']."', " : '';
	isset($_GET['lname']) && $_GET['lname'] != '' ? $values.=" custLName='".$_GET['lname']."', " : '';
	isset($_GET['addr']) && $_GET['addr'] != '' ? $values.=" custAddress='".$_GET['addr']."', " : '';
	isset($_GET['bdate']) && $_GET['bdate'] != '' ? $values.=" custBDate='".$_GET['bdate']."', " : '';
	isset($_GET['gender']) && $_GET['gender'] != '' ? $values.=" custGender='".$_GET['gender']."', " : '';
	isset($_GET['mobile']) && $_GET['mobile'] != '' ? $values.= " custContactNo='".$_GET['mobile']."', " : '';
	isset($_GET['civil_status']) && $_GET['civil_status'] != '' ? $values.=" custCivilStatus='".$_GET['civil_status']."', " : '';
	isset($_GET['discount']) && $_GET['discount'] != ''? $values.=" custDiscount='".$_GET['discount']."', " : '';
	isset($_GET['limit']) && $_GET['limit'] != ''  ? $values.=" custLimit='".$_GET['limit']."', " : '';
	isset($_GET['baddr']) && $_GET['baddr'] != ''  ? $values.=" custBirthPlace='".$_GET['baddr']."', " : '';
	isset($_GET['others']) && $_GET['others'] != ''  ? $values.=" custOthers='".$_GET['others']."', " : '';
	isset($_GET['elem']) ? $values.=" custElem='".$_GET['elem']."', ": '' ;
	isset($_GET['hs']) ? $values.=" custHS='".$_GET['hs']."', ": '' ;
	isset($_GET['coll']) ? $values.=" custCol='".$_GET['coll']."', ": '' ;
	isset($_GET['voc']) ? $values.=" custVoc='".$_GET['voc']."', ":'' ;
	isset($_GET['designation']) && $_GET['designation']!='' ? $values.=" custDesignation='".$_GET['designation']."', ":'' ;
	isset($_GET['section']) && $_GET['section']!='' ? $values.=" custSection='".$_GET['section']."', ":'' ;
	isset($_GET['department']) && $_GET['department']!='' ? $values.=" custDept='".$_GET['department']."', ":'' ;
	isset($_GET['delete']) && $_GET['delete']!='' ? $values.=" custDelete='".$_GET['delete']."', ":'' ;
	isset($_GET['custAccType']) && $_GET['custAccType']!='' ? $values.=" custAccType='".$_GET['custAccType']."', ":'' ;
	isset($_GET['accID1']) && $_GET['accID1']!='' ? $values.=" custID='".$_GET['accID1']."', ":'' ;
	isset($_GET['expirey']) && $_GET['expirey']!='' ? $values.=" custExpire='".$_GET['expirey']."', ":'' ;
	
	$values = substr_replace( $values, "", -2 );
	$values .= " WHERE custID='".$_GET['custID']."'";
	
	new Customer($_GET['custID'],"UPDATE customer SET ".$values,'UPDATE');
}

else if(isset($_GET['account'])){
	$account = new Account;
	$account->connect("INSERT INTO account VALUES(null,'".$_GET['accUsername']."','".$_GET['accPassword']."','".$_GET['accName']."','".$_GET['accID']."',null,'".$_GET['addProduct']."','".$_GET['editProduct']."','".$_GET['deleteProduct']."','".$_GET['addPurchase']."','".$_GET['editPurchase']."','".$_GET['deletePurchase']."','".$_GET['addCustomer']."','".$_GET['editCustomer']."','".$_GET['deleteCustomer']."','".$_GET['addCategory']."','".$_GET['editCategory']."','".$_GET['deleteCategory']."','".$_GET['addAccount']."','".$_GET['editAccount']."','".$_GET['deleteAccount']."','".$_GET['acceptReturn']."','".$_GET['acceptPayment']."','Active')");
}
?>