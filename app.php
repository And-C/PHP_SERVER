<?php
include_once('dbconn.php');
include_once('header.php');
 header("Access-Control-Allow-Origin: *");
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php
		include_once('scripts.php');
	?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
	
	<script src="js/web3.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" />
	<script  src="js/app.js" ></script>
<script src="js/sha256.min.js"></script>
<script src="js/filehash.js"></script>
		<style>
	html,body{height:100%;font-size:16px}
	body{background:url(img/clean-gray-paper.png),#f4f4fe}
	.container{max-width:1280px; }
	nav+.container{margin-top:50px}
	.center{text-align:center}
	.user_main .user_cover {min-height:250px}
	/* .welcome-hero{
		color:#FFF;
		text-shadow:0.15em 0.15em 0.25em #AAA;
	} */
	.login-panel {
		background: #ffffff;background: linear-gradient(to bottom, #ffffff 0%,#f6f6f6 47%,#ededed 100%);
		border-radius:5px;
		padding:40px;
		box-shadow:0px 5px 15px #666;
	}
	.fill-height-or-more {
	  display: flex;
	  flex-direction: column;
	}
	.fill-height-or-more > div {
	  /* these are the flex items */
	  flex: 1;
	}
	.navbar-nav{text-align:right}
	.card {
		width:100%;
		padding:15px;
		margin:30px 10px; 
		box-shadow:0 1px 3px #AAA;
		background:#FFF;
		min-height:50px;
		border-radius:5px !important;
		font-size:1em !important;
	}
	.chat_client{height:100%; width:100%;background:#FFF;margin-top:0px !important;padding-top:0px;padding-bottom:0px;}
	.chat_client .row{margin-top:0px;padding-top:0px }
	.chat_window, .chat_target{width:100%;height:100%;box-shadow:0 0 1px #000;margin:0px; padding:20px; min-height:500px}
	.row .no-float {
	  display: table-cell;
	  float: none;
	}
	/*.app{display:none}*/
	.app{background:rgba(0,0,0,0.1);margin-top:20px}
	#post_update_form textarea{width:100%; font-size:1.25em}
	.template_holder{display:none}
	
	.app_area > .app:not(.active){display:none;} 
	.card{    max-width: none !important;}
	.hidden{display:none;}
	.floatStaus{position:fixed;top:0px; left:0px; width:100%; text-align:center;z-index:999}
	.user_experience_header,.digital_account_header,.issuance_header {padding:5px 15px; display:block}
	.user_experience_add_button,.digital_acc_add_button{float:right}
	.result_inst_name,.result_user_name{cursor:pointer;font-weight:700;text-decoration:underline}
	.transaction_list table{width:100%}
	.unfilled {    box-shadow: 0 1px 4px #FBB;}
	
	</style>
    <title>Certime - Share your digial certificate</title>
  </head>
  <body class="fill-height-or-more">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark ">
	<div class="container">
		<a class="navbar-brand" href="#"  onclick="nav('home')"><img src="/img/certime-logo-01.png" width="35px" height="35px" /></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse " id="navbarCollapse">
			<form class="form-inline mt-2 mt-md-0 " id="login-form" onsubmit="event.preventDefault();">
				<input class="form-control mr-sm-2" type="text" placeholder="Search" onkeyup="if(event.keyCode==13&&this.value!=''){event.preventDefault();search(this.value)}">
			</form>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item active">
				<a class="nav-link" href="#" onclick="nav('home')">Home <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item active">
				<a class="nav-link" href="#" onclick="nav('user_setting')">Settings <span class="sr-only">(current)</span></a>
			</li>
        </ul>
		</div>
	 </div>
    </nav>
	
	<div class="container main app_area">
		
		<div class="app home_app row">
			<div class="col-lg-3 col-md-12 user_updates">
				<div class="card user_basics">
					<div class='rec_border'>
						<div class='user_cover'></div>
						<div class='user_detail' onclick="getSelfProfile()" >
							<div class='user_propic'></div>
							<br>
							<span class="user_name">User Name</span>
							<div class="user_bio">User Bio Lorem Ipsum</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class='home_noti_chat'>
						<span>0</span> unread messages
					</div>
					<div class='home_noti_chat'>
						<span>0</span> awaiting connections
					</div>
				</div>
				<div class="card">
					
						<a class="list-group-item" href="#" onclick="nav('home')"><i class="fa fa-home fa-fw" aria-hidden="true"></i>&nbsp; Home</a>
						<a class="list-group-item" href="#" onclick="viewCertByID(prompt('Please enter the certificate ID'))"><i class="fa fa-book fa-fw" aria-hidden="true"></i>&nbsp; Find Cert by ID</a>
						<a class="list-group-item" href="#"  onclick="viewCertByFingerprint(prompt('Please enter the certificate Fingerprint').toLocaleUpperCase())"><i class="fa fa-book fa-fw" aria-hidden="true"></i>&nbsp; Find Cert by fingerprint </a>
						<a class="list-group-item" href="#" onclick="window.search('myinstitutions')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i>&nbsp; My Institutions</a>
						<a class="list-group-item"href="#" onclick="nav('user_setting')"><i class="fa fa-cog fa-fw" aria-hidden="true"></i>&nbsp; Settings</a>
					
				</div>	
			</div>
			
			<div class="col-lg-6 col-md-12 social_feed">
				<div class="card social_post_updates">
					<h4>Post your updates</h4>
					<form id="post_update_form">
						<textarea name="content"></textarea><br>
						<button onclick="socialPostUpdate()">Submit</button>
					</form>
							<div class="form_response">
							</div>
				</div>
				<hr>

			</div>
			<div class="col-lg-3 col-md-12 feed_nav">
				<div class="card">
					
				</div>
			</div>
		</div>
		
		<div class="app profile_app row">
			<div class="col-lg-12 col-md-12 user_profile">
				<div class="card user_main">
					<div class='user_cover'>
						<div class='user_detail'>
							<div class="user_propic">
							</div><br>
							<span class="user_name">User Name</span><br>
							<div class="user_bio">User Bio Lorem Ipsum</div>
						</div>
					
					</div>
					
					
					<div class='inst_owner'>
						<button onclick="window.framedPage('certissue.php')" class="ic-btn">Issue Certificates</button>
						<i class="fa fa-cog inst_setting_btn" aria-hidden="true"  onclick="" title="profile setting"></i>
					</div>
					<div>
						<button class="follow-btn" onclick="addConnection(5)">Follow</button>
						<button class="chat-btn" onclick="getChat(5)">Chat</button>
					</div>
				</div>
				<div class="card digital_accounts">
					<div class="col-12 center"><br><a href="#" onclick="nav('add_pubkey')" >Add digital accounts</a><br></div>
				</div>
				<div class="card user_experience">
					<div class="row user_experience_template template">
						<div class="col-3"><img src="" title="school_logo" /></div>
						<div class="col-9">
							<div>Institution Name</div>
							<div>Experience Title</div>
							<div>Experience Date</div>
							<div><span>x </span> Not Verified</div>
							<div>Experience Description</div>
						</div>
					</div>
					<div class="row">
						<div class="col-3"><img src="" title="school_logo" /></div>
						<div class="col-9">
							<div>Institution Name</div>
							<div>Experience Title</div>
							<div>Experience Date</div>
							<div><span>x </span> Not Verified</div>
							<div>Experience Description</div>
						</div>
					</div>
				</div>
				<div class="card inst_records">

				</div>
				
			</div><!--
			<div class="col-lg-3 col-md-12 related_profile">
				<h5>Your Connections</h5>
			</div>-->
		</div>

		<div class="app framed_page row">
			<div class="col-lg-12 col-md-12 ">
				<div class="card frame_main">
					<iframe src="" id="targetFrame" name="targetFrame" onload="window.targetFrameLoaded()"></iframe>
				</div>
			</div>
		</div>
		
		<div  class="app chat_app row">
			<div class="chat_client container card">
			<div class="row">
				<div class="col-lg-3 col-md-12 chat_target no-float">
					<div><h4>Chat</h4></div>
					<div class="chat_digest">
					<div class="row chat_digest_element">
						<div class="col-3"><img src="" width=48 height=48/></div>
						<div class="col-9">
							<p class="name">John Doe</p>
							<p class="content">Lorem Ipsum</p>
						</div>
					</div>
					</div>
				</div>
				<div class="col-lg-9 col-md-12 chat_window no-float">
					<div class="center chat_header">
					<center>
						<img src="" title="head" width=48 height=48/>
						<p class="name">John Doe</p>
					</center>
					</div>
					<div class="chat_content wrapper">
						<div class=" row">
							<div class="chat_bubble col-5"></div>
							<div class="chat_bubble col-5">My Chat</div>
							<div class="chat_bubble_user chat_bubble_self col-2"><img src="" title="face"/></div>
						</div>
						<div class=" row">
							<div class="chat_bubble_user col-2"><img src="" title="face"/></div>
							<div class="chat_bubble col-5">Your Chat</div>
							<div class="chat_bubble col-5"></div>
						</div>
						
					</div>
					<div class="chat_input_wrapper">
						<input type="text" name="chat_input" onkeyup="chatInput(this,event)" id="chat_input"/>
					</div> 
				</div>
				</div>
			</div>
		</div>
		
		<div  class="app add_pubkey ">
			<div class="row">

			<div class="col-lg-6 col-md-12 no-float card">
					<h4>Add Public Key</h4>
						<form id="add_pubkey">

							<div class="form-group" onchange="pubkey_network(this.value)">
								<select name="chain">
									<option value=''>Please select network</option>
									<option value="eth">Ethereum</option>
								</select>
							</div>
							

							<div class="form-group">
								Bind this public key to:
								<select class="bind_acc_id" name="id">
									<option value=''>My Account</option>
								</select>
							</div>
							<div class="form-group">
								<select class="pk_list" class="form-control" name="pubkey" onchange="sig_gen()">	
								</select>
							</div>
							
							<div class="form-group">
								<input type="text" class="form-control" name="pubkey_input" placeholder="Public Key" onblur="sig_gen()">
							</div>

							
							<div class="form-group">
								
								<textarea name="proof"></textarea>
							</div>

						</form>
						
						<button type="submit" class="btn btn-primary" onclick="addPubkey()">Submit</button>
							<div class="form_response">
							</div>
					</div>
				</div>

		</div>

				
		<div  class="app add_education ">
			<div class="row">
				<div class="col-lg-6 col-md-12 no-float card">
					<h4>Add Education</h4>
						<form id="add_education">

							<div class="form-group">
								<input type="text" class="form-control" name="issuer_name" onblur="inst_suggestion(this,'close')" onkeyup="inst_suggestion(this)" placeholder="Issuer">
								<input type="hidden" class="form-control issuer_id" name="issuer_id" placeholder="Issuer">
							</div>

							<div class="form-group">
								<input type="text" class="form-control" name="title" placeholder="Qualification Title">
							</div>

							<div class="form-group">
								<input type="text" class="form-control" name="class" placeholder="Class / Tier">
							</div>

							<div class="form-group">
								<input type="text" class="form-control" name="description" placeholder="Short Description">
							</div>

							<div class="form-group">
								<input type="date" class="form-control" name="issuance_date" placeholder="Issuance Date">
							</div>
							<div class="form_response">
							</div>
						</form>
						<button type="submit" class="btn btn-primary" onclick="addEducation()">Submit</button>

					</div>
				</div>

		</div>
				
		<div  class="app user_setting ">
			<div class="row">
				<div class="col-lg-6 offset-lg-3 col-md-12 no-float card">
					<h4>User Account Settings</h4>
						Changes take effect immediately
						<form id="user_setting">

							<div class="form-group">
								<input type="text" class="form-control" name="first_name" placeholder="First Name" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="last_name" placeholder="Last Name" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="tag_line" placeholder="Profile Tagline" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="current_edu" placeholder="Current Education" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="current_edu_title" placeholder="Education Qualification Title" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="current_job" placeholder="Current Occupation" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="current_job_title" placeholder="Occupation Title" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" name="password" placeholder="Password" onblur="editfield('users',this)">
							</div>
							<div class="form-group">
								<select class="form-control" name="privacy" onchange="editfield('users',this)">
									<option value="0">Allow Anyone to see my profile and certificates</option>
									<option value="1">Allow anyone to see my profile and only people I follow to see my certificates</option>
<!---									<option value="0">Allow anyone to see my profile and only allow people I follow to see my certificate proof</option>-->
								</select>
							</div>

							<div class="form_response">
							</div>
						</form>

					</div>
			</div>		
			<div class="row">
				<div class="offset-lg-3 col-lg-6  col-md-12 no-float card ">
					<h4>Create institution page</h4>
					<p>
						If you would like to claim an instituion page you own, or create your institution page on the platform. Please proceed with the following section.
					</p>
					<br>
					<button onclick="nav('add_institution')">Create Institution page</button>
					
					<div style="display:none" class="institution_manager">
						You are the manager of the following institution, click to manage:
						<div class="institution_manager_list">
							
						</div>
					</div>
				</div> 
			</div>

			<div class="row">
				<div class="offset-lg-3 col-lg-6  col-md-12 no-float card ">
					<button onclick="location.href='logout.php'">Logout</button>
				</div> 
			</div>

		</div>
				
		<div  class="app institution_setting ">
			<div class="row">
				<div class="col-lg-6 offset-lg-3 col-md-12 no-float card">
					<h4>Institution Settings</h4>
						Changes take effect immediately
						<form id="inst_setting">

							<div class="form-group">
								<input type="text" class="form-control" name="name" placeholder="Institution Name" onblur="editfield('institution',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="description" placeholder="Description" onblur="editfield('institution',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="type" placeholder="Institution Type" onblur="editfield('institution',this)">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="website" placeholder="Website" onblur="editfield('institution',this)">
							</div>


							<div class="form_response">
							</div>
						</form>

					</div>
			</div>		

		</div>


				
		<div  class="app add_institution ">
			<div class="row">
				<div class="col-lg-6 col-md-12 no-float card">
					<h4>Add Instituion</h4>
						<form id="add_institution">

							<div class="form-group">
								<input type="text" class="form-control" name="name" placeholder="Name">
							</div>

							<div class="form-group">
								<input type="text" class="form-control" name="description" placeholder="Description">
							</div>

							<div class="form-group">
								<input type="text" class="form-control" name="type" placeholder="Institution Type">
							</div>

							<div class="form-group">
								<input type="text" class="form-control" name="website" placeholder="Website">
							</div>
							<div class="form-group">
								<select name="chain">
									<option value="eth">Ethereum</option>
								</select>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="pubkey" placeholder="Public Key">
							</div>

							<!--<div class="form-group">
								<input type="file" class="form-control" name="profile_img" placeholder="Issuance Date">
							</div>-->
							<div class="form_response">
							</div>
						</form>
						<button type="submit" class="btn btn-primary" onclick="addInstitution()">Submit</button>

					</div>
				</div>

		</div>

				
		<div  class="app site_search ">
			<div class="row">
				<div class="col-lg-6 col-md-12 no-float card">
					<h4>Results</h4>
					<h5>Users</h5>
					<div class="user_search_result">
						Loading...
					</div>
<hr>
					<h5>Institutions</h5>
					<div class="inst_search_result">
						Loading...
					</div>

				</div>
 
			</div>

		</div>
				
		<div  class="app address_info ">
			<div class="row">
				<div class="col-lg-12 col-md-12 no-float card">
					<h4>Public Key Information</h4>
					<h5 class="target_address"></h5>
					<h5>Digest</h5>
					<div class="transaction_digest">
						<div>Transactions Count: <span class="tx_count">0</span></div>
						<div>Certificates Issued: <span class="issue_count">0</span></div>
						<div>Issued Certificates ID: <span class="issue_list">0</span></div>
						<div>Certificates Received: <span class="receive_count">0</span></div>
						<div>Received Certificates ID: <span class="receive_list">0</span></div>				
					</div>
					<hr>
					<h5>Transactions</h5>
					<div class="transaction_list">
						<table></table>
					</div>
				</div>
			</div>
		</div>
		<div  class="app cert_info ">
			<div class="row">
				<div class="col-lg-12 col-md-12 no-float card">
					<h4>Certificate Proof</h4>
					<h5 class="certificate_hash_title"></h5>
					<div class="transaction_digest">
						<div>Issuer: <span class="issuer_address">0</span></div>
						<div>Recepient: <span class="recepient_address">0</span></div>
						<div>Proof Fingerprint: <span class="certificate_hash">0</span></div>
						<div>Proof ID: <span class="certificate_id">0</span></div>
						<div>Version: <span class="certificate_version">0</span></div>
						<div>Content: <span class="certificate_content">0</span></div>
						<div>Issuance & Verified Time: <span class="issuance_block_time">0</span></div>
						<div>Revoked?: <span class="revoked_certificate">0</span></div>
					</div>
				</div>
			</div>
		</div>

				
				
		<div  class="app bindcert ">
			<div class="row">
				<div class="col-lg-6 col-md-12 no-float card">
					<h4>Add Certificate Proof</h4>
						<iframe id="bindcert_helper" style="display:none" name="bindcert_helper" onload='bindcert_callback($("#bindcert_helper").contents().text())'></iframe>
						<form id="bindcert" action="bindcert.php"  method="post" target="bindcert_helper" enctype="multipart/form-data">

							<div class="form-group">
								<input type="hidden" class="form-control" name="id"  placeholder="Certificate to proof (autofilled)">
							</div>
							
							<div class="form-group prefill-area">
								<input type="text" class="form-control" name="proof_txid"  placeholder="Fingerprint of certificate OR ID of certificate">
								<br><a href="#" onclick="qrHelper('proof_txid')">Input with QR Code</a>
								<button onclick="event.preventDefault();autofill_certproof();">Next</button>
							</div>
							<div class="autofill_area">
							
							<div class="form-group" style="display:none">
								<select name="proof_chain" value='eth'>
									<!---<option value=''>Please select network</option>--->
									<option value="eth">Ethereum</option>
								</select>
							</div>

				
							<div class="form-group">
								<select name="proof_type" id="proof_type" onchange="merkleOptions(this.value)">
									<option value=''>Select proof type</option>
									<option value="01">Simple File Hash</option>
									<!--<option value="02">Simple Data Hash</option>-->
									<option value="10">Merkle Certificate</option>
								</select>
							</div>
							<div class="form-group optional_form_data form_merkle_options">
								<select onchange="merkleOptions(this.value)" id="merkle_options" name="merkle_options" value='0'>
									<option value='10'>== Please select a method of verification ==</option>
									<option value='100'>Automatic Input with QR Code</option>
									<option value='101'>Upload originals + metadata fingerprint</option>
									<option value='102'>Enter metadata + file fingerprint</option>
									<option value='103'>Upload originals + Input metadata</option>
								</select>
							</div>							
							
							<div class="form-group optional_form_data  form_issuer_id">
								<input type="text" class="form-control" name="issuer_id"  placeholder="Issuer ID">
							</div>

							<div class="form-group optional_form_data  form_file">
								<input type="file" class="form-control" name="proof_file" id="proof_file"  placeholder="Digital Certificate File" onchange="hashIt();">
							</div>
							
							<div class="form-group optional_form_data  form_file_permission">
								<select name="proof_file_permission">
									<option value=''>Select proof file viewing permission</option>
									<option value="1">Proof and discard</option>
									<option value="2">Only to self</option>
									<option value="3">Only to connected users</option>
									<option value="10">Everyone</option>
								</select>
							</div>
							
							<div class="form-group optional_form_data  form_file_hash">
								<input type="text" class="form-control" name="proof_file_hash"  placeholder="Proof file hash" onchange="proof_compute();if($('.active #proof_type').val()=='01'){$('.active #merkle_hash').val(this.value)}">
								<br><a href="#" onclick="qrHelper('proof_file_hash')">Input with QR Code</a>
							</div>
							
							<div class="form-group optional_form_data  form_proof_data">
								<textarea name="proof_data" onchange="hashText(this.value, 'proof_data_hash')" onblur="showDigestTableOverlay()"></textarea>
								<br><a href="#" onclick="qrHelper('proof_data')">Input with QR Code</a>
							</div>
							<div id="proof_data_table" onclick="$('#proof_data_table').hide();$('.form_proof_data').show();">
							
							</div>
							<div class="form-group optional_form_data  form_proof_data_permission">
								<select name="proof_data_permission" id="proof_data_permission" class="unfilled" onchange="if(this.value==''){this.className='unfilled'}else{this.className='filled'}"> 
									<option value=''>Select proof meta data viewing permission</option>
									<option value="1">Proof and discard</option>
									<option value="2">Only to self</option>
									<option value="3">Only to connected users</option>
									<option value="10">Everyone</option>
								</select>
							</div>
							<div class="form-group optional_form_data  form_proof_data_hash">
								<input type="text" class="form-control" name="proof_data_hash"  placeholder="Proof data hash" onchange="proof_compute()">
								<br><a href="#" onclick="qrHelper('proof_data_hash')">Input with QR Code</a>
							</div>
							
							<div class="form-group optional_form_data  form_merkle">
								<input type="text" class="form-control" name="merkle_hash"  id="merkle_hash"  placeholder="Merkle Root">
							</div>
							<div class="form-group optional_form_data form_issuance_date">
								<input type="date" class="form-control" name="issuance_date"  placeholder="Issue Date">
							</div>
							<div class="form-group">
<!--							<select name="force_add">
									<option value=''>Verify all certificate components</option>
									<option value="1">Disable verification</option>
								</select>--->
							</div>
							<div class="form_proof_valid">
							</div>
							<div class="form_response">
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
						</form>
						<!--<button onclick="bindcert();" class="btn btn-primary">Submit</button>--->
						</div>
					</div>
				</div>

		</div>

		
		
		
		
		
	</div>
	<div class="floatStaus hidden" onclick="$(this).addClass('hidden')">
							<div class="alert">
							</div>
	</div>
	<div id="modal"> <!-- data-iziModal-fullscreen="true"  data-iziModal-title="Welcome"  data-iziModal-subtitle="Subtitle"  data-iziModal-icon="icon-home" -->
    <!-- Modal content -->
	</div>
	<div class="template_holder">
	

				<div class="card user_post template">
					<div class="user_post_meta row">
						<div class="user_post_image_holder col-2">
							<img src="" class='user_post_img'  title="head" width=48 height=48/>
						</div>
						<div class="col-10">
							<div class="user_post_origin">John Doe</div>
							<div class="user_post_time">2018-01-01 1:00pm</div> 
						</div>
					</div>
					<div class="user_post_content">
						Lorem Ipsum
					</div>
				</div>
					<div class="row user_experience_header template">
						<h3>Education</h3>
					</div>
					<div class="row digital_account_header template">
						<h3>Digital Accounts</h3>
					</div>
					<div class="row issuance_header template">
						<h3>Issuance</h3>
					</div>
					<div class="row user_experience_element template">
						<div class="col-2"><img class="institution_logo" src="" title="institution_logo" ></div>
						<div class="col-10">
							<div class="ux_in">Institution Name</div>
							<div class="ux_et">Experience Title</div>
							<div class="ux_class">Experience Title</div>
							<div class="ux_edate">Experience Date</div>
							<div class="ux_is_verified"><span>x </span> Not Verified</div>
							<div class="ux_edesc">Experience Description</div>
						</div>
						<div class="col-12 toggleBtn hidden center"><a href="#!" onclick="($(this).parent().parent().find('.toggleArea').toggleClass('hidden'));" >Click to show proof details</a></div>
						<div class="col-12 addProofBtn hidden center"><a href="#" onclick="nav('add_proof')" >+ Click to add proof</a></div>
						<div class="col-12 toggleArea hidden">
							<div class="ux_proof_type">ux_proof_type</div>
							<div class="ux_proof_txid">ux_proof_txid</div>
							<div class="ux_proof_chain">ux_proof_chain</div>
							<div class="ux_proof_issuance_date">Experience Date</div>
							<div class="ux_proof_issuer_id">Experience Date</div>
							<div class="ux_proof_file_hash">Experience Date</div>
							<div class="ux_proof_data_hash">Experience Date</div>
							<div class="ux_proof_file">Experience Date</div>
							<div class="ux_proof_data">Experience Date</div>
							<div class="ux_proof_level">Experience Date</div>
						</div>
					</div>
					
					<div class="row digital_acc_element template">
						<div class="col-2"><img src="" title="network_logo" style="max-width:64px; max-height:64px" /></div>
						<div class="col-10">
							<div class="da_network">Network</div>
							<div class="da_addr">Addr</div>
							<div class="ux_is_verified">Verified <span class="verified_title">ⓘ</span></div>
						</div>
					</div>
					
					<div class="row search_user_item template">
						<div class="col-2"><img src="" class="usericon" title="usericon" style="width:64px; height:64px" /></div>
						<div class="col-10">
							<div class="result_user_name">User Name</div>
							<div class="result_user_info ">Latest occu / edu</div>
						</div>
					</div>
					
					<div class="row search_inst_item template">
						<div class="col-2"><img src="" title="insticon" class="insticon"  style="max-width:64px; max-height:64px" /></div>
						<div class="col-10">
							<div class="result_inst_name">User Name</div>
							<div class="result_inst_info ">Latest occu / edu</div>
						</div>
					</div>
					
					
					<a href="#" class="user_experience_add_button template" onclick="nav('add_experience')" >+ Add</a>
					<a href="#" class="digital_acc_add_button template" onclick="nav('add_pubkey')" >+ Add</a>

					<div class="row chat_digest_element template">
						<div class="col-3"><img src="" width=48 height=48/></div>
						<div class="col-7">
							<p class="name">John Does</p>
							<p class="content">Lorem Ipsum</p>
						</div>
						<div class="col-2">
							>
						</div>
					</div>
					<div class="row inst_record_header template">
						<h3>Recent Issuance</h3>
					</div>
					<div class="row inst_record_template template">
						<div class="col-3"><img src="" class="txid" title="user_icon" /></div>
						<div class="col-9">
							<div class="txid">TXID</div>
							<div class="proof_hash">Proof Hash</div>
							<div class="issuance_data">Issuance Date</div>
							<div class="verified"><span>v </span> Verified</div>
							<div class="inst_owner"><button class="revokebtn" onclick="">Revoke</button></div>
						</div>
					</div>
						<div class=" row template chat_content_element_right">
							<div class=" col-5"></div>
							<div class="chat_bubble col-7" style="text-align:right;">My Chat</div>
							<!--<div class="chat_bubble_user chat_bubble_self col-2"><img src="" title="face"/></div>-->
						</div>
						<div class=" row template chat_content_element_left">
							<!--<div class="chat_bubble_user col-2"><img src="" title="face"/></div>-->
							<div class="chat_bubble col-7">Your Chat</div>
							<div class=" col-5"></div>
						</div>
						


	</div>
	<div class="suggestion_list"></div>
	<!-- CERTTYPE COMMENT -->
	  <div class="modal fade" id="ccModal" style="display:none" role="dialog">
		<div class="modal-dialog">
		
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
			  <h4>Verify the following content:</h4>
				<div id="certjsoncontent">
				
				</div>
		  </div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-success" data-dismiss="modal" onclick="$('#proof_data_table').show();$('.form_proof_data').hide();">Valid</button>
			  <button type="button" class="btn btn-error" data-dismiss="modal" onclick="alert('You should not trust this certificate. If the certificate is yours, please contact your issuing institution')">Invalid</button>
			</div>
		  </div>
		  
		</div>
	  </div>

  <?php include_once('footer.php');?>
	<?php include_once('backToTopButton.php');?>
	</body>
</html>