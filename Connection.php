<?php

class Connection {
	
	private static $conn;
	
	public function connect() {
		$params = parse_ini_file('database.ini');
		if ($params === false) {
			throw new \Exception("Error reading database configuration file");
		}
		$conStr = sprintf("mysql:host=%s;dbname=%s;",
						  $params['host'],$params['database']);
		$pdo = new \PDO($conStr,$params['user'],$params['password']);
		
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}
	
	public static function get() {
		if (null === static::$conn) {
			static::$conn = new static();
		}
		return static::$conn;
	}
	
	protected function __construct() {
		
	}
	
	private function __clone() {
		
	}
	
	private function __wakeup() {
		
	}
}
?>
