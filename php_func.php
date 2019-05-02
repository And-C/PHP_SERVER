<?php
$ETHERSCAN_API_KEY="G4MNQANBUU9JSXDZ3S95GUK3Z5VH8MFCKE";
$CONTRACT_ADDR="0x8dffd6644cf466d083fc6db8c61ad88443e48c99";
function errorReturn($msg){
	http_response_code(400);
	$data=Array("status"=>"Error",'response'=>$msg);
	echo json_encode($data);
	exit();
}
function successReturn($msg){
	$data=Array("status"=>"Success",'response'=>$msg);
	echo json_encode($data);
	exit();
}

function dateCheck($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}
function countrow($con,$table,$req){
	$sql="SELECT COUNT(*) FROM ".$table." WHERE ".$req;
//	echo $sql;
//	exit();
	$result = $con->query($sql);	
	$row = $result->fetch_assoc();
	//print_r($row);
	return $row['COUNT(*)'];
}
function sqlinsert($con,$sql){
	
}
function sha256($pw){
	return hash('sha256',$pw);
}


function getAddressTx($addr){
	global $ETHERSCAN_API_KEY;
	$result=json_decode(file_get_contents("http://api.etherscan.io/api?module=account&action=txlist&address=".$addr."&startblock=694000&endblock=99999999&sort=desc&apikey=".$ETHERSCAN_API_KEY),true);
	return $result;	
}

function ethCall($data){
	global $ETHERSCAN_API_KEY;
	global $CONTRACT_ADDR;
	//0x70a08231000000000000000000000000e16359506c028e51f16be38986ec5746251e9724
	$result=json_decode(file_get_contents("http://api.etherscan.io/api?module=proxy&action=eth_call&to=".$CONTRACT_ADDR."&data=".$data."&tag=latest&apikey=".$ETHERSCAN_API_KEY),true);
	return ($result);
	
}

$usercontent_directory='usercontent/proof/';
$hostedFileSizeLimit=5242880;
?>