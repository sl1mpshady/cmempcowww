<?php
  $data=array();
  include('../class/class.php');
  $general = new General(true);
  $data[] = array($general->busName,$general->regNum,$general->address,$general->contact,$general->salesTax,$general->askEntrusted,$general->printReceipt,$general->cashDuplicate,$general->creditDuplicate,$general->note,$general->noteAlign,$general->noteFontStyle,$general->foot,$general->footAlign,$general->footFontStyle,$general->salesReturn,$general->manager,$general->operationManager,$general->president,$general->panel);
 echo json_encode($data);
?>