<?php
class MY {
        private $test = "Hello World";

        public function getTest(){
                return $this->test;
        }
}

class TEST extends Thread {
        public function __construct($my) {
                $this->my = $my;
        }

        public function run(){
                printf("TEST: %s\n", $this->my->getTest());
        }
}

$my = new MY();
$test = new TEST($my);
$test->start();
