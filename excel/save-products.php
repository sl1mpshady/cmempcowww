<?php
$data = file_get_contents('php://input');
$data = json_decode($data, true);
$a = '';
for($i=0; $i<count($data); $i++){
	$st = '';
	for($j=0; $j<5; $j++)
		$st .= $data[$i][$j]." ";
	$a .= $st."\n";
}
return json_encode($a);
?>