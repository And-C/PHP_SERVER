
<html>
<head>
<title>Web QR</title>


<script type="text/javascript" src="js/llqrcode.js"></script>
<script type="text/javascript" src="js/webqr.js"></script>


<script>
function presentationAssist(){
	window.opener.HandlePopupResult('{"CGA":"3.01","COURSESTAKEN":"40","CREDITSTAKEN":"116","PROGRAMME":"BENG. COMPUTER SCIENCE","_filehash":"9CDF25C7B7A6F91D378EB89819A54CA3664EC221870E161F2E99F220C0B6B01B","_merkle":"636F6FE992D32D99FCC7C6A82FC9BA8AE765258B48EAF98E594B5EBED477D412"}');window.close()
}
</script>
</head>
<style>video{max-width:100% !important}</style>
<body ondblclick="presentationAssist()">
<div id="main">
<div id="mainbody">
<table class="tsel" border="0" width="100%">
<tr>
<td valign="top" align="center" width="50%">
<table class="tsel" border="0">
<tr>
<td><a href="javascript:setwebcam()">Webcam</a></td>
<td><a href="javascript:setimg()">Image</a></td></tr>
<tr><td colspan="2" align="center">
<div id="outdiv">
</div></td></tr>
</table>
</td>
</tr>
<tr><td colspan="3" align="center">
</td></tr>
<tr><td colspan="3" align="center">
<div id="result"></div>
</td></tr>
</table>
<!-- webqr_2016 -->
</div>&nbsp;
<canvas id="qr-canvas" width="800" height="600" style="display:none"></canvas>
<script type="text/javascript">load();</script>
</body>

</html>