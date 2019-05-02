<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();

$ui=$_REQUEST;
$results=Array();
if(!$ui['id']){
		errorReturn('Cert ID not specified');
}

$sql= "SELECT * FROM certificate  WHERE certid='".mysqli_escape_string($con, $ui['id'])."'";
$result = $con->query($sql);	 	
if(mysqli_num_rows($result)) {
	$row = $result->fetch_assoc();
	successReturn($row);
}	else{
	successReturn("No Results");
}
?>