<?php
// Add drop education record
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}
//Drop inputs: drop=1, id=education:id, 
//Add inputs: issuer_id, title, class


if($ui['drop']=='1'){
	if(($ui['id'])==''){
		errorReturn('Item to remove is not provided');
	}

	//Check education record exist
	//Check education record belongs to user
	if(countrow($con,'education',"id='".mysqli_escape_string($con, $ui['id'])."' AND uid='".mysqli_escape_string($con, $_SESSION['uid'])."'")==0){
		errorReturn('The record does not exist or does not belong to you');
	}
	
	$sql= "DELETE FROM education WHERE id='".mysqli_escape_string($con,$ui['id'])."' AND uid='".mysqli_escape_string($con,$_SESSION['uid'])."'"; 
	
	//Proceed Dropping
	if ($con->query($sql) === TRUE) {
		$last_id = $con->insert_id;
		successReturn("Success");
	} else {
	   errorReturn( "Failed to remove");
	}
}else{
	if(empty($ui['issuer_id']) ){
		errorReturn('Please specify institution');
	}
	if(strlen($ui['title'])<2 ){
		errorReturn('Please specify Title');
	}
	if(strlen($ui['class'])<2 ){
		errorReturn('Please specify Qualification Class');
	}
	

	if(!dateCheck($ui['issuance_date'])){
		errorReturn('Please specify qualificaton issuance date');
	}
	//Check isser exist
	if(countrow($con,'institution',"id='".mysqli_escape_string($con, $ui['issuer_id'])."'")==0){
		errorReturn('Institution specified does not exist');
	}
	//Check title length <128
	if(strlen($ui['title'])>128){
		errorReturn("Title length too long, limited to 128 characters");
	}	
	//Check class length <128
	if(strlen($ui['class'])>128){
		errorReturn("Qualification Class length too long, limited to 128 characters");
	}	
	//Check desc length <256
	if(strlen($ui['description'])>256){
		errorReturn("Short Description length too long, limited to 256 characters");
	}	
	//Add
	$sql= "INSERT INTO education (uid,issuer_id,title,class,description,issuance_date,send_time) VALUES ('".mysqli_escape_string($con,$_SESSION['uid'])."','".mysqli_escape_string($con,$ui['issuer_id'])."','".mysqli_escape_string($con,$ui['title'])."','".mysqli_escape_string($con,$ui['class'])."','".mysqli_escape_string($con,$ui['description'])."','".mysqli_escape_string($con,$ui['issuance_date'])."',NOW())"; 
	
	if ($con->query($sql) === TRUE) {
		$last_id = $con->insert_id;
		successReturn(Array("id"=>$last_id));
	} else {
	   errorReturn($sql."Failed to add qualification.");
	}

}

//
//	Below useless for your ref
//

?>