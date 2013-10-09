<?php
class T extends Thread {
        public $data;
        public function run() {
            $this->synchronized(function($thread){
				usleep(100000);
			}, $this);               
        }
}
$t = new T;
$t->start();
$t->synchronized(function($thread){
	var_dump($thread->wait(100));
}, $t);
?>
