<?php
include 'Connection.php';
session_start();
#echo $_SESSION["uid"];
try {
	$pdo = Connection::get()->connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$_SESSION['cid'] = getCustomer($pdo);
} catch (\PDOException $e) {
	echo $e->getMessage();
}
class TableRows extends RecursiveIteratorIterator {
	function __construct($it) {
		parent::__construct($it,self::LEAVES_ONLY);
	}
	function current() {
		return "<td style='width:150px;border:1px solid black;'>".parent::current()."</td>";
	}
	function beginChildren() {
		echo "<tr>";
	}
	function endChildren() {
		echo "</tr>"."\n";
	}
}

function getCustomer($pdo) {
	$sql = "SELECT customer.id FROM customer,user WHERE user.id=:uid;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':uid', $_SESSION['uid']);
	$stmt->execute();
	$res = $stmt->fetch(\PDO::FETCH_ASSOC);
	return $res['id'];
}
function displayCustomer($pdo) {
	$sql = "SELECT f_name,m_name,l_name,email,dob,gender FROM customer WHERE id=:cid;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $_SESSION['cid']);
	$stmt->execute();
	$row = $stmt->fetch(\PDO::FETCH_ASSOC);
	echo "<table style='border: solid 1px black;' cellspacing=10>";
	foreach($row as $k=>$v) {
		echo "<tr><th align='left'>".$k."</th>
	<td style='width:150px;border:1px solid black;'>".$v."</td></tr>";
	}
	echo "</table>";
	return;
}
function displayAcc($pdo) {
	$sql = "SELECT acc_no,balance,status FROM account WHERE holder=:cid;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $_SESSION['cid']);
	$stmt->execute();
	$res = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
	echo "<table style='border: solid 1px black;'>";
	echo "<tr><th>Account Number</th><th>Balance</th><th>Status</th></tr>";
	foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
		echo $v;
	}
	echo "</table>";
	return;
}
?>
<html>
<head><title>Dashboard</title></head>
<body>
<h4>Your Details</h4>
<?php
displayCustomer($pdo);
?>
<h4>Your Accounts</h4>
<?php
displayAcc($pdo);
?>
</body>
</html>
