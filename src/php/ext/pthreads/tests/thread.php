<?php
class ThreadTest extends Thread {
	public function run(){
		/* nothing to do */
	}
}
$thread = new ThreadTest();
if($thread->start())
	var_dump($thread->join());
?>
