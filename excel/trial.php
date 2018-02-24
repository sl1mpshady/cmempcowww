<?php
require_once 'PHPExcel.php';
include 'PHPExcel/IOFactory.php';

//$inputFileType = 'Excel5';
//	$inputFileType = 'Excel2007';
//	$inputFileType = 'Excel2003XML';
//	$inputFileType = 'OOCalc';
//	$inputFileType = 'Gnumeric';
$inputFileName = './sampleData/example1.xls';
$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objReader->setReadDataOnly(false);
$objPHPExcel = $objReader->load($inputFileName);
/*$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory with a defined reader type of ',$inputFileType,'<br />';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
echo 'Turning Formatting off for Load<br />';
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($inputFileName);*/


echo '<hr />';

$sheet = $objPHPExcel->getActiveSheet();
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
//echo $highestColumn." ".$highestRow;
//var_dump($sheetData);
for ($row = 2; $row <= $highestRow; ++ $row) {
    $val=array();
	for ($col = 0; $col < $highestColumnIndex; ++ $col) {
	   $cell = $sheet->getCellByColumnAndRow($col, $row);
	   echo $cell->getValue()." ";
 //End of For loop   
	}
	echo "\n";
}
/*require_once 'PHPExcel.php';
include 'PHPExcel/IOFactory.php';

$path = 'demo.xlsx';
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load("upload/Book.xlsx");
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
 $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
for ($row = 2; $row <= $highestRow; ++ $row) {
    $val=array();
for ($col = 0; $col < $highestColumnIndex; ++ $col) {
   $cell = $worksheet->getCellByColumnAndRow($col, $row);
   $val[] = $cell->getValue();
 //End of For loop   
}

$Col1 = $val[0] ;
$Col2 = $val[1] ;
$Col3 = $val[2];

echo $Col1;
echo $Col2;
echo $Col3;
echo "<br>";



$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load("upload/Book.xlsx");
echo $objPHPExcel;
$sheet = $objPHPExcel->setActiveSheetIndex() ;
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();
echo 'highrow: '.$highestRow.'\n';
echo 'highcol: '.$highestColumn;
//Loop through each row of the worksheet in turn
for ($row = 0; $row <= $highestRow; $row++){ 
	  //  Read a row of data into an array
	  $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
	  //Insert into database
}
echo $rowData;*/
?>