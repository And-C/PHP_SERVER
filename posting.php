<?php
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}


// Check empty inputs
if(empty($ui['content'])){
	errorReturn('Content is empty');
}

// Check input length
if(strlen($ui['content'])>2048){
	errorReturn('Content limits to 2048 characters');
}

// Check parentpost exist
if(!empty($ui['parent_post'])){
if(countrow($con,'post',"id='".mysqli_escape_string($con, $ui['parent_post'])."'")==0){
	errorReturn('Post you are responding to does not exist');
}
}else{
	$ui['parent_post']=0;
}

//Check sender relation with parent_post sender


$sql= "INSERT INTO post (uid,content,send_time,parent_post) VALUES ('".mysqli_escape_string($con,$_SESSION['uid'])."','".mysqli_escape_string($con,$ui['content'])."',NOW(),'".mysqli_escape_string($con,$ui['parent_post'])."')"; 

if ($con->query($sql) === TRUE) {
    $last_id = $con->insert_id;
    successReturn(Array("id"=>$last_id,"content"=>$ui['content'],"uid"=>$_SESSION['uid'],"parent_post"=>$ui['parent_post']));
} else {
   errorReturn( "Posting failed");
}

//	$sql = "SELECT * FROM table WHERE userid=''";
//	$result = $con->query($sql);	
//	$row = $result->fetch_assoc();


?>