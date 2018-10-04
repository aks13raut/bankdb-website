<?php

include 'Connection.php';

try {
	$pdo = Connection::get()->connect();
	cmd($pdo,'BEGIN');
	$uid = insertUser($pdo);
	$addr_id = insertAddress($pdo);
	$cid = insertCustomer($pdo,$addr_id);
	cmd($pdo,'COMMIT');
	
	echo "
	<script>window.alert('Registration Successfull');
	location.href = 'login.html';</script>";
} catch (\PDOException $e) {
	echo $e->getMessage();
}
function cmd($pdo,$cmd) {
	$stmt = $pdo->prepare($cmd);
	$stmt->execute();
}
function insertAddress($pdo) {
	
	$sql = 'INSERT INTO address(addr_line1,addr_line2,city,state,pincode) 
	VALUES(:adrL1,:adrL2,:city,:state,:pincode)';
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':adrL1', $_POST['address-line-1']);
	$stmt->bindValue(':adrL2', isset($_POST['address-line-2'])?$_POST['address-line-2']:'NULL');
	$stmt->bindValue(':city', $_POST['city']);
	$stmt->bindValue(':pincode', $_POST['pincode']);
	$stmt->bindValue(':state', $_POST['state']);
	
	$stmt->execute();
	
	return $pdo->lastInsertId();
}

function insertCustomer($pdo,$p_addr_id) {
	
	$sql = 'INSERT INTO customer(id,f_name,m_name,l_name,mobile,email,dob,perm_addr,cors_addr,gender) 
	VALUES(:cid,:fname,:mname,:lname,:mobile,:email,:dob,:p_address,:c_address,:gender)';
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':cid', $_POST['cid']);
	$stmt->bindValue(':fname', $_POST['first_name']);
	$stmt->bindValue(':mname', isset($_POST['middle_name'])?$_POST['middle_name']:'NULL');
	$stmt->bindValue(':lname', $_POST['last_name']);
	$stmt->bindValue(':mobile', $_POST['mobile']);
	$stmt->bindValue(':email', $_POST['e-mail']);
	$stmt->bindValue(':dob', $_POST['dob']);
	$stmt->bindValue(':p_address', $p_addr_id);
	$stmt->bindValue(':c_address', $p_addr_id);
	$stmt->bindValue(':gender', $_POST['gender']);
	
	$stmt->execute();
	return $_POST['cid'];
}

function insertUser($pdo) {
	
	$sql = "INSERT INTO user(email,pswd) 
	VALUES(:email,AES_ENCRYPT(:pswd,'********'))";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':email', $_POST['e-mail']);
	$stmt->bindValue(':pswd', $_POST['password']);
	
	$stmt->execute();
	return $pdo->lastInsertId();
}
$pdo=NULL;
?>
