	var userData={};
	var loggedin=false;
	var currentPage='home'
	var pageParam={}
	var debug=false
	var followerList={'by':[],'to':[]}
	var web3enabled=false
	var DZ
	var lastPubkeyMap
	var abi=[{"constant":false,"inputs":[{"name":"targetCertID","type":"uint256"}],"name":"revokeCertificate","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"value","type":"string"}],"name":"getCertsByContent","outputs":[{"name":"","type":"uint256[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"value","type":"string"}],"name":"getCertsByProof","outputs":[{"name":"","type":"uint256[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"beneficiary","type":"address"},{"name":"certHash","type":"string"},{"name":"version","type":"string"},{"name":"content","type":"string"}],"name":"newCertificate","outputs":[{"name":"certID","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"","type":"uint256"}],"name":"certificates","outputs":[{"name":"certHash","type":"string"},{"name":"issuer_addr","type":"address"},{"name":"recepient_addr","type":"address"},{"name":"version","type":"string"},{"name":"content","type":"string"},{"name":"isRevoked","type":"bool"},{"name":"issuance_time","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"string_type","type":"uint256"},{"name":"value","type":"string"}],"name":"getMatchCountString","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"value","type":"address"}],"name":"getCertsByIssuer","outputs":[{"name":"","type":"uint256[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"value","type":"string"}],"name":"getCertsByVersion","outputs":[{"name":"","type":"uint256[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"beneficiaries","type":"address[]"},{"name":"certHash","type":"string"},{"name":"version","type":"string"},{"name":"content","type":"string"},{"name":"certHashChar","type":"uint256[]"},{"name":"versionChar","type":"uint256[]"},{"name":"contentChar","type":"uint256[]"}],"name":"batchNewCertificate","outputs":[{"name":"","type":"uint256[]"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"value","type":"address"}],"name":"getCertsByRecepient","outputs":[{"name":"","type":"uint256[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"addr_type","type":"uint256"},{"name":"value","type":"address"}],"name":"getMatchCountAddress","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"}]
	var primaryC = "#009688";

	
    $(document).ready(function () {
        $(document).click(function (event) {
            var clickover = $(event.target);
            var _opened = $(".navbar-collapse").hasClass("navbar-collapse collapse show");
            if (_opened === true && !clickover.hasClass("navbar-toggler") &&!document.activeElement.className.match('form-control mr-sm-2')) {
                $("button.navbar-toggler").click();
            }

        });
    });

	function appHide(){
		$('.app.active').removeClass('active');
		if(		$('.dz-clickable').length){
			//$('.dz-clickable').disable()
			
		}
	}
	function pageInit(){
		//Check Login Status
		//Load User data
		loadUserData();
		loadRecentPost();
		//loadRecentChat();
		loadRecentNotification();
		loadRecentConnectionRequest();
	 }
	 function pageReload(){
		 
	 }
	 function getSelfProfile(){
		 getProfile(userData['id'])
	 }
	 
	 function emptyInputs(){
		$('.app.active input').val('')
	 }
	 function uiUpdate(item,d){
		 if(item=='address_info'){
			 appHide();
			 $('.address_info').addClass('active')

			tx_data=JSON.parse(d.response).result;
			$('.transaction_digest span').each(function(){
				$(this).text('Loading')
			})
			$('.tx_count').text(tx_data.length);
			$('.target_address').text(d.origin);
			var sc_address="0x8dffd6644cf466d083fc6db8c61ad88443e48c99"
			$('.transaction_list table').empty();
			$('.transaction_list table').append('<tr><th>Certificate<br>Transaction</th><th>From</th><th>To</th><th>Time</th><th>Status</th></tr>');
			for (var i=0;i<tx_data.length;i++){

					//explorer
					$('.transaction_list table').append('<tr><td colspan=6><a target="_blank" href="https://etherscan.io/tx/'+tx_data[i]['hash']+'">'+(tx_data[i]['hash'])+'</a></td></tr>');
				
				$('.transaction_list table').append('<tr><td>'+(tx_data[i]['to']==sc_address?'v':'x')+'</td><td><a href="javascript:getAddressData(\''+tx_data[i]['from'].toLowerCase()+'\')">'+tx_data[i]['from'].substring(0,10)+'...</a></td><td><a href="javascript:getAddressData(\''+tx_data[i]['to'].toLowerCase()+'\')">'+tx_data[i]['to'].substring(0,10)+'...</a></td><td>'+new Date(tx_data[i]['timeStamp']*1000).toString()+'</td><td>'+(parseInt(tx_data[i]['confirmations'])>=1?'Confirmed':'<b>UNCONFIRMED</b>')+'</td></tr><tr><td colspan=6><hr></td></tr>');
			}
			// Get issued certificates of this address
			ethCall("getCertsByIssuer",[d.origin],function(data){
				$('.active .issue_count').text(data[0].length);
				$('.active .issue_list').empty();
				for (var i=0;i<data[0].length;i++){
					if(i!=0){$('.active .issue_list').append(', ')}
					$('.active .issue_list').append('<a href="javascript:viewCertByID(\''+data[0][i]+'\')">'+data[0][i]+'</a>')
				}
				
			})
			
			// Get received certificates of this address
			ethCall("getCertsByRecepient",[d.origin],function(data){
				$('.active .receive_count').text(data[0].length);
				$('.active .receive_list').empty();
				for (var i=0;i<data[0].length;i++){
					if(i!=0){$('.active .issue_list').append(', ')}
					$('.active .receive_list').append('<a href="javascript:viewCertByID(\''+data[0][i]+'\')">'+data[0][i]+'</a>')
				}
				
			})
	
					
		 }
		 if(item=='cert_info'){
			 console.log(d)
			appHide();
			$('.cert_info').addClass('active')
	
			$('.cert_info span').each(function(){
				$(this).text('Loading')
			})
			
			$('.certificate_hash_title').text("Certificate #"+d.origin);
			$('.issuer_address').text(d.issuer_addr);
			$('.recepient_address').text(d.recepient_addr);
			$('.certificate_hash').text(d.certHash);
			$('.certificate_id').text(d.origin);
			$('.certificate_version').text(d.version);
			$('.certificate_content').text(d.content);
			$('.issuance_block_time').text(new Date(d.issuance_time*1000)   );
			$('.revoked_certificate').html(d.isRevoked?"<b>Certificate Revoked</b>":"Not Revoked");

		 }
		 if(item=='site_search'){
			 appHide();
			 $('.site_search').addClass('active')
			 $('.user_search_result,.inst_search_result').empty();
			 for (var i=0;i<d.users.length;i++){
				template = $(".template_holder .search_user_item").clone();
				template.removeClass('template')
				template.find('.result_user_name').text(d.users[i].name);
				if(d.users[i].current_job!=''){
					template.find('.result_user_info').text(d.users[i].current_job);
				}else{
					template.find('.result_user_info').text(d.users[i].current_edu);
				}
				if(d.users[i].profile_img==''){
					d.users[i].profile_img='img/default_propic.jpg'
				}
				template.find('.usericon').attr('src',d.users[i].profile_img)
				template.attr('onclick','getProfile("'+d.users[i].id+'")')
				template.appendTo(".user_search_result");
			 }
			 for (var i=0;i<d.inst.length;i++){
				template = $(".template_holder .search_inst_item").clone();
				template.removeClass('template')
				template.find('.result_inst_name').text(d.inst[i].name);
				template.find('.result_inst_info').text(d.inst[i].website);
				if(d.inst[i].profile_img==''){
					d.inst[i].profile_img='img/default_institution.png'
				}
				template.find('.insticon').attr('src',d.inst[i].profile_img)
				template.attr('onclick','getInstitution("'+d.inst[i].id+'")')
				template.appendTo(".inst_search_result");
			 }
		 }
		 if(item=='user_data'){
			appHide();
			 $('.home_app').addClass('active')
			 emptyInputs()
			 if(d.cover_img==''){d.cover_img='img/default_cover.png'}
			 $('.app.home_app .user_cover').css('background-image','url("'+d.cover_img+'")').css('background-size','cover')
			 
			 if(d.profile_img==''){d.profile_img='img/default_propic.jpg'}
			 $('.app  .user_propic').css('background-image','url("'+d.profile_img+'")').css('background-size','cover')
			 $('.app .user_detail .user_name').text(d.last_name+ ' ' + d.first_name)
			 if(d.tag_line==''){d.tag_line='Go to profie and setup your bio'}
			 $('.app .user_detail .user_bio').text(d.tag_line)
		 }
		 if(item=='user_setting'){
			appHide();
			 $('.user_setting').addClass('active')

			$('.user_setting form input,.user_setting form select').each(function( index ) {
				$(this).val(userData[$(this).attr('name')])
			});
			
		 }
		 if(item=='inst_setting'){
			appHide();
			userData.currentInstitution=d
			 $('.institution_setting').addClass('active')
			var inst_ud_key=-1;
			for(var i=0;i<userData.institution.length;i++){
				if(userData.institution[i].id==userData.currentInstitution){
					inst_ud_key=i
				}
			}
			if(inst_ud_key==-1){
				nav('home');
				errorMsg('You do not own this institution')
			}
			$('.institution_setting form input,.institution_setting form select').each(function( index ) {
				$(this).val(userData['institution'][inst_ud_key][$(this).attr('name')])
			});
			
		 }

		 if(item=='user_post'){
			appHide();
			 $('.home_app').addClass('active')
			 emptyInputs()
			 for(var i=0;i<d.length;i++){
				template = $(".template_holder .template.card.user_post").clone();
				template.removeClass('template')
				template.find('.user_post_content').text(d[i].content);
				template.find('.user_post_origin').text(d[i].name);
				template.find('.user_post_time').text(d[i].send_time);
				if(d[i].profile_img==''){
					d[i].profile_img='img/default_propic.jpg'
				}
				template.find('.user_post_img').attr('src',d[i].profile_img)
				
				template.appendTo(".social_feed");
				
			 }
		 }
		 
		 
		 if(item=='chat_digest'){
			// $('.chat_app').addClass('active')
			
			 $('.chat_app .chat_digest').empty()
			 for(var i=0;i<d.length;i++){
				template = $(".template_holder .template.chat_digest_element").clone();
				template.removeClass('template')
				if(d[i].receiver==userData['id']){
					template.find('.name').text(d[i].s_name);	
					if(d[i].s_propic==''){
						d[i].s_propic='img/default_propic.jpg'
					}
					template.find('img').attr('src',d[i].s_propic)
					template.attr('onclick','getChat('+d[i].sender+',"'+d[i].s_name+'","'+d[i].s_propic+'")')
				
				
				}else{
					template.find('.name').text(d[i].r_name);		
					if(d[i].r_propic==''){
						d[i].r_propic='img/default_propic.jpg'
					}
					template.find('img').attr('src',d[i].r_propic)
					template.attr('onclick','getChat('+d[i].sender+',"'+d[i].r_name+'","'+d[i].r_propic+'")')
				}
				template.find('.content').text(d[i].message);

				template.appendTo(".app.chat_app .chat_digest");
				
			 }
		 }
		if(item=='chat_content'){
			appHide()
			 $('.chat_app').addClass('active')
			 emptyInputs()
			 $('.chat_app .chat_content').empty()
			 for(var i=0;i<d.length;i++){
				 var template
				 if(d[i].sender!=userData.id){
				template = $(".template_holder .template.chat_content_element_left").clone();					 
				 }else{
				template = $(".template_holder .template.chat_content_element_right").clone();					 
				 }
				template.removeClass('template')
				
				template.find('.chat_bubble').text(d[i].message);
				
				template.prependTo(".app.chat_app .chat_content");
				
			 }
		 }
		 
		 if(item=='user_profile' || item == 'inst_profile'){
		 appHide();
			 $('.profile_app').addClass('active')
			 emptyInputs()
			console.log(d);


			if(d.users.cover_img==''){d.users.cover_img='img/default_cover.png'}
			 
			 $('.app .user_profile  .user_cover').css('background-image','url("'+d.users.cover_img+'")').css('background-size','cover')
			try{
				Dropzone.forElement(".app .user_profile .user_cover").destroy();
			}catch(e){
				console.log(e);
			}
			if(d.users.id==userData.id&&item=='user_profile'||item=='inst_profile'&&d.users.bind_uid==userData.id){
			try{
			 $('.app .user_profile  .user_cover').dropzone({ url: "file_handler.php?"+ (item=="inst_profile"?"i_":"")+"user_cover" ,header:{ "Target": (item=="inst_profile"?"i_":"")+"user_cover"} ,acceptedFiles:"image/*",addedfile: function(file) {
				console.log(file)
			  },thumbnail: function(file, dataUrl) {
				// Display the image in your file.previewElement
			  },complete:function(d){
				  console.log(d)
				  if(d.status=='success'){
						if(item=='user_profile'){
							getSelfProfile();
						}
				  }else{
					  alert(JSON.decode(d.xhr.responseText).response)
				  }
			  }});
			}catch(e){
				console.log(e)
			}
			}
			 if(d.users.profile_img==''&&item=='user_profile'){d.users.profile_img='img/default_propic.jpg'}
			 if(d.users.profile_img==''&&item=='inst_profile'){d.users.profile_img='img/default_institution.png'}
			 
			 $('.profile_app  .user_profile .user_propic').css('background-image','url("'+d.users.profile_img+'")').css('background-size','cover')
			 
			try{
				Dropzone.forElement(".profile_app  .user_profile .user_propic").destroy();
			}catch(e){
				console.log(e);
			}
			if(d.users.id==userData.id&&item=='user_profile'||item=='inst_profile'&&d.users.bind_uid==userData.id){
				try{
				 $('.profile_app  .user_profile .user_propic').dropzone({ url: "file_handler.php?"+ (item=="inst_profile"?"i_":"")+"user_propic",header:{ "Target": (item=="inst_profile"?"i_":"")+"user_propic" } ,acceptedFiles:"image/*",addedfile: function(file) {
					console.log(file)
				  },thumbnail: function(file, dataUrl) {
					// Display the image in your file.previewElement
				  },complete:function(d){
					  console.log(d)
					  if(d.status=='success'){
							if(item=='user_profile'){
								getSelfProfile();
							}
					  }else{
						  alert(JSON.decode(d.xhr.responseText).response)
					  }
				  }});
				 }catch(e){
					 
				 }
			} 
			 
			if(item=='user_profile'){
			 $('.app  .user_profile .user_name').text(d.users.last_name+ ' ' + d.users.first_name)
			 if(d.users.tag_line==''){d.users.tag_line='No Bio Available'}
			 $('.app  .user_profile .user_bio').text(d.users.tag_line)
			 $('.app  .user_profile .follow-btn').attr('onclick','addConnection('+d.users.id+')')
			 $('.app  .user_profile .chat-btn').attr('onclick','getChat('+d.users.id+',"'+d.users.last_name+ ' ' + d.users.first_name+'","'+d.users.profile_img+'")')	
			}else{
			 $('.app  .user_profile .user_name').text(d.users.name)
			 if(d.users.description==''){d.users.description='No Description Available'}
			 $('.app  .user_profile .user_bio').text(d.users.description)
			}
			 
			 $('.app  .user_profile .ic-btn').addClass('hidden')
			if(d.users.id==userData.id||item=='inst_profile'){
			 $('.app  .user_profile .follow-btn').addClass('hidden')
			 $('.app  .user_profile .chat-btn').addClass('hidden')
			}else{
				$('.app  .user_profile .follow-btn').text('Follow').removeAttr('disabled')
				if(userData.to.indexOf(d.users.id)!=-1){
					$('.app  .user_profile .follow-btn').text('Unfollow')
					$('.app  .user_profile .follow-btn').attr('onclick','addConnection('+d.users.id+',0)')
				}
				if(userData.by.indexOf(d.users.id)!=-1&&userData.to.indexOf(d.users.id)==-1){
					$('.app  .user_profile .follow-btn').text('Follow Back')
				}
				 $('.app  .user_profile .follow-btn').removeClass('hidden')
				 $('.app  .user_profile .chat-btn').removeClass('hidden')
				
			}
			
			$('.digital_accounts').empty();
			$('.inst_owner').hide()
			template = $(".template_holder  .template.digital_account_header").clone();
			template.appendTo(".app.profile_app .digital_accounts");
				if(d.users.id==userData.id){
					template = $(".template_holder  .template.digital_acc_add_button").clone();
					template.prependTo(".app.profile_app .digital_account_header");
				}
				if(item=='inst_profile'){
					if(d.users.bind_uid==userData.id){
						template = $(".template_holder  .template.digital_acc_add_button").clone();
						template.prependTo(".app.profile_app .digital_account_header");	
						 $('.app  .user_profile .ic-btn').removeClass('hidden')
						lastPubkeyMap=d.pubkey
						$('.inst_owner').show()
						$('.inst_setting_btn').attr('onclick','nav("inst_setting",'+d.users.id+')')
					}
				}
			 for(var i=0;i<d.pubkey.length;i++){
				template = $(".template_holder  .template.digital_acc_element").clone();
				template.find('img').attr('src','img/eth_icon.png');
				template.find('.da_network').text(d.pubkey[i].chain)
				template.find('.da_addr').html("<a href='javascript:getAddressData(\""+d.pubkey[i].pubkey.toLowerCase()+"\")'>"+d.pubkey[i].pubkey+"</a>")
				
				template.find('.verified_title').attr('data-toggle','tooltip').attr('title','Signature of "Certi.me proof for '+(item=='inst_profile'?'institution ':'')+'account '+d.users.id+'" : '+d.pubkey[i].signature).tooltip(); 
				
				template.appendTo(".app.profile_app .digital_accounts");
			 }
			 
				 if(d.pubkey.length==0){
					 $(".app.profile_app .digital_accounts").append('<center>No public key registed</center>')
					 if(item=='inst_profile'){
						 $(".app.profile_app .digital_accounts").append('<center>Claim this institution if you own the following address:</center>').append('<center>'+d.users.pubkey.toLowerCase()+'</center>').append('<button onclick="claimPage('+d.users.id+')">Claim</center>')
						 }
				 }
			
			$('.user_experience').empty().hide();
			if(item!='inst_profile'){
				$('.user_experience').show();
			
			template = $(".template_holder  .template.user_experience_header").clone();
			template.appendTo(".app.profile_app .user_experience");

				if(d.users.id==userData.id){
					template = $(".template_holder  .template.user_experience_add_button").clone();
					template.prependTo(".app.profile_app .user_experience_header");
				}
			 for(var i=0;i<d.education.length;i++){
				
				 
				template = $(".template_holder  .template.user_experience_element").clone();
				template.removeClass('template')
				if(d.education[i].profile_img==''){d.education[i].profile_img='img/default_institution.png'}
				template.find('.institution_logo').attr('src',d.education[i].profile_img).attr('width',48).attr('height',48);
				template.find('.ux_in').text(d.education[i].name);
				template.find('.ux_et').text(d.education[i].title);
				template.find('.ux_edate').text(d.education[i].e_issuance_date);
				var isver='Not verified'
				if( d.education[i].proof_level!=null){
					if(d.education[i].proof_level>0&&d.education[i].proof_level<10){
							isver='Data provided, unable to verify'
					}
					if(d.education[i].proof_level==0){
						isver='Fully verified'
					}
					template.find('.toggleBtn').removeClass('hidden')
				}else{
					if(d.users.id==userData.id){
						template.find('.addProofBtn').removeClass('hidden')
						template.find('.addProofBtn a').attr('onclick','nav("add_proof","'+d.education[i].e_id+'")')
					}

				}
				template.find('.ux_is_verified').text(isver);
				console.log(d.education[i])
				
				template.find('.ux_edesc').text('Education Description: '+d.education[i].e_desc);
				
				template.find('.ux_proof_type').text('Proof Type: '+d.education[i].proof_type);
				template.find('.ux_proof_txid').text('Proof TXID: '+d.education[i].proof_txid);
				template.find('.ux_proof_chain').text('Proof Storage Network '+d.education[i].proof_chain);
				template.find('.ux_proof_issuance_date').text('Issuance Date: '+d.education[i].issuance_date);
				template.find('.ux_proof_issuer_id').text('Issuer ID: '+d.education[i].issuer_id);
				template.find('.ux_proof_file_hash').text('File Hash: '+d.education[i].proof_file_hash);
				template.find('.ux_proof_data_hash').text('Data Hash: '+d.education[i].proof_data_hash);
				template.find('.ux_proof_data').html('Proof Data: <br>'+dtt(d.education[i].proof_data));

				template.appendTo(".app.profile_app .user_experience");
				
			 }
			 if(d.education.length==0){
					$(".app.profile_app .user_experience").append('No experience available to display');
 
			 }
			 }
			 
			$(".inst_records").empty().hide();
			if(item=='inst_profile'){
				$('.inst_records').show();
				$(".template_holder  .template.inst_record_header").clone().appendTo(".app.profile_app .inst_records");
				
				 for(var i=0;i<d.issuance.length;i++){
					
					 
					template = $(".template_holder  .template.inst_record_template").clone();
					template.removeClass('template')
					template.find('img').attr('src','img/default_propic.jpg').attr('width',48).attr('height',48);
					template.find('.txid').text(d.issuance[i].proof_txid);
					template.find('.proof_hash').text(d.issuance[i].proof_hash);
					template.find('.issuance_data').text(d.issuance[i].issuance_date);
					template.find('.revokebtn').attr('onclick','revokeCert("'+d.issuance[i].proof_txid+'")');
					template.appendTo(".app.profile_app .inst_records");
				 }
				 if(d.issuance.length==0){
					 $(".app.profile_app .inst_records").append('<center>Empty</center>')
				 }
			}
			//if(item=='inst_profile'){
			//	$('.user_profile').removeClass('.col-lg-9').addClass('col-lg-12')
			//}
		 }
/*
		 if(item=='page_profile'){
		 appHide();
			 $('.institution_app').addClass('active')
			 emptyInputs()
			console.log(d);
			 //if(d.users.cover_img==''){d.users.cover_img='img/default_cover.png'}
			temp='img/default_cover.png'
			 $('.institution_app .page_profile  .page_cover').css('background-image','url("'+temp+'")').css('background-size','cover')

			 if(d.institution.profile_img==''){d.institution.profile_img='img/default_propic.jpg'}
			 
			 $('.institution_app  .page_profile .page_propic').css('background-image','url("'+d.institution.profile_img+'")').css('background-size','cover')
			 if(d.institution.bind_uid==userData.id){
			 try{
			 $('.institution_app  .page_profile .page_propic').dropzone({ url: "file_handler.php?page_propic",header:{ "Target": "page_propic" } ,acceptedFiles:"image/*",addedfile: function(file) {
    console.log(file)
  },thumbnail: function(file, dataUrl) {
    // Display the image in your file.previewElement
  },complete:function(d){
	  console.log(d)
	  if(d.status=='success'){
		getSelfProfile();
	  }else{
		  alert(JSON.decode(d.xhr.responseText).response)
	  }
  }});
			 }catch(e){
				 
			 }
			 }
			 $('.app  .page_profile .page_name').text(d.institution.name)
			 if(d.institution.description==''){d.institution.description='No description available'}
			 $('.app  .page_profile .page_bio').text(d.institution.description)
			 //$('.app  .page_profile .follow-btn').attr('onclick','addConnection('+d.institution.id+')')
			 //$('.app  .page_profile .chat-btn').attr('onclick','getChat('+d.institution.id+',"'+d.institution.name+'","'+d.users.profile_img+'")')
			if(d.institution.id==userData.id){
			 $('.app  .page_profile .follow-btn').addClass('hidden')
			 $('.app  .page_profile .chat-btn').addClass('hidden')
			}else{
			 $('.app  .page_profile .follow-btn').removeClass('hidden')
			 $('.app  .page_profile .chat-btn').removeClass('hidden')
				
			}
			$('.page_profile .digital_accounts').empty();
			template = $(".template_holder  .template.digital_account_header").clone();
			template.appendTo(".institution_app .digital_accounts");
				if(d.institution.bind_uid==userData.id){
					template = $(".template_holder  .template.digital_acc_add_button").clone();
					template.prependTo(".app.active .digital_account_header");
				}
			 for(var i=0;i<d.pubkey.length;i++){
				template = $(".template_holder  .template.digital_acc_element").clone();
				template.find('img').attr('src','img/eth_icon.png');
				template.find('.da_network').text(d.pubkey[i].chain)
				template.find('.da_addr').text(d.pubkey[i].pubkey)
				template.find('.verified_title').attr('data-toggle','tooltip').attr('title','Signature of "Certi.me proof for institution account '+d.institution.id+'" : '+d.pubkey[i].signature).tooltip(); 
				
				template.appendTo(".app.active .digital_accounts");
			 }
			 if(d.pubkey.length==0){
				 $(".app.active .digital_accounts").append('<center>No verified public key</center>')
			 }
			
			$('.page_profile .page_issuance').empty();
			template = $(".template_holder  .template.issuance_header").clone();
			template.appendTo(".institution_app .page_issuance");

			 if(d.pubkey.length==0){
				 $(".app.active .page_issuance").append('<center>No certificate issued</center>')
			 }


			 
		 }
*/
		 
		 if(item=='add_experience'){
			appHide();
			 $('.add_education ').addClass('active')
			 emptyInputs()

		 }				 
		 if(item=='framed_page'){
			appHide();
			 $('.framed_page ').addClass('active')

		 }		 
		 if(item=='add_proof'){
			appHide();
			 $('.bindcert').addClass('active')
			 emptyInputs()
			$('.active [name="proof_type"]').val('');
			$('.autofill_area').hide();
			$('.prefill-area').show();
		 }				 
		 if(item=='add_pubkey'){
			appHide();
			 $('.add_pubkey').addClass('active')
			 emptyInputs()

			web3.eth.getAccounts(function(error, accounts) {
			  if (!error) {
				  console.log(accounts)
				  $('.add_pubkey .pk_list').empty().html('<option value="input">Select address or enter below:</option>')
				for (var i=0;i<accounts.length;i++){
					$('.add_pubkey .pk_list').prepend('<option value="'+accounts[i]+'">'+accounts[i]+'</option>')
				}
				if(accounts.length==0){
					 errorMsg('Please install or unlock Metamask or Mist to continue. Click to learn more about Metamask',10,'window.open("https://metamask.io/")')
				}
			  } else {
				  if(web3enabled){
					  errorMsg('Please install or enable Metamask or Mist to continue. Click to learn more about Metamask',10,'window.open("https://metamask.io/")')
				  }else{
					  errorMsg('Please unlock Metamask or Mist to continue. Click to learn more about Metamask',10,'window.open("https://metamask.io/")')
				  }
				console.error(error);
			  }
			  

			});			 
			$('.bind_acc_id').empty().html('<option value="self">My Personal Account</option>')
			for(var i=0;i<userData.institution.length;i++){
				$('.bind_acc_id').append('<option value="'+userData.institution[i].id+'">Institution account: '+userData.institution[i].name+'</option>')
			}
		 }		

		 if(item=='add_institution'){
			appHide();
			 $('.add_institution').addClass('active')
			 emptyInputs()
			 

		 }				 
		 
	 }
	 
	 function framedPage(target){
		 $('#targetFrame').attr('src',target)
		 uiUpdate('framed_page')
	 }
	 function frameResize(h){
		 $('#targetFrame').css('height',h)
	 }
	 function targetFrameLoaded(){
		 console.log($('#targetFrame').attr('src'));
		 document.getElementById("targetFrame").contentWindow.addressList(lastPubkeyMap)
	 }
	 
	 function loadVerificationDetail(id){
		 $('#modal').iziModal('open')
		 $('#modal').iziModal('setContent', '<center>Loading</center>');
			
			$.post('getproof.php',{id:id}, function(data) {
				console.log(data)
				$("#modal .iziModal-content").html('<div class="row"></div>');
				for (var k in data.response){
					$("#modal .iziModal-content .row").append('<div class="col-4">'+k+'</div><div class="col-8">'+data.response[k]+'</div>')
				}
				
			},'json');
	 }
	 function sig_gen(){
		 
		 var pk=$('.add_pubkey .pk_list').val()
		 if(pk=='input'){
			 pk=$('.add_pubkey input[name="pubkey_input"]').val()
		 }else{
			 $('.add_pubkey input[name="pubkey_input"]').addClass('hidden')
		 }
		 if(pk.substring(0,2)!='0x'){
			 return false;
		 }
		 var signID=userData.id
		 var is_inst=false
		 if($('.add_pubkey .bind_acc_id').val()!='self'){
			 signID=$('.add_pubkey .bind_acc_id').val();
			 is_inst=true
		 }
		 try{
			 console.log(web3.fromUtf8("Certi.me proof for "+(is_inst?"institution ":"")+"account "+signID))
			web3.personal.sign(web3.toHex("Certi.me proof for "+(is_inst?"institution ":"")+"account "+signID), pk, 
							   function(err, res) {
				$('.add_pubkey textarea[name="proof"]').val(res)
			});			 
		 }catch(e){
			console.log(e) 
			 try{
				 console.log(web3.utils.fromUtf8("Certi.me proof for "+(is_inst?"institution ":"")+"account "+signID))
				web3.eth.personal.sign(web3.utils.fromUtf8("Certi.me proof for "+(is_inst?"institution ":"")+"account "+signID), pk, 
								   function(err, res) {
					$('.add_pubkey textarea[name="proof"]').val(res)
				});			 
			 }catch(e){
				console.log(e) 
			 }
		 }
		 
		 //document.getElementById("metamask_frame").contentWindow.siggen("Certi.me proof for "+(is_inst?"institution":"")+" account "+signID , pk);
		 
	 }
	 function sigReturn(sig){
		 $('.add_pubkey textarea[name="proof"]').val(sig)
	 }
	function loadUserData(uiChange=1){
		$.post( "userinfo.php",function( data ) {
		  userData=data.response;
		  loggedin=true;
		  if(uiChange){uiUpdate('user_data',data.response)}
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}
	function getAddressData(addr){
		$.post( "ethData.php",{addr:addr,type:'address'},function( data ) {
			data.origin=addr;
		  uiUpdate('address_info',data);
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
	}
	
	function viewCertByID(id){
		ethCall('certificates',[id],function(data){
		data.origin=[id];
		if(data.issuer_addr=='0x0000000000000000000000000000000000000000'){
			errorMsg('This certificate does not exist.',10)
		}else{
			uiUpdate('cert_info',data)
		}

		})
	}	
	function viewCertByFingerprint(hash){
		ethCall('getCertsByProof',[hash],function(data){
			if(data[0].length==0||typeof data[0]=='undefined'){
				errorMsg("Failed to retrieve relavent certificate, check if you have entered the hash correctly",10)
			}else{
				successMsg("Retrieving your certificate, one moment...",3)
				viewCertByID(data[0][0])
			}
		})
		
		
		ethCall('certificates',[1],function(data){
		data.origin=[1];
		uiUpdate('cert_info',data)
		})
	}
	function abiParse(method,dataArray){
		var methodIndex=-1;
		for(var i=0;i<abi.length;i++){
			if(abi[i].name==method){
				methodIndex=i;
				break;
			}
		}
		if(methodIndex==-1){
			return false;
		}
		return web3.eth.abi.encodeFunctionCall(abi[i], dataArray);
	}
	
	function abiDecode(method,data){
		var methodIndex=-1;
		for(var i=0;i<abi.length;i++){
			if(abi[i].name==method){
				methodIndex=i;
				break;
			}
		}
		if(methodIndex==-1){
			return false;
		}
		console.log(abi[i].outputs)
		console.log(abi[i].outputs)
		return web3.eth.abi.decodeParameters(abi[i].outputs, data);
	}
	
	function ethCall(method,parameters,callBackFunction){
		$.post( "ethData.php",{data:abiParse(method,parameters),type:'certdata'},function( data) {
			callBackFunction(abiDecode(method,JSON.parse(data.response).result ));
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
	}
	
	function addConnection(id,st=1){
		$.post( "connect.php",{status:st,target:id},function( data ) {
		  successMsg('Success')
		  $('.follow-btn').text('✓').attr('disabled',true)
		  loadUserData(0);
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
	}
		function loadUserPost(){
		$.post( "get_post.php",function( data ) {
		  uiUpdate('user_post',data.response)
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}
	
	function nav(page,param){
		if(page=='home'){
			loadUserData()
		}
		if(page=='add_experience'){
			uiUpdate('add_experience')
		}
		if(page=='add_proof'){
			uiUpdate('add_proof')
			$('.bindcert form input[name="id"]').val(param)
		}
		if(page=='add_pubkey'){
			uiUpdate('add_pubkey')
		}
		if(page=='user_setting'){
			uiUpdate('user_setting')
		}
		if(page=='inst_setting'){
			uiUpdate('inst_setting',param)
		}
		if(page=='add_institution'){
			uiUpdate('add_institution')
		}
	}
	
	function homeInit(){
		if(loggedin==false){
			alert('Please login');
			location.href='/';
			return false;
		}		
		//Load feed
		
		
		//Load latest chat
		
		
		//Load friends request
		
	}

			/*$.post("bindcert.php", formData, function(data) {
				successMsg(data );
			},'json').fail(
			function(jqXHR) {
				  
			 }
			);*/
	function bindcert(){
		/*
		event.preventDefault();
		   $.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: "bindcert.php",
				data: new FormData($('form#bindcert')[0]),
				processData: false,
				contentType: false,
				cache: false,
				success: function (data) {
					successMsg(data );
				},
				error: function (e) {
					errorMsg(JSON.parse(e.responseText).response,10)
					$(".app_area .bindcert .form_response").show().addClass('alert alert-danger').text(JSON.parse(e.responseText).response)
					console.log(JSON.parse(e.responseText))
				}
			});
		*/
	/*
		event.preventDefault();
		$(".app_area .bindcert .form_response").hide()
		$.post( "bindcert.php",  $(".app_area #bindcert").serialize(),function( data ) {
		  console.log(data );
		  //redirect to app page
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			  $(".app_area .bindcert .form_response").show().addClass('alert alert-danger').text(JSON.parse(jqXHR.responseText).response)
			  console.log(JSON.parse(jqXHR.responseText))
			  
		 }
		)	*/
	 }
	 
	 function merkleOptions(r){
		 $('.optional_form_data').each(function(){$(this).hide()})
		 $('#proof_data_table').hide();
		 if(r=='01'){
			 $('.form_file,.form_file_permission').show()
		 }
		 if(r=='10'){
			 $('.form_merkle_options').show()
		 }

		 if(r=='100'){
			 r=102
			 //Or go back to last page?
		 }
		
		 if(r=='101'){
			 $('.form_merkle_options,.form_file,.form_file_permission,.form_data_hash,.form_merkle').show()
		 }
		
		 if(r=='102'){
			 $('.form_merkle_options,.form_file_hash,.form_proof_data,.form_proof_data_permission,.form_merkle').show()
		 }
		
		 if(r=='103'){
			 $('.form_merkle_options,.form_file,.form_file_permission,.form_proof_data,.form_proof_data_permission,.form_merkle').show()
		 }
		
	}
	 
	function bindcert_callback(t){	
	if(t==''){return false;}	
		d=JSON.parse(t);
		if(d.status=='Success'){
			successMsg('Successfully registered your certificate on the platform. ID:'+(JSON.parse(d.response).msg));
			getSelfProfile()
		}else{
			errorMsg(d.response)
			
		}
	}
	 
	function chatInput(t,event){
	  if (event.keyCode === 13) {
		  
			$.post( "getchat.php",  {type:'post',msg:t.value,id:pageParam['chat_target']},function( data ) {
			  getChat('-1');
			  t.value='';
			  //redirect to app page
			},'json').fail(
			function(jqXHR) {
				errorMsg(JSON.parse(jqXHR.responseText).response,10)
				  console.log(JSON.parse(jqXHR.responseText))
				  
			 })
		 }
	}	 
	 
	 	function addEducation(){
		event.preventDefault();
		$(".app_area .add_education .form_response").hide()
		$.post( "education.php",  $(".app_area #add_education").serialize(),function( data ) {
		  console.log(data );
		  //redirect to app page
			  $(".app_area .add_education .form_response").show().addClass('alert alert-success').text('Success. Redirecting to your profile')
		  getSelfProfile()
		},'json').fail(
		function(jqXHR) {
			  $(".app_area .add_education .form_response").show().addClass('alert alert-danger').text(JSON.parse(jqXHR.responseText).response)
			  console.log(JSON.parse(jqXHR.responseText))
			  
		 }
		)	
	 } 
	 	function addInstitution(){
		event.preventDefault();
		$(".app_area .add_institution .form_response").hide()
		$.post( "addinstitution.php",  $(".app_area #add_institution").serialize(),function( data ) {
		  console.log(data);
		  //Redirect to claim page if own key
		  claimPage(data.response.id)
		  
		},'json').fail(
		function(jqXHR) {
			  $(".app_area .add_institution .form_response").show().addClass('alert alert-danger').text(JSON.parse(jqXHR.responseText).response)
			  console.log(JSON.parse(jqXHR.responseText))
			  
		 }
		)	
	 } 
	 function claimPage(id){
		 
		$.post( "claiminstitution.php", {id:id},function( data ) {
			successMsg(data.response,10)
			getInstitution(id)
		},'json').fail(
		function(jqXHR) {
				console.log(jqXHR.responseText)

			 // $(".app_area .add_institution .form_response").show().addClass('alert alert-danger').text(JSON.parse(jqXHR.responseText).response)
			  	
				nav('add_pubkey')
				errorMsg(JSON.parse(jqXHR.responseText).response,20)
		 }
		)		 
		 //Check if page key is in user
		 //Success
		 
		 //Else
		// Redirect Public key page
		// Show msg

	 }
	 function addPubkey(){
		event.preventDefault();
		$(".app_area .add_pubkey .form_response").hide()
		$.post( "addpubkey.php",  $(".app_area #add_pubkey").serialize(),function( data ) {
		  console.log(data );
		  //redirect to app page
		  getSelfProfile()
		},'json').fail(
		function(jqXHR) {
			  $(".app_area .add_pubkey .form_response").show().addClass('alert alert-danger').text(JSON.parse(jqXHR.responseText).response)
			  console.log(JSON.parse(jqXHR.responseText))
			  
		 }
		)	
	 } 
	 
	function getProfile(id){
		$.post( "getprofile.php",{id:id},function( data ) {
		  profileData=data.response;
		  uiUpdate('user_profile',data.response)
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}	 	 
	function getInstitution(id){
		$.post( "getprofile.php",{id:id,inst:1},function( data ) {
		  profileData=data.response;
		  uiUpdate('inst_profile',data.response)
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}	 
	function getChatDigest(){
		$.post( "getchat.php",{type:'digest'},function( data ) {
			console.log(data)
		  uiUpdate('chat_digest',data.response)
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response+'. You will be redirected prompty',0,"location.href='index.php'");
			setTimeout(function(){location.href='index.php'},3000)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}
	
	function getChat(id,name,img){
		if(id!='-1'){
			pageParam['chat_name']=name;
			pageParam['chat_target']=id;
			pageParam['chat_img']=img;			
		}else{
			id=pageParam['chat_target']
		}
		$('.chat_header .name').text(name);
		$('.chat_header img').attr('src',img);
		
		$.post( "getchat.php",{type:'all',id:id},function( data ) {
				console.log(data)
	  uiUpdate('chat_content',data.response)
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}
	
	 function socialPostUpdate(){
		event.preventDefault();
		$(".app_area .social_post_updates .form_response").hide()
		$.post( "posting.php",  $(".app_area #post_update_form").serialize(),function( data ) {
		  console.log(data );
			  $(".app_area .social_post_updates .form_response").show().addClass('alert alert-success').text('Posted')
			  $(".app_area .social_post_updates textarea").val('')
		},'json').fail(
		function(jqXHR) {
			  $(".app_area .social_post_updates .form_response").show().addClass('alert alert-danger').text(JSON.parse(jqXHR.responseText).response)
			  console.log(JSON.parse(jqXHR.responseText))
			  
		 }
		)	
		 
	 }
	 
	function editfield(t,e){
		console.log(e)
		e.style="background:#EEEEEE"
		$.post("editfield.php",{t:t,field:e.name,value:e.value,inst_id:userData.currentInstitution},function(data){

				e.style="background:#EEFFEE"
			
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			e.style="background:#FFEEEE"
		 }
	 )

		 
	 }
	 
	 
	function dummyRegister(){
		event.preventDefault();
		$.post( "register.php",  $("#reg-form").serialize(),function( data ) {
		  console.log(data );
		  //redirect to app page
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			  console.log(JSON.parse(jqXHR.responseText) );
		 }
	 )	
	}
	function dummyLogin(){
		event.preventDefault();
		$.post( "login.php",  $("#login-form").serialize(),function( data ) {
		  console.log(data );
		  //redirect to app page
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse(jqXHR.responseText).response,10)
			  console.log(JSON.parse(jqXHR.responseText) );
		 }
	 )	
	}

					
	function search(q){
		$.post( "search.php",{query:q},function( data ) {
		  uiUpdate('site_search',data.response)
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse('Search Failed, Please try again later or with other terms').response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}
	var msgTimeoutEvt	
	function errorMsg(msg,time=10,action){
		$('.floatStaus').removeClass('hidden').attr('onclick',action+";$('.floatStaus').addClass('hidden')")
		$('.floatStaus .alert').text(msg).removeClass().addClass('alert').addClass('alert-danger')
		if(parseInt(time)>1){
			clearTimeout(msgTimeoutEvt)
			msgTimeoutEvt=setTimeout(function(){
				$('.floatStaus').addClass('hidden')
			},time*1000)
		}
	}
	function successMsg(msg,time=10,action){
		$('.floatStaus').removeClass('hidden').attr('onclick',action)
		$('.floatStaus .alert').text(msg).removeClass().addClass('alert').addClass('alert-success')
		if(parseInt(time)>1){
			clearTimeout(msgTimeoutEvt)
			msgTimeoutEvt=setTimeout(function(){
				$('.floatStaus').addClass('hidden')
			},time*1000)
		}
	}
	function inst_val(target,value,element){
		console.log(value)
		console.log(element)
		$('.active [name="issuer_id"]').val(value)
		$('.active [name="issuer_name"]').val($(element).find('.inst_name').text())
		console.log($(element).find('.inst_name').text())
		$('.suggestion_list').hide()
	}
	var floater_timeout
	function inst_suggestion(e,act,inputTarget){
		if(act=="close"){return false;}
		if(act=="nav"){floater_timeout=setTimeout(function(){$('.suggestion_list').hide()},50);return false;}
		var query=e.value;
		var element=$(e)
		var listEle=$('.suggestion_list')
		
		listEle.empty().show().css('position','absolute').css('background','#FFFFFF').css('box-shadow','0px 3px 10px #888').css('top',element.offset()['top']+element.height()+10).css('left',element.offset()['left']).css('width',element.width()).css('max-height','300px').css('overflow-y','scroll')
		$.post( "search.php",{query:query},function( data ) {
			var result=data.response.inst
			console.log(result)
			for (var i=0;i<result.length;i++){
				listEle.append('<div style="cursor:pointer" onclick=\'console.log(1);inst_val("'+inputTarget+'",'+result[i].id+',this)\'><div class="inst_name"><b>'+result[i].name+'</b></div><div>'+(result[i].pubkey==""?"No public key provided":result[i].chain+":"+result[i].pubkey)+'</div></div><hr>')
				
			}
		listEle.append('<div onclick="inst_suggestion(1,\'nav\');nav(\'add_institution\')"><b>Add institution</b><br>If you could not find your certificate issuer</div>')
			
		},'json').fail(
		function(jqXHR) {
			errorMsg(JSON.parse('Search Failed, Please try again later or with other terms').response,10)
			console.error(JSON.parse(jqXHR.responseText) );
		})
		
	}
	var qrWindow
	var qrTarget=''
	function qrHelper(qt){
		qrTarget=qt;
		qrWindow = window.open('qr.php', 'qrWindow');
	}
	function HandlePopupResult(result) {
		$('.active .'+qrTarget+', .active [name="'+qrTarget+'"]').val(result)
		if(qrTarget=='proof_txid'&&isJson(result)){
			j=JSON.parse(result);
			console.log(j)
			$('.active .'+qrTarget+', .active [name="'+qrTarget+'"]').val(j._merkle)
			//Input File Hash
			$('.active [name="proof_file_hash"]').val(j._filehash)
			//Remove useless data and input the metadata
			delete j._merkle;
			delete j._filehash;
			var tempJSONstring=(JSON.stringify(j, Object.keys(j).sort()))
			console.log(j)
			console.log(tempJSONstring)
			hashText(tempJSONstring, 'proof_data_hash')
			$('.active [name="proof_data"]').val(tempJSONstring)
			//Display only related info to the user
			$('.active #merkle_options').val('2');
			autofill_certproof();
			
		}
		
	}
	
	function hashText(v,t){
		SHA_256_HASH(v).then(function(val){
			$('.active [name="'+t+'"]').val(val)
			proof_compute()
		});
	}
	
	function isJson(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}

	function autofill_certproof(){
		if($('.active [name="proof_txid"]').val()==''){return false;}
		
		if(! (/^\d+$/.test($('.active [name="proof_txid"]').val()))){
			//Find CertID with proof
			$('.active [name="proof_txid"]').val($('.active [name="proof_txid"]').val().toLowerCase())
			ethCall('getCertsByProof',[$('.active [name="proof_txid"]').val()],function(data){
				if(data[0].length==0||typeof data[0]=='undefined'){
					errorMsg("Failed to retrieve relavent certificate, check if you have entered the hash correctly",10)
				}else{
					$('.active [name="proof_txid"]').val(data[0][0]);
					autofill_certproof();return false
				}
			})

		}
		var target_cert_id=$('.active [name="proof_txid"]').val()
		//Find and auto fill stuff with certID
		
		ethCall('certificates',[$('.active [name="proof_txid"]').val()],function(data){
			console.log(data);
			data.origin=[$('.active [name="proof_txid"]').val()];
			//Check Recepient ID is in user's verified wallet
			var inWallet=false;
			for(var i=0;i<userData.pubkey.length;i++){
				console.log(userData.pubkey[i].pubkey)
				console.log(data.recepient_addr)
				data.recepient_addr=data.recepient_addr.toLowerCase()
				if(userData.pubkey[i].pubkey.toLowerCase()==data.recepient_addr.toLowerCase()){
					inWallet=true;
				}
			}
			if(data.recepient_addr.toLowerCase()=='0x0000000000000000000000000000000000000000'){
				inWallet=true
			}
			if(!inWallet){
				errorMsg('The recepient address '+data.recepient_addr+' is not registered on our platform, you will fail the certification process if you proceed. If you do own the key, click here to register your address.',20,'nav("add_pubkey")')
			}
			$('.autofill_area').show();
			$('.prefill-area').hide();
	
			$('.active [name="proof_type"]').val(data.version.substr(0, 2))
			$('.active #merkle_options').val('100')
			merkleOptions(data.version.substr(0, 2))
			merkleOptions('100')
			$('.active [name="merkle_hash"]').val(data.certHash)
			$('.active [name="issuance_date"]').val('2000-01-01')
			userData.currentMerkle=data.certHash
			$('.active [name="proof_chain"]').val('eth')
			if(data.version.substr(0, 2)=='00'){
				$('.active [name="proof_file_hash"]').val(data.certHash)
			}
			//IssuerID Search and Fill
			$.post( "search.php", {'pubkey_search':1,'query':data.issuer_addr},function( results ) {
				if(results.response.inst.length==0){
					errorMsg('The issuer\'s key '+data.issuer_addr+' is not registered on our platform, you will fail the certification process if you proceed. Please contact your institution.',20)
				}else{
					$('.active [name="issuer_id"]').val(results.response.inst[0].inst_id)
				}
			},'json').fail(
			function(jqXHR) {
				errorMsg(JSON.parse(jqXHR.responseText).response,10)
				console.log(JSON.parse(jqXHR.responseText) );
			}
			)	
			proof_compute()
			setTimeout(function(){ proof_compute()},500)
			showDigestTableOverlay();


		})		
	}
	
	document.addEventListener('DOMContentLoaded', function() {
	if(debug){
		loadUserPost();
		loadUserData();
		getProfile();
		getChatDigest()
	}else{
		loadUserPost();
		loadUserData();
		getChatDigest()
		
	}
}, false);	
function proof_compute(){
	var type= $('.active [name="proof_type"]').val()
	var merkleOp= $('.active [name="merkle_options"]').val()
	var h0= $('.active [name="proof_file_hash"]').val()
	var h1= $('.active [name="proof_data_hash"]').val()
	var merkleHash= $('.active [name="merkle_hash"]').val()
	
	if(merkleHash!=userData.currentMerkle&&userData.currentMerkle!=''){
		$('.form_proof_valid').text('Your proof fingerprint does not resemble the certificate you provided just now.')
	}else{
	SHA_256_HASH(h0.toUpperCase()+''+h1.toUpperCase()).then(function(val){
		if(val==merkleHash){
		$('.form_proof_valid').text('Your information provided successfully resembles the proof')
		}else{
		$('.form_proof_valid').text('Your information provided does not resembles the proof')
		}
	});
	}

}
function dtt(d){
		try
	{	   d = JSON.parse(d);	}
	catch(e)
	{	   return ''	}
		var html='<table style="border:1px solid #000;width:100%;border-collapse:collapse"><tr><th>Field</th><th>Content</th></tr>'
		for (var k in d){
			html+="<tr><td style='border:solid 1px #888'>"+k+"</td><td  style='border:solid 1px #888'>"+d[k]+"</td></tr>"
		}
		html+='</table>'
		return html;
}
function showDigestTableOverlay(){
	var json=0
	try
	{	   json = JSON.parse($('.active [name="proof_data"]').val());	}
	catch(e)
	{	   alert('invalid json');	}
	if(json){
		$('#ccModal').show().modal('show');
		var html='<table style="border:1px solid #000;width:100%;border-collapse:collapse"><tr><th>Field</th><th>Content</th></tr>'
		for (var k in json){
			html+="<tr><td style='border:solid 1px #888'>"+k+"</td><td  style='border:solid 1px #888'>"+json[k]+"</td></tr>"
		}
		html+='</table>'
		$('#certjsoncontent').html(html);
		$('#proof_data_table').html(html);
		
	}
	
}
function hashIt() {
  var nBytes = 0,
      oFiles = document.getElementById("proof_file").files,
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
		$('.active [name="proof_file_hash"]').val(hashValue)
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
function frameWeb3Enabled(t){
	web3enabled=t
}



  window.addEventListener("load", function() {
    // Checking if Web3 has been injected by the browser (Mist/MetaMask)
    if (typeof web3 !== "undefined") {
      // Use Mist/MetaMask's provider
      window.web3 = new Web3(web3.currentProvider);
	  web3enabled=true
    } else {
      console.log("No web3? You should consider trying MetaMask!");
      // fallback - use your fallback strategy (local node / hosted node + in-dapp id mgmt / fail)
      window.web3 = new Web3(
        new Web3.providers.HttpProvider("http://localhost:8545")
      );
    }
  })
  function issueCert(info){
	  console.log(info)
	var ctx=new web3.eth.Contract(abi, "0x8dffd6644cf466d083fc6db8c61ad88443e48c99");
	//ctx.methods.newCertificate($('#recepient_addr').val(),h01,$('#itype').val()+'.'+$('#dtype'),$('').val('#content')).send({from: '0x08a8ad7C391285cdF77AA3347E0078a4FB59F663'}).on('transactionHash', function(hash){
	ctx.methods.newCertificate(info[0],info[1],info[2],info[3]).send({from: info[4]}).on('transactionHash', function(hash){
		successMsg('Issuance Successful, waiting for confirm<br>Transaction ID:'+hash,10);
	}).on('confirmation', function(confirmationNumber, receipt){
		successMsg('Issuance Successful, Transaction ID:'+hash,10);
	}).on('receipt', function(receipt){
		// receipt example
		console.log(receipt);
	}).on('error',errorMsg); // If there's an out of gas error the second parameter is the receipt.
  }
    function revokeCert(info){
	  console.log(info)
	var ctx=new web3.eth.Contract(abi, "0x8dffd6644cf466d083fc6db8c61ad88443e48c99");
	//ctx.methods.newCertificate($('#recepient_addr').val(),h01,$('#itype').val()+'.'+$('#dtype'),$('').val('#content')).send({from: '0x08a8ad7C391285cdF77AA3347E0078a4FB59F663'}).on('transactionHash', function(hash){
	ctx.methods.revokeCertificate(info[0]).send({from: $('.da_addr a').text()}).on('transactionHash', function(hash){
		successMsg('Revoke Successful, waiting for confirm<br>Transaction ID:'+hash,10);
	}).on('confirmation', function(confirmationNumber, receipt){
		successMsg('Revoke Successful, Transaction ID:'+hash,10);
	}).on('receipt', function(receipt){
		// receipt example
		console.log(receipt);
	}).on('error',errorMsg); // If there's an out of gas error the second parameter is the receipt.
  }
  
  function batchIssueCert(info,addr){
	  console.log(info,addr)
	var ctx=new web3.eth.Contract(abi, "0x8dffd6644cf466d083fc6db8c61ad88443e48c99");
	//ctx.methods.newCertificate($('#recepient_addr').val(),h01,$('#itype').val()+'.'+$('#dtype'),$('').val('#content')).send({from: '0x08a8ad7C391285cdF77AA3347E0078a4FB59F663'}).on('transactionHash', function(hash){
	ctx.methods.batchNewCertificate(info.recipient,info.proof,info.version,info.content,info.proofLength,info.versionLength,info.contentLength).send({from: addr}).on('transactionHash', function(hash){
		successMsg('Issuance Successful, waiting for confirm<br>Transaction ID:'+hash,10);
	}).on('confirmation', function(confirmationNumber, receipt){
		successMsg('Issuance Successful, Transaction ID:'+hash,10);
	}).on('receipt', function(receipt){
		// receipt example
		console.log(receipt);
	}).on('error',errorMsg); // If there's an out of gas error the second parameter is the receipt.
	  
  }
$("#modal").iziModal();
/*
    // APP >

    web3.eth.getAccounts(function(error, accounts) {
      if (!error) {
        web3.eth.getBalance(accounts[0], function(error, balance) {
          if (!error) {
            console.log(
              "Your account: " +
                accounts[0] +
                " has a balance of: " +
                balance.toNumber() / 1000000000000000000 +
                "Ether"
            );
          } else {
            console.error(error);
          }
        });
      } else {
        console.error(error);
      }
	  
	web3.personal.sign(web3.toHex("message to sign"), accounts[0], 
					   function(err, res) {
		console.log(err,res)
	});

    });
	web3.version.getNetwork((err, netId) => {
  switch (netId) {
    case "1":
      console.log('This is mainnet')
      break
    case "2":
      console.log('This is the deprecated Morden test network.')
      break
    case "3":
      console.log('This is the ropsten test network.')
      break
    default:
      console.log('This is an unknown network.')
  }

});

console.log(web3.version.api);


  });

*/

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  
}