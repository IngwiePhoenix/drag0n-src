<?php
class O extends Stackable { 
	public function run() {

	}
}

class T extends Thread {
	public $o;

	public function run() {
		$this->o = new O();	
	}
}

$t = new T();
$t->start();
$t->join();

var_dump($t->o);
?>
