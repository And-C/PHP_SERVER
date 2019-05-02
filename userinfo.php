<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}
$ui=$_REQUEST;
$d=[];
$sql= "SELECT * FROM users WHERE id='".mysqli_escape_string($con, $_SESSION['uid'])."'";
	$result = $con->query($sql);	
if(mysqli_num_rows($result)>0){
	$row = $result->fetch_assoc();
	unset($row['password']);
	$d=$row;
	
}else{
	errorReturn('User not found');
}

$sql= "SELECT target FROM relationship WHERE source='".$_SESSION['uid']."'";
$result = $con->query($sql);	
$rows=[];
while($row = $result->fetch_assoc()) {
  $rows[]=$row['target'];
}	
$d['to']=$rows;

$sql= "SELECT * FROM pubkey WHERE id='".$_SESSION['uid']."' AND id_type=0";
$result = $con->query($sql);	
$rows=[];
while($row = $result->fetch_assoc()) {
  $rows[]=$row;
}	
$d['pubkey']=$rows;


$sql= "SELECT source FROM relationship WHERE target='".$_SESSION['uid']."'";
$result = $con->query($sql);	
$rows=[];
while($row = $result->fetch_assoc()) {
  $rows[]=$row['source'];
}
$d['by']=$rows;

if($d['is_institution']=='1'){
$d['institution']=Array();
$sql= "SELECT * FROM institution WHERE bind_uid='".$_SESSION['uid']."'";
$result = $con->query($sql);	
$rows=[];
while($row = $result->fetch_assoc()) {
  array_push($d['institution'],$row);
}

}
successReturn($d);

?>