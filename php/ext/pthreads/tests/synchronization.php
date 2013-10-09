<?php
class T extends Thread {
        public $data;
        public function run() {
            $this->synchronized(function($thread){
				$thread->data = true;
				$thread->notify();
			}, $this);               
        }
}
$t = new T;
$t->start();
$t->synchronized(function($thread){
	if (!$thread->data) {
		var_dump($thread->wait());
	} else var_dump($thread->data);
}, $t);
?>
