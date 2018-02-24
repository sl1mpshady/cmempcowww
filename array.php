<?php
$myData = json_decode($_GET['testData']);
/*print_r($myData);
echo '\n';
echo $myData[1]->measID;*/
foreach ($myData as $obj) {
   echo $obj->measID.' '.$obj->conversion.'<br>';
}
?>