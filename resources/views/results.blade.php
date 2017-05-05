@extends('layouts.master')

@section('title')
ORFanID - results
@endsection

@section('body')

<link type="text/css" rel="stylesheet" href="css/jquery.dataTables.min.css"
media="screen,projection" />

<script type="text/javascript" src="js/plotly-latest.min.js"></script>
<script type="text/javascript" src="js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="js/export/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="js/export/buttons.flash.min.js"></script>
<script type="text/javascript" src="js/export/vfs_fonts.js"></script>
<script type="text/javascript" src="js/export/buttons.html5.min.js"></script>
<script type="text/javascript" src="js/export/buttons.print.min.js"></script>

<title>Orphan Genes Finder</title>

<script type="text/javascript">
$(document).ready(
	function() {
		var data = [ {
			x : [ 'class', 'superkingdom', 'phylum', 'family', 'genus',
			'species', 'strict' ],
			y : [ 4, 6, 5, 5, 4, 5, 5 ],
			type : 'bar',
			marker : {
				color : '#ef6c00'
			}
		} ];
		var layout = {
			yaxis: {
			title: 'Number of Orphan Genes'
		}}
		Plotly.newPlot('genesummary', data, layout);

		$('#ORFanGenes').DataTable( {
			"ajax": "users/sureshhewabi@gmail.com/1492507706/ORFanGenes.json",
			"oLanguage": {
				"sStripClasses": "",
				"sSearch": "",
				"sSearchPlaceholder": "Enter Search Term Here",
				"sInfo": "Showing _START_ -_END_ of _TOTAL_ genes",
				"sLengthMenu": '<span>Rows per page:</span>'+
				'<select class="browser-default">' +
				'<option value="5">5</option>' +
				'<option value="10">10</option>' +
				'<option value="20">20</option>' +
				'<option value="50">50</option>' +
				'<option value="100">100</option>' +
				'<option value="-1">All</option>' +
				'</select></div>'
			},
			dom: 'frtlipB',
			buttons: [['csv', 'print']],
		});
		$('#ORFanGenesSummary').DataTable( {
			"ajax": "data/ORFanGenesSummary.json",
			"oLanguage": {
				"sStripClasses": "",
				"sSearch": "",
				"sSearchPlaceholder": "Enter Search Term Here"
			},
			dom: 'frt'
		});

		$('#blastresults').DataTable( {
			"columnDefs": [
				{
					"targets": [ 1 ],
					"visible": false,
					"searchable": false
				}],
				"ajax": "users/sureshhewabi@gmail.com/1492507706/blastresults.json",
				"oLanguage": {
					"sStripClasses": "",
					"sSearch": "",
					"sSearchPlaceholder": "Enter Search Term Here",
					"sInfo": "Showing _START_ -_END_ of _TOTAL_ blast results",
					"sLengthMenu": '<span>Rows per page:</span>'+
					'<select class="browser-default">' +
					'<option value="5">5</option>' +
					'<option value="10">10</option>' +
					'<option value="20">20</option>' +
					'<option value="50">50</option>' +
					'<option value="100">100</option>' +
					'<option value="-1">All</option>' +
					'</select></div>'
				},
				dom: 'frtlipB',
				buttons: [['csv', 'print']]
			});

			// add materialize CSS to print buttons
			$('.buttons-csv').addClass('waves-effect waves-light btn');
			$('.buttons-print').addClass('waves-effect waves-light btn');
		});

		</script>
		<style type="text/css">
		body {
			display: flex;
			min-height: 100vh;
			flex-direction: column;
		}

		main {
			flex: 1 0 auto;
		}
	}
	</style>

	<main>

		<div class="row">
			<div class="col s10 offset-s2">
				 <span class="new badge" data-badge-caption="nr">Database :</span>
				  <span class="new badge" data-badge-caption="1e-3">E-value:</span>
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
				<H3> Blast Results</H3>
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
	</main>

	@endsection
