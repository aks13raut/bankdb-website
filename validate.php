<?php
include 'Connection.php';
session_start();
function checkUser($pdo) {
	
	$sql = "SELECT id FROM user WHERE email = :email AND :pswd = AES_DECRYPT(pswd,'********');";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':email', $_POST['e-mail']);
	$stmt->bindValue(':pswd', $_POST['password']);
	$stmt->execute();
	$res = $stmt->fetch(\PDO::FETCH_ASSOC);
	return $res['id'];
}
if (isset($_POST["e-mail"])){
try {
	$pdo = Connection::get()->connect();
	$_SESSION["uid"] = checkUser($pdo);
	echo "id: ";
	echo $_SESSION["uid"];
	if ($_SESSION['uid'] > 0) {
		echo "<script>location.href = 'customerPage.php';</script>";
	}
	else if($_SESSION['uid'] === '0'){
		echo "<script>location.href = 'adminPage.php';</script>";
	}
	else {
		
		echo "<script>window.alert('Incorrect Login Credentials');
		location.href = 'index.html#login_form';</script>";
	}
} catch (\PDOException $e) {
	echo $e->getMessage();
}
$pdo=NULL;
}
?>
