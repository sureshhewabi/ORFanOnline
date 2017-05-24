@extends('layouts.master')

@section('title')
	ORFanID - Input
@endsection

@section('body')

<link type="text/css" rel="stylesheet" href="css/orfanid-input.css">
<script type="text/javascript" src="js/orfanid-input.js"></script>

<main>
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
	<div class="row hidden" id="advanceparameterssection">
		<div class="col offset-s1 s10">
			<h6>Advanced parameters:</h6><br>
			<p class="range-field">
				<label for="maxevalue">Maximum E-value for BLAST(e-10):</label>
	     	<input type="range" id="maxevalue"  name="maxevalue" min="1" max="10" value="3"/>
				<label for="maxtargets">Maximum target sequences for BLAST:</label>
				<input type="range" id="maxtargets" name="maxtargets" min="100" max="1000" value="{{Config::get('orfanid.default_maxtargetseq')}}"/>
	   	</p>
		</div>
	</div>
	<div class="row">
		<div class="col offset-s1 s4">
			<a id="load-example-data" class="waves-effect waves-light">Example</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;
			<a id="advanceparameterslink" class="waves-effect waves-light">Advanced parameters</a>
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
