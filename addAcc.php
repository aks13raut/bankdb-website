<?php
include 'Connection.php';

session_start();

try {
	$pdo = Connection::get()->connect();
	addAccount($pdo);
} catch (\PDOException $e) {
	echo $e->getMessage();
}

function addAccount($pdo) {
	
	$sql = "INSERT INTO account(acc_no,holder,status) VALUES(:acc,:cid,'unapproved');";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':acc', $_POST['acc_no']);
	$stmt->bindValue(':cid', $_SESSION['cid']);
	$stmt->execute();
	if($stmt){
		echo "<script>window.alert('Account Added, Approval Pending');
		location.href = 'addAcc.html';</script>";
	}
	return;
}
$pdo=NULL;
?>
