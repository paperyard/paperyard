<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;

class searchDocumentController extends Controller
{
    //

    public function index(){
        return view('pages/search_docs');
    }

    // autocomplete
    public function typeHead(Request $req){
      
      //default
      $folders    = "not_found";
      $clean_tags = "not_found";
      $clean_text = "not_found";

      //filter folder or no filter.
      if($req->autocomplte_filter=="folder" || $req->autocomplte_filter=="no_filter"){
            //get folders names
            $folders = DB::table('folders')
            ->where('folder_user_id', Auth::user()->id)
            ->where('folder_name', 'LIKE', '%' . $req->keyword . '%')
            ->select('folders.folder_name')
            ->get();
            if(count($folders)==0){
                $folders = "not_found";
            }
            //return $folders.
      }else{
           $folders = "not_found";
      }
      
      //filter tags or no filter.
      if($req->autocomplte_filter=="tag" || $req->autocomplte_filter=="no_filter"){
            
            //get/store documents ids using tags.
            $arr_tags = [];
            
            $tags = DB::table('documents')
            ->where('doc_user_id', Auth::user()->id)
            ->where('is_archive', 1)
            ->where('tags', 'LIKE', '%' . $req->keyword . '%')
            ->select('documents.doc_id','documents.tags')
            ->get();

            if(count($tags)>0){

                foreach($tags as $key=>$t){
                    //documents.tags = "a,b,c,d"
                    //convert comma separated string to array.
                    $tagsArray = explode(',', $t->tags);
                    //get elements with matching keyword
                    foreach($tagsArray as $val){
                        // push value to array.
                        if (strpos(strtoupper($val), strtoupper($req->keyword)) !== false) {
                               array_push($arr_tags,$val);
                        }
                    }
                }
                // remove duplicates
                $clean_tags = array_unique($arr_tags);
            }
            else{
                $clean_tags = "not_found";
            }
            // return clean_tags.
       }
       //fulter fulltext or no filter
       if($req->autocomplte_filter=="full_text" || $req->autocomplte_filter=="no_filter"){
            
            $filter_text = [];
            //get users documents ids 
            $user_doc = DB::table('documents')
            ->where('doc_user_id', Auth::user()->id)
            ->where('is_archive', 1)
            ->select('documents.doc_id')
            ->get();

            if(count($user_doc)>0){
           
                //store documents ids in array.
                $doc_ids = [];
                foreach($user_doc as $usr){
                   array_push($doc_ids, $usr->doc_id);
                }
                //find documents ids in documents pages
                $text = DB::table('document_pages')
                ->whereIn('doc_id', $doc_ids)
                ->where('doc_page_text', 'LIKE', '%' . $req->keyword . '%')
                ->select('document_pages.doc_page_text')->get();

                if(count($text)>0){
                
                    foreach($text as $key=>$t){
                        //documents.tags = "a,b,c,d"
                        //convert comma separated string to array.
                        //remove linebreaks
                        $nlbText = str_replace(array("\r", "\n"), ' ', $t->doc_page_text);
                        $textArray = explode(" ", $nlbText);
                        //get elements with matching keyword
                        foreach($textArray as $val){
                            // push value to array.
                            if (strpos(strtoupper($val), strtoupper($req->keyword)) !== false) {
                                   array_push($filter_text,$val);
                            }
                        }
                    }
                    // remove duplicates
                    $clean_text = array_unique($filter_text);
                    // return clean_text
                }else{
                    $clean_text = "not_found";
                }    
  
            }else{
                $clean_text = "not_found";
            }
        }//end if fulltext filter

        //tag folder fulltext.
        $json_response = json_encode(array('folders'=>$folders,'tags'=>$clean_tags,'fulltext'=>$clean_text));
        // # Return the response
        return $json_response;

    }
    

    public function searchDocument(Request $req){
         
        //keypress enter search. no filter
        if($req->filter=="no_filter"){
           $docs = $this->searchNoFilter($req->keyword);
        }
        if($req->filter=="folder"){
            $docs = $this->searchFolders($req->keyword);
        }
        
        if($req->filter=="tag") {
            $docs = $this->searchTags($req->keyword);
        }
        if($req->filter=="full_text"){
            $docs = $this->searchFullText($req->keyword);
        }

        if(count($docs)>0){
            $json_response = json_encode($docs);
            return $json_response;
        }else{
            return "error";
        }

    }

    //search on keypress enter
    public function searchNoFilter($keyword){
        
        //store document ids found in keyword tag,folder and text
        $docs_ids = [];

        //get folder ids
        $folder_ids = DB::table('folders')->where('folder_user_id', Auth::user()->id)
        ->where('folder_name', 'LIKE', '%' . $keyword . '%')
        ->select('folders.folder_id')
        ->get();

        //if folder found
        if(count($folder_ids)>0){

            $folder_docs_ids = [];
             //get ids from each folder
            foreach($folder_ids as $fid){
                 array_push($folder_docs_ids, $fid->folder_id);
            }

            //get documents ids from this folder ids.
            $folder_docs = DB::table('documents')
            ->where('is_archive', 1)
            ->where('doc_user_id', Auth::user()->id)
            ->whereIn('doc_folder_id',$folder_docs_ids)
            ->select('documents.doc_id')
            ->get();

            if(count($folder_docs)>0){
                 foreach($folder_docs as $fod){
                     array_push($docs_ids,$fod->doc_id);
                 }
            }
        }
        //------------------------------------------------------------------------------  
        //get tag ids
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
        
        //get fulltext ids
        $user_docs = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->select('documents.doc_id')
        ->get();

        $user_doc_ids = [];
        if(count($user_docs)>0){

              foreach($user_docs as $uid){
                  array_push($user_doc_ids, $uid->doc_id);
              }

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
        $find_all_ids = array_unique($docs_ids);

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->whereIn('documents.doc_id', $find_all_ids)
        ->leftJoin('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.memory',
            'documents.tax_relevant',
            'documents.note',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview'
        )->get();

        if(count($documents)>0){
            foreach($documents as $key=>$d){
                //append doc size to obj result
                $fname = "static/documents_ocred/" . $d->doc_ocr;
                $fsize = filesize($fname);
                $d->size = $this->FileSizeConvert($fsize);
            }
            return $documents;
        }else{
            return $documents;
        }    

    }

    //search documents with tag keyword
    public function searchTags($keyword){

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->where('tags', 'LIKE', '%' . $keyword . '%')
        ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.memory',
            'documents.tax_relevant',
            'documents.note',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview'
        )->get();
        
        if(count($documents)>0){
            foreach($documents as $key=>$d){
                //append doc size to obj result
                $fname = "static/documents_ocred/" . $d->doc_ocr;
                $fsize = filesize($fname);
                $d->size = $this->FileSizeConvert($fsize);
            }
            return $documents;
        }else{
            return $documents;
        }    
    }
    
    //search documents with folder keyword
    public function searchFolders($keyword){
        
        //get folder id using folder name
        $folderID = DB::table('folders')->where([
            ['folder_user_id', '=', Auth::user()->id],
            ['folder_name', '=', $keyword]
        ])->first();

        if(count($folderID)>0){
            $documents = DB::table('documents')
            ->where('is_archive', 1) 
            ->where('doc_user_id', Auth::user()->id)
            ->where('doc_folder_id', $folderID->folder_id)
            ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
            ->groupBy('document_pages.doc_id')
            ->select(
                'documents.doc_id',
                'documents.doc_ocr',
                'documents.sender',
                'documents.receiver',
                'documents.date',
                'documents.tags',
                'documents.category',
                'documents.memory',
                'documents.tax_relevant',
                'documents.note',
                'document_pages.doc_page_image_preview',
                'document_pages.doc_page_thumbnail_preview'
            )->get();
            
            //documents found in folder
            if(count($documents)>0){
                foreach($documents as $key=>$d){
                    //append doc size to obj result
                    $fname = "static/documents_ocred/" . $d->doc_ocr;
                    $fsize = filesize($fname);
                    $d->size = $this->FileSizeConvert($fsize);
                }
                return $documents;
            }else{
                return $documents;
            }
        }else{
            $documents = $folderID;
            return $documents;
        }
            

    }

    public function searchFullText($keyword){
        
        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->where('document_pages.doc_page_text', 'LIKE', '%' . $keyword . '%')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.memory',
            'documents.tax_relevant',
            'documents.note',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview'
        )->get();
        
        if(count($documents)>0){
            foreach($documents as $key=>$d){
                //append doc size to obj result
                $fname = "static/documents_ocred/" . $d->doc_ocr;
                $fsize = filesize($fname);
                $d->size = $this->FileSizeConvert($fsize);
            }
            return $documents;
        }else{
            return $documents;
        }
    }

    //convert file size to human readable.
    public function FileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "TB",
                    "VALUE" => pow(1024, 4)
                ),
                1 => array(
                    "UNIT" => "GB",
                    "VALUE" => pow(1024, 3)
                ),
                2 => array(
                    "UNIT" => "MB",
                    "VALUE" => pow(1024, 2)
                ),
                3 => array(
                    "UNIT" => "KB",
                    "VALUE" => 1024
                ),
                4 => array(
                    "UNIT" => "B",
                    "VALUE" => 1
                ),
            );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }
}
