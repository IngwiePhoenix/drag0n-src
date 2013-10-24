<?php
define ("SCONST", "mystring");
define ("LCONST", 10);
define ("DCONST", 1.19);
define ("NCONST", null);
define ("BCONST", true);

class TestThread extends Thread {
	public function run() {
		foreach (array(
			"string" => SCONST,
			"long" => LCONST,
			"double" => DCONST,
			"null" => NCONST,
			"boolean" => BCONST
		) as $key => $constant) {
			printf("%s:", $key);
			var_dump($constant);
		}
	}
}

$thread = new TestThread();
$thread->start();
?>
