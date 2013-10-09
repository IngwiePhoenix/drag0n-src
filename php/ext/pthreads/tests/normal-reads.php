<?php
class Test extends Thread {
	public function run() { 
		$this->name = sprintf("%s", __CLASS__);
	}
}

$thread = new Test();
$thread->start();
$thread->join();
foreach($thread as $key => $value)
	var_dump($value);
?>
