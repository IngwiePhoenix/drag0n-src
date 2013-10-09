<?php
class ThreadTest extends Thread {
	public function objectTest(){
		return $this->value;
	}
	
	public function run(){
		$this->value = 1;
	}
}
$thread = new ThreadTest();
if($thread->start()) {
	$thread->join();
	var_dump($thread->objectTest());
}
?>
