<?php
include 'Connection.php';
try {
	$pdo = Connection::get()->connect();
	
	echo "you did it(y)";
} catch (\PDOException $e) {
	echo $e->getMessage();
}
?>
