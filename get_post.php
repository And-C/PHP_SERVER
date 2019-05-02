<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}
$ui=$_REQUEST;
$uid=$_SESSION['uid'];
$sql= "SELECT u.profile_img as profile_img , CONCAT(u.last_name,' ',u.first_name) as name,p.content as content,p.send_time as send_time,p.uid as uid FROM post as p LEFT JOIN users as u on p.uid=u.id WHERE uid IN (SELECT distinct(target) FROM relationship as r WHERE source='$uid'  )OR uid = '$uid' AND parent_post=0 ORDER BY p.send_time DESC";
	$result = $con->query($sql);	
	$rows=[];
while($row = $result->fetch_assoc()) {
  $rows[]=$row;

}	
successReturn($rows);

?>