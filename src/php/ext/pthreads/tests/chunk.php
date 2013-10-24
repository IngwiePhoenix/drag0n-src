<?php
class S extends Stackable {
    public function run(){}
}

$s = new S();
$s->merge(array_fill(0, 10000, true));

var_dump($s->chunk(10));
?>
