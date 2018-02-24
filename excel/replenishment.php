<?php
require_once('../Connections/connection.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

require_once dirname(__FILE__) . '/PHPExcel.php';
mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_database);
//if(isset($_GET['to'])){
	$objPHPExcel = new PHPExcel();
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
	//$objPHPExcel->getProperties()->setCreator($_GET['accName'])
	//			->setLastModifiedBy($_GET['accName'])
	//			->setTitle('Customers Summary Report')
	//			->setDescription("This document is a list of customers with their credits.");
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);


	$objRichText = new PHPExcel_RichText();
	$objRichText->createTextRun('CAGDIANAO MINING EMPLOYEES MULTI-PURPOSE COOPERATIVE')
				->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1')
				->getCell('A1')->setValue($objRichText);
	$objPHPExcel->getActiveSheet()->getStyle('A1')
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objRichText = new PHPExcel_RichText();
	$objRichText->createTextRun('Cagdianao Mining Corporation')
				->getFont()->setItalic(true);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:H2')
				->getCell('A2')->setValue($objRichText);
	$objPHPExcel->getActiveSheet()->getStyle('A2')
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objRichText = new PHPExcel_RichText();
	$objRichText->createTextRun('Brgy. Valencia, Cagdianao, Dinagat Island')
				->getFont()->setItalic(true);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:H3')
				->getCell('A3')->setValue($objRichText);
	$objPHPExcel->getActiveSheet()->getStyle('A3')
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	$objRichText = new PHPExcel_RichText();
	$objRichText->createTextRun('STOCKS REPLENISHMENT')
				->getFont()->setUnderline(true)->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:F5')
				->getCell('A5')->setValue($objRichText);
	$objPHPExcel->getActiveSheet()->getStyle('A5')
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A8','No.')
				->setCellValue('B8','PRODUCT ID')
				->setCellValue('C8','ITEM DESCRIPTION')
				->setCellValue('D8','UNIT')
				->setCellValue('E8','OHB')
				->setCellValue('F8','SOLD DAILY')
                ->setCellValue('G8','ROP')
                ->setCellValue('H8','FOR ORDER');
	/*
	$type = $_GET['type'];
	$value = $_GET['value'];
	$type1 = $_GET['type2'];
	if($type1!='all'){
		if($type1=='regular')
			$type1 = "AND custAccType = 'Regular'";
		else
			$type1 = "AND (custAccType = 'Casual Skilled' OR custAccType = 'Casual Non Skilled')";
	} else 
		$type1 = '';
	if($value!=NULL){
		if($type=='custID')
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."'), UCASE(DATE_FORMAT('".$_GET['from']."','%M %d, %Y')) as FROM_, UCASE(DATE_FORMAT('".$_GET['to']."','%M %d, %Y')) as TO_, DATE_FORMAT(NOW(),'%b%d,%Y-%h:%i%p') FROM customer WHERE custID LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
		else if($type=='custName')
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."'), UCASE(DATE_FORMAT('".$_GET['from']."','%M %d, %Y')) as FROM_, UCASE(DATE_FORMAT('".$_GET['to']."','%M %d, %Y')) as TO_, DATE_FORMAT(NOW(),'%b%d,%Y-%h:%i%p') FROM customer WHERE getCustName(custID) LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
		else if($type=='custSection')	
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."'), UCASE(DATE_FORMAT('".$_GET['from']."','%M %d, %Y')) as FROM_, UCASE(DATE_FORMAT('".$_GET['to']."','%M %d, %Y')) as TO_, DATE_FORMAT(NOW(),'%b%d,%Y-%h:%i%p') FROM customer WHERE custSection LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
		else if($type=='custDept')
			$query = "SELECT custID, custFname, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."'), UCASE(DATE_FORMAT('".$_GET['from']."','%M %d, %Y')) as FROM_, UCASE(DATE_FORMAT('".$_GET['to']."','%M %d, %Y')) as TO_, DATE_FORMAT(NOW(),'%b%d,%Y-%h:%i%p') FROM customer WHERE custDept LIKE '%".$value."%' AND custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID ASC";
	}
	else
        $query = "SELECT custID, custFName, custMName, custLName, custSection, custDept, getCustCreditLedger(custID,'".$_GET['to']."'), UCASE(DATE_FORMAT('".$_GET['from']."','%M %d, %Y')) as FROM_, UCASE(DATE_FORMAT('".$_GET['to']."','%M %d, %Y')) as TO_, DATE_FORMAT(NOW(),'%b%d,%Y-%h:%i%p') FROM customer WHERE custDelete='False' AND getCustCreditLedger(custID,'".$_GET['to']."')>0 ".$type1." ORDER BY custID asc" ;
    */
    $query = "SELECT prodID, prodDesc, measID, prodOHQuantity,getDailyConsumption(prodID),getROP(prodID) ,prodMaximumQty-getROP(prodID) FROM product" ;
	$query=mysql_query($query);
	$index=9;
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$date = '';
	while($so=mysql_fetch_array($query)){
		$objPHPExcel->getActiveSheet()
					->setCellValueByColumnAndRow(0,$index,$index-8)
					->setCellValueByColumnAndRow(1,$index,$so[0])
					->setCellValueByColumnAndRow(2,$index,$so[1])
					->setCellValueByColumnAndRow(3,$index,$so[2])
					->setCellValueByColumnAndRow(4,$index,number_format($so[3],2))
                    ->setCellValueByColumnAndRow(5,$index,number_format($so[4],2))
                    ->setCellValueByColumnAndRow(6,$index,number_format($so[5],2))
                    ->setCellValueByColumnAndRow(7,$index,number_format($so[6],2));
		//$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,$index)->getNumberFormat()->setFormatCode('#,##0.00');
		$index+=1;
		/*$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:F7')
				->setCellValue('A7','PAYROLL: '.$so['FROM_'].' - '.$so['TO_']);
		$date = $so[9];*/
	}
			
	mysql_free_result($query);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="products-replenishment-'.$date.'.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;

?>