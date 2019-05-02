<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();
if($_SESSION['uid']==''){
	exit();
}
$ui=$_REQUEST;

if($ui['t']=='users'){
	if(!in_array($ui['field'],Array('first_name','last_name','password','tag_line','current_job','current_job_title','current_edu','current_edu_title','privacy'))){
		errorReturn("Invalid field to edit");
	}
	$arrLen=Array('first_name'=>64,'last_name'=>64,'password'=>-1,'tag_line'=>128,'current_job'=>64,'current_job_title'=>32,'current_edu'=>64,'current_edu_title'=>32,'privacy'=>1);
	if($arrLen[$ui['field']]!=-1){
		if(strlen($ui['value'])>$arrLen[$ui['field']]){
			errorReturn("Input length too long, limited to ".$arrLen[$ui['field']]." characters");
		}
	}
	if(strlen($ui['value'])==0){
			errorReturn("Should not be empty");
	}

	if($ui['field']=='password'){
		//hashpw
		$ui['value']=sha256($ui['value']);
	}
	$sql = "UPDATE users SET ".$ui['field']." = '".mysqli_escape_string($con,$ui['value'])."' WHERE id='".$_SESSION['uid']."'";
	$result = $con->query($sql);
	successReturn(1);
	exit();
}
if($ui['t']=='institution'){
	if(!in_array($ui['field'],Array('name','description','type','website'))){
		errorReturn("Invalid field to edit");
	}
	$arrLen=Array('name'=>128,'description'=>512,'type'=>32,'website'=>1024);
	if($arrLen[$ui['field']]!=-1){
		if(strlen($ui['value'])>$arrLen[$ui['field']]){
			errorReturn("Input length too long, limited to ".$arrLen[$ui['field']]." characters");
		}
	}
	if(strlen($ui['value'])==0){
			errorReturn("Should not be empty");
	}

	$sql = "UPDATE institution SET ".$ui['field']." = '".mysqli_escape_string($con,$ui['value'])."' WHERE id='".mysqli_escape_string($con,$ui['inst_id'])."'";
	$result = $con->query($sql);
	successReturn(1);
	exit();
}
?>