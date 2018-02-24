<?php
require_once('base64.php');
/*function encrypt($string,$key){
	$encrypted = '';
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$encrypted.=$char;
	}
	return base64_encode($encrypted);
}
$password = new Password('admin123','admin','z8XR1tefk5c=');
echo $password->encrypt()." \n";
echo $password->decrypt()." \n";
$password = new Password('arianneuba','admin Siegfred B. Pagador AV');
echo $password->encrypt()." \n";
echo $password->decrypt();*/
/*echo str_rot13('Xvyyunk777 ');
echo str_rot13('NevnaarHon123 ');
echo str_rot13('Nevnaar123 ');
echo str_rot13('fnzfhat ');
echo str_rot13('nqzva2 ');
echo str_rot13('nqzva1 ');
echo str_rot13('nqzva123 ');
echo encrypt('mrml233122','jireh-s')." ";
echo encrypt('Killhax777','siegfred')." ";
echo encrypt('ArianneUba123','ariannuba')." ";
echo encrypt('Arianne123','arianneuba')." ";
echo encrypt('samsung','samsung')." ";
echo encrypt('admin2','admin2')." ";
echo encrypt('admin1','admin1')." ";
echo encrypt('admin123','admin')." ";*/
phpinfo();
?>