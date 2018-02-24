<?php
include 'PHPExcel/IOFactory.php';
include 'PHPExcel.php';
require('../class/class.php');
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
			$customer = new Customer();
			$error = false;
			$totalD = $totalC = 0;
            for ($row = 2; $row <= $highestRow; $row++){
				$credit = 0;
				$custID = ''; 
				$ded = 0;
				for ($col = 0,$column = 'A'; $col < $highestColumnIndex; $col++,$column++) {
					$cell = $sheet->getCellByColumnAndRow($col, $row);
						
					if($col==0){
						if(!$customer->check_id($cell->getValue())){
							$error = true;
							$rowData[0] = 'Error';
							$rowData[1] = 'On Row '.$row.' Column '.$column;	
							$rowData[2] = 'Customer not found with the ID of <strong>'.$cell->getValue().'</strong>.'.EOL.'Please update the file.';
							break 2;
						}
						$rowData[$row-2][$col] = $cell->getValue();
						//echo $rowData[$row-2][$col].EOL;
						$custID = $cell->getValue();
					}
					else if($col==1 || $col==2){
						$customer1 = new Customer($custID,'SELECT UCASE(CONCAT(custLName,", ",custFName," ",MID(custMName,1,1),".")) as name, custCredit FROM customer WHERE custID="'.$custID.'"','deduction');
						if($col==1)
							$rowData[$row-2][$col] = $customer1->emp_name;
						else {
							if($customer1->emp_credit<=0){
								$error = true;
								$rowData[0] = 'Error';
								$rowData[1] = 'On Row '.$row.' Column '.$column;	
								$rowData[2] = 'Cannot select customer with zero (0) credit amount'.EOL.'Please update the file.';
								break 2;
							}else {
								$rowData[$row-2][$col] = number_format($customer1->emp_credit,2);
								$credit = $customer1->emp_credit;
								$totalC += $customer1->emp_credit;	
							}
						}
					}
					else{
						/*Please change line 57*/
						//if(is_nan($cell->getValue())==1){
						if($cell->getValue()=='is_nan'){
							$error = true;
							$rowData[0] = 'Error';
							$rowData[1] = 'On Row '.$row.' Column '.$column;	
							$rowData[2] = '<strong>'.$cell->getValue().'</strong>. is not a valid number'.EOL.'Please update the file.';
							break 2;
						}
						else {
							if($col==3){
								$rowData[$row-2][$col] = number_format($cell->getValue(),2);
								$ded = $cell->getValue();
								$totalD += $cell->getValue();
								if($cell->getValue()>$credit){
									$error = true;
									$rowData[0] = 'Error';
									$rowData[1] = 'On Row '.$row.' Column '.$column;	
									$rowData[2] = 'Deduction must not be greater than the specified credit amount.'.EOL.'Please update the file.';
									break 2;
								}
							}
							else {
								$rowData[$row-2][$col] = number_format($credit-$ded,2);
								$credit = $ded = 0;
							}
						}
					}
					
				}
				
            }
			
			unlink($_FILES['file']['tmp_name']);
			if($error)
				echo json_encode($rowData);
			else{
				$rowData[$highestRow-1][0] = number_format($highestRow-1,0);
				$rowData[$highestRow-1][1] = number_format($totalC,2);
				$rowData[$highestRow-1][2] = number_format($totalD,2);
				echo json_encode($rowData);
			}
				
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