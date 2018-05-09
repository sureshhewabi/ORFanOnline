@extends('layouts.master')

@section('title')
	ORFanID - Clamp Results
@endsection

@section('body')

<link type="text/css" rel="stylesheet" href="css/jquery.dataTables.min.css"
media="screen,projection" />
<link type="text/css" rel="stylesheet" href="css/orfanid-results.css">
<script type="text/javascript" src="js/plotly-latest.min.js"></script>
<script type="text/javascript" src="js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="js/export/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="js/export/buttons.flash.min.js"></script>
<script type="text/javascript" src="js/export/vfs_fonts.js"></script>
<script type="text/javascript" src="js/export/buttons.html5.min.js"></script>
<script type="text/javascript" src="js/export/buttons.print.min.js"></script>

<script type="text/javascript" src="js/orfanid-clamp-results.js"></script>

<main>
	<div class="row">
		<div class="col offset-s1 col s10">
<H5> Clamp Results</H5>
		  <table id="clampresults" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th>Chromosome</th>
		                <th>HumanGenes</th>
		                <th>Retrotranspon</th>
		                <th>Mouse Ortholog</th>
		                <th>Dog Ortholog</th>
		                <th>HumanParalog</th>
		                <th>Protein Family</th>
		                <th>Candidate orphan genes</th>
		                <th>Protein coding orphan Genes</th>
										<th>Orphan Genes Download</th>
		            </tr>
		        </thead>
		    </table>
		</div>
	</div>
</main>
@endsection
