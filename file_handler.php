<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();

$ui=$_REQUEST;
$table="users";
$target= ($_SERVER['QUERY_STRING']);
if(!$_SESSION['uid']){
		errorReturn('Not logged in');
}
$usercontent_directory='usercontent/img/';
if($target=='user_cover'){
	$usercontent_directory='usercontent/img/';

	//Check fields: Uploaded File
	if(!file_exists($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
		errorReturn('Please specify your file');
	}
	if(($_FILES['file']['size'])>$hostedFileSizeLimit) {
		errorReturn('Oversized file. Choose non-hosted method instead.');
	}
	$hash=hash_file ("sha256" , $_FILES['file']['tmp_name']);
	$target_file = $usercontent_directory .$hash.'.'.  end((explode(".", $_FILES["file"]["name"])));
			if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		   errorReturn( "File upload failed");
		}
	$sql= "UPDATE users SET cover_img = '".mysqli_escape_string($con,$target_file)."' WHERE id = '".$_SESSION['uid']."'";
	if ($con->query($sql) === TRUE) {
		successReturn(Array("path"=>$target_file));
	}else{
		   errorReturn( $sql);
	}
}


if($target=='user_propic'){
	$usercontent_directory='usercontent/img/';

	//Check fields: Uploaded File
	if(!file_exists($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
		errorReturn('Please specify your file');
	}
	if(($_FILES['file']['size'])>$hostedFileSizeLimit) {
		errorReturn('Oversized file. Choose non-hosted method instead.');
	}
	$hash=hash_file ("sha256" , $_FILES['file']['tmp_name']);
	$target_file = $usercontent_directory .$hash.'.'.  end((explode(".", $_FILES["file"]["name"])));
			if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		   errorReturn( "File upload failed");
		}
	$sql= "UPDATE users SET profile_img = '".mysqli_escape_string($con,$target_file)."' WHERE id = '".$_SESSION['uid']."'";
	if ($con->query($sql) === TRUE) {
		successReturn(Array("path"=>$target_file));
	}else{
		   errorReturn( $sql);
	}
}


if($target=='i_user_cover'){
	if(!isset($_SESSION['i_id'])){errorReturn();}
	$usercontent_directory='usercontent/img/';

	//Check fields: Uploaded File
	if(!file_exists($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
		errorReturn('Please specify your file');
	}
	if(($_FILES['file']['size'])>$hostedFileSizeLimit) {
		errorReturn('Oversized file. Choose non-hosted method instead.');
	}
	$hash=hash_file ("sha256" , $_FILES['file']['tmp_name']);
	$target_file = $usercontent_directory .$hash.'.'.  end((explode(".", $_FILES["file"]["name"])));
			if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		   errorReturn( "File upload failed");
		}
	$sql= "UPDATE institution SET cover_img = '".mysqli_escape_string($con,$target_file)."' WHERE id = '".$_SESSION['i_id']."'";
	if ($con->query($sql) === TRUE) {
		successReturn(Array("path"=>$target_file));
	}else{
		   errorReturn( $sql);
	}
}


if($target=='i_user_propic'){
	if(!isset($_SESSION['i_id'])){errorReturn();}
	$usercontent_directory='usercontent/img/';

	//Check fields: Uploaded File
	if(!file_exists($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
		errorReturn('Please specify your file');
	}
	if(($_FILES['file']['size'])>$hostedFileSizeLimit) {
		errorReturn('Oversized file. Choose non-hosted method instead.');
	}
	$hash=hash_file ("sha256" , $_FILES['file']['tmp_name']);
	$target_file = $usercontent_directory .$hash.'.'.  end((explode(".", $_FILES["file"]["name"])));
			if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		   errorReturn( "File upload failed");
		}
	$sql= "UPDATE institution SET profile_img = '".mysqli_escape_string($con,$target_file)."' WHERE id = '".$_SESSION['i_id']."'";
	if ($con->query($sql) === TRUE) {
		successReturn(Array("path"=>$target_file));
	}else{
		   errorReturn( $sql);
	}
}


?>