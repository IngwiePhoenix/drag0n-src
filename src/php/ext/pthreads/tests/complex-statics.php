<?php
class sql {
	public static $connection;

	public static function __callstatic($method, $args){
		$tid = Thread::getThreadId();
		if (isset(self::$connection[$tid])) {
			return call_user_func_array(array(self::$connection[$tid], "_{$method}"), $args);
		} else {
			self::$connection[$tid] = new sql();
			if (isset(self::$connection[$tid]))
				return call_user_func_array(array(self::$connection[$tid], "_{$method}"), $args);
		}
	}
	
	public function _query($sql){
		printf("%s: %s\n", __METHOD__, $sql);
	}
}

class UserThread extends Thread {
    public function run () {
        /* execute queries */
		sql::query("SELECT * FROM mysql.user");
		sql::query("SELECT * FROM mysql.user");
		
    }
}

sql::query("SELECT * FROM mysql.user");
sql::query("SELECT * FROM mysql.user");

$thread = new UserThread();
$thread->start();
?>
