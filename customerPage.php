<?php
include 'Connection.php';
session_start();
if ($_SESSION["uid"]<=0 or !isset($_SESSION["uid"])){
	echo "<script>window.alert('Login first, To access this page');
	location.href = 'index.html#login_form';</script>";
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
	echo "<tr><th>Account Number</th><th>Balance(₹)</th><th>Status</th></tr>";
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
	echo "<tr><th>TID</th><th>FROM</th><th>TO</th><th>Amount(₹)</th><th>Date & Time</th></tr>";
	foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
		echo $v;
	}
	echo "</table>";
	return;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="./styles/homepage.css">
		<link rel="icon" href="banklogo4.png">
		<link rel="stylesheet" href="style3.css">
		<title>Dashboard</title>
	</head>
<body>
<nav>
  <ul>
	<li><a href="#"><img src="./images/home.png" style="width:32px;height:32px"></a></li>
	<li><a href="#news" style="height:32px">News</a></li>
	<li><a href="transfer.php" style="height:32px">Transfer Money</a></li>
	<li><a href="addAcc.html" style="height:32px">Add Account</a></li>
	<li><a href="#transactions_view" style="height:32px">Transactions</a></li>
	<li style="float:right">
	<a href="#" onclick="document.forms['logoutForm'].submit();" style="height:32px">Logout</a></li>
	<form name='logoutForm' action='logout.php' method='get'>
	</form>
  </ul>
</nav>

 
<style>

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
<h1><center>Welcome</center></h1>
<?php

echo "<h2>Your Details</h2><div id='holder'>";
displayCustomer($pdo);
echo "</div>";
echo "<h2>Your Accounts</h2><div id='holder'>";
displayAcc($pdo);
echo "</div>";
echo "<h2 id='transactions_view'>Transactions</h2><div id='holder'>";
displayTransaction($pdo);
echo "</div>";
?>
<br><br>
<footer class="ct-footer">
    <div class="container">
        <ul class="ct-footer-list text-center-sm">
            <li>
				<h2 class="ct-footer-list-header">Learn More</h2>
                <ul>
                <li>
					<a href="index.html">Company</a>
					<a href="index.html">Clients</a>
					<a href="index.html">News</a>
					<a href="index.html">Careers</a>
                </li>                
                </ul>
            </li>
            <li>
                <h2 class="ct-footer-list-header">Services</h2>
                <ul>
                <li>
					<a href="index.html">Loan</a>
					<a href="index.html">FD</a>
					<a href="index.html">Locker</a>
					<a href="index.html">Gold Loan</a>
					<a href="index.html">Support</a>
                </li>
                </ul>
            </li>
            <li>
                <h2 class="ct-footer-list-header">The Industry</h2>
                <ul>
                <li>
					<a href="index.html">Thought Leadership</a>
					<a href="index.html">Webinars</a>
					<a href="index.html">Events</a>
					<a href="index.html">Sponsorships</a>
					<a href="index.html">Training Program</a>
					<a href="index.html">Advisors</a>
					<a href="index.html">Activities &amp; Campaigns</a>
				</li>
                </ul>
            </li>
            <li>
                <h2 class="ct-footer-list-header">Public Relations</h2>
                <ul>
                <li>
					<a href="index.html">WebCorpCo Blog</a>
					<a href="index.html">Hackathons</a>
					<a href="index.html">Videos</a>
					<a href="index.html">News Releases</a>
					<a href="index.html">Newsletters</a>
				</li>
                </ul>
            </li>
            <li>
                <h2 class="ct-footer-list-header">About</h2>
                <ul>
                <li>
					<a href="index.html">FAQ</a>
					<a href="index.html">Our Board</a>
					<a href="ourteam.html">Our Staff</a>
					<a href="contactus.html">Contact Us</a>
				</li>
                </ul>
            </li>
        </ul>
	<ul class="ct-socials">
		<li>
			<a href="https://www.facebook.com/login/" target="_blank"><img alt="facebook" src="./images/facebook.png" style="width:20px ; height:20px"></a>
		</li>
		<li>
			<a href="https://twitter.com/login" target="_blank"><img alt="twitter" src="./images/twitter.png" style="width:20px ; height:20px"></a>
		</li>
		<li>
			<a href="https://www.youtube.com/" target="_blank"><img alt="youtube" src="./images/youtube.png" style="width:20px ; height:20px"></a>
		</li>
		<li>
			<a href="https://www.instagram.com/" target="_blank"><img alt="instagram" src="./images/insta.png" style="width:20px ; height:20px"></a>
		</li>
	</ul>
    </div>         
	<div class="inner-right">
		<p>Copyright © 2018 AKSHATAMOLADITYA</p>
	</div>                   
</footer>
</body>
</html>
<?php
$pdo=NULL;
?>