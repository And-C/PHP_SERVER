<?php
// Add drop education record
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}

$ui['pubkey']=strToLower($ui['pubkey']);

//Check institution exist
// Nah actually there could be multiple institution with same names.


if(countrow($con,'institution',"pubkey='".mysqli_escape_string($con, $ui['pubkey'])."'")){
	errorReturn('Instituion with this key Exists');
}
if(countrow($con,'pubkey',"pubkey='".mysqli_escape_string($con, $ui['pubkey'])."' AND id_type='1'")){
	errorReturn('Instituion with this key Exists');
}
//Check name within 128 char
	if(strlen($ui['name'])>128&&strlen($ui['name'])<1){
		errorReturn("Name length too long, limited to 1-128 characters");
	}
//Check pubkey within 128 char
	if(strlen($ui['pubkey'])>128&&strlen($ui['pubkey'])<1){
		errorReturn("Pubkey length too long, limited to 1-128 characters");
	}//Check pubkey within 128 char
	if(strlen($ui['pubkey'])!=42&&strlen($ui['chain'])=='eth'){
		errorReturn("Pubkey format incorrect");
	}
//Check network within 128 char
	if(strlen($ui['chain'])>12&&strlen($ui['chain'])<1){
		errorReturn("NetworkID length too long, limited to 1-128 characters");
	}	
//Check desc within 1024 char
	if(strlen($ui['description'])>128&&strlen($ui['description'])<1){
		errorReturn("Name length too long, limited to 1-128 characters");
	}	
//Check institution type valid
	//$institution_types=Array('Kindergarten', 'Elementary', 'Secondary', 'Higher', 'University', 'Research','Military', 'Government', 'Non-profit', 'Company', 'Personal' );
	//Actually there are too many to list... Let the user add...
	
	if(strlen($ui['type'])>32&&strlen($ui['type'])<1){
		errorReturn("Institution type length too long, limited to 1-32 characters");
	}	
//Add new institution and return institution id
	if(!empty($ui['url'])){
		if(!filter_var($ui['url'], FILTER_VALIDATE_URL)||strlen($ui['type'])>1024){
			errorReturn("URL length too long or invalid, limited to 1-1024 characters");
		}
	}
//Add institution
	$sql= "INSERT INTO institution (name,pubkey,chain,description,reg_date,type,website) VALUES ('".mysqli_escape_string($con,$ui['name'])."','".mysqli_escape_string($con,$ui['pubkey'])."','".mysqli_escape_string($con,$ui['chain'])."','".mysqli_escape_string($con,$ui['description'])."',NOW(),'".mysqli_escape_string($con,$ui['type'])."','".mysqli_escape_string($con,$ui['website'])."')"; 

	//echo $sql;
	if ($con->query($sql) === TRUE) {
		$last_id = $con->insert_id;
		successReturn(Array("id"=>$last_id));
	} else {
	   errorReturn( "Failed to add institution.");
	}




?>