<?php
require_once(dirname(__FILE__) . "/Escpos.php");
/*try {
	$connector = new WindowsPrintConnector("Receipt");
	
	$printer = new Escpos($connector);
	$printer -> setJustification(Escpos::JUSTIFY_CENTER); 
	$printer -> feed(1);
	$printer -> text("POS PRINTER!\n");
	
	$printer -> cut();
	
	$printer -> close();
} catch(Exception $e) {
	echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}
*/
try {
	/* Set up command */
	$source = "print1.html";
	$width = 550;
	$dest = tempnam(sys_get_temp_dir(), 'escpos') . ".png";
	$cmd = sprintf("wkhtmltoimage -n -q --width %s %s %s",
		escapeshellarg($width),
		escapeshellarg($source),
		escapeshellarg($dest));
	
	/* Run wkhtmltoimage */
	ob_start();
	shell_exec($cmd); // Can also use popen() for better control of process
	$outp = ob_get_contents();
	ob_end_clean();
	if(!file_exists($dest)) {
		throw new Exception("Command $cmd failed: $outp");
	}

	/* Load up the image */
	try {
		$img = new EscposImage($dest);
	} catch(Exception $e) {
		unlink($dest);
		throw $e;
	}
	unlink($dest);

	/* Print it */
	//$printer = new Escpos(); // Add connector for your printer here.
	$connector = new WindowsPrintConnector("Receipt");
	
	$printer = new Escpos($connector);
	$printer -> bitImage($img); // bitImage() seems to allow larger images than graphics() on the TM-T20.
	$printer -> cut();
	$printer -> close();
} catch(Exception $e) {
	echo $e -> getMessage();
}