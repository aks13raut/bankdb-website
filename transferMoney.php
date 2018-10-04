<?php
include 'Connection.php';
session_start();
if (isset($_SESSION["cid"])){
try {
	$pdo = Connection::get()->connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT balance,status FROM account WHERE acc_no=".$_POST['from_acc'];
	foreach($pdo->query($sql, PDO::FETCH_ASSOC) as $row){
    		if($row['status']!='active'){
			echo "<script>window.alert('Account not active.contact your nearest branch.');
location.href = 'transfer.php';</script>";
			return;
		}
		if($row['balance']<$_POST['amount']){
			echo "<script>window.alert('Insufficient Balance in acc no ".$_POST['from_acc']."');
location.href = 'transfer.php';</script>";
			return;
		}
	}
	$pdo->beginTransaction();
	$sql='UPDATE account SET balance=balance-:amount WHERE acc_no=:acc1';
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':acc1', $_POST['from_acc']);
	$stmt->bindValue(':amount', $_POST['amount']);
	$stmt->execute();
	$sql = 'UPDATE account SET balance=balance+:amount WHERE acc_no=:acc2';
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':acc2', $_POST['to_acc']);
	$stmt->bindValue(':amount', $_POST['amount']);
	$stmt->execute();
	if($stmt->rowCount()==0){
		$pdo->rollBack();
		echo "<script>window.alert('Invalid account no ".$_POST['to_acc']."');
location.href = 'transfer.php';</script>";
		return;
	}
	$sql = 'INSERT INTO transaction(from_acc,to_acc,amount)	VALUES(:acc1,:acc2,:amount)';
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':acc1', $_POST['from_acc']);
	$stmt->bindValue(':acc2', $_POST['to_acc']);
	$stmt->bindValue(':amount', $_POST['amount']);
	$stmt->execute();
	$pdo->commit();
	echo "<script>window.alert('Transfer Successful.');
location.href = 'customerPage.php';</script>";
} catch (\PDOException $e) {
	echo $e->getMessage();
}
$pdo=NULL;
}
?>
