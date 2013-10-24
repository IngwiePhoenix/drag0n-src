<?php
class TestThread extends Thread {
	static $static = "pthreads rocks!";

	public function run() { var_dump(self::$static); }
}

$thread = new TestThread();
$thread->start();
?>
