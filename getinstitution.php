<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();

$ui=$_REQUEST;
$results=Array();
if(!$ui['id']){
		errorReturn('User not logged in and user to fetch not given');
}
$sql= "SELECT * FROM institution WHERE id='".mysqli_escape_string($con, $ui['id'])."'";
	$result = $con->query($sql);	
if(mysqli_num_rows($result)>0){
	$results['institution'] = $result->fetch_assoc();
	
}else{
	errorReturn('User not found');
}



$sql= "SELECT * FROM pubkey  WHERE id_type=1 AND id='".mysqli_escape_string($con, $ui['id'])."'";
$result = $con->query($sql);	
$rows=[];
while($row = $result->fetch_assoc()) {
  $rows[]=$row;
}	
$results['pubkey']=$rows;


	successReturn($results);

?>