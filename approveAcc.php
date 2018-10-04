<?php
include 'Connection.php';
session_start();
if ($_SESSION["uid"]!=0 or !isset($_SESSION["uid"])){
	echo "Login as Admin to access this page";
	header('Location:login.html');
}
try {
	$pdo = Connection::get()->connect();
	approve($pdo);
} catch (\PDOException $e) {
	echo $e->getMessage();
}

function approve($pdo) {

	$sql = "UPDATE account SET status='active',balance=:bal where acc_no=:acc";
	$stmt = $pdo->prepare($sql);
	echo $_POST["acc"];
	echo $_POST["bal"];
	$stmt->bindValue(':acc', $_POST["acc"]);
	$stmt->bindValue(':bal', $_POST['bal']);
	$stmt->execute();
	if($stmt){
	echo "<script>window.alert('Account Activated');
	location.href = 'adminPage.php';</script>";
	}
	return;
}
$pdo=NULL;
?>
