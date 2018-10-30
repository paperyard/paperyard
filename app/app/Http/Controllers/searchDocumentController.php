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
    /*
        in linux dev env. use pluck.
        $titles = DB::table('roles')->pluck('title');
        foreach ($titles as $title) {
            echo $title;
        }
    */

    public function index(){
        return view('pages/search_docs');
    }

    // autocomplete ========================================================================================
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
            
            $folders = count($folders)>0?$folders:"not_found";
            //return $folders.
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


    // Search documents  =============================================================================================
    public function searchDocument(Request $req){
         
        //keypress enter search. no filter
        if($req->filter=="no_filter" && !empty($req->keyword)){
             
             $oprs = ['=','>','<'];
             if(count(array_intersect($oprs,str_split($req->keyword)))>0){
                //run customsearch
                $bin_operators = ['and','or','not','xor'];
                $kw = explode(" ",$req->keyword);
                if(count(array_intersect($bin_operators, $kw))>0){
                     $docs = $this->customSearchAdvance($req->keyword);
                }else{
                     $docs = $this->customSearchBasic($req->keyword);
                }
             }else{
                //run basic search
                $docs = $this->searchNoFilter($req->keyword);
             }
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

        //return all documents when no keyword 
        if($req->filter=="no_filter" && empty($req->keyword)){
            $docs = $this->returnAllDocuments();
        }


        if(count($docs)>0 && $docs!="invalid_format"){
            
            $doc_tags = [];
            foreach($docs as $d){                
                if($d->date!=null){
                    $n_date = new \DateTime($d->date);
                    $short_date = date_format($n_date,"d.m.Y");
                    $d->date = $short_date;
                }
                //get tags
                if(count($d->tags_array)>0){
                   $arr_tags = array_merge($doc_tags,$d->tags_array);
                   $doc_tags = $arr_tags;
                }
            }
            //remove unique, remove empty element
            $unique_tags =   array_filter(array_unique($doc_tags));
            $json_response = json_encode(array('doc_datas'=>$docs,'doc_tags'=>$unique_tags));
            return $json_response;

        }
        else if($docs=="invalid_format"){
            return "invalid_format";
        }
        else{
            return "error";
        }
    }


    // return all documnets ======================================================
    public function returnAllDocuments(){
       
        $pr_status = ['ocred_final','ocred_final_failed'];
        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->whereIn('process_status', $pr_status)
        ->leftJoin('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->leftJoin('folders','documents.doc_folder_id','=','folders.folder_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.reminder',
            'documents.tax_relevant',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name'
        )->get();

        if(count($documents)>0){
            foreach($documents as $key=>$d){
                //append doc size to obj result
                $fname =   storage_path('app/documents_ocred') . '/' . $d->doc_ocr;
                $fsize =   filesize($fname);
                $d->size = $this->FileSizeConvert($fsize);
                $tagsArr = explode(",",$d->tags);
                $d->tags_array = $tagsArr;
            }
            return $documents;
        }else{
            return $documents;
        }    

    }

    //search on keypress enter  =============================================================================================
    public function searchNoFilter($keyword){
        
         //store document ids found in keyword tag,folder and text
        $docs_ids = [];

        //get folder ids --------------------------------------------------------------
        $folder_ids = DB::table('folders')
        ->where('folder_user_id', Auth::user()->id)
        ->where('folder_name', 'LIKE', '%' . $keyword . '%')
        ->pluck('folder_id')->toArray();

        //if folder found
        if(count($folder_ids)>0){

            //get documents ids from this folder ids.
            $folder_docs = DB::table('documents')
            ->where('is_archive', 1)
            ->where('doc_user_id', Auth::user()->id)
            ->whereIn('doc_folder_id',$folder_ids)
            ->pluck('doc_id')->toArray();

            if(count($folder_docs)>0){
                $docs_ids = array_merge($docs_ids,$folder_docs);
            }
        }
        //get tag ids -------------------------------------------------------------------  
        $tags_ids = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->where('tags', 'LIKE', '%' . $keyword . '%')
        ->pluck('doc_id')->toArray();

        if(count($tags_ids)>0){
            $docs_ids = array_merge($docs_ids,$tags_ids);
        }
 
        //get fulltext ids  ---------------------------------------------------------------
        $user_docs = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->pluck('doc_id')->toArray();

        if(count($user_docs)>0){

            $text_ids = DB::table('document_pages')->whereIn('doc_id', $user_docs)
            ->where('doc_page_text', 'LIKE', '%' . $keyword . '%')
            ->pluck('doc_id')->toArray();
              
            if(count($text_ids)>0){
                $docs_ids = array_merge($docs_ids,$text_ids);
            }
        }

        //search for all fields in document --------------------------------------------
        similar_text("tax relevant",$keyword,$if_tax_relevant);
        $is_tax_relevant = $if_tax_relevant>=50? "on":false;
        
        $search_in_all = DB::table('documents')
        ->where('doc_user_id',Auth::user()->id)
        ->where('is_archive',1)
        ->where([
            ['sender',    'LIKE', '%' . $keyword . '%'],
            ['receiver',  'LIKE', '%' . $keyword . '%', 'or'],
            ['category',  'LIKE', '%' . $keyword . '%', 'or'],
            ['date',      'LIKE', '%' . $keyword . '%', 'or'],
            ['note',      'LIKE', '%' . $keyword . '%', 'or'],
            ['reminder',  'LIKE', '%' . $keyword . '%', 'or'],
        ])
        ->when($is_tax_relevant, function ($query, $is_tax_relevant) {
            return $query->orWhere('tax_relevant', $is_tax_relevant);
        })
        ->pluck('doc_id')->toArray();
        
        if(count($search_in_all)>0){      
           $docs_ids = array_merge($docs_ids,$search_in_all);
        }
        //-------------------------------------------------------------------------------
        return $this->getDocumentsDetails($docs_ids);

    }

    public function getDocumentsDetails($docs_ids){

        $pr_status = ['ocred_final','ocred_final_failed']; 

        $find_all_ids = array_unique($docs_ids);
        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->whereIn('documents.doc_id', $find_all_ids)
        ->whereIn('process_status', $pr_status)
        ->leftJoin('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->leftJoin('folders','documents.doc_folder_id','=','folders.folder_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.reminder',
            'documents.tax_relevant',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name'
        )->get();

        if(count($documents)>0){
            foreach($documents as $key=>$d){
                //append doc size to obj result
                $fname =   storage_path('app/documents_ocred') . '/' . $d->doc_ocr;
                $fsize =   filesize($fname);
                $d->size = $this->FileSizeConvert($fsize);
                $tagsArr = explode(",",$d->tags);
                $d->tags_array = $tagsArr;
            }
            return $documents;
        }else{
            return $documents;
        }    
    }


    //search documents with tag keyword =============================================================================================
    public function searchTags($keyword){

        $pr_status = ['ocred_final','ocred_final_failed']; 
        
        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->where('tags', 'LIKE', '%' . $keyword . '%')
        ->whereIn('process_status', $pr_status)
        ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->leftJoin('folders','documents.doc_folder_id','=','folders.folder_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.reminder',
            'documents.tax_relevant',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name'
        )->get();
        
        if(count($documents)>0){
            foreach($documents as $key=>$d){
                //append doc size to obj result
                $fname = storage_path('app/documents_ocred') . '/' . $d->doc_ocr;
                $fsize = filesize($fname);
                $d->size = $this->FileSizeConvert($fsize);
                $tagsArr = explode(",",$d->tags);
                $d->tags_array = $tagsArr;
            }
            return $documents;
        }else{
            return $documents;
        }    
    }
    
    //search documents with folder keyword =============================================================================================
    public function searchFolders($keyword){
        
        $pr_status = ['ocred_final','ocred_final_failed']; 
        
        //get folder id using folder name
        $folderID = DB::table('folders')->where([
            ['folder_user_id', '=', Auth::user()->id],
            ['folder_name', '=', $keyword]
        ])->first();

        if(count($folderID)>0){
            $documents = DB::table('documents')
            ->where('is_archive', 1) 
            ->where('doc_user_id', Auth::user()->id)
            ->whereIn('process_status', $pr_status)
            ->where('doc_folder_id', $folderID->folder_id)
            ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
            ->leftJoin('folders','documents.doc_folder_id','=','folders.folder_id')
            ->groupBy('document_pages.doc_id')
            ->select(
                'documents.doc_id',
                'documents.doc_ocr',
                'documents.sender',
                'documents.receiver',
                'documents.date',
                'documents.tags',
                'documents.category',
                'documents.reminder',
                'documents.tax_relevant',
                'documents.note',
                'documents.origin',
                'document_pages.doc_page_image_preview',
                'document_pages.doc_page_thumbnail_preview',
                'folders.folder_name'
            )->get();
            
            //documents found in folder
            if(count($documents)>0){
                foreach($documents as $key=>$d){
                    //append doc size to obj result
                    $fname = storage_path('app/documents_ocred') . '/' . $d->doc_ocr;
                    $fsize = filesize($fname);
                    $d->size = $this->FileSizeConvert($fsize);
                    $tagsArr = explode(",",$d->tags);
                    $d->tags_array = $tagsArr;
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
    //search documents with fulltext keyword =============================================================================================
    public function searchFullText($keyword){

        $pr_status = ['ocred_final','ocred_final_failed']; 

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->whereIn('process_status', $pr_status)
        ->join('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->leftJoin('folders','documents.doc_folder_id','=','folders.folder_id')
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
            'documents.reminder',
            'documents.tax_relevant',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name' 
        )->get();
        
        if(count($documents)>0){
            foreach($documents as $key=>$d){
                //append doc size to obj result
                $fname = storage_path('app/documents_ocred') . '/' . $d->doc_ocr;
                $fsize = filesize($fname);
                $d->size = $this->FileSizeConvert($fsize);
                $tagsArr = explode(",",$d->tags);
                $d->tags_array = $tagsArr;
            }
            return $documents;
        }else{
            return $documents;
        }
    }

    //==============================================   FUNCTIONS OTF ======================================================

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

    // barchart datas 
    public function getBarchartDatas(){

         //return 
         $year_range = [];
         //return
         $months_docs_arr = [];
         //get years
         $years = DB::table('documents')
         ->where('doc_user_id', Auth::user()->id)
         ->select(DB::raw('YEAR(created_at) as year'))
         ->groupBy(DB::raw('YEAR(created_at)'))
         ->get();

         if(count($years)>0){
             foreach($years as $year){
                  array_push($year_range, $year->year);
             }
             foreach($year_range as $key=>$yr){
                  $months = [];
                  $docs   = [];
                  $datas = $this->getDocDatas($yr);
                  foreach($datas as $key2=>$d){
                      array_push($months, $d->months);
                      array_push($docs, $d->num_of_documents);
                  }
                  $md = array('month'=>$months,'docs'=>$docs);
                  array_push($months_docs_arr,$md);
             }
         }
         $datas = array('year_range'=>$year_range,'mdr'=>$months_docs_arr); 
         return json_encode($datas);
    }

    // barchar year
    public function getDocDatas($year){
          
         $datas = DB::table('documents')
         ->select(
             DB::raw('count(`doc_id`) as num_of_documents'),
             DB::raw('MONTHNAME(`created_at`) as months'),
             DB::raw('YEAR(created_at) as year')
         )
         ->whereRaw('YEAR(created_at)= "'.$year.'"')
         ->groupBy(DB::raw('MONTH(created_at)'))
         ->groupBy(DB::raw('YEAR(created_at)'))
         ->get();
         
         return $datas;
    }


    //============================================= CUSTOM SEARCH ======================================================


    public function customSearchBasic($keyword){

        // AND LIKE
        // check if search is custom
        // check if custom search has logic

        //expression sent by user custom search
        $expression = $keyword;
        //convert all string to lower case then convert to array.
        $expression = explode(" ", strtolower($expression));
        //move index to proper position
        $expression = array_values(array_filter($expression));

        //where conditions
        $conditions = [];
        //normal columns have = operator
        $columns_normal  = ["sender","receiver","tags",'category','note'];
        //config columns have different operator.
        $columns_config  = [
            ["col"=>"date", "str_i"=>4],
            ["col"=>"number_of_pages", "str_i"=>15],
            ["col"=>"reminder", "str_i"=>8],
            ["col"=>"tax_relevant", "str_i"=>12]
        ];
        $operators   = ["=","<",">","<=",">=","!=","!"];
        $total_equal = 0;
        $total_col   = 0;

        $tax_relevant = false;
        $tax_opr = null;
        
        //------------------------------------CHECK FORMAT----------------------------------------------
        //check each expression for valid format.
        //store conditions for where clause.
        foreach($expression as $key=>$exp){
              //0,4 = 1-4
              //expression index
              //e_i used to get operator from string explode
              $e_i = 0;
              $current_culomn = null;
              $ch_col = null; 
              
              foreach($columns_config as $c_i){
                  //substring exp & check if column has match
                  $ch_col = substr((string)$exp,0,$c_i['str_i']);
                  if($ch_col == $c_i['col']){
                      //columns is valid store to current column & end loop
                      $current_culomn = $ch_col;
                      //store str_i num to e_i to be used in determining index of operator
                      $e_i = $c_i['str_i'];
                      //add 1 to total col for format checking.
                      $total_col   +=1;
                      $total_equal +=1;
                      break;
                  }
              }
              //column need to be config.

              if($e_i>0){
                  $opr = null;
                  //$e_i is the index of first operator.
                  if(count(array_intersect([$exp[$e_i]],$operators))==1){
                      if($exp[$e_i+1]=="="){
                         $opr = $exp[$e_i].$exp[$e_i+1];
                      }else{
                         $opr = $exp[$e_i];
                      }
                  }else{
                     return "invalid_format";
                  }
                  //$opr - $current_culomn
                  $exp = explode($opr, $exp);
                  //check if explode has column[0] and value[1]
                  if(count($exp)==2){
                    if($exp[0]=="tax_relevant"){
                      array_push($conditions,[$exp[0],"=","on"]);
                      array_push($conditions,[$exp[0],$opr,$exp[1]]);
                      $tax_relevant = $exp[1];
                      $tax_opr = $opr;
                    }else{
                      array_push($conditions,[$exp[0],$opr,$exp[1]]);
                    }
                  }  

              }
              //normal column
              else{
                  //if each exp has = then + 1 to toal_equal
                  $total_equal += substr_count($exp, '=')==1? 1:0;
                  //convert each value to array 
                  $exp = explode("=", $exp);
                  //check if explode has column[0] and value[1]
                  if(count($exp)==2){
                    //if value has valid culomn name +1
                    $total_col += count(array_intersect([$exp[0]],$columns_normal))==1? 1:0;
                    array_push($conditions,[ $exp[0],'LIKE', '%' . $exp[1] . '%' ]);
                  }
              } 
        }
        //------------------------------------------------------------------------------------------------
        /*
        VALID FORMAT
        count expression = count total_equal = total_column
        */
        if(($total_equal == $total_col) && ($total_col ==count($expression)) ){

           $custom_srch_basic = DB::table('documents')
           ->where('doc_user_id',Auth::user()->id)
           ->where('is_archive',1)
           ->where($conditions)
           ->when($tax_relevant, function ($query, $tax_relevant) use ($tax_opr) {
                return $query->whereYear('created_at',$tax_opr,$tax_relevant);
           })->pluck('doc_id')->toArray();
           
           return $this->getDocumentsDetails($custom_srch_basic);

        }else{
           return "invalid_format";
        }

    }


    // custom search with binary operations
    public function customSearchAdvance($keyword){

        //expression sent by user custom search
        $expression = $keyword;
        //convert all string to lower case then convert to array.
        $expression = explode(" ", strtolower($expression));
        //move index to proper position
        $expression = array_values(array_filter($expression));
        //binary operators
        $b_opr = ['and','or','not','xor'];
        //even->store expression
        $exp_even = [];
        //odd ->store operator
        $opr_odd  = [];

        foreach($expression as $key=>$e){
            if($key % 2 == 0){
                 array_push($exp_even,$e);
            }else{
                 array_push($opr_odd, $e);
            }
        }
        //check if operator is valid
        $opr_odd = array_map('strtolower', $opr_odd);
        
        $total_opr = 0;
        foreach($opr_odd as $opr){
            $total_opr += count(array_keys($b_opr, $opr));
        }
        if($total_opr!=count($opr_odd)){
           return "invalid_format";
        }

        if(count($exp_even)-count($opr_odd)!=1){
           return "invalid_format";
        }
        //dont not continue

        //where conditions
        $conditions = [];
        //normal columns have = operator
        $columns_normal  = ["sender","receiver","tags",'category','note'];
        //config columns have different operator.
        $columns_config  = [
            ["col"=>"date", "str_i"=>4],
            ["col"=>"number_of_pages", "str_i"=>15],
            ["col"=>"reminder", "str_i"=>8],
            ["col"=>"tax_relevant", "str_i"=>12]
        ];

        $reverse_bin_opr = [
            ['opr'=>'=',  'rv_opr'=>'!='],
            ['opr'=>'!=', 'rv_opr'=>'='],
            ['opr'=>'>',  'rv_opr'=>'<='],
            ['opr'=>'<',  'rv_opr'=>'>='],
            ['opr'=>'>=', 'rv_opr'=>'<' ],
            ['opr'=>'<=', 'rv_opr'=>'>' ],
        ];
        $operators   = ["=","<",">","<=",">=","!=","!"];
        $total_equal = 0;
        $total_col   = 0;

        $tax_relevant = false;
        $tax_opr = null;
        $tax_bin_opr = null;
        
        //------------------------------------CHECK FORMAT----------------------------------------------
        //check each expression for valid format.
        //store conditions for where clause.
        foreach($exp_even as $key=>$exp){
              //0,4 = 1-4
              //expression index
              //e_i used to get operator from string explode
              $e_i = 0;
              $current_culomn = null;
              $ch_col = null; 
              
              foreach($columns_config as $c_i){
                  //substring exp & check if column has match
                  $ch_col = substr((string)$exp,0,$c_i['str_i']);
                  if($ch_col == $c_i['col']){
                      //columns is valid store to current column & end loop
                      $current_culomn = $ch_col;
                      //store str_i num to e_i to be used in determining index of operator
                      $e_i = $c_i['str_i'];
                      //add 1 to total col for format checking.
                      $total_col   +=1;
                      $total_equal +=1;
                      break;
                  }
              }
              //column need to be config.
              if($e_i>0){
                  $opr = null;
                  //$e_i is the index of first operator.
                  if(count(array_intersect([$exp[$e_i]],$operators))==1){
                      if($exp[$e_i+1]=="="){

                         $opr = $exp[$e_i].$exp[$e_i+1];
                      }else{
                         $opr = $exp[$e_i];
                      }
                  }else{
                     return "invalid_format";
                  }
                  //$opr - $current_culomn
                  $exp = explode($opr,$exp);
                  //check if explode has column[0] and value[1]
                  if(count($exp)==2){
                    if($key==0){
                        if($exp[0]=="tax_relevant"){
                          array_push($conditions,[$exp[0],"=","on"]);
                          $tax_relevant = $exp[1];
                          $tax_opr = $opr;
                        }else{
                          array_push($conditions,[ $exp[0], $opr, $exp[1]]);
                        }
                    }
                    if($key>0){
                        $binary_index = $key-1;
                        if($exp[0]=="tax_relevant"){
                          array_push($conditions,[$exp[0],"=","on"]);
                          $tax_relevant = $exp[1];
                          $tax_opr = $opr;
                          $tax_bin_opr = $opr_odd[$binary_index];
                        }
                        else{
                            if($opr_odd[$binary_index]=="and"){
                               array_push($conditions,[ $exp[0], $opr, $exp[1] ]);
                            }
                            if($opr_odd[$binary_index]=="or"){
                               array_push($conditions,[ $exp[0], $opr, $exp[1], 'or' ]);
                            }
                            if($opr_odd[$binary_index]=="not"){
                               foreach($reverse_bin_opr as $rv_opr){
                                  if($rv_opr['opr']==$opr){
                                      $opr = $rv_opr['rv_opr'];
                                      break;
                                  }
                               }
                               array_push($conditions,[ $exp[0], $opr, $exp[1] ]);
                            }
                            if($opr_odd[$binary_index]=="xor"){
                               array_push($conditions,[ $exp[0], $opr, $exp[1], 'xor' ]);
                            } 
                        }
                    }           
                  // ---------------------------------------------------------------------------------------------
                  }  

              }
              //normal column
              else{
                  //if each exp has = then + 1 to toal_equal
                  $total_equal += substr_count($exp, '=')==1? 1:0;
                  //convert each value to array 
                  $exp = explode("=", $exp);

                  //check if explode has column[0] and value[1]
                  if(count($exp)==2){
                      //if value has valid culomn name +1   
                      $total_col += count(array_intersect([$exp[0]],$columns_normal))==1? 1:0;
                  
                      if($key==0){
                          array_push($conditions,[ $exp[0],'LIKE', '%' . $exp[1] . '%' ]);
                      }
                      if($key>0){
                          $binary_index = $key-1;
                          if($opr_odd[$binary_index]=="and"){
                             array_push($conditions,[ $exp[0],'LIKE', '%' . $exp[1] . '%' ]);
                          }
                          if($opr_odd[$binary_index]=="or"){
                             array_push($conditions,[ $exp[0],'LIKE', '%' . $exp[1] . '%', 'or' ]);
                          }
                          if($opr_odd[$binary_index]=="not"){
                             array_push($conditions,[ $exp[0],'!=', $exp[1] ]);
                          }
                          if($opr_odd[$binary_index]=="xor"){
                             array_push($conditions,[ $exp[0],'LIKE', '%' . $exp[1] . '%', 'xor' ]);
                          }
                      }
                   }   
              } 
        }
        //------------------------------------------------------------------------------------------------
        /*
        VALID FORMAT
        count expression = count total_equal = total_column
        */

        $expression = count($expression) - count($opr_odd);

        if(($total_equal == $total_col) && ($total_col == $expression) ){
           
            $custom_srch_advance = DB::table('documents')
            ->where('doc_user_id',Auth::user()->id)
            ->where('is_archive',1)
            ->where($conditions)
            ->when($tax_relevant, function ($query, $tax_relevant) use ($tax_opr,$tax_bin_opr) {
                if($tax_bin_opr=="and" || $tax_bin_opr==null){
                  return $query->whereYear('created_at',$tax_opr,$tax_relevant);
                }
                else{
                  return $query->whereYear('created_at',$tax_opr,$tax_relevant,$tax_bin_opr);
                }  
            })->pluck('doc_id')->toArray();
      

            return $this->getDocumentsDetails($custom_srch_advance);
   
        }else{
           return "invalid_format";
        }


    }










}
