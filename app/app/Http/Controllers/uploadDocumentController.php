<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
// storage/file facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Image;
use DB;

class uploadDocumentController extends Controller
{
    //
    public function index(){
         return view('pages/user_upload_documents');
    }

    public function fileUpload(Request $req){

          $documentDatas = [];

          if($req['file']!=NULL){

              // set user time zone.
              date_default_timezone_set(Auth::user()->user_timezone);

              //post files name file0, file1, file2. to get files add integer.
              $doc =  $req['file'];
              //get original file name / use basename function to get name without file extension.
              $org_filename = basename($doc->getClientOriginalName(), '.'.$doc->getClientOriginalExtension());
              //using timestamps and random string for naming.
              $base_n = $org_filename . '-' . str_random(5);
              //get file extension
              $ext = $doc->getClientOriginalExtension();
              //----------------------------------------------
              //set origin name
              $doc_org =  $base_n . "-" . "org" . "." . $ext;
              //set processed name
              $doc_prc =  $base_n . "-" . "prc" . "." . "pdf";
              //set ocr name
              $doc_ocr =  $base_n . "-" . "ocr" . "." . "pdf";
              //store names to array of arrays for single insert.
              $doc_dataz = array(
                  "doc_user_id" => Auth::user()->id,
                  "doc_org" => $doc_org,
                  "doc_prc" => $doc_prc,
                  "doc_ocr" => $doc_ocr,
                  "t_process" => time(),
                  "process_status" => 'new',
                  "created_at" =>  \Carbon\Carbon::now(),
                  "updated_at" =>  \Carbon\Carbon::now(),
              );
              // storage
              array_push($documentDatas, $doc_dataz);
              Storage::disk('documents')->putFileAs('documents_new', $doc, $doc_org);
        }

        //save to database
        try {
            DB::table('documents')->insert($documentDatas);
            return "success";
        } catch(\Illuminate\Database\QueryException $err){
            return "error";
         // Note any method of class PDOException can be called on $err.
        }


    }
}
