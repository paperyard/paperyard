<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;

class settingsController extends Controller
{
    // RETURN SETTINGS PAGE
    public function index(){

    	 $timezone = DB::table('users')->where('id', Auth::user()->id)->select('user_timezone')->first();
       return view('pages/settings')->with(compact('timezone'));
    }
    // UPDATE TIMEZONE
    public function changeTimeZone(Request $req){

        try {
          // set user time zone.
          date_default_timezone_set(Auth::user()->user_timezone);

          DB::table('users')->where('id', Auth::user()->id)->update(['user_timezone'=>$req->timezone]);
          return "success";

        } catch(\Illuminate\Database\QueryException $err){
           return "error";
         // Note any method of class PDOException can be called on $err.
        }
    }

    public function updateDownloadFilenameFormat(Request $req){
        $arrFormat = [];
        foreach($req->dataFormat as $key=>$f){
            array_push($arrFormat,$f['value']);
        }
        $format = implode(',', $arrFormat);
        $saveFormat = DB::table('users')->where('id', Auth::user()->id)->update(['download_filename_format'=>$format]);
        if(count($saveFormat)>0){
           return "success";
        }

    }

    public function returnDownloadFilenameFormat(){
        $user_dff = DB::table('users')->where('id', Auth::user()->id)->first();

        $labelDatas = [];
        $arrFormat = explode(',', $user_dff->download_filename_format); 
        
        foreach($arrFormat as $key=>$f){
              
              if($f=="doc_ocr"){
                  $label = "Old Filename";
              }else{
                  $label = $f;
              }
              $data = array(
                 "label" => ucwords($label),
                 "value" => $f
              );  
              array_push($labelDatas,$data);                            
        }

        $json_response = json_encode($labelDatas);
        return $json_response;
    }
}
