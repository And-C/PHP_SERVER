<?php
include_once('dbconn.php');
include_once('php_func.php');
session_start();
$ui=$_REQUEST;
if($ui['type']=='certdata'){
	successReturn(json_encode(ethCall($ui['data'])));	
}

if($ui['type']=='address'){
	successReturn(json_encode(getAddressTx($ui['addr'])));	
}


?>