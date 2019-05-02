<?php
include_once('dbconn.php');
include_once('header.php');
include_once('php_func.php');
 header("Access-Control-Allow-Origin: *");
 getAddressTx("0x08a8ad7C391285cdF77AA3347E0078a4FB59F663");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="js/sha256.min.js"></script>
<script src="js/filehash.js"></script>
<script>
var h0=''
var h1=''
var h01=''
var certFormat={1:['Issuer','Receiver','Date','Qualification','Steam','Class','NationalID']}
function certmeta(v){
	var temp=certFormat[v];
	$('#certmeta').html('')
	console.log(temp)
	$('#certmeta').html('<form name="metainputs" id="metainputs"></form>')
	for(var i=0;i<temp.length;i++){
		$('#metainputs').append('<input type="text" name="'+temp[i]+'" placeholder="'+capitalizeFirstLetter(temp[i])+'" /><br>')
	}
}
function capitalizeFirstLetter(string) {
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
	var tempJSONstring=(JSON.stringify(tempJSON, Object.keys(tempJSON).sort()))
	SHA_256_HASH(tempJSONstring).then(function(result){
		h1=result
		document.getElementById("output2").innerHTML = 'H1: '+ h1
		updateMerkle(h0,h1)
	});
}
function updateMerkle(h0,h1){
		console.log(h0+''+h1)
		SHA_256_HASH(h0+''+h1).then(function(val){
		h01=val
		document.getElementById("output3").innerHTML = 'H01: '+ h01
		});
}
function datatext(v){
	SHA_256_HASH(v).then(function(val){
		h0=val
		document.getElementById("output").innerHTML = 'H0: '+ h0
	});
	
}
</script>

<div>
<textarea name="datatext" onkeyup="datatext(this.value)"></textarea>
<form name="uploadForm">
    <input id="uploadInput" type="file" name="myFiles" onchange="hashIt();"> 
</form>

  <span id="output"></span>
</div>

<select onchange="certmeta(this.value)">
	<option value="-1">Please Select Certificate Type</option>
	<option value="1">Diploma</option>
</select>
<div id="merkle">
Digest: 
<div id="certmeta">

</div>
<button onclick="updateMeta()">Done</button>
</div>
  <span id="output2"></span><br>
  <span id="output3"></span>

<div id="result">
</div>
