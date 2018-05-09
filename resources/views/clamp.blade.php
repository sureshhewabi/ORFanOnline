@extends('layouts.master')

@section('title')
	ORFanID - Clamp
@endsection

@section('body')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
<link type="text/css" rel="stylesheet" href="css/orfanid-input.css">
<script type="text/javascript" src="{{ url('js/jquery-1.12.4.js') }}"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
  

<script type="text/javascript" src="js/orfanid-clamp.js"></script>
<main>
{!! Form::open(['route' => 'clamp.store','method' => 'POST']) !!}

<div class="row">
  <h4>Comparative Genomic Studies of ORFan genes in Mammalian Genomes
</h4>
</div>

<div class="row">
  <div class="col s6">
    <h5>Purpose</h5>
    <p>Investigate the function/s, if any, of the ORFan gene sequences identified in previous studies from the literature, with a special focus of Clamp et al. study. However, the initial goal is to find out all the orphan genes of the human genome.</p>

    <h5>Methods</h5>
    <p>Human Genome GRCh38.p7 was used for the analysis. </p>

    <p>A bioinformatics pipeline was developed using R language with BiomaRt package to filter genes. BiomaRt is the official filtering tool to query ensemble databases. There were six main steps in the filtering process:
      <ol>
      <li>Removal of Retrotransposons/Pseudogenes</li>
      <li>Removal of orthologous genes with Dog</li>
      <li>Removal of orthologous genes with Mouse</li>
      <li>Removal of paralogous genes within Human</li>
      <li>Removal of known pfam genes</li>
      <li>Removal of genes that absent the protein sequence (i.e. non-coding genes)</li>
    </ol>
    </p>
  </div>
  <div class="col s6">
    <img src="images/Clamp.png" alt="Clamp method" height="50%" width="50%">
  </div>
</div>
 <h5> Reference</h5>
    <p> Clamp, M., Fry, B., Kamal, M., Xie, X., Cuff, J., Lin, M.F., Kellis, K., Lindblad-Toh, K., and Lander, E. S. (2007) “Distinguishing protein-coding and noncoding genes in the human genome”. PNAS 2007 December, 104 (49) 19428-19433</p>

<hr>
<div class="row">
  <div class="col offset-s2 input-field col s5">
      <select id="chromosome" name="chromosome">
        <option value="" disabled selected>Choose your option</option>
        <option value="All">All</option>
        <option value="1">Chromosome 1</option>
        <option value="2">Chromosome 2</option>
        <option value="3">Chromosome 3</option>
        <option value="4">Chromosome 4</option>
        <option value="5">Chromosome 5</option>
        <option value="6">Chromosome 6</option>
        <option value="7">Chromosome 7</option>
        <option value="8">Chromosome 8</option>
        <option value="9">Chromosome 9</option>
        <option value="10">Chromosome 10</option>
        <option value="11">Chromosome 11</option>
        <option value="12">Chromosome 12</option>
        <option value="13">Chromosome 13</option>
        <option value="14">Chromosome 14</option>
        <option value="15">Chromosome 15</option>
        <option value="16">Chromosome 16</option>
        <option value="17">Chromosome 17</option>
        <option value="18">Chromosome 18</option>
        <option value="19">Chromosome 19</option>
        <option value="20">Chromosome 20</option>
        <option value="21">Chromosome 21</option>
        <option value="22">Chromosome 22</option>
        <option value="X">Chromosome X</option>
      </select>
      <label>Select Chromosome: </label>
    </div>
    <div class="col offset-s1 col s2">
      <button class="btn waves-effect waves-light"  type="submit" name="action"  id="submit">Submit
        <i class="material-icons right">send</i>
      </button>
    </div>
<!--<a class="waves-effect waves-light btn" href="#modal1">Modal</a>-->
<div id="modal1" class="modal" >
    <div class="modal-content">
      <h6>  ORFanID Clamp In Progress.... </h6>
	   	<div class="progress">
      		<div class="indeterminate"></div>
		</div>
		<div class="row">
		<div class="col s12">
			<div class="col offset-s2 s1">
					
				</div>
	       <div  class="col s4"> 
		  <img src="images/loading4.gif" alt="Loading">
		   </div > 
		   </div>
		   </div>
      <!--<p>A bunch of text</p>-->
    </div>
    <div class="modal-footer">
     <!-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Possible Close Implematioation</a> -->
    </div>
    </div>
  </div>


{!! Form::close() !!}
</main>
@endsection
