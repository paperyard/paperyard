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

         //return no of archived docs for knob
         $knob = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['process_status','=','ocred_final'],
             ['is_archive','=',1]
         ])->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])
         ->count();

         return view('pages/dashboard')->with(compact('last_opened','latest_opened','knob'));
    }

    public function toEditDocs(){

         //return oldest document
         $doc = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['process_status','=','ocred_final'],
             ['is_archive','=',0]
         ])
         ->select('documents.doc_id')
         ->first();

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
         ])
         ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])
         ->count();

         //return no. of documents needed to edit.
         $queueProcess = ['processing','ocred','failed','rerun_failed','final_process'];
         $queueDocs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',0]
         ])
         ->whereIn('process_status', $queueProcess)
         ->count();

          //return no. failed
         $failedProcess = ['failed_decrypting','failed_force'];
         $failedDocs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',0]
         ])
         ->whereIn('process_status', $failedProcess)
         ->count();


         //return documents this week
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
         ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])
         ->groupBy(DB::raw('WEEKDAY(created_at)'))
         ->get();

         if(count($docs_this_week)>=1){
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
             'num_to_edit' => $num_pending_docs,
             'num_archived'=>$num_archive_docs,
             'num_queue'=>$queueDocs,
             'num_failed_docs'=>$failedDocs,
             'oldest_doc'=>$doc,
             'bar_datas'=>$bar_datas,
             'week'=>$week
         ));
         // # Return the response
         return $json_response;

    }

    public function searchAutoComplete(Request $req){

        //======get folders names ===============================================================================================
        $folders = DB::table('folders')
        ->where('folder_user_id', Auth::user()->id)
        ->where('folder_name', 'LIKE', '%' . $req->doc_keyword . '%')
        ->select('folders.folder_name')
        ->get();

        if(count($folders)==0){
           $folders = "not_found";
        }
        //->return $folders

        //======get tags =========================================================================================================
        $array_tags = [];

        $tags = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->where('tags', 'LIKE', '%' . $req->doc_keyword . '%')
        ->select('documents.tags')
        ->get();

        if(count($tags)>=1){
            foreach($tags as $key=>$t){
            //documents.tags = "a,b,c,d"
            //convert comma separated string to array.
            $tagsArray = explode(',', $t->tags);
            //get elements with matching keyword
                foreach($tagsArray as $val){
                    // push value to array.
                    if (strpos(strtoupper($val), strtoupper($req->doc_keyword)) !== false) {
                           array_push($array_tags,$val);
                    }
                }
            }
            // remove duplicates
            $unique_array_tags = array_unique($array_tags);
        }else{
            $unique_array_tags = "not_found";
        }
        //->return $unique_array_tags

        //======get fulltext =====================================================================================================
        $filter_text = [];
        //get user documents ids
        $user_doc = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->select('documents.doc_id')->get();

        if(count($user_doc)>=1){
            //store ids in array
            $doc_ids = [];
            foreach($user_doc as $usr){
                array_push($doc_ids, $usr->doc_id);
            }
            //get document pages text using array of document ids
            $text = DB::table('document_pages')
            ->whereIn('doc_id', $doc_ids)
            ->where('doc_page_text', 'LIKE', '%' . $req->doc_keyword . '%')
            ->select('document_pages.doc_page_text')
            ->get();

            if(count($text)>=1){
               foreach($text as $key=>$t){
                    //remove linebreaks
                    $nlbText = str_replace(array("\r", "\n"), ' ', $t->doc_page_text);
                    $textArray = explode(" ", $nlbText);
                    //get elements with matching keyword
                    foreach($textArray as $val){
                        // push value to array.
                        if (strpos(strtoupper($val), strtoupper($req->doc_keyword)) !== false) {
                               array_push($filter_text,$val);
                        }
                    }
                }
                // remove duplicates
                $clean_text = array_unique($filter_text);
                //->return clean_text
            }else{
                $clean_text = "not_found";
            }

        }else{
            $clean_text = "not_found";
        }

        //tag folder fulltext.
        $json_response = json_encode(array('folders'=>$folders,'tags'=>$unique_array_tags,'fulltext'=>$clean_text));
        // # Return the response
        return $json_response;

    }

    public function enterSearchDocumnets(Request $req){

        //$req->doc_keyword

        //store documents ids found in tag, folder and fulltext
        $docs_ids = [];
        //------------------------------------------------------------------------------
        //FOLDERS -------------get folder using passed keyword--------------------------
        $folder_id = DB::table('folders')->where('folder_user_id', Auth::user()->id)
        ->where('folder_name', 'LIKE', '%' . $req->doc_keyword . '%')
        ->select('folders.folder_id')
        ->first();

        //if folder is found
        if(count($folder_id)>0){
            //get documents ids from folder.
            $folder_docs = DB::table('documents')
            ->where('doc_user_id', Auth::user()->id)
            ->where('is_archive', 1)
            ->where('doc_folder_id',$folder_id->folder_id)
            ->select('documents.doc_id')
            ->get();
            //loop/store ids
            if(count($folder_docs)>0){
                 foreach($folder_docs as $fo_id){
                     array_push($docs_ids,$fo_id->doc_id);
                 }
            }
        }
        //-------------------------------------------------------------------------------

        //TAGS--------------get tags using passed keyword--------------------------------
        $tags_ids = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->where('tags', 'LIKE', '%' . $req->doc_keyword . '%')
        ->select('documents.doc_id')
        ->get();

        if(count($tags_ids)>0){
            foreach($tags_ids as $tid){
                array_push($docs_ids,$tid->doc_id);
            }
        }
        //-------------------------------------------------------------------------------

        //FULLTEXT---------- find passed keyword in doc pages ---------------------------
        //get all users documents id.
        $user_docs = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->select('documents.doc_id')->get();
        //store all user documents ids in array.
        $user_doc_ids = [];
        if(count($user_docs)>0){
            foreach($user_docs as $uid){
                array_push($user_doc_ids,$uid->doc_id);
            }

            //find password keyword in each doc pages. get ids and store in array
            $text_ids = DB::table('document_pages')->whereIn('doc_id', $user_doc_ids)
            ->where('doc_page_text', 'LIKE', '%' . $req->doc_keyword . '%')
            ->select('document_pages.doc_id')
            ->get();

            if(count($text_ids)>0){
                foreach($text_ids as $txt){
                    array_push($docs_ids,$txt->doc_id);
                }
            }
        }
        //-------------------------------------------------------------------------------
        //get unique ids in doc_ids array.
        $unique_doc_ids = array_unique($docs_ids);

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->where('process_status', 'ocred_final')
        ->whereIn('documents.doc_id', $unique_doc_ids)
        ->leftJoin('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.doc_org',
            'documents.approved',
            'document_pages.doc_page_image_preview'
        )->get();

        if(count($documents)>=1){
            $json_response = json_encode($documents);
            return $json_response;
        }else{
            return "error";
        }

    }

    public function selectSearchDocumnets(Request $req){

        // $req->doc_keyword
        // $req->doc_filter

        if($req->doc_filter=="tag"){
            $docs = $this->filterTag($req->doc_keyword);
        }

        if($req->doc_filter=="folder"){
            $docs = $this->filterFolder($req->doc_keyword);
        }

        if($req->doc_filter=="fulltext"){
            $docs = $this->filterFulltext($req->doc_keyword);
        }

        if(count($docs)>0){
            $json_response = json_encode($docs);
            return $json_response;
        }else{
            return "error";
        }

     }
     //return documents using tag keyword
     public function filterTag($keyword){

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->where('tags', 'LIKE', '%' . $keyword . '%')
        ->where('process_status', 'ocred_final')
        ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.doc_org',
            'documents.approved',
            'document_pages.doc_page_image_preview'
        )->get();

        return $documents;
     }

     //return documents using folder keyword
     public function filterFolder($keyword){

         //get folder id using folder name
        $folderID = DB::table('folders')->where([
            ['folder_user_id', '=', Auth::user()->id],
            ['folder_name', '=', $keyword]
        ])->first();

        if(count($folderID)>0){
            $documents = DB::table('documents')
            ->where('doc_user_id', Auth::user()->id)
            ->where('is_archive', 1)
            ->where('doc_folder_id', $folderID->folder_id)
            ->where('process_status', 'ocred_final')
            ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
            ->groupBy('document_pages.doc_id')
            ->select(
                'documents.doc_id',
                'documents.doc_ocr',
                'documents.doc_org',
                'documents.approved',
                'document_pages.doc_page_image_preview'
            )->get();
            return $documents;
        }
     }

     //return documents using fulltext keyword
     public function filterFulltext($keyword){

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->where('process_status', 'ocred_final')
        ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->where('document_pages.doc_page_text', 'LIKE', '%' . $keyword. '%')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.doc_org',
            'documents.approved',
            'document_pages.doc_page_image_preview'
        )->get();

        if(count($documents)>=1){
            return $documents;
        }
     }


}
