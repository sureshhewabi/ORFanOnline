@extends('layouts.master')

@section('title')
ORFanID - results
@endsection

@section('body')

<link type="text/css" rel="stylesheet" href="css/jquery.dataTables.min.css"
media="screen,projection" />
<link type="text/css" rel="stylesheet" href="css/orfanid-results.css">

<script type="text/javascript" src="js/plotly-latest.min.js"></script>
<script type="text/javascript" src="js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/orfanid-results.js"></script>

<script type="text/javascript" src="js/export/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="js/export/buttons.flash.min.js"></script>
<script type="text/javascript" src="js/export/vfs_fonts.js"></script>
<script type="text/javascript" src="js/export/buttons.html5.min.js"></script>
<script type="text/javascript" src="js/export/buttons.print.min.js"></script>
<script>

$(function(){

	  $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
           }
       })

			var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

			  $('a[data-modal-id]').click(function(e) {
				e.preventDefault();
				$("body").append(appendthis);
				$(".modal-overlay").fadeTo(500, 0.7);
				$(".js-modalbox").fadeIn(500);
				var modalBox = $(this).attr('data-modal-id');
				$('#'+modalBox).fadeIn($(this).data());
				  var userMail=readCookie("user-email");
				   var datetime = $('#currentDateTime').val();
				   var userid = $('#username').text();
					console.log("datetime :"+datetime);
					$('#resultName').val("Result : "+datetime+" "+userid);


				   			 if (userMail != "") {
						     	console.log("userMail :"+userMail);
								$('#userEmail').val(userMail);
								//saveResults(userid,userMail,datetime);

						    } 
				
			  });  
			  
			  
			$(".js-modal-close, .modal-overlay").click(function() {
				console.log("modal-close");
			  $(".modal-box, .modal-overlay").fadeOut(500, function() {
				$(".modal-overlay").remove();
			  });
			});
			 
			$(window).resize(function() {
			  $(".modal-box").css({
				top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
				left: ($(window).width() - $(".modal-box").outerWidth()) / 2
			  });
			});
			 
			$(window).resize();


																															         $("#btnCloseModal").on('click', function() {
				console.log("modal-close");
			  $(".modal-box, .modal-overlay").fadeOut(500, function() {
				$(".modal-overlay").remove();
			  });
});

		$("#btnSaveCokkie").on('click', function() {
				console.log("Save Cokkie Click");
				createCookie('user-email',$('#userEmail').val(),365);
				 var userMail=readCookie("user-email");
				  var datetime = $('#currentDateTime').val();
				  var userid = $('#username').text();
						 if (userMail != "") {
if(validateEmail(userMail)){
				saveResults(userid,userMail,datetime);
}
						}else {
				    alert('Empty Email Feild !');
}
		});
			
			 function saveResults (userid , userMail, datetime) {
			 			console.log("Save userMail"+userMail);
			 	 	  var data={"id":userid,"datetime":datetime,"email":userMail,"organism": $('#hdnorganism').val(),"taxonomyLevels":$('#hdntaxonomyLevels').val()}
//ENSURE FRIST STRING GET SAVED SHOUD REMOVED "," MANUALLY
									        	  $.ajax({
													  type: "POST",
													url: "/saveresult",
													async:false,
													dataType: "json",
													data: {"metaResult":data,
			                   							"_token": "{{ csrf_token() }}"},
														    success: function (data) {
														        console.log(data);
											$(".modal-box, .modal-overlay").fadeOut(500, function() {
											$(".modal-overlay").remove();
												 });
																	
														    },
														 error: function (data) {
							  								console.log('Error:', data);
														         

													 }
										});

			}

			});



function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function validateEmail(emailaddress){  
   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;  
   if(!emailReg.test(emailaddress)) {  
        alert("Please enter valid email id !");
        return false;
   }  else {
   			 return true;
   }

}
	
</script>
<title>ORFanID - Results</title>

	<main>
		<div style="height: 0px;width: 0px;overflow:hidden;">
			<a href="#" id="username">{{ Session::get('userid') }}</a>
		</div>
		<div class="row">
			<div class="col s10 offset-s2">
					<a class="waves-effect waves-light btn" href="#" data-modal-id="popup">Save Result	<i class="material-icons right">send</i></a>
<input type="hidden"  id="hdnorganism" name="hdnorganism" value="{{ $metadata->organism }}"  />
<input type="hidden"  id="hdntaxonomyLevels" name="hdntaxonomyLevels" value="{{ $metadata->taxonomyLevels }}"  />
<span class="new badge" data-badge-caption='{{ $metadata->organism }}'>Organism :</span>
<span class="new badge" data-badge-caption='{{ $metadata->taxonomyLevels }}'>Tax.Level :</span>
				 <span class="new badge" data-badge-caption='{{ $metadata->blast_db }}'>Database :</span>
				  <span class="new badge" data-badge-caption='{{ $metadata->blast_evalue }}'>E-value:</span>
					<span class="new badge" data-badge-caption='{{ $metadata->blast_max_hits }}'>Max blast hits:</span>
			</div>
    </div>


		<div class="row">
			<div class="col s5 offset-s1 center-align">
				<H4> ORFan Genes</H4>
				<table id="ORFanGenesSummary" class="display" cellspacing="0">
					<thead>
						<tr>
							<th width ="60%">Taxonomy Level</th>
							<th width ="40%">No of orphan Genes</th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="col s6 center-align">
				<H4> ORFan gene summary</H4>
				<dir id="genesummary">
				</dir>
			</div>
		</div>

		<div class="divider"></div>
			<div class="section">
				<div id="ORFanGenesTable" class="row">
					<div class="col s10 offset-s1 center-align">
						<H3> ORFan Genes</H3>
						<table id="ORFanGenes" class="display" cellspacing="0">
							<thead>
								<tr>
									<th>Gene</th>
									<th>Description</th>
									<th>ORFan Gene Level</th>
									<th>Taxonomy Level</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>

		<div class="divider"></div>
			<div class="section">
					<div id="BlastResultsTable" class="row">
						<div class="col s10 offset-s1 center-align">
							<h3> Blast Results</h3>
							<h6 id="selectedgeneid"></h6>
							<table id="blastresults" class="display" cellspacing="0">
								<thead>
									<tr>
										<th>#</th>
										<th>Gene</th>
										<th>Taxonomy Level</th>
										<th>Taxonomy Name</th>
										<th>Parent Taxonomy Name</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
			</div>
				<div id="popup" class="modal-box">  
				  <header>
				    <a href="#" class="js-modal-close close">×</a>
				    <h6> <input type="text" id="resultName" name="resultName" value="" ></h6> 
				  </header>
				  <div class="modal-body">
							<div class="row">
								<div class="input-field col offset-s1 s10">
									<label for="lblEmail">Email </label>
									<input type="email" id="userEmail" name="userEmail" class="materialize-text">
									<input type="hidden"  id="currentDateTime" name="currentDateTime" value="<?=date('Y-m-d H:i:s');?>"  />
								</div>
							</div>
				  </div>
				  <footer>
				  <button class="btn waves-effect waves-light" type="button" name="btnSaveCokkie"  id="btnSaveCokkie">Save
							 	<i class="material-icons right">send</i>
					 		</button>
				    <a href="#" name="btnCloseModal"  id="btnCloseModal" class="waves-effect waves-light btn">Close</a>
				  </footer>
				</div>
	</main>

	@endsection
