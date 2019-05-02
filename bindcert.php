<?php
// Add drop education record
include_once('dbconn.php');
include_once('php_func.php');
$ui=$_REQUEST; //User-input
session_start();
if(!$_SESSION['uid']){
	errorReturn('User not logged in');
}
$certCheckFailureStatus=0;
$ui['proof_hash']=$ui['merkle_hash'];

//This is here until latest smart contract deployed;
$ui['issuance_date']='2000-01-01';

//

/* Proof type
Simple Hash File (H0)
Simple Hash Text (H1)
Merkle Root 
*/
// Field 'force_add=1' allow level <10 to be added to database

	//Check fields:
	// Proof Chain,Proof TXID,  Proof hash, Proof Type
//	if(empty($ui['proof_hash']) ){
//		errorReturn('Please specify valid proof fingerprint');
//	}
// This should be retrieved from the blockchain
	if(empty($ui['proof_txid']) ){
		errorReturn('Please specify valid transaction identifier');
	}

	if(empty($ui['proof_chain']) ){
		errorReturn('Please specify valid network your proof is recorded in');
	}

	if(empty($ui['proof_type']) ){
		errorReturn('Please specify valid proof type');
	}

	$tx_data=file_get_contents('http://certi.me:3000/ethcall?method=certificates&param='.$_REQUEST['proof_txid']);
	function getHttpCode($http_response_header)
	{
		if(is_array($http_response_header))
		{
			$parts=explode(' ',$http_response_header[0]);
			if(count($parts)>1) //HTTP/1.0 <code> <text>
				return intval($parts[1]); //Get code
		}
		return 0;
	}
	
	if(getHttpCode($http_response_header)!=200){
		errorReturn('Unable to get transaction data');
	}
	$tx_data=json_decode($tx_data,true);
	//{"0":"f84bad044a6127461270b94c7bffda5239efb103db624dee90b79efebb3e136e","1":"0x08a8ad7C391285cdF77AA3347E0078a4FB59F663","2":"0x08a8ad7C391285cdF77AA3347E0078a4FB59F663","3":"10.0","4":"0","__length__":5,"certHash":"f84bad044a6127461270b94c7bffda5239efb103db624dee90b79efebb3e136e","issuer_addr":"0x08a8ad7C391285cdF77AA3347E0078a4FB59F663","recepient_addr":"0x08a8ad7C391285cdF77AA3347E0078a4FB59F663","version":"10.0","content":"0"}
	// Check if user input is honest against the blockchain
	$tx_data['certHash']=strtoupper($tx_data['certHash']);
	$tx_data['issuer_addr']=strtolower($tx_data['issuer_addr']);
	$tx_data['recepient_addr']=strtolower($tx_data['recepient_addr']);
	$tx_data['issuer_addr']=strtolower($tx_data['issuer_addr']);
	if(strtoupper($tx_data['certHash'])!=strtoupper($ui['merkle_hash'])){
		errorReturn('The proof you provided does not match the records on the public records'.strtoupper($tx_data['certHash']).' // '.strtoupper($ui['merkle_hash']));
	}

	$ui['proof_type']=substr($tx_data['version'],0,2);

	
	// Check transaction include proof hash (Else downgrade proof status)
	// Get transaction (issuance) date
		// Not available
	// Get transaction input output addresses
	
	// Check issuer id = entry in table:institution	
	// Check education:id belong to education:uid=$session{'id']
		//** Skipped, fetch data from table:education
	$sql = "SELECT uid,issuer_id FROM education WHERE id='".mysqli_escape_string($con, $ui['id'])."'";
	try {
		$result = $con->query($sql);	
		$row = $result->fetch_assoc();
		$ui['uid']=$row['uid'];
		$ui['issuer_id']=$row['issuer_id'];
		
		if($ui['uid']!=$_SESSION['uid']){
		errorReturn('Certificate is not yours');
		}
	} catch (Exception $e) {
		errorReturn('Failed to retrieve related education qualification');
	}
	if(empty($ui['issuer_id']) ){
		errorReturn('Please specify issuer');
	}
	//Check if issuer has public key registered in platform
	if(countrow($con,'pubkey',"id='".mysqli_escape_string($con, $ui['issuer_id'])."' AND id_type=1")==0){
		if($ui['force_add']!='1'){
			errorReturn('The issuer\'s public identification key is not registered in our platform. Please contact your issuer or select the correct institution for your academic records.');	
		}else{
			$certCheckFailureStatus=2;
		}
	}
	
	
	//Check if user has public key registered in platform and matches the certificate, if the certificate is recepient directed
	if($tx_data['recepient_addr']){
		if(countrow($con,'pubkey',"id='".mysqli_escape_string($con, $_SESSION['uid'])."' AND id_type=0 AND pubkey='".mysqli_escape_string($con, $tx_data['recepient_addr'])."'")==0){
			if($ui['force_add']!='1'){
				if($tx_data['recepient_addr']!='0x0000000000000000000000000000000000000000'){
					errorReturn('The certificate recepient public key '.$tx_data['recepient_addr'].' does not match with any of your registered public key.');						
				}
			}else{
				$certCheckFailureStatus=4;
			}
		}	
	}
	
	// Check tx sender pubkey = institution pubkey (if any)
		if(countrow($con,'pubkey',"id='".mysqli_escape_string($con, $ui['issuer_id'])."'  AND pubkey='".mysqli_escape_string($con, $tx_data['issuer_addr'])."'")==0 || countrow($con,'institution',"id='".mysqli_escape_string($con, $ui['issuer_id'])."'  AND pubkey='".mysqli_escape_string($con, $tx_data['issuer_addr'])."'")==0){
			
			if($ui['force_add']!='1'){
				errorReturn('The public key that your certificate provided, '.$tx_data['issuer_addr'].' does not match with the institution you selected. ');	
			}else{
				$certCheckFailureStatus=3;
			}
		}
	
	
	

if($ui['proof_type']=='01'){
	//Simple Hash File
	
	//Check fields: Proof file permission
	if(empty($ui['proof_file_permission']) ){
		errorReturn('Please specify valid permission for your files to be viewed');
	}
	
	//Check fields: Uploaded File
	if(!file_exists($_FILES['proof_file']['tmp_name']) || !is_uploaded_file($_FILES['proof_file']['tmp_name'])) {
		errorReturn('Please specify your file');
	}
	if(($_FILES['proof_file']['size'])>$hostedFileSizeLimit&&$ui['proof_file_permission']!='1') {
		errorReturn('Oversized file. Choose non-hosted method instead.');
	}
	
	$target_file = $usercontent_directory .$ui['proof_hash'].'.'.  end((explode(".", $_FILES["proof_file"]["name"])));
	
	//Check fields: Hash(uploaded_file) == proof file hash
	if(hash_file ("sha256" , $_FILES['proof_file']['tmp_name'])!=$ui['proof_hash']){
		if($ui['force_add']!='1'){
			errorReturn('The fingerprint of your file does not resemble your proof. Make sure you uploaded the original digital document instead of scanned or such document in different digital format.');	
		}else{
			$certCheckFailureStatus=1;
		}
	}
	
	//proof status:
	// 1 : Filehash != H(uploaded)
	// 2 : Issuer ID has no pubkey
	// 3 : Issuer ID pubkey is not pubkey in TX
	// 4 : Recepient Pubkey is not user's pubkey in tx 
	// 10 : FH=H(F) && issuer on blockchain == issuer in platform && recepient on Blockchain == user pubkey
	//Insert data to
	//proofhash prooftype prooftxid proofchain issuancedate recepientid issuerid  prooffilehash prooffilepermission prooflevel
	
	//Check issuance date valid
	if(!dateCheck($ui['issuance_date'])){
		errorReturn('Certificate date invalid');	
	}
	if($ui['proof_file_permission']==''){$ui['proof_file_permission']=0;}
	
	$sql= "INSERT INTO certificate (proof_hash,proof_type,proof_txid,proof_chain,issuance_date,recepient_id, issuer_id, proof_file_path,proof_file_permission,proof_level) VALUES ('".mysqli_escape_string($con,$ui['proof_hash'])."','".mysqli_escape_string($con,$ui['proof_type'])."','".mysqli_escape_string($con,$ui['proof_txid'])."','".mysqli_escape_string($con,$ui['proof_chain'])."','".mysqli_escape_string($con,$ui['issuance_date'])."','".mysqli_escape_string($con,$_SESSION['uid'])."','".mysqli_escape_string($con,$ui['issuer_id'])."','".mysqli_escape_string($con,$ui['proof_file_path'])."','".mysqli_escape_string($con,$ui['proof_file_permission'])."','".mysqli_escape_string($con,$certCheckFailureStatus)."')"; 

	if ($con->query($sql) === TRUE) {
		$last_id = $con->insert_id;
		// Update education certid
		$sql = "UPDATE education SET certid = '$last_id' WHERE id='".mysqli_escape_string($con,$ui['id'])."'";
		$result = $con->query($sql);
		//Transfer file to userdata and rename 
		if (!move_uploaded_file($_FILES["proof_file"]["tmp_name"], $target_file)) {
		   errorReturn( "File upload failed");
		}
		successReturn(Array("id"=>$last_id));
	} else {
	   errorReturn( "Posting failed");
	}
	
}
//Skip this for now
if($ui['proof_type']=='02'){
	//
	
	//Check fields: Proof data permission
	if(empty($ui['proof_data_permission']) ){
		errorReturn('Please specify valid permission for your data to be viewed');
	}
	
	//Check fields: Uploaded data
	if(empty($ui['proof_data']) ){
		errorReturn('Please specify data to be verified');
	}
	
	if(strlen($ui['proof_data'])>5192 && $ui['proof_data_permission']!=0){
		errorReturn('Data oversize, please use verify only instead of hosted storage.');
	}
	
	

	
	//Check fields: Hash(uploaded_file) == proof file hash
	if(hash('sha256', $ui['proof_data'])!=$ui['proof_hash']){
		if($ui['force_add']!='1'){
			errorReturn('The fingerprint of your data does not resemble your proof. Make sure you uploaded the original digital data.');	
		}else{
			$certCheckFailureStatus=1;
		}
	}
	
	//proof status:
	// 1 : Datahash != H(uploaded)
	// 2 : Issuer ID has no pubkey
	// 3 : Issuer ID pubkey is not pubkey in TX
	// 4 : Recepient Pubkey is not user's pubkey in tx 
	// 10 : FH=H(F) && issuer on blockchain == issuer in platform && recepient on Blockchain == user pubkey
	//Insert data to
	//proofhash prooftype prooftxid proofchain issuancedate recepientid issuerid  prooffilehash prooffilepermission prooflevel
	
	//Check issuance date valid
	if(!dateCheck($ui['issuance_date'])){
		errorReturn('Certificate date invalid');	
	}
	if($ui['proof_data_permission']==''){$ui['proof_data_permission']=0;}

		
	$sql= "INSERT INTO certificate (proof_hash,proof_type,proof_txid,proof_chain,issuance_date,recepient_id, issuer_id, proof_data,proof_file_permission,proof_level) VALUES ('".mysqli_escape_string($con,$ui['proof_hash'])."','".mysqli_escape_string($con,$ui['proof_type'])."','".mysqli_escape_string($con,$ui['proof_txid'])."','".mysqli_escape_string($con,$ui['proof_chain'])."','".mysqli_escape_string($con,$ui['issuance_date'])."','".mysqli_escape_string($con,$_SESSION['uid'])."','".mysqli_escape_string($con,$ui['issuer_id'])."','".mysqli_escape_string($con,$ui['proof_data'])."','".mysqli_escape_string($con,$ui['proof_data_permission'])."','".mysqli_escape_string($con,$certCheckFailureStatus)."')"; 

	if ($con->query($sql) === TRUE) {
		$last_id = $con->insert_id;
		// Update education certid
		$sql = "UPDATE education SET certid = '$last_id' WHERE id='".mysqli_escape_string($con,$ui['id'])."'";
		$result = $con->query($sql);

		
		successReturn(Array("id"=>$last_id));
	} else {
	   errorReturn( "Posting failed");
	}
	
	
}

if($ui['proof_type']=='10'){
	//Merkle Root
	
	// Check file uploaded or file hash provided
	
	// Check meta data uploaded or data hash provided
	
	// Proof (merkle root) already checked check
	
	// If there is file uploaded, check proof_file_permission
	
	// If there is data uploaded, check proof_data_permission
	
	//proof status:
	// 1 : datahash != merkle
	// 2 : Issuer ID has no pubkey
	// 3 : Issuer ID pubkey is not pubkey in TX
	// 4 : Recepient Pubkey is not user's pubkey in tx 
	// 5 : Only data hashes of merkle branch is provided
	// 10 : FH=Merkle && issuer on blockchain == issuer in platform && recepient on Blockchain == user pubkey
	
	//Update education:title and education:class

	//Check fields: Proof file permission
	//Merkle Options
	//100 -> 102
	//101 -> File + data print
	//102 -> File print + metadata
	//103 -> File + meta
	if($ui['merkle_options']=='100'){$ui['merkle_options']='102';}
	if(empty($ui['proof_file_permission'])&&($ui['merkle_options']=='101'||$ui['merkle_options']=='103') ){
		errorReturn('Please specify valid permission for your files to be viewed');
	}
	
	//Check fields: Uploaded File OR File Hash
	if((!file_exists($_FILES['proof_file']['tmp_name']) || !is_uploaded_file($_FILES['proof_file']['tmp_name']))&&(empty($ui['proof_file_hash']))) {
		errorReturn('You did not upload a file to be proven or a file hash (fingerprint) to compute the Merkle proof');
	}
	if(($_FILES['proof_file']['size'])>$hostedFileSizeLimit&&$ui['proof_file_permission']!='1') {
		errorReturn('Oversized file. Choose proof-and-discard method instead.');
	}
	

	//Check fields: Proof data permission
	if(empty($ui['proof_data_permission'])&&($ui['merkle_options']=='102'||$ui['merkle_options']=='103') ){
		errorReturn('Please specify valid permission for your data to be viewed');
	}
	
	//Check fields: Uploaded data
	if(empty($ui['proof_data'])&&empty($ui['proof_data_hash']) ){
		errorReturn('Please specify data to be verified');
	}
	
	if(strlen($ui['proof_data'])>5192 && $ui['proof_data_permission']!=0){
		errorReturn('Data oversize, please use verify only instead of hosted storage.');
	}
	if(!dateCheck($ui['issuance_date'])){
		errorReturn('Certificate date invalid');	
	}
	
	// Now Check Mekle Root	
	
	$filehash='';
	if(($ui['merkle_options']=='102')){
		$filehash=$ui['proof_file_hash'];
	}else{
		$filehash=hash_file ("sha256" , $_FILES['proof_file']['tmp_name']);
	}
	$target_file = $usercontent_directory .$filehash.'.'.  end((explode(".", $_FILES["proof_file"]["name"])));
	
	$datahash='';
	if($ui['merkle_options']=='101'||$ui['merkle_options']=='103'){
		$datahash=$ui['proof_data_hash'];
	}else{
		$datahash=hash ("sha256" , $ui['proof_data']);
	}

	if(empty($datahash)||empty($filehash)){
		if($ui['force_add']!='1'){
			errorReturn('Either your document data or the orignal document should be uploaded to provide an informed proof');	
		}else{
			$certCheckFailureStatus=5;
		}
	}

	if(strToUpper(hash('sha256',strToUpper($filehash).''.strToUpper($datahash)))!=strToUpper($ui['proof_hash'])&&strToUpper(hash('sha256',strToUpper($datahash).''.strToUpper($filehash)))!=strToUpper($ui['proof_hash'])){
		if($ui['force_add']!='1'){
			errorReturn('The fingerprints generated from your data and file does not resemble the proof. Please make sure you uploaded the original digital document instead of a scanned copy, or entered your certificate details with mistakes. Result:'.strToUpper(hash('sha256',strToUpper($filehash).''.strToUpper($datahash))).' From '.strToUpper($datahash).' '.strToUpper($filehash).' Target:'.$ui['proof_hash']);	
		}else{
			$certCheckFailureStatus=1;
		}
	}
	if($ui['proof_file_permission']==''){$ui['proof_file_permission']=0;}
	if($ui['proof_data_permission']==''){$ui['proof_data_permission']=0;}

	
	$sql= "INSERT INTO certificate (proof_hash,proof_type,proof_txid,proof_chain,issuance_date,recepient_id, issuer_id, proof_file_hash,proof_file_path,proof_data_hash,proof_data,proof_file_permission,proof_data_permission,proof_level) VALUES ('".mysqli_escape_string($con,$ui['proof_hash'])."','".mysqli_escape_string($con,$ui['proof_type'])."','".mysqli_escape_string($con,$ui['proof_txid'])."','".mysqli_escape_string($con,$ui['proof_chain'])."','".mysqli_escape_string($con,$ui['issuance_date'])."','".mysqli_escape_string($con,$_SESSION['uid'])."','".mysqli_escape_string($con,$ui['issuer_id'])."','".mysqli_escape_string($con,$ui['proof_file_hash'])."','".mysqli_escape_string($con,$ui['proof_file_path'])."','".mysqli_escape_string($con,$ui['proof_data_hash'])."','".mysqli_escape_string($con,$ui['proof_data'])."','".mysqli_escape_string($con,$ui['proof_file_permission'])."','".mysqli_escape_string($con,$ui['proof_data_permission'])."','".mysqli_escape_string($con,$certCheckFailureStatus)."')"; 

	if ($con->query($sql) === TRUE) {
		$last_id = $con->insert_id;
		// Update education certid
		$sql = "UPDATE education SET certid = '$last_id' WHERE id='".mysqli_escape_string($con,$ui['id'])."'";
		$result = $con->query($sql);
		//Transfer file to userdata and rename 
		if ($_FILES["proof_file"]["tmp_name"]!=''&&!move_uploaded_file($_FILES["proof_file"]["tmp_name"], $target_file)) {
		   errorReturn( "File upload failed");
		}
		successReturn(json_encode(Array("id"=>$last_id)));
	} else {
	   errorReturn( "Posting failed".$sql);
	}
	
}






//	Below useless for your ref
//



?>