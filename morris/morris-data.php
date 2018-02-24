<?php 
$echo = "<script>$(function() {
    Morris.Area({
        element: 'morris-area-chart',
		data: [";

$query1 = $query2 = $query3 = "SELECT ";
for($j=date('Y')-1; $j<=date('Y'); $j++){
	for($i=0; $i<12; $i++){
		$query1.=' getSOByDate("'.$j.'-'.($i+1).'-01")';
		$query2.=' getPOByDate("'.$j.'-'.($i+1).'-01")';
		$query3.=' getDByDate("'.$j.'-'.($i+1).'-01")';
		
		if($j==date('Y') && $i==date('M')+1)
			$i=11;
		if(!($i+1==12 && $j==date('Y'))){
			$query1.=',';
			$query2.=',';
			$query3.=',';
		}
	}
}
mysql_select_db($database_connSIMS, $connSIMS);
$rsSalesOrder = mysql_query($query1, $connSIMS) or die(mysql_error());
$row_rsSalesOrder = mysql_fetch_array($rsSalesOrder);
$rsPurchaseOrder = mysql_query($query2, $connSIMS) or die(mysql_error());
$row_rsPurchaseOrder = mysql_fetch_array($rsPurchaseOrder);
$rsDamages = mysql_query($query3, $connSIMS) or die(mysql_error());
$row_rsDamages = mysql_fetch_array($rsDamages);
$it=0;
for($j=date('Y')-1; $j<=date('Y') && $it<mysql_num_fields($rsSalesOrder); $j++){
	for($i=0; $i<12 && $it<mysql_num_fields($rsSalesOrder); $i++,$it++){
		if($row_rsSalesOrder[$it]=='') $row_rsSalesOrder[$it]=0;
		if($row_rsPurchaseOrder[$it]=='') $row_rsPurchaseOrder[$it]=0;
		if($row_rsDamages[$it]=='') $row_rsDamages[$it]=0;
		$k = $i;
		if($i<10)
			$k = '0'.$i;
		$k +=1;
		if($k<10)
			$k = '0'.$k;
		$echo .= "{period: '".$j."-".$k."-01"."',so: ".$row_rsSalesOrder[$it].",po: ".$row_rsPurchaseOrder[$it].",damage: ".$row_rsDamages[$it]."}";
		if($it+1!=mysql_num_fields($rsSalesOrder))
			$echo.= ',';
		else
			$echo.='],xkey: "period",ykeys:["so","po","damage"],labels:["Sales Order","Purchase Order","Damages"],pointSize:2,hideHover: "auto", resize: true});});';
	}
}
$echo.= '</script>';
echo $echo;
?>