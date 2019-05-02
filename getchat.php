<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();

$ui=$_REQUEST;
$results=Array();

	if(!$_SESSION['uid']){
		errorReturn('User not logged in');
	}
if($ui['type']=='digest'){
	$sql= "SELECT * FROM (SELECT IF(c.sender < c.receiver,CONCAT(c.sender, '-', c.receiver),CONCAT(c.receiver, '-', c.sender)) AS cid, c.*,CONCAT(u1.first_name,' ',u1.last_name) as s_name,CONCAT(u2.first_name,' ',u2.last_name) as r_name, u1.profile_img as s_propic, u2.profile_img as r_propic FROM chatroom as c left join users as u1 on c.sender=u1.id left join users as u2 on c.receiver=u2.id WHERE c.sender='".mysqli_escape_string($con, $_SESSION['uid'])."' OR  c.receiver='".mysqli_escape_string($con, $_SESSION['uid'])."' ORDER BY c.time DESC LIMIT 100) as x GROUP BY x.cid";
	$result = $con->query($sql);	
	$rows=Array();
	//echo $sql;
	while($row = $result->fetch_assoc()) {
	  $rows[]=$row;
	}	
	successReturn($rows);
}

if($ui['type']=='all'){
	$sql= "SELECT * FROM chatroom as c  WHERE c.sender='".mysqli_escape_string($con, $_SESSION['uid'])."' AND  c.receiver='".mysqli_escape_string($con, $ui['id'])."' OR c.sender='".mysqli_escape_string($con, $ui['id'])."' AND  c.receiver='".mysqli_escape_string($con, $_SESSION['uid'])."' ORDER BY time DESC";
	$result = $con->query($sql);	
	$rows=Array();
	while($row = $result->fetch_assoc()) {
	  $rows[]=$row;
	}	
	successReturn($rows);
}

if($ui['type']=='post'){
	if($_SESSION['uid']==$ui['id']){
   errorReturn( "Alone");
	}
	$sql= "INSERT INTO chatroom (`sender`,`receiver`,`message`,`time`) VALUES ('".mysqli_escape_string($con,$_SESSION['uid'])."','".mysqli_escape_string($con,$ui['id'])."','".mysqli_escape_string($con,$ui['msg'])."',NOW())"; 
if ($con->query($sql) === TRUE) {
    successReturn('Success');
} else {
   errorReturn( "Send msg failed");
}

}

?>