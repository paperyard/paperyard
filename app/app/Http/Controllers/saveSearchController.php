<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;

class saveSearchController extends Controller
{
    //

    //get user saved searches
    public function userSavedSearch(){
        $search_searches = DB::table('saved_searched')
        ->where('ss_user_id', Auth::user()->id)
        ->orderBy('ss_id', 'desc')
        ->get();
		$json_response = json_encode($search_searches);
		return $json_response;
    }

    //save saved search
    public function saveSearch(Request $req){

    	$save = DB::table('saved_searched')
    	->where('ss_user_id', Auth::user()->id)
    	->insert(
    		[
	    		'ss_keyword' =>   $req->ss_datas[0],
	    		'ss_filter'  =>   $req->ss_datas[1],
	    		'ss_name'    =>   $req->ss_datas[2],
	    		'ss_user_id'=>Auth::user()->id
    	    ]
    	);

    	if(count($save)>0){
    	  return "success";
    	}
    	else{
    		return "fail";
    	}
    }

    //rename/update save search
    public function renameSaveSearch(Request $req){
          $upd_ss_name = DB::table('saved_searched')
          ->where([
          	 ['ss_user_id','=',Auth::user()->id],
          	 ['ss_id','=',$req->ss_data[0]]
          ])	 
          ->update(['ss_name'=>$req->ss_data[1]]);

          if(count($upd_ss_name)>0){
          	 return "success_renamed";
          }
    }      

    //delete save search
    public function deleteSaveSearch(Request $req){
         $delete = DB::table('saved_searched')
         ->where([
          	 ['ss_user_id','=',Auth::user()->id],
          	 ['ss_id','=',$req->ss_id]
         ])->delete();

         if(count($delete)>0){
         	   return "success_deleted";
         }
    }


}
