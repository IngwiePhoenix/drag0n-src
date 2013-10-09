<?php
class TestThread extends Thread {
	public $default;
	
	public function run() {
		var_dump($this->default); 
	}
}

$thread = new TestThread();
$thread->start();
?>
