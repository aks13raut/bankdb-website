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
		echo "<button class='collapsible'>▼ Acc No: ".$row['acc_no']."</button>";
		echo "<div class='content'>";
		displayCustomer($pdo,$row['holder']);
		echo "<form name='approveForm".$count."' action='approveAcc.php' method='POST'>";
		echo "<input type='hidden' name='acc' value=".$row['acc_no'].">";
		echo "<br><br><label for='bal'>Balance : </label>";
		echo "<input type='number' name='bal'><br>";
		echo "<button type='submit' class='approve_btn'>Approve</button></form></div>";
		$count += 1;
	}
	return;
}
function dispDetails($pdo) {
	$sql = "SELECT count(DISTINCT holder) as customer,count(acc_no) as acc FROM account;";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$res = $stmt->fetch();
	$no_customers = $res['customer'];
	$no_accounts = $res['acc'];
	echo "
	<div class='card_holder'>
	<div class='card'>
     <h4><b>".$no_customers." Customers</b></h4> 
    </div>
	<div class='card'>
     <h4><b>".$no_accounts." Accounts</b></h4> 
    </div>
	<div style='clear: left;'>
	</div>
	";
	return;
}
function displayCustomer($pdo,$cid) {
	$sql = "SELECT f_name,m_name,l_name,email,dob,gender FROM customer WHERE id=:cid;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $cid);
	$stmt->execute();
	$row = $stmt->fetch(\PDO::FETCH_ASSOC);
	echo "<table class='customer_table' >";
	echo "<tr>
			<th align='left'>Customer Id</th>
			<td>".$cid."</td>
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
function displayTransaction($pdo) {
	$sql = "SELECT * FROM `transaction` ORDER BY date_time DESC";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$res = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
	echo "<table>";
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
#collapsible_holder {
	padding: 8px;
	background-color: #8497b2;
	color: #1c133a;
	margin: 0 5px 10px 5px;
}
#transaction_holder {
	padding: 8px;
	background-color: #8497b2;
	color: #1c133a;
	margin: 0 5px 10px 5px;

}
#transaction_holder table{
	color: #0f233f; 
	border: 1px solid #0f233f;
}
	
.collapsible {
  background-color: #0f233f;
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
  background-color: #44536b;
}
.content {
  padding: 0 18px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  background-color: #f1f1f1;
}

.customer_table {
   color: #0c3013; 
   margin: 18px 0 0 0;
}
.customer_table td {
   border: 1px solid black;
   padding: 5px 10px;
}

.approve_btn {
  padding: 5px;
  text-align: center;
  cursor: pointer;
  outline: none;
  color: #f1f1f1;
  background-color: #4CAF50;
  border: none;
  border-radius: 5px;
  box-shadow: 0 5px #999;
}
.approve_btn:hover {background-color: #3e8e41}

.approve_btn:active {
  background-color: #3e8e41;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}

.card_holder {
	margin: -10px;
}
.card {
	background-color: #0f233f;
	color: #9fa5ad;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
	float: left;
	text-align:center;
	border-radius: 10px;
	margin: 10px;
	padding: 2px 16px;
	width: 150px;
}
.card:hover {
	color:#e0ebf9;
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
</style>
</head>
<body style="background-color: #3c5275;color:#e0ebf9">
<h1><b><center>Admin Page</center></b></h1>
<?php
 dispDetails($pdo);
 echo "<div id='collapsible_holder'><h4>Pending Account Approvals</h4>";
 displayAccApr($pdo);
 echo "</div>";
 echo "<div id='transaction_holder'><h4>Transactions</h4>";
 displayTransaction($pdo);
 echo "</div>";
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
