<?php
require_once dirname(__FILE__) . '/PHPExcel.php';
$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
$rendererLibrary = 'dompdf';
$rendererLibraryPath = dirname(__FILE__).'/library/'.$rendererLibrary;

if (!PHPExcel_Settings::setPdfRenderer($rendererName,$rendererLibraryPath)) {
	die(
		'Please set the $rendererName and $rendererLibraryPath values' .
		PHP_EOL .
		' as appropriate for your directory structure'
	);
}
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("CMEMPCO") 
                 ->setDescription("This document is a stock log for a particular product.");
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
/*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Date')
            ->setCellValue('B1', 'Time')
            ->setCellValue('B2', 'Start')
            ->setCellValue('C2', 'End');
*/
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.2);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.5);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.5);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M1')->setCellValue('A1','CAGDIANAO MINING EMPLOYEES MULTI-PURPOSE COOPERATIVE');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(4);

$objPHPExcel->getActiveSheet()->setTitle('Simple');
$objPHPExcel->getActiveSheet()->setShowGridLines(false);

$objPHPExcel->setActiveSheetIndex(0);

header('Cache-Control:private, must-revalidate, post-check=0, pre-check=0, max-age=0');
header('Content-Disposition:inline; filename="stock_logs.pdf"');
header('Content-Type:application/pdf');




$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('php://output');
//$objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
//$objWriter->save("05featuredemo.pdf");

exit;

?>