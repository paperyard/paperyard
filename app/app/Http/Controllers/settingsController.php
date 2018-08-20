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
}
