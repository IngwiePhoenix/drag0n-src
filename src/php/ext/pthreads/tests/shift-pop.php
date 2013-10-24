<?php
class S extends Stackable {
	public function run(){}
}
$s = new S();
$s[] = "help";
var_dump($s);
var_dump($s->shift());
var_dump($s);
$s[] = "next";
var_dump($s);
var_dump($s->pop());
var_dump($s);
while (@$i++<100)
    $s[$i]=$i;
var_dump($s);
while (($next = $s->pop())) {
    var_dump($next);
}
?>
