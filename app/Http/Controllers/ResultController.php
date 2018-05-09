<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Cookie;
use App\Metadata;
use Log;

class ResultController extends Controller
{

    public function index()
    {
        return view('input');
    }

	 public function previewResult($result_id)
    {
           //var_dump($result_id);
        return view('savedresult',['result_id' => $result_id]);
    }

	 public function saveResult(Request $request)
    {
       $post = $request->all();
       $metaResult = $post['metaResult'];
       $saveResult=",".json_encode($metaResult);
      
       Log::info('saveResult Starting'.$saveResult);

         $userdir = public_path()."/data/";
        $inputfile = $userdir."results.json";
        file_put_contents($inputfile,$saveResult,FILE_APPEND);

        // other method to save data
        //file_put_contents($inputfile,  $metaResult);
        //$fh = fopen($inputfile, 'w') or die("can't open file");
        //fseek($fh, -3, SEEK_END);
        //fwrite($fh,json_encode($metaResult));
       // fclose($inputfile);

       $msg = "Saved Results Sucesfully!";
      return response()->json(array('msg'=> $msg), 200);
    }

       public function readResultFile(Request $request)
    {

      
         $userdir = public_path()."/data/";
        $inputfile = $userdir."results.json";
        $string = file_get_contents( $inputfile);
         Log::info('Reading Results');

        // other method to save data
        //file_put_contents($inputfile,  $metaResult);
        //$fh = fopen($inputfile, 'w') or die("can't open file");
        //fseek($fh, -3, SEEK_END);
        //fwrite($fh,json_encode($metaResult));
       // fclose($inputfile);

       $msg = "This is a simple message.";
      //return response()->json('{"data":['.$string.']}', 200);
       return response('{"data":['.$string.']}', 200);
    }


   
}
