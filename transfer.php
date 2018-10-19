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
 <link rel="stylesheet" href="./styles/style3.css">
 <link rel="stylesheet" href="./styles/homepage.css">
 <title>Transfer</title>
</head>
<body>
<nav>
  <ul>
	<li><a href="customerPage.php"><img src="./images/home.png" style="width:32px;height:32px"></a></li>
	<li><a href="#news" style="height:32px">News</a></li>
	<li><a href="transfer.php" style="height:32px">Transfer Money</a></li>
	<li><a href="addAcc.html" style="height:32px">Add Account</a></li>
	<li><a href="customerPage.php#transactions_view" style="height:32px">Transactions</a></li>
	<li style="float:right">
	<a href="#" onclick="document.forms['logoutForm'].submit();" style="height:32px">Logout</a></li>
	<form name='logoutForm' action='logout.php' method='get'>
	</form>
  </ul>
</nav>
 <center>
 <h1>Transfer Money</h1>
 <hr>
 <br>
 <br>
 <form name="transferForm" action="transferMoney.php" method="POST">
  <label for="to_acc"> To account no: </label> 
   <input type="text" pattern="[0-9]{10}" name="to_acc" style="width:97px;" required><br>
  <label for="from_acc"> From account no: </label>
<?php
  listAccNo($pdo);
?>
  <label for="amount"> Amount: </label>
   <input type="text" pattern="[0-9]+" name="amount" >₹<br>
   <br>
  <button type="submit" style="background-color: #ffa500;
  border: none; color: white; padding: 15px 32px;
  text-align: center; text-decoration: none;
  display: inline-block;">Transfer</button>
 </form>
 </center>
</body>
</html>
<?php
$pdo=NULL;
?>
