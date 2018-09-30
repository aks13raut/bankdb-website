<?php
include 'Connection.php';

session_start();

try {
	$pdo = Connection::get()->connect();
	$_SESSION["uid"] = checkUser($pdo);
	echo "id: ";
	echo $_SESSION["uid"];
	if ($_SESSION['uid'] > 0) {
	echo "<script>location.href = 'customerPage.php';</script>";
	}
	else {
	echo "<script>window.alert('Incorrect Login Credentials');
	location.href = 'login.html';</script>";
	}
} catch (\PDOException $e) {
	echo $e->getMessage();
}

function checkUser($pdo) {
	
	$sql = "SELECT id FROM user WHERE email = :email AND :pswd = AES_DECRYPT(pswd,'********');";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':email', $_POST['e-mail']);
	$stmt->bindValue(':pswd', $_POST['password']);
	$stmt->execute();
	$res = $stmt->fetch(\PDO::FETCH_ASSOC);
	return $res['id'];
}
?>
