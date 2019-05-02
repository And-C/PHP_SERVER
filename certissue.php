<html>
<head>

</head>

<body>
<style>
.modal-body {
	display: table !important;
}
div #qr_wrapper{
	width: 50%;
	float: left;
}
div #qr_info{
	width: 47%;
	float: left;
}
div #qr_wrapper > canvas {
  width:100% !important;
}

.btn {
  background-color: #009688;
  border: none;
  color: white;
  padding: 12px 30px;
  cursor: pointer;
  font-size: 20px;
}

/* Darker background on mouse-over */
.btn:hover {
  background-color: white;
  border-color: #009688;
  color: #009688;
}

div #jsoncontent_wrap{
	padding: 10px;
	border: 2px solid #009688;
	border-radius: 5px;
}

div #qrdownload_wrap{
	padding: 10px;
	border: 2px solid #009688;
	border-radius: 5px;
}

@media only screen and (max-width: 1080px) {
	#qr_wrapper{
	width: 100%;
	}
	#qr_info{
		width: 100%;
	}
	#qrspace{
		width:100% ;
		height:15px;
	}
}
a #download{
	text-decoration: none;
}

.tooltip {
  position: relative !important;
  display: inline-block !important;
  opacity: 100 !important;
  margin-top: 5px;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}



</style>



<div id="main" onresize="parent.frameResize($('#main').height()+30);" style="padding: 15px;"><?php
include_once('dbconn.php');
include_once('header.php');
include_once('php_func.php');
 header("Access-Control-Allow-Origin: *");
 getAddressTx("0x08a8ad7C391285cdF77AA3347E0078a4FB59F663");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="js/sha256.min.js"></script>
<script src="js/filehash.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
var h0='' //FILE HASH
var h1=''//METADATA HASH
var h01=''//MERKLE
//CERTTYPE FIELDS, 0 IS FOR DYNAMIC CONTENT, 
var certFormat={	1:['Issuer','Receiver','Date','Qualification','Stream','Class'],
					2:['CGA','CreditsTaken','CoursesTaken','Programme'],
					0:[]}




//V: CERTFORMAT
function certmeta(v){
	if(v=='0'){
		//SHOW PLACE FOR EDITING CUSTOM FIELDS AT CERTFORMAT 0 SELECTED
		$('#customfields').show()
	}else{
		$('#customfields').hide()
	}
	var temp=certFormat[v];
	$('#certmeta').html('')
	console.log(temp)
	//CLEAR CERTMETA FROM EXISTING ITEMS, CREATE AREA FOR ITEMS IN CUSTOM FIELDS / SELECTED CERTFORMAT
	$('#certmeta').html('<form name="metainputs" id="metainputs"></form>')
	for(var i=0;i<temp.length;i++){
		$('#metainputs').append('<input type="text" name="'+temp[i]+'" placeholder="'+capitalizeFirstLetter(temp[i])+'" /><br>')
	}
}
function capitalizeFirstLetter(string) {
	//
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function objectifyForm(formArray) {//serialize data function

  var returnArray = {};
  for (var i = 0; i < formArray.length; i++){
    returnArray[formArray[i]['name']] = formArray[i]['value'];
  }
  return returnArray;
}
function updateMeta(){
	var temp=($('#metainputs').serializeArray())
	var tempJSON={}
	for(var i=0;i<temp.length;i++){
		tempJSON[temp[i]['name'].toLocaleUpperCase()]=temp[i]['value'].toLocaleUpperCase()
	}
	//CONVERT ALL META INPUTS TO REQUIRED FORMAT
	var tempJSONstring=(JSON.stringify(tempJSON, Object.keys(tempJSON).sort()))
	
	//HASH IT
	SHA_256_HASH(tempJSONstring).then(function(result){
		//
		h1=result.toUpperCase()
		//UPDATE H1
		document.getElementById("output2").innerHTML = 'Digest Fingerprint: '+ h1
		//DISPLAY H1
		//UPDATE MERKLE ROOT
		//SHOW PLACE FOR WHAT TO PUT ON BLOCKCHAIN AND THE OPTION FOR SUBMITTING TX
		updateMerkle(h0,h1)
		$('#chaininfo').show()
	});
}
function updateMerkle(h0,h1){
		console.log(h0+''+h1)
		SHA_256_HASH(h0.toUpperCase()+''+h1.toUpperCase()).then(function(val){
		h01=val
		document.getElementById("output3").innerHTML = 'Fingerprint: '+ h01
		//DISPLAY UPDATED MERKLE
		});
}
function datatext(v){
	//DEPRECIATED
	SHA_256_HASH(v.toUpperCase()).then(function(val){
		h0=val
		alert('')
		document.getElementById("output").innerHTML = 'File Fingerprint: '+ h0
	});
	
}

function hashIt() {
	//HASH A FILE
  var nBytes = 0,
      oFiles = document.getElementById("uploadInput").files,
      nFiles = oFiles.length;
  for (var nFileId = 0; nFileId < nFiles; nFileId++) {
	  //console.log(oFiles[nFileId]);
	  
	  var reader = new FileReader();
	  
	  reader.onload = function(e) {
       var text = reader.result;
       //document.getElementById("previewImg").src ='data:image/png;base64,'+(arrayBufferToBase64(reader.result)); 
	   
        //console.log("Read in ", text);
		  
      var promise = crypto.subtle.digest({name: "SHA-256"},   convertStringToArrayBufferView(text));   
    
    promise.then(function(result){
        var hashValue = convertArrayBufferToHexaDecimal(result);
		h0=hashValue.toUpperCase()
		document.getElementById("output").innerHTML = 'File hash: '+ hashValue
		if($('#itype').val()!='10'){
			//IF PROOF TYPE IS FILE ONLY (METADATA INPUT NOT REQUIRED)
			//DIRECTLY SHOW THE CHAIN INFO
			h01=h0
			$('#chaininfo').show()

		}
    });
		  	  
};

reader.readAsText(oFiles[nFileId]);
	  
	  
	
    nBytes += oFiles[nFileId].size;
  }
  var sOutput = nBytes + " bytes";
  // optional code for multiples approximation
  for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
    sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
  }
  // end of optional code

}
//https://8gwifi.org/docs/window-crypto-digest.jsp

setInterval(function(){
	//TO UPDATE THE FRAME SIZE IN PARENT WINDOW (APP.PHP)
	var a=$('#ccModal .modal-dialog').height()+50; 
	if($('#main').height()>a){
		a=$('#main').height()
	}
	if($('#qrModal .modal-dialog').height()+50>a){
		a=$('#qrModal .modal-dialog').height()+50
	}
	console.log(a)
	parent.frameResize(a+60);
},1000)




function layoutChange(i){
	//HIDE ALL FIELDS
	$('.fields').each(function(){
		$(this).css('display','none')
	})
	//SHOW FIELDS FOR MERKLE ITEMS CLASS="MERKLE FIELDS"
	if(i=='10'){
	$('.merkle.fields').each(function(){
		$(this).css('display','block')
	})
	}
	//SHOW FIELDS FOR FILE ONLY ITEMS CLASS="PE FIELDS"
	if(i=='01'){
		$('.pe.fields').each(function(){
			$(this).css('display','block')
		})
		if($("#output").text()==''){
			$("#chainInfo").show();
		}
	}
}
</script>


<!--------------- ALL modals ----------------------->

<!-- CERTTYPE COMMENT -->
  <div class="modal fade" id="ccModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">What's the difference of different type of certificatation issuance</h4>
        </div>
        <div class="modal-body">
          <h3>Full Certificate Issuance</h3>
This is the recommended type of certificate, this type of certificate is most secure and least prone to fraud.
You will need to provide the following to issue the certificate:<br>
1: The digital of the certificate<br>
2: The metadata that correspond to the digital certificate<br>
Both of these information will form a digital fingerprint, which is recorded publicly. The original and metadata, however, are kept offline and private. <br>
Certificate recepient can share the certificate with others and verify the validity of the detail without by sharing either the certificate orignal or the certificate data. Protecting the privacy of users while enabling secure verification.
<br>
<h3>Proof of document existence issuance</h3>
This issuance type is recommended for digital based document. Recepients are required to share the digital original to resemble to proof, which limits the method of sharing and may results in privacy issue. It is not recommended for paper-based document and documents with private information.<br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


 <!-- QR Code -->
  <div class="modal fade" id="qrModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Generated QR code</h4>
        </div>
        <div class="modal-body">
        	
         	<div id="qr_wrapper">
				<!--- TODO @Richard
					DONE
		
				-->
			</div>
			<div id="qrspace" style="float:left;width: 3%;height:15px;"></div>
			<div id= "qr_info">
				<div id="jsoncontent_wrap">
					<p>JSON Objects content:</p>
					<input type="text" id="jsonobj" style="width:90%;" readonly>
					<div class="tooltip">
						<button class="btn" onclick="myFunction()" onmouseout="outFunc()">
							<span class="tooltiptext" id="myTooltip">Copy to clipboard</span>
							Copy
						</button>
					</div>
				</div>
				<br>
				<div id="qrdownload_wrap">
					<p>You can download the QR code as a .png file</p>
					<p>This QR code will be used for verification later. Please save it.</p>
					<br>
						<a id="download" download="qr.png">
							<button class="btn" onclick="download_canvas()"><i class="fa fa-download"></i> Download</button>
						</a>
				</div>
				
				
			</div>

			
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<!------------- End of ALL modals ------------------>
<div id="formArea">
<div id="tobeissued" style="padding:20px; background:#CCFFCC; cursor:pointer; display:none" onclick="issueCert()"> There are <span class="count">0</span> certificates to be issued. Click to issue now</div>
<h1>Certificate Issuance</h1>
<select onchange="layoutChange(this.value);if(this.value!='10'){this.value=10}" name="itype" id="itype">
	<option value="">Please select type of certficiate to issue</option>
	<option value="10">Full certficiate issuance</option>
	<option value="01">Proof of document existence issuance</option>
<!--	<option value="01">Proof of metadata existence issuance</option>-->
</select>
<a href="#" onclick="$('#wit').toggle()" data-toggle="modal" data-target="#ccModal">What is this?</a><br>
<!---
<textarea name="datatext" onkeyup="datatext(this.value)"></textarea>
--->

<!-- 
FIELDS FOR COMPUTING FILE HASH
-->
<div class="fields merkle pe">
	<h3>Digital orignal</h3>
	<form name="uploadForm">
		<input id="uploadInput" type="file" name="myFiles" onchange="hashIt();"> 
	</form>
	  <span id="output"></span>
</div>



<br>
<!-- 
FIELDS FOR CERT METADATA
-->
<h3 class="fields merkle">Certificate Data</h3>
<select onchange="certmeta(this.value)" id="dtype" class="fields merkle">
	<option value="-1">Please Select Certificate Type</option>
	<option value="1">Diploma</option>
	<option value="2">Transcript</option>
	<option value="0">Others</option>
</select>

<div id="merkle" class="merkle fields">
	Digest: 
	<div id="customfields">
		When using custom fields, you should always specify the fields to allow verification of the certificate
		<div id="fieldlist">
		<!-- 
		CUSTOM FIELD HOLDER
		-->
		</div>
		<button onclick="certFormat['0']=[];certmeta('0')">Remove all fields</button>
		<input type="text" name="fieldname" id="fieldname" />
		<button onclick="if(!certFormat['0'].includes($('#fieldname').val())){certFormat['0'].push($('#fieldname').val());}$('#fieldname').val('');certmeta('0')">Add Field</button>
	</div>
	<div id="certmeta">

	</div>
	<button onclick="updateMeta()">Create Certificate Fingerprint</button>
</div>

  <span id="output2" class="fields"><!-- H1 --></span><br>
  <span id="output3" class="fields"><!-- h01--></span>

<div id="result" class="fields">
</div><br>
<div id="chaininfo" class="fields">
    <input id="recepient_addr" name="recepient_addr" placeholder="Recepient Address"> <br>
    <input id="content" name="content" placeholder="Publicly Viewable Content"> <br>
	<select name="issuance_address" id="issuance_address">
	
	</select><br><br>
<button onclick="batchCert()" >Add another certificate to issue</button><br><br>
<button onclick="issueCert()" >Issue Certificate</button>
</div>
</div>

<script>
// HIDE ALL TO INITIALIZE
	$('.fields').each(function(){
		$(this).css('display','none')
	})
	function addressList(a){
		console.log(a)
		$('#issuance_address').empty()
		for(var i=0;i<a.length;i++){
			$('#issuance_address').append('<option>'+a[i].pubkey+'</option>')
		}
	}
			$('#chaininfo').hide()
			$('#customfields').hide()
var web3enabled
var ethacc
function noMM(){
					 alert('Please install or unlock Metamask or Mist and reload the page to continue')
	$('body').html('<center><br><br><button onclick="location.reload()">Enabled Metamask, Click to reload</button> <br> <a href="https://metamask.io" target="_blank">Learn more about Metamask</a></center>')
}
  window.addEventListener("load", function() {
    // Checking if Web3 has been injected by the browser (Mist/MetaMask)
    if (typeof web3 !== "undefined") {
      // Use Mist/MetaMask's provider
      window.web3 = new Web3(web3.currentProvider);
	  web3enabled=true
	  			web3.eth.getAccounts(function(error, accounts) {
			  if (!error) {
				  ethacc=accounts
				if(accounts.length==0){
					noMM()
				}
			  } else {
				  if(web3enabled){
					noMM()
				  }else{
					noMM()
				  }
				console.error(error);
			  }
			  

			});	
    } else {
					noMM()
      console.log("No web3? You should consider trying MetaMask!");
      // fallback - use your fallback strategy (local node / hosted node + in-dapp id mgmt / fail)
      window.web3 = new Web3(
        new Web3.providers.HttpProvider("http://localhost:8545")
      );
    }
  })
  var batchedCertInfo
  function biInit(){
	  batchedCertInfo={
		  recipient:[],
		  proof:'',
		  version:'',
		  content:'',
		  proofLength:[],
		  versionLength:[],
		  contentLength:[]
	  }
  }
  biInit()
  function importCSV(){
	  
  }
  
  function batchCert(){
	  if(addressCheck()){
		  $('#qrModal').modal('show');
		  if($('#content').val()==''){$('#content').val(0)}
		  batchedCertInfo.recipient.push($('#recepient_addr').val())
		  batchedCertInfo.versionLength.push(($('#itype').val()+'.'+$('#dtype').val()).length)
		  batchedCertInfo.proofLength.push((h01).length)
		  batchedCertInfo.contentLength.push(($('#content').val()).length)
		  batchedCertInfo.content+=$('#content').val()
		  batchedCertInfo.version+=$('#itype').val()+'.'+$('#dtype').val()
		  batchedCertInfo.proof+=h01
		  certQR(1);
		 
	  }
  }
  function issueCert(){
	  //CALL PARENT FRAME TO ISSUE CERT
	  if(addressCheck()){
		$('#qrModal').modal('show');
	  if($('#content').val()==''){$('#content').val(0)}
		certQR()
		
		if(batchedCertInfo.recipient.length>0){
			batchCert()
			parent.batchIssueCert(batchedCertInfo,$('#issuance_address').val());
			$('#tobeissued').hide();
			biInit()
		}else{
		  parent.issueCert([$('#recepient_addr').val(),h01,$('#itype').val()+'.'+$('#dtype').val(),$('#content').val(),$('#issuance_address').val()])		
		}
	  }

  }
  
  function addressCheck(){
	  if($('#recepient_addr').val()!=''){
		  if(!(/^(0x){1}[0-9a-fA-F]{40}$/i.test($('#recepient_addr').val()))){
			  alert('The target address you specified is incorrect. Leave empty if there are no specified target.');
			  return false
		  }
	  }else{
		  $('#recepient_addr').val('0x0000000000000000000000000000000000000000')
	  }
	  return true;
  }
  function certQR(batchCertUI){
	  //GENERATE STRING FOR CREATING QR CODE
	  	var temp=($('#metainputs').serializeArray())
		var tempJSON={}
		if($('#itype').val()=='10'){			
			for(var i=0;i<temp.length;i++){
				tempJSON[temp[i]['name'].toLocaleUpperCase()]=temp[i]['value'].toLocaleUpperCase()
			}		
		}
		tempJSON['_filehash']=h0.toLocaleUpperCase()
		tempJSON['_merkle']=h01.toLocaleUpperCase()
		var tempJSONstring=(JSON.stringify(tempJSON, Object.keys(tempJSON).sort()))
		console.log(tempJSONstring);
		/* TODO: Make QR Code with 'tempJSONstring' and put the QR in QR_wrapper div */
		document.getElementById("jsonobj").value = tempJSONstring;
		
		document.getElementById("qr_wrapper").innerHTML = "";
		$('#qr_wrapper').qrcode({
  			render: 'canvas',
  			text: tempJSONstring
		});

		if(batchCertUI==1){
			 $('#formArea input:not(#issuance_address),#formArea select:not(#issuance_address)').each(function(){
				 $(this).val('')
			 })
			 $('.merkle #output').text('')
			 layoutChange('');
			 $('#tobeissued').show();
			 $('#tobeissued .count').text(batchedCertInfo.recipient.length);
			$("#output").text('')
			h01=''
			h0=''
		}
  }
  function download_canvas(){
  	var download = document.getElementById("download");
  	var canvas = document.getElementById('qr_wrapper').querySelector('canvas');
  	var dataURL = canvas.toDataURL();
	download.setAttribute("href", dataURL.replace("image/png", "image/octet-stream"));
  }
  function myFunction() {
	var copyText = document.getElementById("jsonobj");
	copyText.select();
	document.execCommand("copy");

	var tooltip = document.getElementById("myTooltip");
	tooltip.innerHTML = "Copied!";
  }

  function outFunc() {
	var tooltip = document.getElementById("myTooltip");
	tooltip.innerHTML = "Copy to clipboard";
  }
</script>
</div>
</body></html>