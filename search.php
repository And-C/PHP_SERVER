<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}
$ui=$_REQUEST;
$uid=$_SESSION['uid'];
$query='\'%'.mysqli_escape_string($con, $ui['query']).'%\'';

if($ui['query']==''){
	errorReturn('Input required');

}




$sql= "SELECT id_type,GROUP_CONCAT(id SEPARATOR ',') AS id_list,GROUP_CONCAT(pubkey SEPARATOR ',') FROM pubkey WHERE CONCAT(chain,':',pubkey) LIKE ".$query." GROUP BY id_type";
$result = $con->query($sql);	
$chainsearch=[];
while($row = $result->fetch_assoc()) {
  $chainsearch[$row['id_type']]=$row;
}

$sql= "SELECT profile_img, CONCAT(last_name,' ',first_name) as name
,id,current_job,current_job_title,current_edu,current_edu_title FROM users WHERE CONCAT(last_name,' ',first_name) LIKE  ".$query."  OR CONCAT(current_job,current_job_title,current_edu,current_edu_title) LIKE ".$query." ";

if(isset($chainsearch[0])){
	$sql.=' OR id in ('.$chainsearch[0]['id_list'].')';
}
$result = $con->query($sql);	
$rows=[];
if($result){
while($row = $result->fetch_assoc()) {
  $rows[]=$row;
}	
}
$results=Array();
$results['users']=$rows;

$sql= "SELECT pubkey,id,profile_img, name
,description,website,chain FROM institution WHERE CONCAT(description,website,name,pubkey) LIKE  ".$query;
if(isset($chainsearch[1])){
	$sql.=' OR id in ('.$chainsearch[1]['id_list'].')';
}
if($ui['pubkey_search']=='1'){
$sql= "SELECT DISTINCT (id) as inst_id FROM ((SELECT id FROM pubkey WHERE id_type=1 AND pubkey='".mysqli_escape_string($con, $ui['query'])."') UNION (SELECT id FROM institution WHERE pubkey='".mysqli_escape_string($con, $ui['query'])."')) as t1";
}

if($query=="'%myinstitutions%'"){
$sql= "SELECT pubkey,id,profile_img, name
,description,website,chain FROM institution WHERE bind_uid =".$_SESSION['uid'];

}

$result = $con->query($sql);	
$rows=[];

if($result){
while($row = $result->fetch_assoc()) {
  $rows[]=$row;
}	
}
$results['inst']=$rows;



successReturn($results);

?>