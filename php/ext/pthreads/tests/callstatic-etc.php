<?php
class Test {
    public static function __callStatic ($name, $args) {
        var_dump($name, $args);
    }
}

class UserThread extends Thread {
    public function run () {
        Test::called_func("argument");
    }
}

$thread = new UserThread;
$thread->start();
?>
