<?php
class TestThread extends Thread {
	public function run(){
		/* silent fatal error */
		echo @MY::$FATAL;
	}
}
$test = new TestThread();
$test->start();
usleep(100000);
var_dump($test->isRunning());
?>
