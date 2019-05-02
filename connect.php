<?php
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
$connection_update=false;
$remove_connection=false;
if($ui['status']=='0'){
	$remove_connection=true;
}
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}


// Check empty inputs
if(empty($ui['target'])){
	errorReturn('Target is empty');
}
if($ui['target']==$_SESSION['uid']){
	errorReturn('You could not connect with yourself');
}
if(($ui['status'])==''){
	errorReturn('Connection status level is empty');
}


// Check user already pending
if(countrow($con,'relationship',"`source`='".$_SESSION['uid']."' AND `target`='".mysqli_escape_string($con, $ui['target'])."'")!=0){
//	errorReturn('You already have pending or existing relationship with user you would like to connect to');
	$connection_update=true;
}

//Check target user exist
if(countrow($con,'users',"`id`='".mysqli_escape_string($con, $ui['target'])."'")==0){
	errorReturn('User you are connecting to does not exist.');
}


$sql= "INSERT INTO relationship (`source`,`target`,`status`,`time`) VALUES ('".mysqli_escape_string($con,$_SESSION['uid'])."','".mysqli_escape_string($con,$ui['target'])."','".mysqli_escape_string($con,$ui['status'])."',NOW())"; 
if($connection_update){
	$sql= "UPDATE relationship SET status='".mysqli_escape_string($con,$ui['status'])."' WHERE `source`='".mysqli_escape_string($con,$_SESSION['uid'])."' AND `target`='".mysqli_escape_string($con,$ui['target'])."'"; 
}
if($remove_connection){
	$sql= "DELETE FROM relationship WHERE `source`='".mysqli_escape_string($con,$_SESSION['uid'])."' AND `target`='".mysqli_escape_string($con,$ui['target'])."'"; 
}


if ($con->query($sql) === TRUE) {
    successReturn('Success');
} else {
   errorReturn( "Add connection failed");
}

//	$sql = "SELECT * FROM table WHERE userid=''";
//	$result = $con->query($sql);	
//	$row = $result->fetch_assoc();


?>