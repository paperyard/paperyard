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

    // search documents without filter
    public function typeHead(Request $req){

      //===========================AUTO COMPLETE ================================
      if($req->acf=="folder"){
            //get folders names
            $folders = DB::table('folders')
            ->where('folder_user_id', Auth::user()->id)
            ->where('folder_name', 'LIKE', '%' . $req->keyword . '%')
            ->select('folders.folder_name')
            ->get();

            $json_response = json_encode(array('folders' => $folders));
            // # Return the response
            return $json_response;
      }
      if($req->acf=="tag"){

            $f_tags = [];
            //get tags
            $tags = DB::table('documents')
            ->where('doc_user_id', Auth::user()->id)
            ->where('tags', 'LIKE', '%' . $req->keyword . '%')
            ->select('documents.doc_id','documents.tags')
            ->get();

            foreach($tags as $key=>$t){
                //documents.tags = "a,b,c,d"
                //convert comma separated string to array.
                $tagsArray = explode(',', $t->tags);
                //get elements with matching keyword
                foreach($tagsArray as $val){
                    // push value to array.
                    if (strpos(strtoupper($val), strtoupper($req->keyword)) !== false) {
                           array_push($f_tags,$val);
                    }
                }
            }
            // remove duplicates
            $clean_tags = array_unique($f_tags);
            //get full text
            $json_response = json_encode(array('tags' => $clean_tags));
            // # Return the response
            return $json_response;
       }
       if($req->acf=="full_text"){

           $filter_text = [];
           $user_doc = DB::table('documents')->where('doc_user_id', Auth::user()->id)->select('documents.doc_id')->get();
           $doc_ids = [];
           foreach($user_doc as $usr){
               array_push($doc_ids, $usr->doc_id);
           }
           $text = DB::table('document_pages')->whereIn('doc_id', $doc_ids)->select('document_pages.doc_page_text')->get();

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

            $json_response = json_encode(array('text' => $clean_text));
            // # Return the response
            return $json_response;

       }


    }
    // search documents with filters TAGS, FOLDER , FULL TEXT
    public function searchDocumentWithFilter(Request $req){


         // NO FILTER FIND ALL IN FOLDER TAGS AN TEXT. QUERY TAKE LONGER
         if($req->filter=="no_filter"){

                $docs_ids = [];
                //get folder ids
                $folder_ids = DB::table('folders')->where('folder_user_id', Auth::user()->id)
                ->where('folder_name', 'LIKE', '%' . $req->keyword . '%')
                ->select('folders.folder_id')
                ->get();

                $folder_docs_ids = [];
                 //get documents from each folder
                if(count($folder_ids)>0){
                    foreach($folder_ids as $fid){
                         array_push($folder_docs_ids,$fid->folder_id);
                    }
                }
                //get documents ids from this folder ids.
                $folder_docs = DB::table('documents')
                ->where('doc_user_id', Auth::user()->id)
                ->whereIn('doc_folder_id',$folder_docs_ids)
                ->select('documents.doc_id')
                ->get();

                if(count($folder_docs)>0){
                     foreach($folder_docs as $fod){
                         array_push($docs_ids,$fod->doc_id);
                     }
                }
                //get tag ids
                $tags_ids = DB::table('documents')->where('doc_user_id', Auth::user()->id)
                ->where('tags', 'LIKE', '%' . $req->keyword . '%')
                ->select('documents.doc_id')
                ->get();

                if(count($tags_ids)>0){
                    foreach($tags_ids as $tid){
                        array_push($docs_ids,$tid->doc_id);
                    }
                }

                //get fulltext ids
                $user_docs = DB::table('documents')->where('doc_user_id', Auth::user()->id)
                ->select('documents.doc_id')->get();

                $user_doc_ids = [];
                if(count($user_docs)>0){
                      foreach($user_docs as $uid){
                          array_push($user_doc_ids, $uid->doc_id);
                      }
                      $text_ids = DB::table('document_pages')->whereIn('doc_id', $user_doc_ids)
                      ->where('doc_page_text', 'LIKE', '%' . $req->keyword . '%')
                      ->select('document_pages.doc_id')
                      ->get();
                      if(count($text_ids)>0){
                            foreach($text_ids as $txt){
                                array_push($docs_ids,$txt->doc_id);
                            }
                      }
                }
                //---------------
                $find_all_ids = array_unique($docs_ids);

                $documents = DB::table('documents')
                ->where('doc_user_id', Auth::user()->id)
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

                foreach($documents as $key=>$d){
                    //append doc size to obj result
                    $fname = "static/documents_ocred/" . $d->doc_ocr;
                    $fsize = filesize($fname);
                    $d->size = $this->FileSizeConvert($fsize);
                }

                if(count($documents)>=1){
                    $json_response = json_encode($documents);
                    return $json_response;
                }
         }

         // FOLDER  ===================================================================================================
         if($req->filter=="folder"){

                //get folder id using folder name
                $folderID = DB::table('folders')->where([
                    ['folder_user_id', '=', Auth::user()->id],
                    ['folder_name', '=', $req->keyword]
                ])->get();

                $f_ids = [];

                foreach($folderID as $i){
                    array_push($f_ids, $i->folder_id);
                }

                $documents = DB::table('documents')
                ->where('doc_user_id', Auth::user()->id)
                ->whereIn('doc_folder_id', $f_ids)
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

                foreach($documents as $key=>$d){
                    //append doc size to obj result
                    $fname = "static/documents_ocred/" . $d->doc_ocr;
                    $fsize = filesize($fname);
                    $d->size = $this->FileSizeConvert($fsize);
                }

                if(count($documents)>=1){
                    $json_response = json_encode($documents);
                    return $json_response;
                }

        }
        // TAGS  =====================================================================================================
        if ($req->filter=="tag") {
             # code...

                $documents = DB::table('documents')
                ->where('doc_user_id', Auth::user()->id)
                ->where('tags', 'LIKE', '%' . $req->keyword . '%')
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

                foreach($documents as $key=>$d){
                    //append doc size to obj result
                    $fname = "static/documents_ocred/" . $d->doc_ocr;
                    $fsize = filesize($fname);
                    $d->size = $this->FileSizeConvert($fsize);
                }

                if(count($documents)>=1){
                    $json_response = json_encode($documents);
                    return $json_response;
                }
        }
        // TEXT ======================================================================================================
        if($req->filter=="full_text"){
             # code
                $documents = DB::table('documents')
                ->where('doc_user_id', Auth::user()->id)
                ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
                ->where('document_pages.doc_page_text', 'LIKE', '%' . $req->keyword . '%')
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

                foreach($documents as $key=>$d){
                    //append doc size to obj result
                    $fname = "static/documents_ocred/" . $d->doc_ocr;
                    $fsize = filesize($fname);
                    $d->size = $this->FileSizeConvert($fsize);
                }

                if(count($documents)>=1){
                    $json_response = json_encode($documents);
                    return $json_response;
                }

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
