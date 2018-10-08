<?php
include 'Connection.php';
session_start();
if ($_SESSION["uid"]<=0 or !isset($_SESSION["uid"])){
	echo "Error: Login first, To access this page";
	header('Location:login.html');
}
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
		return "<td style='width:150px;border:1px solid #35514e;'>".parent::current()."</td>";
	}
	function beginChildren() {
		echo "<tr>";
	}
	function endChildren() {
		echo "</tr>"."\n";
	}
}

function getCustomer($pdo) {
	$sql = "SELECT customer.id FROM customer INNER JOIN user ON customer.email=user.email WHERE user.id=:uid;";
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
	echo "<table class='customer_table' >";
	echo "<tr>
			<th align='left'>Customer Id</th>
			<td>".$_SESSION['cid']."</td>
		  </tr>";
	echo "<tr>
			<th align='left'>Name</th>
			<td>".$row['f_name']." ".$row['m_name']." ".$row['l_name']."</td>
		  </tr>";
	echo "<tr>
			<th align='left'>Email</th>
			<td>".$row['email']."</td>
		  </tr>";
	echo "<tr>
			<th align='left'>Gender</th>
			<td>".$row['gender']."</td>
		  </tr>";
	echo "<tr>
			<th align='left'>DOB</th>
			<td>".$row['dob']."</td>
		  </tr>";
	echo "</table>";
	return;
}
function displayAcc($pdo) {
	$sql = "SELECT acc_no,balance,status FROM account WHERE holder=:cid;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $_SESSION['cid']);
	$stmt->execute();
	$res = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
	echo "<table style='border: solid 1px #35514e;'>";
	echo "<tr><th>Account Number</th><th>Balance</th><th>Status</th></tr>";
	foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
		echo $v;
	}
	echo "</table>";
	return;
}
function displayTransaction($pdo) {
	$sql = "SELECT * FROM transaction WHERE from_acc in (SELECT acc_no FROM account WHERE holder=:cid)
	or to_acc in (SELECT acc_no FROM account WHERE holder=:cid) order by date_time;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $_SESSION['cid']);
	$stmt->execute();
	$res = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
	echo "<table style='border: solid 1px #35514e;'>";
	echo "<tr><th>TID</th><th>FROM</th><th>TO</th><th>Amount</th><th>Date & Time</th></tr>";
	foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
		echo $v;
	}
	echo "</table>";
	return;
}
?>
<html>
<head>
 <title>Dashboard</title>
 <style>
h1 {
	width=100%;
	background-color:#04302b;
	color: #dbfffb;
	padding: 20px;
}
#holder {
	padding: 8px;
	background-color: #04302b;
	color: #dbe0df;
	margin: 0 5px 10px 5px;

}
#holder table{
	color: #fff; 
	border: 1px solid #0f233f;
}

.customer_table {
   color: #dbfffb; 
}
th {
   color: #bbb;
   padding: 5px 10px;
}
td {
	padding: 7px;
}
.customer_table td {
   border: 1px solid #35514e;
}

.btn {
  text-decoration: none;
  padding: 5px;
  text-align: center;
  cursor: pointer;
  outline: none;
  color: #f1f1f1;
  background-color: #086d62;
  border: none;
  border-radius: 5px;
  box-shadow: 0 5px #999;
  margin: 15px;
}
.btn:hover {background-color: #064c44}

.btn:active {
  background-color: #064c44;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}
</style>
</head>
<body style='background-color:#dbfffb;color:#0f443f'>
<h1><center>Welcome</center></h1>
<?php

echo "<h2>Your Details</h2><div id='holder'>";
displayCustomer($pdo);
echo "</div>";
echo "<h2>Your Accounts</h2><div id='holder'>";
displayAcc($pdo);
echo "</div>";
echo "<h2>Transactions</h2><div id='holder'>";
displayTransaction($pdo);
echo "</div>";
?>
<a class='btn' href="transfer.php">Transfer Money</a>
<a class='btn' href="addAcc.html">Add Account</a>
</body>
</html>
<?php
$pdo=NULL;
?>
