<?php
class parentClass {
    private $var;

    public function __construct() {
        echo $this->var;
    }
}

class childClass extends parentClass {

}

class clientThread extends Thread {

    public function run() {
        $objChild = new childClass();

    }               

}


$objClientThread = new clientThread();
$objClientThread->start();
