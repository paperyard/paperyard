<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;

/*
this controller has common search code used in
-reminder page
-merge document page
-dashboard
*/
class commonSearchDocumentController extends Controller
{
    //return autocomplete 
    public function autoComplete(Request $req){

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

    //returns documents from selected autocomplete
    public function selectAutoCompleteSearch(Request $req){

        // $req->doc_keyword
        // $req->doc_filter
        if($req->doc_filter=="tag"){
            $docs = $this->searchTags($req->doc_keyword);
        }
        if($req->doc_filter=="folder"){
            $docs = $this->searchFolders($req->doc_keyword);
        }
        if($req->doc_filter=="fulltext"){
            $docs = $this->searchFulltext($req->doc_keyword);
        }
        if($req->doc_filter=="no_filter"){
            $docs = $this->enterkeySearch($req->doc_keyword);
        }

        if(count($docs)>0){
            $docs = $this->generateDownloadFormat($docs);
            return json_encode($docs);
        }else{
            return "error";
        }
    }


    //search for documents using enter key.
    public function enterkeySearch($keyword){

        //$req->doc_keyword
        //store documents ids found in tag, folder and fulltext
        $docs_ids = [];
        //------------------------------------------------------------------------------
        //FOLDERS -------------get folder using passed keyword--------------------------
        $folder_id = DB::table('folders')->where('folder_user_id', Auth::user()->id)
        ->where('folder_name', 'LIKE', '%' . $keyword . '%')
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
        ->where('tags', 'LIKE', '%' . $keyword . '%')
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
            ->where('doc_page_text', 'LIKE', '%' . $keyword . '%')
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
            'documents.process_status',
            'documents.sender',
            'documents.receiver',
            'documents.tags',
            'documents.date',
            'documents.category',
            'documents.created_at',
            'document_pages.doc_page_image_preview'
        )->get();

        return $documents;

    }

   // search document with selected tag
   public function searchTags($keyword){

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
                'documents.process_status',
                'documents.sender',
                'documents.receiver',
                'documents.tags',
                'documents.date',
                'documents.category',
                'documents.created_at',
                'document_pages.doc_page_image_preview'
        )->get();
         
        return $documents;
   }

   // search document with selected folder
   public function searchFolders($keyword){

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
                'documents.process_status',
                'documents.sender',
                'documents.receiver',
                'documents.tags',
                'documents.date',
                'documents.category',
                'documents.created_at',
                'document_pages.doc_page_image_preview'
            )->get();
            return $documents;
        }
   	
   }

   // search document with selected text
   public function searchFulltext($keyword){

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
                'documents.process_status',
                'documents.sender',
                'documents.receiver',
                'documents.tags',
                'documents.date',
                'documents.category',
                'documents.created_at',
                'document_pages.doc_page_image_preview'
        )->get();

        if(count($documents)>=1){
            return $documents;
        }
   	
    }
    
    //generate download format otf based on user downloadfileformat
    public function generateDownloadFormat($datas){
        $format = "";
        $ext = ".pdf";
        $dash = "-";
        $d_date = new \DateTime();
        $date   = date_format($d_date, "ymd");

        $arrFormat = explode(',',Auth::user()->download_filename_format);
        foreach($datas as $key=>$d){
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
        }
        return $datas;  
    }



}
