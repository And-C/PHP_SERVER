<?php
// Add drop education record
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}

//Get instituion pubkey and chain
$sql= "SELECT * FROM institution WHERE id='".mysqli_escape_string($con, $ui['id'])."'";
	$result = $con->query($sql);	
		
if(mysqli_num_rows($result)>0){
	$result =$result->fetch_assoc();
}else{
	errorReturn('Institution not found');
}



//Check institution exist
// Nah actually there could be multiple institution with same names.
if(countrow($con,'pubkey',"id='".mysqli_escape_string($con, $_SESSION['uid'])."' AND id_type='0' AND chain = '".mysqli_escape_string($con, $result['chain'])."' AND pubkey= '".mysqli_escape_string($con, $result['pubkey'])."'")==0){
	errorReturn('You do not own the instituion\'s key. Please add the following key to your profile and claim again: Network:'.$result['chain'].' Key:'.$result['pubkey']);
}

//Change pubkey owner to the institution
$sql = "UPDATE pubkey SET id_type=1 , id= '".mysqli_escape_string($con,$ui['id'])."' WHERE id='".$_SESSION['uid']."' AND id_type='0' AND chain = '".mysqli_escape_string($con, $result['chain'])."' AND pubkey= '".mysqli_escape_string($con, $result['pubkey'])."'";
$result = $con->query($sql);


//Change the page owner to user
$sql = "UPDATE institution SET bind_uid= '".mysqli_escape_string($con,$_SESSION['uid'])."' WHERE id='".$ui['id']."'";
$result = $con->query($sql);


//Change user to institution

$sql = "UPDATE users SET is_institution='1' WHERE id='".$_SESSION['uid']."'";
$result = $con->query($sql);



successReturn('You\'ve successfully claimed the page');


?>