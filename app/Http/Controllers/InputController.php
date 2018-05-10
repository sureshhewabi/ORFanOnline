<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Cookie;
use App\Metadata;
use Log;

class InputController extends Controller
{

    public function index()
    {
        return view('input');
    }

    public function store(Request $request)
    {
        $metadata = new Metadata();

        // assign a unique random ID per analysis
        $uniqueID = uniqid();
        session(['userid' => $uniqueID]);

        Log::info('New User ID : ' . $request->session()->get('userid'));

        // retreive data from the input form
        $organism = $request->input('organismName');
        $taxonomyLevels = $request->input('taxonomyLevels');

        // eg: Escherichia coli str. K-12 substr. MG1655 (511145)
        // split string by open bracket and remove the last characher
        // which is the closing bracket to extract Taxonomy ID
        $organismTaxId = substr(explode("(", $organism)[1], 0, -1);


        //take the organisal name eg :Escherichia coli str. K-12 substr. MG1655  from  Escherichia coli str. K-12 substr. MG1655 (511145)
        $organismName = explode("(", $organism)[0];
        Log::debug('organism : ' . $organismName . "Taxid:" . $organismTaxId . " Taxlevel " . $taxonomyLevels);


        // FASTA gene sequence
        $content = $request->input('genesequence');

        // if user already logged in
        if ($request->session()->has('userid')) {
            // path where results files get saved temporary for each analysis
            $userdir = public_path() . "/users/" . session('userid') . "/";
            $inputfile = $userdir . "inputfile.fasta";

            //  create the nested folder structure if it does not exists
            // and grant full permission
            if (!file_exists(dirname($inputfile)))
                mkdir(dirname($inputfile), 0777, true);

            // ============== Preprocessing  ==============

            // 1. save input sequence to a file in FASTA format.
            file_put_contents($inputfile, $content);

            // 2. create ID File
            $IDoutputFile = $userdir . "IDFile.id";
            $extractIdsFromFasta = config('orfanid.extractIdsFromFasta');

            // initialise to null
            $out = NULL;
            $out = shell_exec($extractIdsFromFasta . ' ' . $inputfile . ' ' . $IDoutputFile);
            if (is_null($out)) {
                Log::debug('extractIdsFromFasta location : ' . $extractIdsFromFasta . ' ' . $inputfile . ' ' . $IDoutputFile);
            }


            # ============== Run BLASTP programme ==============

            # full file path where blastp programme installed in local computer
            $blastscript = config('orfanid.blastprogram');
            # filename of the output file
            $blastoutputFile = $userdir . "blastResults.bl";
            # output format - tab delimmeterd file(6)
            $outfmt = "6";
            # maximum expected value/evalue
            $default_maxevalue = config('orfanid.default_maxevalue');
            # maximum number of target sequence results(number of hits)
            $default_maxtargetseq = $request->input('maxtargets'); #config('orfanid.default_maxtargetseq');
            # number of threads should use, if blastp programme run in local computer
            $defalt_threads = "4";
            # use online blast or blast in local computer
            $defalt_blastmethod = "online";
            # blast command

            //if ($organismName=="All"){
            //$blastcommand = $blastscript." -query ".$inputfile." -db nr -outfmt 6 -max_target_seqs ".$default_maxtargetseq." -evalue ".$default_maxevalue." -out ".$blastoutputFile." -remote ";
            //}else{
            $blastcommand = $blastscript . " -query " . $inputfile . " -db nr -outfmt 6 -max_target_seqs " . $default_maxtargetseq . " -evalue " . $default_maxevalue . " -out " . $blastoutputFile . " -remote -entrez_query \"" . $taxonomyLevels . "[Organism]\"";
            //}
            Log::debug('BLASTP command : ' . $blastcommand);

            // initialise to null
            $out = NULL;
            Log::debug('Blasting Started');
            $out = shell_exec($blastcommand);
            Log::debug('Blasting Ended');
            Log::debug('Blast command returned : ' . $out);
                if (file_exists($blastoutputFile)) {

                    // copy blast data to metadata
                    $metadata->blast_evalue = $default_maxevalue;
                    $metadata->blast_db = "nr";
                    $metadata->blast_max_hits = $default_maxtargetseq;
                    $metadata->taxonomyLevels = $taxonomyLevels;
                    $metadata->organism = $organism;


                    # ============== Run ORFanFinder ==============

                    # full file path where ORFanFinder programme installed in local computer
                    $ORFanFinder = config('orfanid.ORFanFinder');
                    #node File
                    $nodefile = config('orfanid.nodefile');
                    #name File
                    $namefile = config('orfanid.namefile');
                    # database
                    $database = config('orfanid.database');
                    # orfanFinder output File
                    $ORFanFinderOutputfile = $userdir . "orfanResults.csv";

                    #ORFanFinder command
                    $ORFanCommand = $ORFanFinder . " -query " . $blastoutputFile . " -id " . $IDoutputFile . " -nodes " . $nodefile . " -names " . $namefile . " -db " . $database . " -tax " . $organismTaxId . " -threads 4" . " -out " . $ORFanFinderOutputfile;
                    Log::debug('ORFanCommand : ' . $ORFanCommand);

                    // initialise to null
                    $out = NULL;
                    # Run ORFanFinder command
                    $out = shell_exec($ORFanCommand);
                    Log::debug('ORFanCommand Executed!');
//                    if (is_null($out)) {
//                        Log::warning('No output produced by ORFanCommand!');
//                        Log::debug('ORFanCommand : ' . $ORFanCommand);
//                    }
                } else {
                    Log::warning('No output produced by BLASTP');
                    $this->alert("Error: Failed to produce BLAST output! Please check your input file format and adjust blast advance parameters and retry!");
                    Log::debug('blastoutputFile does not exist:' . $blastoutputFile);
                    $this->redirectToMain();
                }


                // ============== Report Results ==============

                $ORFanGenesSummary = array();
                $ORFanGenesSummaryList = array();
                $orfanGenesList = array();
                $blastrecordsfullList = array();

                if (file_exists($ORFanFinderOutputfile)) {
                    // open file in read only mode
                    $handle = fopen($ORFanFinderOutputfile, "r");
                    // if successfully opened the file
                    if ($handle) {
                        // read file line by line
                        while (($line = fgets($handle)) !== false) {
                            // split line by tab delimmeter
                            $columns = explode("\t", $line);

                            // first column contains the Gene ID (column[0])
                            // remove the last pipe charactor (|) which is not required for gene ID
                            $geneID = substr($columns[0], 0, -1);

                            // check if string has '-' charactor
                            // eg: class ORFan - Gammaproteobacteria
                            if (strpos($columns[1], '-') !== false) {
                                // split second column to get ORF Gene Level and the Taxonomy Level
                                list($orfanLevel, $taxonomyLevel) = explode(" - ", $columns[1]);
                                $blastrecordsfullList = array_merge($blastrecordsfullList, $this->extractBlastHits($geneID, $columns));
                            } elseif (strpos($columns[1], 'native gene') !== false) {
                                // split second column to get ORF Gene Level and the Taxonomy Level
                                list($orfanLevel, $taxonomyLevel) = array($columns[1], '');
                                $blastrecordsfullList = array_merge($blastrecordsfullList, $this->extractBlastHits($geneID, $columns));
                            } else { // strict ORFan does not have taxonomy or any other details
                                list($orfanLevel, $taxonomyLevel) = array($columns[1], '');
                            }

                            $orfanGenes = array($geneID, 'Not Available', $orfanLevel, $taxonomyLevel);
                            array_push($orfanGenesList, $orfanGenes);

                            // create orfan gene summary Array
                            if (array_key_exists($orfanLevel, $ORFanGenesSummary)) {
                                $ORFanGenesSummary[$orfanLevel] = $ORFanGenesSummary[$orfanLevel] + 1;
                            } else {
                                $ORFanGenesSummary[$orfanLevel] = 1;
                            }
                        }
                        fclose($handle);

                        // copy $ORFanGenesSummary to $ORFanGenesSummaryList array in array format
                        // eg: ["class ORFan", 1]
                        foreach ($ORFanGenesSummary as $key => $value) {
                            array_push($ORFanGenesSummaryList, array($key, $value));
                        }

                        // create a JSON file for summary chart
                        $ORFanGenesSummaryChartFile = $userdir . "ORFanGenesSummaryChart.json";
                        $ORFanGenesSummaryChartContent = '{"x":' . json_encode(array_keys($ORFanGenesSummary)) . ',' .
                            '"y":' . json_encode(array_values($ORFanGenesSummary)) .
                            '}';
                        // save orphan genes in JSON format.
                        file_put_contents($ORFanGenesSummaryChartFile, $ORFanGenesSummaryChartContent);

                        $ORFanGenesSummaryFile = $userdir . "ORFanGenesSummary.json";
                        $ORFanGenesSummaryContent = '{"data":' . json_encode($ORFanGenesSummaryList) . '}';
                        // save orphan genes in JSON format.
                        file_put_contents($ORFanGenesSummaryFile, $ORFanGenesSummaryContent);

                        $ORFanGenesFile = $userdir . "ORFanGenes.json";
                        $content = '{"data":' . json_encode($orfanGenesList) . '}';
                        // // save orphan genes in JSON format.
                        file_put_contents($ORFanGenesFile, $content);

                        // Save blast results
                        $blastresultsFile = $userdir . "blastresults.json";
                        $blastcontent = '{"data":' . json_encode($blastrecordsfullList) . '}';
                        // // save orphan genes in JSON format.
                        file_put_contents($blastresultsFile, $blastcontent);
                        Log::debug('Process Successfully Completed!');
                        Log::debug('===============================');
                    } else {
                        // error opening the file.
                    }
                } else {
                    print "ORFanFinderOutputfile not available<br>" . $ORFanFinderOutputfile . "<br>";
                    $this->alert("Failed to produce ORFanFinder output file! Please check your input file format and adjust blast advance parameters and retry!");
                    $this->redirectToMain();
                }
//            }
        } else {
            return redirect('input');
        }
        return view('results')->with('metadata', $metadata);
    }

    private function extractBlastHits($geneID, $columns)
    {

        $index = 1;

        $blastrecordsList = array();

        $columnlength = count($columns);

        if ($columnlength > 2) {
            for ($i = 2; $i < $columnlength - 1; $i++) {
                $column = $columns[$i];
                preg_match_all("/\[[^\]]*\]/", $column, $rankCountRecord);

                $bracktedString = $rankCountRecord[0][0];

                if (count(explode(",", $bracktedString)) >= 2) {
                    $rankName = substr(explode(",", $bracktedString)[0], 1);
                    $rankCount = substr(explode(",", $bracktedString)[1], 0, -1);
                    //  echo '<br>'."rank and count:".$rankName."->".$rankCount.'<br>';
                }

                $arr = preg_split('/\[.*?\]\s*/', $column);
                $blasthits = explode(",", $arr[1]);
                $numberofhits = count($blasthits);
                for ($j = 0; $j < $numberofhits; $j++) {

                    // make sure each hit has taxonomy name and its parent taxonomy
                    // Eg:  Include - Salmonella(Enterobacteriaceae)
                    //      Exlude - (Vibrio)
                    $taxpair = preg_split('/[a-zA-Z]\(.*?\)\s*/', $blasthits[$j]);
                    $taxpaircount = count($taxpair);
                    // if there are two pairs
                    if ($taxpaircount > 1) {
                        $blastrecords = array($index++,
                            $geneID,
                            $rankName,
                            explode("(", $blasthits[$j])[0],
                            substr(explode("(", $blasthits[$j])[1], 0, -1));
                        array_push($blastrecordsList, $blastrecords);
                    }
                }
            }
        }
        return $blastrecordsList;
    }

    function alert($msg)
    {
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }

    function redirectToMain()
    {
        echo "<script type='text/javascript'>window.location.href='input';</script>";
    }
}
