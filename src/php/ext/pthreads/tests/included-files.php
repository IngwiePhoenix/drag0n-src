<?php
define("INC", sprintf("%s/includeme.php", dirname(__FILE__)));

include(INC);
class TestThread extends Thread {
	public function run(){
		require_once(INC);
		if (!function_exists("myTestFunc")) {
			printf("FAILED\n");
		} else printf("OK\n");
	}
}
$test = new TestThread();
$test->start();
?>
