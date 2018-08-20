<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// symfony process for running sub-process.
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpProcess;
// storage/file facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Image;
use DB;
use Mail;
use Spatie\PdfToImage\Pdf;

class mergeDocumentController extends Controller
{
    //
    public function index(){
    	return view('pages/merge_pdf');
    }

    public function mdAutocomplete(Request $request){

      $docs = DB::table('documents')
    	->where([
           ['doc_user_id','=',Auth::user()->id],
           ['process_status','=','ocred_final']
    	])
      ->where('doc_ocr', 'LIKE', '%' . $request->doc_keyword . '%')
    	->select('documents.doc_ocr','documents.doc_id')->get();

    	$json_response = json_encode($docs);
        // # Return the response
        return $json_response;
    }

    public function selectDoc(Request $request){

       $doc = DB::table('documents')
       ->where('doc_user_id', Auth::user()->id)
       ->where('documents.doc_id', $request->doc_id)
       ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
       ->groupBy('document_pages.doc_id')
       ->select('documents.doc_id','documents.doc_ocr','document_pages.doc_page_image_preview')
       ->get();

       $json_response = json_encode($doc);
       // Return the response
       return $json_response;
    }

    public function mergeDocuments(Request $request){

        // documents location
        $doc1 = 'static/documents_ocred/' . $request->doc1_name;
        $doc2 = 'static/documents_ocred/' . $request->doc2_name;
        // output file location/name
        $output_doc = 'static/documents_ocred/' . str_random(10) . ".pdf";

        $doc1_pages = DB::table('document_pages')->where('doc_id',$request->doc1_id)->get();
        $doc2_pages = DB::table('document_pages')->where('doc_id',$request->doc2_id)->get();

        $doc1_total_pages = count($doc1_pages);
        $doc2_total_pages = count($doc2_pages);

        //append document ===========================================================
        if($request->merge_rule=="append"){

            //================= UPDATE DOCUMENT PAGES ===========================================
            //set current document 1 page.
            $dp1Current = 1;

            //loop update document 1 pages
            foreach($doc1_pages as $dp1){
                 DB::table('document_pages')
                 ->where('doc_id', $dp1->doc_id)
                 ->where('doc_page_image_preview', $dp1->doc_page_image_preview)
                 ->update(['doc_page_num'=>$dp1Current]);
                 $dp1Current+=1;
            }

            // set doc 2 current page. document 2 start at last page of document 1.
            $dp2Current = $doc1_total_pages;

            foreach($doc2_pages as $dp2){
                 $dp2Current+=1;
                 DB::table('document_pages')
                 ->where('doc_id', $dp2->doc_id)
                 ->where('doc_page_image_preview', $dp2->doc_page_image_preview)
                 ->update(['doc_id'=>$request->doc1_id,'doc_page_num'=>$dp2Current]);
            }

            //================= APPEND DOCUMENT 2 to DOCUMENT 1 ==================================
            $params = "pdftk $doc1 $doc2 cat output $output_doc && mv $output_doc $doc1";
            $process = new Process($params);
            $process->disableOutput();
            $process->start();
            $process->wait();

            //delete document 2 ======================
            File::delete((string)$doc2);

            //delete document 2 datas =========================
            DB::table('documents')->where('doc_id', $request->doc2_id)->delete();

            //session flash
            session()->flash('merge_success', 'Documents successfully merged.');
            return "success";

        }

        //interleave document ==============================================================
        if($request->merge_rule=="interleave"){
        //interleave document

            //document 1 pages becomes odd 1,3,5
            $oddMax  = count($doc1_pages)*2-1;
            //document 1 pages becomes odd 2,4,6
            $evenMax = count($doc2_pages)*2;

            $oddCurrent = 1;
            foreach($doc1_pages as $dp1){

                 DB::table('document_pages')
                 ->where('doc_id', $dp1->doc_id)
                 ->where('doc_page_image_preview', $dp1->doc_page_image_preview)
                 ->update(['doc_page_num'=>$oddCurrent]);

                 if($oddCurrent>$evenMax){
                    $oddCurrent+=1;
                 }else{
                    $oddCurrent+=2;
                 }
            }

            $evenCurrent = 2;
            foreach($doc2_pages as $dp2){

                 DB::table('document_pages')
                 ->where('doc_id', $dp2->doc_id)
                 ->where('doc_page_image_preview', $dp2->doc_page_image_preview)
                 ->update(['doc_id'=>$request->doc1_id,'doc_page_num'=>$evenCurrent]);

                 if($evenCurrent>$oddMax){
                    $evenCurrent+=1;
                 }else{
                    $evenCurrent+=2;
                 }
            }

            //================= INTERLEAVE/COLLATE DOCUMENT 2 to DOCUMENT 1 ==================================
            $params = "pdftk $doc1 $doc2 shuffle output $output_doc && mv $output_doc $doc1";
            $process = new Process($params);
            $process->disableOutput();
            $process->start();
            $process->wait();

            //delete document 2 ======================
            File::delete((string)$doc2);

            //delete document 2 datas =========================
            DB::table('documents')->where('doc_id', $request->doc2_id)->delete();

            //session flash
            session()->flash('merge_success', 'Documents successfully merged.');
            return "success";


        }
    }
}
