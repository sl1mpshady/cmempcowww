<?php
require_once('../Connections/connection.php');
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

class Database {
	private $data = array();
	var $query;
	public function connect($query){
		$this->query=mysql_query($query) or die(mysql_error());
		return $this->query;
	}
	public function __set($variable, $value){
		$this->data[$variable] = $value;
	}
	public function __get($variable){
        if(isset($this->data[$variable])){
            return $this->data[$variable];
        }else{
            return false;
        }
    }
	public function __close(){
		mysql_free_result($this->query);
	}
}
class Deduction extends Database {
	public function __construct($custID=NULL,$grossCredit=NULL,$deduction=NULL,$netCredit=NULL,$accID=NULL,$type=NULL){
		if($type=='INSERT')
			$query = $this->connect("INSERT INTO pd_temp_list VALUES ('$custID','$grossCredit','$deduction','$netCredit','$accID','".session_id()."')");
		else if($type=='UPDATE')
			$query = $this->connect("UPDATE pd_temp_list SET deduction='$deduction',netCredit='$netCredit' WHERE custID='$custID' AND grossCredit='$grossCredit' AND accID='$accID' AND sessionID='".session_id()."'");
		else if($type=='DELETE')
			$query = $this->connect("DELETE FROM pd_temp_list WHERE custID='$custID' AND accID='$accID' AND sessionID='".session_id()."'");
	}
	public function _New($totalDeduction,$totalCredit,$customers,$accID){
		$this->connect("INSERT INTO deduction VALUES(null,'$totalDeduction','$totalCredit','$customers',null,'$accID','".session_id()."')");
		$query = $this->connect("SELECT dedID FROM deduction WHERE dedTotalDeduction='$totalDeduction' AND dedTotalCustomerCredit='$totalCredit' AND dedCustomers='$customers' AND dedAssign='$accID' AND sessionID='".session_id()."' ORDER BY dedID DESC LIMIT 0,1");
		$fetch = mysql_fetch_array($query);
		$this->__set('dedID',$fetch[0]);
		$this->__close();
	}
}
class General extends Database {
	public function __construct($fetch=NULL,$sql=NULL){
		if($fetch=='SELECT'){
			$query = $this->connect("SELECT busName,regNum,address,contact,salesTax,askEntrusted,printReceipt,cashDuplicate,creditDuplicate,note,noteAlign,noteFontStyle,foot,footAlign,footFontStyle,salesReturn,president,operationManager,manager,panel FROM general_ LIMIT 0,1");
			$num = mysql_num_rows($query);
			 if($num == 0){
				$this->__close();;
				return false;
			 }
			 else{
				$general = mysql_fetch_array($query);
				$this->__set('busName',$general['busName']);
				$this->__set('regNum',$general['regNum']);
				$this->__set('address',$general['address']);
				$this->__set('contact',$general['contact']);
				$this->__set('salesTax',$general['salesTax']);
				$this->__set('askEntrusted',$general['askEntrusted']);
				$this->__set('printReceipt',$general['printReceipt']);
				$this->__set('cashDuplicate',$general['cashDuplicate']);
				$this->__set('creditDuplicate',$general['creditDuplicate']);
				$this->__set('note',$general['note']);
				$this->__set('noteAlign',$general['noteAlign']);
				$this->__set('noteFontStyle',$general['noteFontStyle']);
				$this->__set('foot',$general['foot']);
				$this->__set('footAlign',$general['footAlign']);
				$this->__set('footFontStyle',$general['footFontStyle']);
				$this->__set('salesReturn',$general['salesReturn']);
				$this->__set('manager',$general['manager']);
				$this->__set('operationManager',$general['operationManager']);
				$this->__set('president',$general['president']);
				$this->__set('panel',$general['panel']);
				$this->__close();
			 }
		}
		else{
			return $this->connect($sql);
		}
	}
	public function update($sql){
		
	}
}
class _Return extends Database {
	public function __construct($retID=NULL, $sql=NULL, $type=NULL){
		return true;
	}	
	public function new_($retType,$retSubject,$retCost,$retQty,$retGood,$retBad,$retAssign,$note,$type1){
		if($retType=='PO')
			$type1 = NULL;
		$this->connect("INSERT INTO _return VALUES(null,'$retType','$retSubject','$retCost','$retQty','$retGood','$retBad','$retAssign',null,'".session_id()."','$note','$type1')");
		$this->connect("UPDATE sales_order SET saleNetAmount=0 WHERE saleNetAmount<0");
		$query = $this->connect("SELECT retID FROM _return WHERE sessionID='".session_id()."' AND retType='$retType' AND retSubject='$retSubject' AND retCost='$retCost' AND retQty='$retQty' AND retGood='$retGood' AND retBad='$retBad' AND retAssign='$retAssign' ORDER BY retID DESC LIMIT 0,1");
		$fetch = mysql_fetch_array($query);
		$id = $fetch[0];
		$this->__close();
		return $id;
	}
	public function type1($retID,$retCost,$retQty,$retAssign,$retType){
		if($retType=='RP'){
			$this->connect("INSERT INTO replacement VALUES(null,'$retID','$retQty','$retCost','$retAssign','".session_id()."')");
			$query = $this->connect("SELECT repID FROM replacement WHERE sessionID='".session_id()."' AND repCost='$retCost' AND repQty='$retQty' AND repAssign='$retAssign' ORDER BY repID DESC LIMIT 0,1");
		}
		else {
			$this->connect("INSERT INTO refund VALUES(null,'$retID','$retQty','$retCost','$retAssign','".session_id()."')");
			$query = $this->connect("SELECT refID FROM refund WHERE sessionID='".session_id()."' AND refCost='$retCost' AND refQty='$retQty' AND refAssign='$retAssign' ORDER BY refID DESC LIMIT 0,1");	
		}
		$fetch = mysql_fetch_array($query);
		$this->__close();
		return $fetch[0];
	}
	public function products($retID,$prodID,$prodCost,$prodQty,$prodQtyReturn,$prodStatus,$retSubject,$retType,$unit,$type) {
		$this->connect("INSERT INTO _return_product_list VALUES('$retID','$prodID','$unit','$prodQty','$prodQtyReturn','$prodCost','$prodStatus')");
		if($retType=='SO'){
			if($type=='RP'){
				if($prodStatus!='Good') {
					$this->connect("INSERT INTO damages VALUES ('SO',$retID,$retSubject,getReturnAssign($retID),'$prodID','$unit',$prodCost,null)");
					$query = $this->connect("SELECT saleCustomer FROM sales_order WHERE saleID='$retSubject'");
					$query = mysql_fetch_array($query);
					$cust = $query[0];
					if($cust!='DAMAGE') {
						$query = $this->connect("SELECT conversion FROM so_product_list WHERE saleID='$retSubject' AND prodID='$prodID' AND unit='$unit'");
						$query = mysql_fetch_array($query);
						$conv = $query[0];
						$this->__close();
						$this->connect("UPDATE product p SET p.prodOHQuantity=p.prodOHQuantity-($conv*$prodQtyReturn) WHERE p.prodID='$prodID'");
					}
				}
				return $this->connect("CALL replaceProducts($retSubject,'$prodID','$unit',$prodQtyReturn)");
			}
			else {
				$this->connect("UPDATE so_product_list s SET  s.cost=(s.cost/s.prodQty)*(s.prodQty-$prodQtyReturn), s.grossPrice = (s.grossPrice/s.prodQty)*(s.prodQty-$prodQtyReturn), s.netPrice = (s.netPrice/s.prodQty)*(s.prodQty-$prodQtyReturn), s.prodQty=s.prodQty-$prodQtyReturn WHERE s.saleID='$retSubject' AND s.prodID='$prodID' AND s.unit='$unit'");
				if($prodStatus=='Good'){
					$query = $this->connect("SELECT conversion FROM so_product_list WHERE saleID='$retSubject' AND prodID='$prodID' AND unit='$unit'");
					$query = mysql_fetch_array($query);
					$conv = $query[0];
					$this->__close();
					$this->connect("UPDATE product p SET p.prodOHQuantity=p.prodOHQuantity+($conv*$prodQtyReturn), p.prodSQuantity=p.prodSQuantity-($conv*$prodQtyReturn) WHERE p.prodID='$prodID'");
					$query = $this->connect("SELECT cost/prodQty FROM so_product_list WHERE saleID='$retSubject' AND prodID='$prodID'");
					$query = mysql_fetch_array($query);
					$cost = $query[0];
					$this->__close();
					$query = $this->connect("SELECT purcID FROM po_product_list WHERE prodID='$prodID' AND unit='$unit' AND prodCost/prodQuantity='$cost' AND remainStock>0 ORDER BY purcID DESC LIMIT 0,1");
					$query = mysql_fetch_array($query);
					$purcID1 = $query[0];
					$this->__close();
					$this->connect("UPDATE po_product_list SET remainStock=remainStock+$prodQtyReturn WHERE purcID='$purcID1'");
				}
				else
					$this->connect('INSERT INTO damages VALUES ("SO",$retID,$retSubject,getReturnAssign($retID),"$prodID","$unit",$prodCost)');
				return $this->connect("UPDATE sales_order s SET s.saleNetAmount=getSaleNetAmount(s.saleID), s.saleGrossAmount=getSaleGrossAmount(saleID) WHERE s.saleID=$retSubject");
			}
		}
		else{
			$this->connect("UPDATE po_product_list s SET s.prodCost=(s.prodCost/s.prodQuantity)*(s.prodQuantity-$prodQtyReturn), s.remainStock=(s.prodQuantity-$prodQtyReturn),s.prodQuantity=s.prodQuantity-$prodQtyReturn WHERE s.purcID='$retSubject' AND s.prodID='$prodID' AND s.unit='$unit'");
			if($prodStatus!='Good')
				$this->query('INSERT INTO damages VALUES ("PO",$retSubject,getReturnAssign($retID),"$prodID","$unit",$prodCost,null)');
			return $this->connect("UPDATE purchase p SET p.purcCost=getPurcCost($retSubject), p.purcQty=getPurcQty($retSubject) WHERE p.purcID=$retSubject");	
		}
		return true;
	}	
}
class Measurement extends Database {
	public function __construct($measID=NULL){
		if($measID!=NULL){
			$query = $this->connect("SELECT * FROM measurement WHERE measID='".$measID."'");
			$num = mysql_num_rows($query);
			 if($num == 0){
				$this->__close();
			 	return false;
			 }
			 else{
			 	$measurement = mysql_fetch_array($query);
				$this->__set('measID',$measurement['measID']);
				$this->__set('measDesc',$measurement['measDesc']);
				$this->__set('measMeasurement',$measurement['measMeasurement']);
				$this->__close();
			 }
		}
	}
	public function New_($measID,$measDesc,$measMeasurement){
		return $this->connect("INSERT INTO measurement VALUES ('$measID','$measDesc','$measMeasurement','Active','False')");
	}
	public function update($measU,$measID,$measDesc,$measMeasurement){
		$this->connect("SET foreign_key_checks=0");
		return $this->connect("UPDATE measurement SET measID='$measID', measDesc='$measDesc', measMeasurement='$measMeasurement' WHERE measID='$measU'");
	}
	public function check($measID){
		$query = $this->connect("SELECT * FROM measurement WHERE measID='$measID'");
		$num = mysql_num_rows($query);
		$check = ($num>0 ? true : false);
		$this->__close();
		return $check;
	}
}
class Category extends Database {
	public function __construct($catID=NULL,$type=NULL,$catName=NULL,$catDiscount=NULL,$catStatus=NULL,$catMeasurement=NULL,$catSubProd=NULL){
		if($type!=NULL){
			$catStatus = ($catStatus=='true' ? 'Active' : 'Inactive');
			$query = $type=="INSERT" ? "INSERT INTO category VALUES (null,'$catName','$catDiscount','$catMeasurement','$catSubProd','$catStatus','False')" : "UPDATE category SET catName='$catName', catDiscount='$catDiscount', catStatus='$catStatus', catMeasurement='$catMeasurement', catSubProduct='$catSubProd' WHERE catID='$catID'";
			$query = $this->connect($query);
		}
		else if($catID!=NULL){
			$query = $this->connect("SELECT * FROM category WHERE catID='".$catID."'");
			$num = mysql_num_rows($query);
			 if($num == 0){
				$this->__close();;
			 	return false;
			 }
			 else{
			 	$category = mysql_fetch_array($query);
				$this->__set('catName',$category['catName']);
				$this->__set('catDiscount',$category['catDiscount']);
				$this->__set('catStatus',$category['catStatus']);
				$this->__set('catMeasurement',$category['catMeasurement']);
				$this->__set('catSubProduct',$category['catSubProduct']);
				$this->__close();
			 }
		}
	}
	public function check($catName,$catID=NULL){
		$query = $catID==NULL ? $this->connect("SELECT * FROM category WHERE catName='$catName'") : $this->connect("SELECT * FROM category WHERE catName='$catName' AND catID!='$catID'");
		$num = mysql_num_rows($query);
		$check = ($num>0 ? true : false);
		$this->__close();
		return $check;
	}
	public function delete($catID){
		$this->connect("set foreign_key_checks=0");
		$query = $this->connect("UPDATE category SET catStatus='Inactive', catDelete='True' WHERE catID='$catID'");
		return $query;
	}
}
class Product extends Database {
	public function __construct($prodID=NULL, $type=NULL){
		if($prodID!=NULL){
			 $query = $this->connect("SELECT *,getProductRemaining('".$prodID."') as sold,getProdName(prodID) as sub_prodName, getMeasurement(measID) as measurement FROM product WHERE prodID='".$prodID."'");
			 $num = mysql_num_rows($query);
			 if($num == 0){
				$this->__close();
			 	return false;
			 }
			 else{
				if($type==NULL){
					$product=mysql_fetch_array($query);
					$this->__set('prodID',$product['prodID']);
					$this->__set('prodName',$product['prodDesc']);
					$this->__set('prodPrice',number_format($product['prodPrice'],2));
					$this->__set('prodStock',$product['prodOHQuantity']);
					$this->__set('discount',$product['discount']);
					$this->__set('sub_prodID',$product['prodID']);
					$this->__set('sub_prodQty',$product['prodQty']);
					$this->__set('sub_prodName',$product['sub_prodName']);
					$this->__set('prodMeasurement',$product['measurement']);
					$this->__set('unit',$product['measID']);
				}
				else {
					$product=mysql_fetch_array($query);
					$this->__set('prodID',$product['prodID']);
					$this->__set('prodName',$product['prodDesc']);
					$this->__set('prodPrice',number_format($product['prodPrice'],2));
					$this->__set('prodStock',$product['prodOHQuantity']);
					$this->__set('prodCategory',$product['prodMeasurement']);
					$this->__set('prodStatus',$product['prodStatus']);
					/*$this->__set('prodID',$prodID);*/
					$this->__set('prodReorderQty',$product['prodReorderQty']);
					$this->__set('prodMaximumQty',$product['prodMaximumQty']);
					$this->__set('prodLeadtime',$product['prodLeadtime']);
					$this->__set('prodMeasurement',$product['measID']);
					$this->__set('prodMeasurement1',$product['measurement']);
				}
					$this->__close();
			 }
			 
			
		}
	}
	public function register($prodID,$desc,$measurement,$stock,$price,$prodStatus,$prodReorderQty,$prodMaximumQty,$leadtime){
		$prodStatus = ($prodStatus=='true' ? 'Active' : 'Inactive');
		$query = $this->connect("INSERT INTO product VALUES ('$prodID','$desc','$stock','$stock',0,'$price','$measurement','$prodStatus','False','$prodReorderQty','$prodMaximumQty','$leadtime')");
		return true;
	}
	public function update($oldID,$prodID,$desc,$measurement,$stock,$price,$prodStatus,$prodReorderQty,$prodMaximumQty,$leadtime){
		$this->connect("set foreign_key_checks=0");
		$prodStatus = ($prodStatus=='true' ? 'Active' : 'Inactive');
		$query = $this->connect("UPDATE product SET prodID='$prodID', prodDesc='$desc', prodPrice='$price', prodStatus='$prodStatus', prodReorderQty='$prodReorderQty', prodMaximumQty='$prodMaximumQty', measID='$measurement', prodLeadtime='$leadtime' WHERE prodID='$oldID'");
		return $query;
	}
	public function delete($prodID,$loc){
		$this->connect("set foreign_key_checks=0");
		if($loc == 'product') {
			//$query = $this->connect("UPDATE product SET prodStatus='Inactive', prodDelete='True' WHERE prodID='$prodID'");
			$query = $this->connect("DELETE FROM product WHERE prodID='$prodID'");
		}
		else
			$query = $this->connect("DELETE FROM ".$loc." WHERE prodID='$prodID' AND sessionID='".session_id()."'"); 
		return $query;
	}
	public function delete_temp_list($prodID,$loc,$unit){
		return $this->connect("DELETE FROM ".$loc." WHERE prodID='$prodID' AND unit='$unit' AND sessionID='".session_id()."'");
	}
	public function check($prodID){
	   if (mysql_num_rows($this->connect("SELECT * from product WHERE prodID='".$prodID."'")) == 0)
	   		$return = false;
	   else
			$return = true;
	   
	   $this->__close(); 
	   return $return;
	}
	
	
}
class temp_list extends Database {
	public function __construct($prodID,$prodQty,$gross,$net,$accID,$type, $SO=NULL,$unit=NULL,$freight=NULL){
		if($type != 'INSERT'){
			if($type == 'DELETE' && $SO==NULL)
				$this->connect($type." FROM so_temp_list WHERE prodID='$prodID' AND unit='$unit' AND accID='$accID' AND sessionID='".session_id()."'"); 
			else if($SO == 'purchase'){
				if($type == 'UPDATE'){
					//echo $type." po_product_temp_list SET prodQuantity='$prodQty', prodCost='$gross',unit='$unit', freight='$freight' WHERE prodID='$prodID' AND unit='$unit' AND accID='$accID' AND sessionID='".session_id()."'";
					$this->connect($type." po_product_temp_list SET prodQuantity='$prodQty', prodCost='$gross',unit='$unit', freight='$freight' WHERE prodID='$prodID' AND unit='$unit' AND accID='$accID' AND sessionID='".session_id()."'"); 
				}else{
					$this->connect($type." FROM po_product_temp_list WHERE prodID='$prodID' AND accID='$accID' AND sessionID='".session_id()."'"); 
				}
			}
			else
				$this->connect($type." so_temp_list SET proDQty = proDQty + '$prodQty', netPrice='$net', totQty=proDQty*conversion WHERE prodID='$prodID' AND unit='$unit' AND accID='$accID' AND sessionID='".session_id()."'"); 
		}else{
			if($SO == NULL)
				$this->connect($type." INTO so_temp_list VALUES ('$prodID','$unit',$prodQty*$freight,'$prodQty','$freight','$net','$accID','".session_id()."')");
			else if($SO == 'purchase'){
				$this->connect($type." INTO po_product_temp_list VALUES ('$prodID','$prodQty','$gross','$accID','".session_id()."','$unit','$freight')");
			}
			else
				$this->connect($type." INTO payment_so_temp_list VALUES ('$prodID','$prodQty','$gross','$net','".session_id()."')");
		}
	}
}
class payment extends Database {
	public function temp_list($saleID,$payReceive,$saleBalance,$accID){
		$this->connect("INSERT INTO payment_so_temp_list VALUES ('$saleID','$payReceive','$saleBalance','$accID','".session_id()."')");
	}
}
class sales_order extends Database {
	private $balance=0, $due, $credit;
	public $ID;
	public function __construct($custID=NULL,$net=NULL,$gross=NULL,$type=NULL,$accID=NULL,$due=NULL,$cash=NULL,$entrusted=NULL,$credit=NULL){
		if($custID != NULL && $net!=NULL && $gross!=NULL && $type!=NULL && $accID!=NULL){
			$panelPrice = 0;
			if($type == 'cash'){
				$this->due = "CURDATE()";
				$this->credit = $credit;
				$type = 'Cash';
			}
			else if($type == 'credit'){
				$this->due = $due;
				$this->balance = $net;
				$this->credit = $credit+$net;
				$type = 'Credit';
			}
			else {
				$type = 'Panel';
				$query = $this->connect("SELECT panel FROM general_");
				$fetch = mysql_fetch_array($query);
				$panelPrice = $net - ($net*($fetch[0]*0.01));
				$this->balance = $net;
				$this->credit = $credit+$net;
			}
			$this->connect("INSERT INTO sales_order VALUES (null,'$net','$gross','$this->balance','$custID','$cash','$entrusted',null,'$this->due','$accID','$type','".session_id()."','$this->credit',$panelPrice)");
			
			$query = $this->connect("SELECT getSaleID('$custID','$accID','".session_id()."') as saleID");
			$fetch = mysql_fetch_array($query);
			$this->__set('saleID',$fetch[0]);
			$this->__close();	
		}
	}
	public function payment($custID,$pay,$change,$accID){
		$this->connect("INSERT INTO payment VALUES(null,'$pay','$change','$accID',null,'$custID','".session_id()."')");
		$this->connect("UPDATE customer SET custCredit=custCredit-$pay WHERE custID='$custID'");
		$query = $this->connect("SELECT payID,payDateTime FROM payment WHERE payReceived='$pay' AND payAssign='$accID' AND custID='$custID' AND sessionID='".session_id()."' ORDER BY payID DESC");
		$fetch = mysql_fetch_array($query);
		$this->__set('payID',$fetch[0]);
		$date=date_create($fetch[1]);
		$fetch[1] = date_format($date,"M d, o h:i A");
		$this->__set('payDateTime',$fetch[1]);
		$this->__close();
	}
}
class Password extends Database {
	private $string;
	private $key;
	private $encrypted;
	private $decrypted;
	public function __construct($string,$key){
		$this->string = $string;
		$this->key = $key;
	}
	public function encrypt(){
		$this->encrypted = '';
		for($i=0; $i<strlen($this->string); $i++) {
			$char = substr($this->string, $i, 1);
			$keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$this->encrypted.=$char;
		}
		$this->encrypted = base64_encode($this->encrypted);
		return $this->encrypted;
	}
	public function decrypt() {
		$this->decrypted = '';
		$string = base64_decode($this->encrypted);
		
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$this->decrypted.=$char;
		}
		return $this->decrypted;
	}
}
class Account extends Database {
	public function __construct($accID=NULL){
		$query = $this->connect("SELECT * FROM account WHERE accID='$accID'");
		$col = array('accID','accUsername','accPassword','accName','addedBy','regDateTime','accStatus');
		$x = 0;
		while($fetch = mysql_fetch_array($query)){
			for($i=0;$i<count($col);$i++)
				$this->__set($col[$i],$fetch[$i]);
		}
	}
	public function check($accUsername){
		$query = $this->connect("SELECT accID FROM account WHERE accUsername='$accUsername'");
		$num = mysql_num_rows($query);
		$check = ($num>0 ? true : false);
		$this->__close();
		return $check;
	}
	public function checkPassword($accIDC,$accUsername,$accPassword){
		$accPassword = new Password($accPassword,$accUsername);
		$accPassword = $accPassword->encrypt();
		$query = $this->connect("SELECT accID FROM account WHERE accID='$accIDC' AND accUsername='$accUsername' AND accPassword='$accPassword'");
		$num = mysql_num_rows($query);
		$check = ($num>0 ? true : false);
		$this->__close();
		return $check;
	}
	public function changePassword($accIDC,$accUsername,$accPassword){
		$accPassword = new Password($accPassword,$accUsername);
		$accPassword = $accPassword->encrypt();
		return $this->connect("UPDATE account SET accPassword='$accPassword' WHERE accID='$accIDC' AND accUsername='$accUsername'");
	}
	public function create($uname,$name,$pass,$type,$status,$account){
		$pass = new Password($pass,$uname);
		$status =  ($status==true) ? 'Active' : 'Banned'; 
		$this->connect("INSERT INTO account VALUES(null,'$uname','$name','".$pass->encrypt()."','$type','$status','$account',null,null,null,null,null)");
		return true;
	}
	public function update($uname,$name,$pass,$type,$status,$account){
		$pass = new Password($pass,$uname);
		$status =  ($status=='true' && $status!='Delete') ? 'Active' : 'Banned';	
		$this->connect("UPDATE account SET accName='$name', accPassword='".$pass->encrypt()."', accType='$type', accStatus='$status',updateBy=$account WHERE accUsername='$uname'");
		return true;
	}
}
class Customer extends Database {
	public function __construct($custID=NULL, $sql=NULL, $type=NULL){
		if($custID!=NULL){
			$query = $this->connect($sql);
			if($type != 'UPDATE'){
				$num = mysql_num_rows($query);
				if($num == 0)
					return false;
				else{
					while($customer=mysql_fetch_array($query)){
						if($type!=NULL){
							$this->__set('name',$customer['name']);
							$this->__set('credit',number_format($customer['custCredit'],2));
							if($type == 'sale'){
								$this->__set('limit',number_format($customer['custLimit'],2));
								$this->__set('expDate',$customer['exp']);
							}
							else if($type == 'payment')
								$this->__set('last_payment', $customer['last']);
							else if($type == 'return')
								$this->__set('last_return', $customer['last']);
							else if($type == 'deduction'){
								$this->__set('emp_name', $customer['name']);
								$this->__set('emp_credit',$customer['custCredit']);
							}
						}
						else{
							$this->__set('custID',$customer['custID']);
							$this->__set('custFName',$customer['custFName']);
							$this->__set('custMName',$customer['custMName']);
							$this->__set('custLName',$customer['custLName']);
						}
						
					}
				}
				$this->__close();
			}
		}
		
	}
	public function check($fname,$mname,$lname,$custID=NULL){
		$query = $custID==NULL ? $this->connect("SELECT * FROM customer WHERE custFName='$fname' AND custMName='$mname' AND custLName='$lname' AND custDelete!='Perma'") : $this->connect("SELECT * FROM customer WHERE custID!='$custID' AND custFName='$fname' AND custMName='$mname' AND custLName='$lname' AND custDelete!='Perma'");
		$num = mysql_num_rows($query);
		$check = ($num>0 ? true : false);
		$this->__close();
		return $check;
	}
	public function check_id($custID){
		$query = $this->connect("SELECT * FROM customer WHERE custID='".$custID."' AND custDelete!='Perma' LIMIT 0,1");
		$num = mysql_num_rows($query);
		$check = ($num>0 ? true : false);
		$this->__close();
		return $check;
	}
	public function register($custID,$fname,$mname,$lname,$addr,$bdate,$gender,$baddress,$mobile,$civil_status,$photo,$elem,$hs,$coll,$voc,$others,$designation,$section,$dept,$accType,$limit,$expDate){
		$elem = ($elem!='' ? 'True' : 'False');
		$hs = ($hs!='' ? 'True' : 'False');
		$coll = ($coll!='' ? 'True' : 'False');
		$voc = ($voc!='' ? 'True' : 'False');
		$others = ($others!='' ? $others : ' ');
		$photo = ($photo!='' ? $photo : 'default.png');
		if($expDate=='')
			$expDate = '0000-00-00';
		if($accType=='reg')
			$accType = 'Regular';
		else if($accType=='casSki')
			$accType = 'Casual Skilled';
		else
				$accType = 'Casual Non Skilled';		
		$query = $this->connect("SELECT custID FROM customer WHERE custID='$custID' AND custDelete='Perma'");
		if(mysql_num_rows($query)==0)
			return $this->connect("INSERT INTO customer VALUES('$custID','$fname','$mname','$lname','$bdate','$gender','$addr','$baddress','$civil_status','$mobile','$elem','$hs','$coll','$voc','$others','$designation','$section','$dept','$accType','$expDate',0,'$limit',null,NULL,'False')"); 
		else	
			return $this->connect("UPDATE customer SET custFname='$fname', custMName='$mname', custLName='$lname', custBDate='$bdate', custGender='$gender', custAddress='$addr', custBirthPlace='$baddress', custCivilStatus='$civil_status', custContactNo='$mobile', custElem='$elem', custHS='$hs',custCol='$coll', custVoc='$voc', custOthers='$others', custDesignation='$designation', custSection='$section', custDept='$dept', custAccType='$accType', custExpire='$expDate', custLimit='$limit', custDelete='False' WHERE custID='$custID'");
		$this->__close();
	}

	public function delete($custID){
		$this->connect("UPDATE customer SET custDelete='True' WHERE custID='$custID'");
		$this->__close();
	}
}

?>