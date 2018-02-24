<?php
require_once(dirname(__FILE__) . "/Escpos.php");
class item {
		private $name;
		private $price;
		private $dollarSign;
	
		public function __construct($name = '', $price = '', $dollarSign = false) {
			$this -> name = $name;
			$this -> price = $price;
			$this -> dollarSign = $dollarSign;
		}
		
		public function __toString() {
			$rightCols = 10;
			$leftCols = 38;
			/*if($this -> dollarSign) {
				$leftCols = $leftCols / 2 - $rightCols / 2;
			}*/
			$left = str_pad($this -> name, $leftCols) ;
			
			$sign = ($this -> dollarSign ? 'P ' : '');
			$right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
			return "$left$right\n";
		}
	}
try {
	include('../class/class.php');
	require_once('../Connections/connection.php');
	$general = new General(true);
	$connector = new WindowsPrintConnector("Receipt");
	$printer = new Escpos($connector);
	$printer -> setJustification(Escpos::JUSTIFY_CENTER); 
	$items = array();
	$printer -> selectPrintMode(Escpos::MODE_DOUBLE_WIDTH);
	$printer -> setEmphasis(true);
	$printer -> text($general->busName."\n");
	$printer -> setEmphasis(false);
	$printer -> selectPrintMode();
	if($general->address!='')
		$printer -> text($general->address."\n");
	if($general->regNum!='')
		$printer -> text($general->regNum."\n");
	if($general->contact!='')
		$printer -> text($general->contact."\n");
	$printer -> feed();
	$printer -> setEmphasis(true);
	if($dupl==true)
		$printer -> text("** CUSTOMER'S COPY **\n");
	$printer -> text("Sales Order No. ".$_GET['saleID']);
	$printer -> setEmphasis(false);
	$printer -> feed();
	$printer -> setJustification();
	$printer -> feed();
	mysql_connect($db_hostname,$db_username,$db_password);
	mysql_select_db($db_database);
	$saleID = $_GET['saleID'];
	$query1=mysql_query("SELECT getAccName(saleAssign) as server,saleNetAmount,getCustName(saleCustomer) as customer,saleEntrusted, DATE_FORMAT(saleDateTime, '%b %e, %Y %h:%i %p') as saleDateTime,saleType,saleCash, getCustCreditLedger(saleCustomer,saleDateTime) as credit11, salePanel FROM sales_order WHERE saleID='$saleID' LIMIT 0,1");
	$sales_order=mysql_fetch_array($query1);
	$printer -> text("Served By: ".$sales_order['server']."\n");
	$printer -> text("Served To: ".$sales_order['customer']."\n");
	if($general->askEntrusted=='true' && $sales_order['saleType'] == 'Credit')
		$printer -> text("Entrusted To: ".$sales_order['saleEntrusted']."\n");
	$type = $sales_order['saleType'];
	if(($general->cashDuplicate=='true' && $sales_order['saleType']=='Cash') || ($general->creditDuplicate=='true' && $sales_order['saleType']=='Credit'))
		$type='None';
	$printer -> text("Type: ".$sales_order['saleType']."\n");
	$printer -> feed();
	$printer -> text("------------------------------------------------\n");
	$printer -> text(new item('Item', 'Total'));
	$printer -> text("------------------------------------------------\n");
	$qty = 0;
	$count = 0;
	 $query=mysql_query("SELECT getProdName(prodID) as prodName,netPrice/prodQty as prodPrice, prodQty, netPrice FROM so_product_list where saleID='$saleID'");
		while($product=mysql_fetch_array($query)){
			 array_push($items,new item($product['prodName'], ''),new item('@ '.number_format($product['prodPrice'],2).' x'.$product['prodQty'], number_format($product['netPrice'],2)));
			 $qty += $product["prodQty"];
		}
	mysql_free_result($query);
	mysql_free_result($query1);
	foreach($items as $item) {
		$printer -> text($item);
	}
	$general->salesTax = $general->salesTax * 0.01;
	$printer -> feed();
	$printer -> text("Total of ".$qty." item(s)\n");
	$printer -> text(new item('Vatable Sales', number_format($sales_order['saleNetAmount']-$sales_order['saleNetAmount']*$general->salesTax,2)));
	$printer -> text(new item('VAT '.number_format($general->salesTax*100,2).'%', number_format($sales_order['saleNetAmount']*$general->salesTax,2)));
	$printer -> text(new item('Total Sale', number_format($sales_order['saleNetAmount'],2)));
	if($sales_order['saleType']=='Cash'){
		$printer -> text(new item('Cash', number_format($sales_order['saleCash'],2)));
		$change = $sales_order['saleType']=='Cash' ? number_format($sales_order['saleCash']-$sales_order['saleNetAmount'],2) : '0.00';
		$printer -> text(new item('Change', $change));
	}
	else {
		if($sales_order['saleType']=='Panel')
			$printer -> text(new item('Amount Given', $sales_order['salePanel']));
		$printer -> text(new item("Customer's Credit", number_format($sales_order['credit11'],2)));
		
	}
	
	$printer -> text('Date & Time '.$sales_order['saleDateTime']."\n");
	$printer -> feed();
	if($general->noteAlign == center)
		$printer ->setJustification(Escpos::JUSTIFY_CENTER);
	else if($general->noteAlign == right)
		$printer ->setJustification(Escpos::JUSTIFY_RIGHT);
	else
		$printer ->setJustification();
	if($general->note!=''){
		$printer -> text($general->note."\n");
		$printer -> feed();
	}
	if($general->footAlign == center)
		$printer ->setJustification(Escpos::JUSTIFY_CENTER);
	else if($general->footAlign == right)
		$printer ->setJustification(Escpos::JUSTIFY_RIGHT);
	else
		$printer ->setJustification();
	if($general->foot!=''){
		$printer -> text($general->foot."\n");
	}
	$printer -> feed(3);
	
	$printer -> cut();
	//$printer -> pulse();

	$printer -> close();
	if(($general->cashDuplicate=='true' && $sales_order['saleType']=='Cash') || ($general->creditDuplicate=='true' && $sales_order['saleType']=='Credit')){
		$general = new General(true);
		$connector = new WindowsPrintConnector("Receipt");
		$printer = new Escpos($connector);
		$printer -> setJustification(Escpos::JUSTIFY_CENTER); 
		$items = array();
		$printer -> selectPrintMode(Escpos::MODE_DOUBLE_WIDTH);
		$printer -> setEmphasis(true);
		$printer -> text($general->busName."\n");
		$printer -> setEmphasis(false);
		$printer -> selectPrintMode();
		if($general->address!='')
			$printer -> text($general->address."\n");
		if($general->regNum!='')
			$printer -> text($general->regNum."\n");
		if($general->contact!='')
			$printer -> text($general->contact."\n");
		$printer -> feed();
		$printer -> setEmphasis(true);
		$printer -> text("** CUSTOMER'S COPY **\n");
		$printer -> text("Sales Order No. ".$_GET['saleID']);
		$printer -> setEmphasis(false);
		$printer -> feed();
		$printer -> setJustification();
		$printer -> feed();
		mysql_connect($db_hostname,$db_username,$db_password);
		mysql_select_db($db_database);
		$saleID = $_GET['saleID'];
		$query1=mysql_query("SELECT getAccName(saleAssign) as server,saleNetAmount,getCustName(saleCustomer) as customer,saleEntrusted, DATE_FORMAT(saleDateTime, '%b %e, %Y %h:%i %p') as saleDateTime,saleType,saleCash, getCustCreditLedger(saleCustomer,saleDateTime) as credit11, salePanel FROM sales_order WHERE saleID='$saleID' LIMIT 0,1");
		$sales_order=mysql_fetch_array($query1);
		$printer -> text("Served By: ".$sales_order['server']."\n");
		$printer -> text("Served To: ".$sales_order['customer']."\n");
		if($general->askEntrusted=='true' && $sales_order['saleType'] == 'Credit')
			$printer -> text("Entrusted To: ".$sales_order['saleEntrusted']."\n");
		$type = $sales_order['saleType'];
		if(($general->cashDuplicate=='true' && $sales_order['saleType']=='Cash') || ($general->creditDuplicate=='true' && $sales_order['saleType']=='Credit'))
			$type='None';
		$printer -> text("Type: ".$sales_order['saleType']."\n");
		$printer -> feed();
		$printer -> text("------------------------------------------------\n");
		$printer -> text(new item('Item', 'Total'));
		$printer -> text("------------------------------------------------\n");
		 $qty = 0;
		 $query=mysql_query("SELECT getProdName(prodID) as prodName,netPrice/prodQty as prodPrice, prodQty, netPrice FROM so_product_list where saleID='$saleID'");
			while($product=mysql_fetch_array($query)){
				 array_push($items,new item($product['prodName'], ''),new item('@ '.number_format($product['prodPrice'],2).' x'.$product['prodQty'], number_format($product['netPrice'],2)));
				 $qty += $product["prodQty"];
			}
		mysql_free_result($query);
		mysql_free_result($query1);
		foreach($items as $item) {
			$printer -> text($item);
		}
		$general->salesTax = $general->salesTax * 0.01;
		$printer -> feed();
		$printer -> text("Total of ".$qty." item(s)\n");
		$printer -> text(new item('Vatable Sales', number_format($sales_order['saleNetAmount']-$sales_order['saleNetAmount']*$general->salesTax,2)));
		$printer -> text(new item('VAT '.number_format($general->salesTax*100,2).'%', number_format($sales_order['saleNetAmount']*$general->salesTax,2)));
		$printer -> text(new item('Total Sale', number_format($sales_order['saleNetAmount'],2)));
		if($sales_order['saleType']=='Cash'){
			$printer -> text(new item('Cash', number_format($sales_order['saleCash'],2)));
			$change = $sales_order['saleType']=='Cash' ? number_format($sales_order['saleCash']-$sales_order['saleNetAmount'],2) : '0.00';
			$printer -> text(new item('Change', $change));
		}
		else {
			if($sales_order['saleType']=='Panel')
				$printer -> text(new item('Amount Given', $sales_order['salePanel']));
			$printer -> text(new item("Customer's Credit", number_format($sales_order['credit11'],2)));
		}
		
		$printer -> text('Date & Time '.$sales_order['saleDateTime']."\n");
		$printer -> feed();
		if($general->noteAlign == center)
			$printer ->setJustification(Escpos::JUSTIFY_CENTER);
		else if($general->noteAlign == right)
			$printer ->setJustification(Escpos::JUSTIFY_RIGHT);
		else
			$printer ->setJustification();
		if($general->note!=''){
			$printer -> text($general->note."\n");
			$printer -> feed();
		}
		if($general->footAlign == center)
			$printer ->setJustification(Escpos::JUSTIFY_CENTER);
		else if($general->footAlign == right)
			$printer ->setJustification(Escpos::JUSTIFY_RIGHT);
		else
			$printer ->setJustification();
		if($general->foot!=''){
			$printer -> text($general->foot."\n");
		}
		$printer -> feed(3);
		
		$printer -> cut();
		//$printer -> pulse();
	
		$printer -> close();
	}
	
} catch(Exception $e) {
	echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}

?>
