<?php
class TestThread extends Thread {
	public function run(){
		@i_do_not_exist();
	}
}
$test = new TestThread();
$test->start();
$test->join();
var_dump($test->isTerminated());
?>
