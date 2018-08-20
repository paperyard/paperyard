<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;


class dashboardController extends Controller
{
    //
    public function index(){

         $last_opened = DB::table('documents_viewed')->where('view_user_id', Auth::user()->id)
         ->join('document_pages', 'documents_viewed.view_doc_id','=','document_pages.doc_id')
         ->groupBy('document_pages.doc_id')
         ->select('documents_viewed.view_id','document_pages.doc_page_thumbnail_preview as thumbnail')
         ->orderBy('documents_viewed.view_id', 'desc')
         ->limit(6)->get();

         $latest_opened = DB::table('documents_viewed')->where('view_user_id', Auth::user()->id)
         ->join('document_pages', 'documents_viewed.view_doc_id','=','document_pages.doc_id')
         ->groupBy('document_pages.doc_id')
         ->select('documents_viewed.view_id','document_pages.doc_page_thumbnail_preview as thumbnail')
         ->orderBy('documents_viewed.view_id', 'desc')
         ->first();

         return view('pages/dashboard')->with(compact('last_opened','latest_opened'));
    }

    public function toEditDocs(){

         //return no. of documents needed to edit.
         $num_pending_docs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['process_status','=','ocred_final'],
             ['is_archive','=',0]
         ])->count();

         //return no of archived docs
         $num_archive_docs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['process_status','=','ocred_final'],
             ['is_archive','=',1]
         ])->count();

        $json_response = json_encode(array('num_to_edit' => $num_pending_docs,'num_archived'=>$num_archive_docs));
        // # Return the response
        return $json_response;

    }

    public function searchAutoComplete(Request $req){
        $docs = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('doc_ocr', 'LIKE', '%' . $req->doc_name . '%')
        ->select('documents.doc_id','documents.doc_ocr')
        ->get();

        $json_response = json_encode(array('doc_names' => $docs));
        // # Return the response
        return $json_response;
    }

    public function searchSpecificDocuments(Request $req){
        $docs = DB::table('documents')
        ->where([
            ['doc_user_id','=',Auth::user()->id],
            ['doc_id','=',$req->doc_id]
        ])
        ->select('documents.doc_id','documents.doc_ocr','documents.doc_org','documents.approved','documents.process_status')
        ->get();

        if(count($docs)>0){
            $json_response = json_encode($docs);
            // # Return the response
            return $json_response;
        }else{
            return 0;
        }
    }

    public function searchDocuments(Request $req){

         $docs = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('doc_ocr', 'LIKE', '%' . $req->doc_name . '%')
        ->select('documents.doc_id','documents.doc_ocr','documents.doc_org','documents.approved','documents.process_status')
        ->get();

        if(count($docs)>0){
            $json_response = json_encode($docs);
            // # Return the response
            return $json_response;
        }else{
            return 0;
        }
    }
}
