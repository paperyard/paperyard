<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use DB;

class shareDocumentController extends Controller
{
    // RETURN SHARE DOCUMENT PAGE
    public function index(){

    	$check = DB::table('shared_documents')->where('share_user_id', Auth::user()->id)->get();
    	return view('pages/share')->with(compact('check'));
    }

    // get shared documents
    public function returnSharedDocs(){

	    try {
			$docs = DB::table('shared_documents')->where('share_user_id',Auth::user()->id)
            ->join('documents', 'shared_documents.share_doc_ids', '=', 'documents.doc_id')
            ->select('shared_documents.*','documents.doc_id','documents.doc_ocr')
			->get();
			# JSON-encode the response
			$json_response = json_encode($docs);
			// # Return the response
			return $json_response;
	    } catch(\Illuminate\Database\QueryException $err){
	       return "error";
	     // Note any method of class PDOException can be called on $err.
	    }
    }

    // REMOVE SHARED DOCUMENT
    public function removeShared(Request $req){

        //remove from shared table
    	  DB::table('shared_documents')->where([
              ['share_user_id', '=', Auth::user()->id ],
              ['share_id', '=', $req->shared_id ]
    	  ])->delete();
    	  //update document shared status
    	  DB::table('documents')->where([
              ['doc_user_id', '=', Auth::user()->id ],
              ['doc_id', '=', $req->doc_id ]
        ])->update(['shared' => 0 ]);
    }

    // ADD PASSWORD TO SHARED DOCUMENT
    public function generatePassword(Request $req){

            $pass = str_random(5);
            DB::table('shared_documents')->where([
               ['share_user_id', '=', Auth::user()->id ],
               ['share_id', '=', $req->shared_id ]
            ])->update(['share_password' => $pass ]);
            return "success";
    }

    // SHARE DOCUMENT
    public function shareDocument($username,$hash){

           $document = DB::table('shared_documents')
           ->where([
              ['share_hash', '=', $hash]
           ])
           ->join('documents', 'shared_documents.share_doc_ids', '=', 'documents.doc_id')
           ->join('document_pages', 'documents.doc_id', '=', 'document_pages.doc_id')
           ->groupBy('document_pages.doc_id')
           ->select('shared_documents.*','documents.doc_ocr','document_pages.doc_page_image_preview')
           ->first();
           return view('pages/share_public')->with(compact('document'));
    }

    // VERIFY PASSWORD ON SHARED DOCUMENT
    public function verifyShared(Request $req){

         $check = DB::table('shared_documents')->
         where('share_password', $req->share_password)->get();
         if(count($check)>=1){
           return "success";
         }else{
           session()->flash('share_pass_not_match', 'Invalid password.');
           return "failed";
         }

    }

    public function shareDocumentWithPass($username,$hash,$password){

         $check = DB::table('shared_documents')->
         where('share_password', $password)->get();

         if(count($check)>=1){

           $document = DB::table('shared_documents')
           ->where([
              ['share_hash', '=', $hash]
           ])
           ->join('documents', 'shared_documents.share_doc_ids', '=', 'documents.doc_id')
           ->join('document_pages', 'documents.doc_id', '=', 'document_pages.doc_id')
           ->groupBy('document_pages.doc_id')
           ->select('shared_documents.*','documents.doc_ocr','document_pages.doc_page_image_preview')
           ->first();

           return view('pages/share_public_w_password')->with(compact('document'));
         }else{
            return redirect('error_404');
         }

    }
}
