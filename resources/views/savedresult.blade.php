@extends('layouts.master')

@section('title')
ORFanID - results
@endsection

@section('body')

<link type="text/css" rel="stylesheet" href="{{ url('css/jquery.dataTables.min.css') }}"
media="screen,projection" />
<link type="text/css" rel="stylesheet" href="{{ url('css/orfanid-results.css') }}">

<script type="text/javascript" src="{{ url('js/plotly-latest.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/jquery-1.12.4.js') }}"></script>
<script type="text/javascript" src="{{ url('js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ url('js/orfanid-preview.js') }}"></script>

<script type="text/javascript" src="{{ url('js/export/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/export/buttons.flash.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/export/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ url('js/export/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/export/buttons.print.min.js') }}"></script>

<title>ORFanID - Results</title>

	<main>
		<div style="height: 0px;width: 0px;overflow:hidden;">
			<a href="#" id="username"> {{ $result_id }}</a>
			<input type="hidden"  id="username2" name="username2" value="{{$result_id}}"  />
		</div>
		<div class="row">
			<div class="col s10 offset-s2">
					
				 <span class="new badge" data-badge-caption='{{-- $metadata->blast_db --}}'>Database :</span>
				  <span class="new badge" data-badge-caption='{{-- $metadata->blast_evalue --}}'>E-value:</span>
					<span class="new badge" data-badge-caption='{{-- $metadata->blast_max_hits --}}'>Max blast hits:</span>
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
									<input type="text" id="userEmail" name="userEmail" class="materialize-text">
									<input type="hidden"  id="currentDateTime" name="currentDateTime" value="<?=date('Y-m-d H:i:s');?>"  />
								</div>
							</div>
				  </div>
				  <footer>
				  <button class="btn waves-effect waves-light" type="button" name="btnSaveCokkie"  id="btnSaveCokkie">Save
							 	<i class="material-icons right">send</i>
					 		</button>
				    <a href="#" class="waves-effect waves-light btn">Close</a>
				  </footer>
				</div>
	</main>

	@endsection
