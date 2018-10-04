<?php
include 'Connection.php';
session_start();
if ($_SESSION["uid"]!=0 or !isset($_SESSION["uid"])){
	echo "Login as Admin to access this page";
	header('Location:login.html');
}
try {
	$pdo = Connection::get()->connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
	echo $e->getMessage();
}

function displayAccApr($pdo) {
	$sql = "SELECT holder,acc_no FROM account WHERE status='unapproved'";
	$count=1;
	foreach($pdo->query($sql, PDO::FETCH_ASSOC) as $row){
		echo "<button class='collapsible'>▼ ".$row['acc_no']."</button>";
		echo "<div class='content'>";
		displayCustomer($pdo,$row['holder']);
		echo "<form name='approveForm".$count."' action='approveAcc.php' method='POST'>";
		echo "<input type='hidden' name='acc' value=".$row['acc_no'].">";
		echo "<br><br><label for='bal'>Balance : </label>";
		echo "<input type='number' name='bal'><br>";
		echo "<button type='submit'>Approve</button></form></div>";
		$count += 1;
	}
	return;
}
function displayCustomer($pdo,$cid) {
	$sql = "SELECT f_name,m_name,l_name,email,dob,gender FROM customer WHERE id=:cid;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $cid);
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
function displayTransaction($pdo) {
	$sql = "SELECT * FROM transaction WHERE from_acc in (SELECT acc_no FROM account WHERE holder=:cid)
	or to_acc in (SELECT acc_no FROM account WHERE holder=:cid) order by date_time;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $_SESSION['cid']);
	$stmt->execute();
	$res = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
	echo "<table style='border: solid 1px black;'>";
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
 <title>Admin Page</title>
<style>
.collapsible {
  background-color: #777;
  color: white;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.active, .collapsible:hover {
  background-color: #555;
}

.content {
  padding: 0 18px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  background-color: #f1f1f1;
}
</style>
</head>
<body>
<h4>Show</h4>

<h4>Pending Account Approvals</h4>
<?php
 displayAccApr($pdo);
# echo "<p name='customerDetails'>";
# if(isset($_POST['dispCust']))
#  displayCustomer($pdo);
# echo "</p><p name='accountDetails'>";
# if(isset($_POST['dispCust']))
#  displayAcc($pdo);
#echo "</p>";
#displayTransaction($pdo);
?>
</body>
<script>
var coll = document.getElementsByClassName('collapsible');
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener('click', function() {
    this.classList.toggle('active');
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + 'px';
    } 
  });
}
</script>
</html>
<?php
$pdo=NULL;
?>
