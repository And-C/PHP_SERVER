<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();

$ui=$_REQUEST;
$results=Array();
if(!$ui['id']&&$ui['inst']!='1'){
	$ui['id']=$_SESSION['uid'];
	if(!$_SESSION['uid']){
		errorReturn('User not logged in and user to fetch not given');
	}
}

if($ui['inst']!='1'){
	$sql= "SELECT * FROM users WHERE id='".mysqli_escape_string($con, $ui['id'])."'";
		$result = $con->query($sql);	
	if(mysqli_num_rows($result)>0){
		$results['users'] = $result->fetch_assoc();
		unset($results['users']['password']);
	}else{
		errorReturn('User not found');
	}
}else{
	$sql= "SELECT * FROM institution WHERE id='".mysqli_escape_string($con, $ui['id'])."'";
		$result = $con->query($sql);	
	if(mysqli_num_rows($result)>0){
		$results['users'] = $result->fetch_assoc();
		if($results['users']['bind_uid']==$_SESSION['uid']){
			$_SESSION['i_id']=$results['users']['id'];
		}else{
			unset($_SESSION['i_id']);
		}
	}else{
		errorReturn('Institution not found');
	}
}
if($ui['inst']!='1'){
$sql= "SELECT *,e.issuance_date as e_issuance_date,e.id as e_id,e.description as e_desc FROM education as e LEFT JOIN institution as i on e.issuer_id=i.id LEFT JOIN certificate as c on e.certid=c.certid  WHERE uid='".mysqli_escape_string($con, $ui['id'])."'";
$result = $con->query($sql);	
$rows=[];
while($row = $result->fetch_assoc()) {
  $rows[]=$row;
}	
$results['education']=$rows;
}
if($ui['inst']!='1'){
	$sql= "SELECT * FROM pubkey  WHERE id_type=0 AND id='".mysqli_escape_string($con, $ui['id'])."'";
}else{
	$sql= "SELECT * FROM pubkey  WHERE id_type=1 AND id='".mysqli_escape_string($con, $ui['id'])."'";
}
$result = $con->query($sql);	
$rows=[];
while($row = $result->fetch_assoc()) {
  $rows[]=$row;
}	
$results['pubkey']=$rows;

if($ui['inst']=='1'){
	//get recently binded certificate with this institution registed
	$sql= "SELECT * FROM certificate  WHERE issuer_id='".mysqli_escape_string($con, $ui['id'])."' AND proof_level=0 ORDER BY certid DESC LIMIT 5";
	$result = $con->query($sql);	
	$rows=[];
	while($row = $result->fetch_assoc()) {
	  $rows[]=$row;
	}	
	$results['issuance']=$rows;
}


	successReturn($results);

?>