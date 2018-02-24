<?php
class Password {
	private $string;
	private $key;
	private $encrypted;
	private $decrypted;
	public function __construct($string,$key,$decrypted=''){
		$this->string = $string;
		$this->key = $key;
		$this->encrypted = $decrypted;
	}
	public function encrypt(){
		$this->encrypted = '';
		for($i=0; $i<strlen($this->string); $i++) {
			$char = substr($this->string, $i, 1);
			$keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$this->encrypted.=$char;
		}
		$this->encrypted = base64_encode($this->encrypted);
		return $this->encrypted;
	}
	public function decrypt() {
		$this->decrypted = '';
		$string = base64_decode($this->encrypted);
			
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$this->decrypted.=$char;
		}
		return $this->decrypted;
	}
}
?>