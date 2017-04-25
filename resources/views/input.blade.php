@extends('layouts.master')

@section('title')
	ORFanID - Input
@endsection

@section('body')

<script type="text/javascript">
	$(document).ready(function() {

				$('#genesequence').trigger('autoresize');
				$('.modal').modal();
				$('select').material_select();
				var organisms = [];

				$.ajax({
				  url: 'data/TaxData.json',
				  async: false,
				  dataType: 'json',
				  success: function (response) {
						$.each(response, function(key, val) {
										var options = "";
						 				options = val.SpeciesName + '(' + val.NCBITaxID+')';
										organisms[options] = "null";
								 });
				  	 }
					 });
				console.log(organisms);
				$('input.autocomplete').autocomplete({
					data :organisms,
					limit : 10 // The max amount of results that can be shown at once. Default: Infinity.
				});

				var taxonomy = [];

				$( "#organismName" ).change(function() {
					$.ajax({
					  url: 'data/TaxData.json',
					  async: false,
					  dataType: 'json',
					  success: function (response) {
							var selectedOrganism = $('#organismName').val();
							var regularExpr = /\((.*)\)/;

							var selectedOrganismTaxID = selectedOrganism.match(regularExpr)[1];
							$.each(response, function(key, val) {
								var options = "";
								if(val.NCBITaxID == selectedOrganismTaxID){
									$('select').empty().html(' ');
									$.each(val.Taxonomy, function(key, val) {
										var value = val.substr(9,val.length);
										$('select').append($("<option></option>").attr("value",value).text(value));
									});
									// re-initialize (update)
									$('select').material_select();
								}
							});
					  },
						error:function(error){
							alert(error);
						}
					});
				});

				$('#submit').click(function(){
					console.log('submit clicked');
					$('#modal1').modal('open');
				});

				$('#load-example-data').click(function(){
					console.log('#example link clicked');
          $('#genesequence').load('data/Ecoli_511145.fasta');
          $('#genesequence').addClass('active');
          $('#organismName').val('Escherichia coli str. K-12 substr. MG1655 (511145)');
          $.ajax({
						url: 'data/TaxData.json',
						async: false,
						dataType: 'json',
						success: function (response) {
							var selectedOrganism = $('#organismName').val();
							var regularExpr = /\((.*)\)/;

							var selectedOrganismTaxID = selectedOrganism.match(regularExpr)[1];
							$.each(response, function(key, val) {
          			var options = "";
          			if(val.NCBITaxID == selectedOrganismTaxID){
          				$('select').empty().html(' ');
          				$.each(val.Taxonomy, function(key, val) {
	                	var value = val.substr(9,val.length);
                  	$('select').append($("<option></option>").attr("value",value).text(value));
                	});
                	// re-initialize (update)
                	$('select').material_select();
                }
              	});
              },
                 error:function(error){
                 alert(error);
                 }
              });
          $(function() {
            Materialize.updateTextFields();
            });
          return true;
            });
   		});
</script>
<main>

	<!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Modal Header</h4>
      <p>A bunch of text</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
    </div>
  </div>

	{!! Form::open(['route' => 'input.store','method' => 'POST']) !!}
	<div class="row">
		<div class="col s12">
				<div class="input-field col offset-s1 s10">
					<textarea id="genesequence" name="genesequence" class="materialize-textarea">
					</textarea>
					<label for="genesequence">Protein Sequence</label>
				</div>
		</div>
	</div>
	<div class="row">
		<div class="col s6">
			<div class="row">
				<div class="input-field col offset-s2 s10">
					<input type="text" id="organismName" name="organismName" class="autocomplete">
					<label for="organismName">Organism</label>
				</div>
			</div>
		</div>
		<div class="col s6">
			<div class="row">
				<div class="input-field col s10">
					<select id="taxonomyLevels" name="taxonomyLevels">
						<option value="" disabled selected>Choose your option</option>
					</select>
					<label>Taxonomy Level</label>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col offset-s1 s1">
			<a id="load-example-data" class="waves-effect waves-light">Example</a>
		</div>
		<div class="col offset-s7 s2">
			<button class="btn waves-effect waves-light" type="submit" name="action" id="submit">Submit
			 	<i class="material-icons right">send</i>
	 		</button>
		</div>
	</div>
	{!! Form::close() !!}
	</main>
@endsection
