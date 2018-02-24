<?php
include 'PHPExcel/IOFactory.php';
if(isset($_FILES['file'])){
if($_FILES['file']['name']){
    if(!$_FILES['file']['error'])
    {

        $inputFile1 = $_FILES['file']['name'];
        $extension = strtoupper(pathinfo($inputFile1, PATHINFO_EXTENSION));
        if($extension == 'XLSX' || $extension == 'XLS' || $extension == 'ODS'){

            //Read spreadsheeet workbook
            try {
				//move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $_FILES["file"]["name"]);

				//$inputFile = ;
                 $inputFile = $_FILES['file']['tmp_name'];	
				 //echo $inputFile.'\n';
                 $inputFileType = PHPExcel_IOFactory::identify($inputFile);
				// echo $inputFileType.'\n';
				 $objReader = PHPExcel_IOFactory::createReader($inputFileType);
				 //echo $objReader.'\n';
				 $objReader->setReadDataOnly(false);
                 $objPHPExcel = $objReader->load($inputFile);
				 $objPHPExcel = PHPExcel_IOFactory::load($inputFile);
				// echo true;
				 //echo $objPHPExcel.'\n';
				 
            } catch(Exception $e) {
                    die($e->getMessage());
            }
            //Get worksheet dimensions
            //$sheet = $objPHPExcel->getSheet(0); 
			//echo 'asas';
			$sheet = $objPHPExcel->getActiveSheet();
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            //Loop through each row of the worksheet in turn
			$rowData = array();
            for ($row = 0; $row <= $highestRow; $row++){ 
                    //  Read a row of data into an array
					for ($col = 0; $col < $highestColumnIndex; ++ $col) {
						$cell = $sheet->getCellByColumnAndRow($col, $row);
	   					$rowData[$row][$col] = $cell->getValue();
					}	
  
                    //Insert into database
            }
			echo json_encode($rowData);
        }
        else{
            echo "Please upload an XLSX or ODS file";
        }
    }
    else{
        echo $_FILES['file']['error'];
    }
}
}













/*$upload = basename($_FILES['file']['name']);
$type = substr($upload, strrpos($upload, '.') + 1);
$size = $_FILES['file']['size']/1024; 

if ($_FILES["file"]["error"] > 0){
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
}
else{
	echo "Upload: " . $upload . "<br>";
	echo "Type: " . $type . "<br>";
	echo "Size: " . $size . " kB<br>";
}
*/

?>