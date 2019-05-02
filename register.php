<?php
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
if($_SESSION['uid']!=''){errorReturn('User '.$_SESSION['uid'].' loggedin');}

// Check empty inputs
if(empty($ui['username'])||
	empty($ui['first_name'])||
	empty($ui['last_name'])||
	empty($ui['email'])||
	empty($ui['password'])||
	empty($ui['dob'])){
	errorReturn('Required fields empty');
}

// Check input length
if(strlen($ui['username'])>32){
	errorReturn('Username limits to 32 characters');
}
if(strlen($ui['first_name'])>64){
	errorReturn('First Name limits to 64 characters');
}
if(strlen($ui['last_name'])>64){
	errorReturn('Last Name limits to 64 characters');
}
if(strlen($ui['email'])>128){
	errorReturn('Email limits to 128 characters');
}
// Check input legal
if (!ctype_alnum($ui['username'])) {
   errorReturn( "Username alphanumberic only");
}
if (!filter_var($ui['email'], FILTER_VALIDATE_EMAIL)) {
   errorReturn( "Invalid Email");
}
if (!dateCheck($ui['dob'])) {
   errorReturn( "Invalid Date, YYYY-MM-DD format required");
}

// Check username exist
if(countrow($con,'users',"username='".mysqli_escape_string($con, $ui['username'])."'")){
	errorReturn('Username Exists');
}
if(countrow($con,'users',"email='".mysqli_escape_string($con, $ui['email'])."'")){
	errorReturn('Email Exists');
}

// Insert new data
$pw=sha256($ui['password']);

$sql= "INSERT INTO users (username,first_name,last_name,email,password,dob,reg_date) VALUES ('".mysqli_escape_string($con,$ui['username'])."','".mysqli_escape_string($con,$ui['first_name'])."','".mysqli_escape_string($con,$ui['last_name'])."','".mysqli_escape_string($con,$ui['email'])."','".mysqli_escape_string($con,$pw)."','".mysqli_escape_string($con,$ui['dob'])."',CURDATE())"; 

if ($con->query($sql) === TRUE) {
    $last_id = $con->insert_id;
	session_start();
	$_SESSION['uid']=mysqli_insert_id($con);
    successReturn(Array("id",mysqli_insert_id($con)));
	exit();
} else {
   errorReturn( "Error: " . $con->error);
}

//	$sql = "SELECT * FROM table WHERE userid=''";
//	$result = $con->query($sql);	
//	$row = $result->fetch_assoc();


?>