<?php
function TestFunction(){
	return __FUNCTION__;
}

class TestThread extends Thread {
	public function run() { 
		printf("%s\n", TestFunction()); 
	}
}

$thread = new TestThread();
$thread->start();
?>
