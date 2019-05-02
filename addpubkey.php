<?php
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}

if($ui['pubkey']=='input'){
	$ui['pubkey']=$ui['pubkey_input'];
}

// Check empty inputs
if(empty($ui['pubkey'])){
	errorReturn('public key is empty');
}
if(empty($ui['chain'])){
	errorReturn('Chain is empty');
}

if(($ui['id']==''||$ui['id']=='self')){
	$ui['id']=$_SESSION['uid'];
	$ui['is_institution']=0;
}else{
	$ui['is_institution']=1;
}

$ui['pubkey']=strtolower($ui['pubkey']);

if(countrow($con,'pubkey',"pubkey='".mysqli_escape_string($con, $ui['pubkey'])."' AND chain='".mysqli_escape_string($con, $ui['chain'])."'")){
	errorReturn('The address already exist in our records');
}

$res = file_get_contents('http://certi.me:3000/verifySig?sig='.urlencode($ui['proof']).'&challenge='.urlencode('Certi.me proof for'.($ui['is_institution']=='1' ? ' institution' : '').' account '.$ui['id']));

if($res!=$ui['pubkey']){
   errorReturn( "Your signature does not resemble your address: ".$res);
}

$sql= "INSERT INTO pubkey (`pubkey`,`chain`,`id`,`id_type`,`signature`) VALUES ('".mysqli_escape_string($con,$ui['pubkey'])."','".mysqli_escape_string($con,$ui['chain'])."','".mysqli_escape_string($con,$ui['id'])."','".mysqli_escape_string($con,$ui['is_institution'])."','".mysqli_escape_string($con,$ui['proof'])."')"; 
if ($con->query($sql) === TRUE) {
    successReturn('Success');
} else {
   errorReturn( "Add pubkey failed".$sql);
}

//	$sql = "SELECT * FROM table WHERE userid=''";
//	$result = $con->query($sql);	
//	$row = $result->fetch_assoc();


?>