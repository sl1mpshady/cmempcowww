<?php
include 'PHPExcel/IOFactory.php';
include 'PHPExcel.php';
if(isset($_FILES['file'])){
if($_FILES['file']['name']){
    if(!$_FILES['file']['error'])
    {

        $inputFile1 = $_FILES['file']['name'];
        $extension = strtoupper(pathinfo($inputFile1, PATHINFO_EXTENSION));
        if($extension == 'XLSX' || $extension == 'XLS' || $extension == 'ODS'){
            try {
                 $inputFile = $_FILES['file']['tmp_name'];	
                 $inputFileType = PHPExcel_IOFactory::identify($inputFile);
				 $objReader = PHPExcel_IOFactory::createReader($inputFileType);
				 $objReader->setReadDataOnly(true);
                 $objPHPExcel = $objReader->load($inputFile);
				 $objPHPExcel = PHPExcel_IOFactory::load($inputFile);
				 
            } catch(Exception $e) {
                    die($e->getMessage());
            }
			$sheet = $objPHPExcel->getActiveSheet();
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

			$rowData = array();
			
			$error = false;
			
            for ($row = 2; $row <= $highestRow; $row++){ 
					for ($col = 0,$column = 'A'; $col < $highestColumnIndex; $col++,$column++) {
						$cell = $sheet->getCellByColumnAndRow($col, $row);
						if($col == 2 && ($cell->getValue() != 'Active' && $cell->getValue() != 'Inactive')){
							$error = true;
							$rowData[0] = 'Error';
							$rowData[1] = 'On Row '.$row.' Column '.$column;
							$rowData[2] = 'It can only contain either <strong>Active</strong> or <strong>Inactive</strong>.';
							break 2;
						}
						else if(($col == 3 || $col == 4)){
							if(!is_numeric($cell->getValue())){
								$error = true;
								$rowData[0] = 'Error';
								$rowData[1] = 'On Row '.$row.' Column '.$column;
								$rowData[2] = 'It can only contain <strong>numbers</strong>.';
								break 2;
							}
							else {
								if($col==4)
									$rowData[$row-2][$col] = number_format( $cell->getValue(),2);
								else{
									if(is_float($cell->getValue())){
									   if(number_format(substr($cell->getValue(),strpos($cell->getValue(),".")+1)) == 0)
										  $rowData[$row-2][$col] = number_format( $cell->getValue(),0);
									   else
										  $rowData[$row-2][$col] = number_format( $cell->getValue(),2);
									}
									else
										$rowData[$row-2][$col] = number_format( $cell->getValue(),0);
								}
									
							}
						}
						
						else
	   						$rowData[$row-2][$col] = $cell->getValue();
					}
            }
			unlink($_FILES['file']['tmp_name']);
			if($error)
				echo json_encode($rowData);
			else
				echo json_encode($rowData);
        }
        else{
            echo json_encode("Please upload an XLSX or ODS file");
        }
    }
    else{
        echo $_FILES['file']['error'];
    }
}
}
?>