<?php
class TestThread extends Thread {
	public function run() { printf("%s\n", DateTime::ISO8601 ); }
}

$thread = new TestThread();
$thread->start();
?>
