@extends('layouts.master')

@section('title')
ORFanID - results
@endsection

@section('body')
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" media="screen,projection" />

<link type="text/css" rel="stylesheet" href="{{ url('css/jquery.dataTables.min.css') }}"
media="screen,projection" />

<script type="text/javascript" src="{{ url('js/jquery-1.12.4.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="{{ url('js/jquery.dataTables.min.js') }}"></script>
<link type="text/css" rel="stylesheet" href="{{ url('css/saved-results.css') }}">
<script type="text/javascript" src="{{ url('js/saved-results.js') }}"></script>
<script type="text/javascript" >
$(document).ready(function() {

		 var userMail=readCookie("user-email");
			
				   			 if (userMail != "") {
						     	console.log("userMail :"+userMail);
								$('#userEmail').val(userMail);
								//saveResults(userid,userMail,datetime);

						    }else {
						    	alert("No Saved Results for your Mail!");
						    } 

		/* $.ajax({
	    				type: "GET",
						url: "/readresult",
						async:false,
						dataType: "json",
						success: function (data) {
							console.log(data);

						},
						error: function (data) {
						 console.log('Error:', data);							         
						}									
				});  */   

	    	 $('#ResultViewTable').DataTable({
                        "pageLength": 25,
                         "dom": "Bfrtip",
                        "ajax": {
                        	"url":"/readresult",
                        	"type":"GET",
                        	"dataType": "json",
                        },
                          "aoColumns": [
                       { "data": "datetime", "searchable": true },
                        {  "data": "id", "searchable": true },
                        { "data": "email", "searchable": true}, { "data": "organism" }, { "data": "taxonomyLevels" },
                       { "data": "id",               
                        "render": function ( data, type, full, meta ) {
                			 return '<a href=previewsave/'+data+' target="_blank" >View</a>';
                			}}
                   ]
                    }
                ); 

	 $( "#dateSelect" ).datepicker({ dateFormat: 'yy-mm-dd',
   onSelect: function(dateText) {
    console.log("Selected date: " + dateText + " input's current value: " + this.value);

    	 $('#ResultViewTable').dataTable().fnFilter(dateText);
    
  
  } });

 	$("#clearDate").on('click', function() {
 		  console.log("Clear Data");
  $('#ResultViewTable').dataTable().fnDestroy();
 		   //$('#ResultViewTable').fnClearTable();
 		  	 $('#ResultViewTable').DataTable({
                        "pageLength": 25,
                         "dom": "Bfrtip",
                        "ajax": {
                        	"url":"/readresult",
                        	"type":"GET",
                        	"dataType": "json",
                        },
                          "aoColumns": [
                       { "data": "datetime", "searchable": true },
                        {  "data": "id", "searchable": true },
                        { "data": "email", "searchable": true}, { "data": "organism" }, { "data": "taxonomyLevels" },
                       { "data": "id",               
                        "render": function ( data, type, full, meta ) {
                			 return '<a href=previewsave/'+data+' target="_blank" >View</a>';
                			}}
                   ]
                    }
                ); 	

     });

			

});
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
	</script>
<title>ORFanID - Results</title>

	<main>
		<div style="height: 0px;width: 0px;overflow:hidden;">
			<a href="#" id="username">{{--Session::get('userid')--}}</a>
		</div>
		<div class="row">
			<div class="col s12 center">
<H5> Saved ORFanID Results</H5>
			</div>
    </div>
		<div class="row">
		<div class="col s6">
			<div class="row">
				
				<div class="input-field col offset-s2 s10">
					<!--<label for="lblEmail">Email </label>-->
					<input  type="email" disabled  id="userEmail" name="userEmail" class="materialize-text">
				
				</div>
			</div>
		</div>
		<div class="col s6">
			<div class="row">
				<div class="input-field col s8">
					
 				 <input type="text" class="datepicker" id="dateSelect">
					<label>Date </label>
				</div>
				<div class="input-field col s2">
					
					  <button class="btn" type="button" name="clearDate"  id="clearDate">Clear
	 		</button>
				</div>
			</div>
		</div>
	</div>
			<div class="section">
				<div id="savedResultView" class="row">
					<div class="col s10 offset-s1 center-align">
						
						<table id="ResultViewTable" class="display" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>Date</th>
									<th>Result ID</th>
									<th>E Mail</th>
<th>Organism</th><th>Taxonomy Level</th>
									<th></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									<th>Result ID</th>
									<th>E Mail</th>
<th>Organism</th><th>Taxonomy Level</th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
	</main>

	@endsection

