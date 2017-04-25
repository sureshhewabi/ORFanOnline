<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;

class InputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session(['username' => 'sureshhewabi@gmail.com']);
        return view('input');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // retreive data from the input form
      $organism = $request->input('organismName');
      $content = $request->input('genesequence');

      // if user already logged in
      if ($request->session()->has('username')) {

        // save input gene sequence
        //$date = new DateTime();
        //$sessionid = $date->format('U');
        $sessionid = "1492507706";

        $userdir = public_path()."/users/".session('username')."/".$sessionid."/";
        $inputfile = $userdir."inputfile.fasta";

        //  create the nested folder structure if it does not enchant_broker_dict_exists
        // and grant full permission
        // if(!file_exists(dirname($inputfile)))
        //   mkdir(dirname($inputfile), 0777, true);
        //
        // // save input sequence to a file in FASTA format.
        // file_put_contents($inputfile, $content);

        // create ID File
        $IDoutputFile = $userdir."IDFile.id";
        $extractIdsFromFasta = "/Applications/XAMPP/xamppfiles/htdocs/ORFanOnline/public/scripts/extractIdsFromFasta";
        //shell_exec($extractIdsFromFasta.' '.$inputfile.' '.$IDoutputFile);

        // Run BLAST script

        # ============== BLASTP Settings ==============

        # full file path where blastp programme installed in local computer
        $blastscript = "/usr/local/ncbi/blast/bin/blastp";
        # filename of the output file
        $blastoutputFile = $userdir."blastResults.bl";
        # output format - tab delimmeterd file(6)
        $outfmt = "6";
        # maximum expected value/evalue
        $defalt_maxevalue = "1e-3";
        # maximum number of target sequence results(number of hits)
        $defalt_maxtargetseq = "1000";
        # number of threads should use, if blastp programme run in local computer
        $defalt_threads = "4";
        # use online blast or blast in local computer
        $defalt_blastmethod = "online";

        // $blastcommand = $blastscript." -query ".$inputfile." -db nr -outfmt 6 -max_target_seqs ".$defalt_maxtargetseq." -evalue ".$defalt_maxevalue." -out ".$blastoutputFile.
        //            " -remote";
        $blastcommand = $blastscript." -query ".$inputfile." -db nr -outfmt 6 -max_target_seqs 100 -evalue 1e-3 -out ".$blastoutputFile." -remote";

        //shell_exec($blastcommand);
//                "-entrez_query",
//                organism+"[Organism]"

        // run ORFanFinder Tool

        # ============== ORFanFinder Settings ==============

        # full file path where ORFanFinder programme installed in local computer
        $ORFanFinder = "/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/src/ORFanFinder/ORFanFinder";
        # output file generated from the ORFanFinder programme
        $ORFan_outputfile = $userdir."orfanResults.csv";
        #node File
        $nodefile = public_path()."/data/nodes.txt";
        #name File
        $namefile = public_path()."/data/names.txt";
        # database
        $database = "/Users/suresh/Documents/Tools/ORFanFinder/ORFanFinder-1.1.2/databases/uniBacteria.hdb";
        # orfanFinder output File
        $ORFanFinderOutputfile = $userdir."orfanResults.csv";

        print $userdir."<br>";

        #ORFanFinder command
        $ORFanCommand = $ORFanFinder." -query ".$blastoutputFile." -id ".$IDoutputFile." -nodes ".$nodefile." -names ".$namefile." -db ".$database." -tax "."511145"." -threads 4"." -out ".$ORFanFinderOutputfile;

        # Run ORFanFinder command
        //shell_exec($ORFanCommand);

        $orfanGenesList = array();

        if(file_exists($ORFanFinderOutputfile)){
          $handle = fopen($ORFanFinderOutputfile, "r");
          if ($handle) {
            // read file line by line
            while (($line = fgets($handle)) !== false) {
                // process the line read.
                $columns = explode( "\t", $line );

                // first column contains the Gene ID
                $geneID = $columns[0];
                print "<hr>";
                print "Gene ID : ".$geneID;
                print "<br>";
                if ( strpos($columns[1], '-') !== false ) {
                    // split second column to get ORF Gene Level and the Taxonomy Level
                    list($geneLevel,$taxonomyLevel) = explode( " - ", $columns[1]);
                    $this->extractBlastHits($columns);
                } elseif(strpos( $columns[1], 'native gene') !== false ){
                    // split second column to get ORF Gene Level and the Taxonomy Level
                    list($geneLevel,$taxonomyLevel) =  array($columns[1], '');
                    $this->extractBlastHits($columns);
                }else { // strict ORFan does not have taxonomy or any other details
                    list($geneLevel,$taxonomyLevel) =  array($columns[1], '');
                }
                print "Gene Level : ".$geneLevel;
                print "<br>";
                print "Taxonomy Level : ".$taxonomyLevel;
                print "<br>";

                $orfanGenes = array($geneID, 'Not Available',$geneLevel,$taxonomyLevel);
                array_push($orfanGenesList, $orfanGenes);
            }
            fclose($handle);

            print "<br>";
            print json_encode($orfanGenesList);

            $ORFanGenesFile = $userdir."ORFanGenes.json";
            $content = '{"data":'.json_encode($orfanGenesList).'}';
            // // save orgon genes in JSON format.
             file_put_contents($ORFanGenesFile, $content);

          } else {
            // error opening the file.
          }
        }else{
          print "ORFanFinderOutputfile not available<br>".$ORFanFinderOutputfile."<br>";
        }


    }else{
      return redirect('input');
    }
        print "<br>";
        print "<br>";
        return $request->session()->get('username');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function extractBlastHits($columns){
        //print "Need to extract blast results".implode("<br><br>", $columns).'<br>';
        $columnlength = count($columns);
        // for ($i = 2; $i < $columnlength; $i++) {
        //       $column = $columns[$i];
        //       //echo $column.'<br><br>';
        //       preg_match_all("/\[[^\]]*\]/", $column, $rankCountRecord);
        //       echo "<hr>";
        //       $bracktedString =  $rankCountRecord[0][0];
        //
        //       if (count(explode( ",", $bracktedString)) >= 2) {
        //            $rankName = explode( ",", $bracktedString)[0];
        //            $rankCount = explode( ",", $bracktedString )[1];
        //            echo "rank and count:".$rankName."->".$rankCount;
        //
        //        }
        // }
    }
}
