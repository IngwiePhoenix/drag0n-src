<?php
class ThreadTest extends Thread {
	public static function staticTest(){
		return 1;
	}
	
	public function run(){
		$this->result = self::staticTest();
	}
}
$thread = new ThreadTest();
if($thread->start())
	if ($thread->join())
		var_dump($thread->result);
?>
