<?php
ini_set("include_path", ":/var/lib/other");
class Test extends Thread {
	public function run(){
		printf("%s: %s\n", __METHOD__, ini_get("include_path"));
	}
}
$test = new Test();
$test->start();
?>
