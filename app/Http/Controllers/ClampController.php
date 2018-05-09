<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
class ClampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clamp');
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
      // if ($request->session()->has('userid')) {
      //   // path where results files get saved for each analysis
      //   $userdir = public_path()."/users/".session('userid')."/";
      // }else{
      //   // assign a unique random ID per analysis
      //   $uniqueID = uniqid();
      //   session(['userid' => $uniqueID]);
      //
      //   //  create the nested folder structure if it does not exists
      //   // and grant full permission
      //
      //     if (!mkdir($userdir, 0777, true)){
      //       die('Failed to create folder for users analysis');
      //     }
      //
      //
      //   $userdir = public_path()."/users/".session('userid')."/";
      // }
          $chromosome = $request->input('chromosome');
          // if user is already logged in
          $outputFile = '/var/www/html/ORFanOnline/public/data/summary.json';
          //$outputFile = $userdir.'summary.json';
          $command = "Rscript ".config('orfanid.clamp_rscript'). " --chromosome ".$chromosome." --output ".$outputFile;
          $out = shell_exec($command);
          if( is_null($out) ){
             Log::warning('No output produced by Clamp');
             Log::debug('Clamp command : '.$command);
          }
          return view('clampresults');
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
}
