<?php
include 'Connection.php';
session_start();
if (!isset($_SESSION["uid"]) or $_SESSION["uid"]<=0){
	echo "Error: Login first, To access this page";
	header('Location:login.html');
}
try {
	$pdo = Connection::get()->connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	echo $_SESSION['cid'];
	
} catch (\PDOException $e) {
	echo $e->getMessage();
}

function listAccNo($pdo) {
	$sql = "SELECT acc_no FROM account WHERE holder=".$_SESSION['cid'];
	echo "<select name='from_acc'>";
	foreach($pdo->query($sql, PDO::FETCH_ASSOC) as $row){
    		echo 'Acc_no: ' . $row['acc_no'] . '<br>';
		echo "<option value='".$row['acc_no']."'>".$row['acc_no']."</option>";
	}
	echo "</select><br><br>";
	return;
}
?>
<!DOCTYPE html>
<html>
<head>
 <title>Transfer</title>
</head>
<body bgcolor=#D1F2EB font-color=#1C2833>
 <center><h1>Trnasfer Money</h1></center>
 <hr>
 <form name="transferForm" action="transferMoney.php" method="POST">
  <label for="to_acc"> To account no: </label> 
   <input type="text" pattern="[0-9]{10}" name="to_acc" required><br>
  <label for="from_acc"> From account no: </label>
<?php
  listAccNo($pdo);
?>
  <label for="amount"> Amount: </label>
   <input type="text" pattern="[0-9]+" name="amount" ><br>
  <button type="submit">Transfer</button>
 </form>
</body>
</html>
<?php
$pdo=NULL;
?>
