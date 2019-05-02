
<html>
<head>
<title>Signature</title>
</head>
<style>video{max-width:100% !important}</style>
<body>

<script type="text/javascript">

  window.addEventListener("load", function() {
    // Checking if Web3 has been injected by the browser (Mist/MetaMask)
    if (typeof web3 !== "undefined") {
      // Use Mist/MetaMask's provider
      window.web3 = new Web3(web3.currentProvider);
		parent.frameWeb3Enabled(1);
    } else {
		parent.frameWeb3Enabled(0);
      console.log("No web3? You should consider trying MetaMask!");
      // fallback - use your fallback strategy (local node / hosted node + in-dapp id mgmt / fail)
      window.web3 = new Web3(
        new Web3.providers.HttpProvider("http://localhost:8545")
      );
    }
  })
function siggen(msg,address){
	console.log(msg,address)
	web3.personal.sign(web3.toHex(msg, address, 
		function(err, res) {
			try {
				window.opener.sigReturn(res);
				parent.sigReturn(res);
			}
			catch (err) {}
			window.close();
			return false;
		}
		))
}
</script>
</body>

</html>