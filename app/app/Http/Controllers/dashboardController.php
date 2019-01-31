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
         
         date_default_timezone_set(Auth::user()->user_timezone);

         //recent opened documents
         $last_opened = DB::table('documents_viewed')->where('view_user_id', Auth::user()->id)
         ->join('document_pages', 'documents_viewed.view_doc_id','=','document_pages.doc_id')
         ->groupBy('document_pages.doc_id')
         ->select('documents_viewed.view_id','documents_viewed.view_doc_id','document_pages.doc_page_thumbnail_preview as thumbnail')
         ->orderBy('documents_viewed.view_id', 'desc')
         ->limit(6)->get();

         //latest recent opened document
         $latest_opened = DB::table('documents_viewed')->where('view_user_id', Auth::user()->id)
         ->join('document_pages', 'documents_viewed.view_doc_id','=','document_pages.doc_id')
         ->groupBy('document_pages.doc_id')
         ->select('documents_viewed.view_id','documents_viewed.view_doc_id','document_pages.doc_page_thumbnail_preview as thumbnail')
         ->orderBy('documents_viewed.view_id', 'desc')
         ->first();
         
         $prs_stat = ['ocred_final','ocred_final_failed'];
         //return no of archived docs for knob
         $knob = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',1]
         ])
         ->whereIn('process_status',$prs_stat)
         ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])
         ->count();

         return view('pages/dashboard')->with(compact('last_opened','latest_opened','knob'));
    }

    public function toEditDocs(){
         
         //set user timezone -------------------------------------------------------------------------------------
         date_default_timezone_set(Auth::user()->user_timezone);

         $prs_stat = ["ocred_final","ocred_final_failed"];
 

         //return recent document to be edit ---------------------------------------------------------------------
         $doc = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['process_status','=','ocred_final'],
             ['is_archive','=',0]
         ])
         ->select('documents.doc_id')
         ->first();

         //return recent document failed --------------------------------------------------------------------------
         $doc_failed = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['process_status','=','ocred_final_failed'],
             ['is_archive','=',0]
         ])
         ->select('documents.doc_id')
         ->first();

         //return no. of documents needed to edit. -----------------------------------------------------------------
         $num_pending_docs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['process_status','=','ocred_final'],
             ['is_archive','=',0]
         ])->count();
        

         //return no of archived docs --------------------------------------------------------------------------------
         $num_archive_docs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',1]
         ])
         ->whereIn('process_status',$prs_stat)
         ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])
         ->count();

         //return no. of documents in queue --------------------------------------------------------------------------
         $queueProcess = ['processing','ocred','failed','rerun_failed','final_process','new'];
         $queueDocs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',0]
         ])
         ->whereIn('process_status', $queueProcess)
         ->count();

          //return no. failed documents -------------------------------------------------------------------------------
         $failedProcess = ['ocred_final_failed','password_protected','not_pdf'];
         $failedDocs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',0]
         ])
         ->whereIn('process_status', $failedProcess)
         ->count();

         //============================================================================================================
         $json_response = json_encode(array(

             'num_to_edit'          =>      $num_pending_docs,
             'num_archived'         =>      $num_archive_docs,
             'num_queue'            =>      $queueDocs,
             'num_failed_docs'      =>      $failedDocs,
             'oldest_doc'           =>      $doc,
             'oldest_doc_failed'    =>      $doc_failed

         ));
         // # Return the response
         return $json_response;

    }


    public function returnBarKnobDatas(){

        $prs_stat = ["ocred_final","ocred_final_failed"];

         //return doc datas for knob and barchart ----------------
        $bar_datas = [];
        $week      = [];

        $docs_this_week  = DB::table('documents')
        ->select(
           'documents.created_at',
            DB::raw('count(`doc_id`) as documents'),
            DB::raw('DAYNAME(`created_at`) as day')
         )
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->whereIn('process_status', $prs_stat)
        ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])
        ->groupBy(DB::raw('WEEKDAY(created_at)'))
        ->get();

         if(count($docs_this_week)>0){
             foreach($docs_this_week as $docs)
             {
                array_push($bar_datas, $docs->documents);
                array_push($week, $docs->day);
             }
         }else{
             $bar_datas = [0,0,0,0,0,0,0];
             $week      = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
         }


        $json_response = json_encode(array(

             'bar_datas'=>$bar_datas,
             'week'=>$week
         
         ));
         // # Return the response
         return $json_response;


    }

    public function return_ocr_failed_documents(){

         $prs_stat = ['ocred_final_failed','password_protected','not_pdf'];

         $failed_ocr_docs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',0]
         ])
         ->whereIn('process_status', $prs_stat)
         ->select(
             'documents.doc_id',
             'documents.doc_ocr',
             'documents.doc_org',
             'documents.approved',
             'documents.process_status',
             'documents.sender',
             'documents.receiver',
             'documents.tags',
             'documents.date',
             'documents.category',
             'documents.created_at')
         ->orderBy('doc_id', 'DESC')
         ->get();

          $format  =  "";
          $ext     =  ".pdf";
          $dash    =  "-";
          $d_date  =  new \DateTime();
          $date    =  date_format($d_date, "ymd");
          
          //download format
          $arrFormat = explode(',',Auth::user()->download_filename_format);
          foreach($failed_ocr_docs as $key=>$d){
            foreach($arrFormat as $key2=>$f){
                if($f=="YYMMDD"){
                    $format .= $date.$dash;
                }
                elseif($f=="doc_ocr"){
                    $format .= substr($d->$f, 0, -14).$dash;
                }
                else{
                   if($d->$f!=""){ 
                        $format .= $d->$f.$dash;
                   } 
                }
            }
            //insert new object 
            $d->download_format = substr($format, 0, -1).$ext;
            $format = "";

            //date format
            if($d->date!=null){
              $n_date = new \DateTime($d->date);
              $short_date = date_format($n_date,"d.m.Y");
              $d->date = $short_date;
            }
          }
                 
          return json_encode($failed_ocr_docs);

    }


}
