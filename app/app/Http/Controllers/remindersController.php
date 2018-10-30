<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;


class remindersController extends Controller
{
    // return list of reminders
    public function index(){
    	   $reminder_exist = DB::table('reminders')->where('rm_user_id', Auth::user()->id)->get();
    	   return view('pages/reminders')->with(compact('reminder_exist'));
    }

    // edit reminder\
    public function editReminder($reminder_id){
        
        $rm_id = DB::table('reminders')
        ->where('rm_user_id', Auth::user()->id)
        ->where('rm_id', $reminder_id)
        ->select('rm_id')
        ->first();

        if(count($rm_id)>0){
             return view('pages/reminders_edit')->with(compact('rm_id'));
        }
    }

    // request for reminders
    public function getReminders(){
         $reminder = DB::table('reminders')
         ->where('rm_user_id', Auth::user()->id)
         ->join('documents','reminders.rm_doc_id','=','documents.doc_id')
         ->select('reminders.*','documents.reminder')
         ->orderBy('reminders.rm_id', 'desc')
         ->get();

         foreach($reminder as $rm){
                $get_tasks = DB::table('reminders_tasks')
                ->where('reminder_id',$rm->rm_id)
                ->get();

                foreach($get_tasks as $task){
                    $task_status  = $task->task_complete==1? true:false;
                    $task->select = $task_status;
                }
                $rm->task_list = $get_tasks; 

                $n_date = new \DateTime($rm->reminder);
                $short_date = date_format($n_date,"d.m.Y");
                $rm->reminder = $short_date;
         }

         return  json_encode($reminder);
    }

    //get reminders 
    public function getToEditReminder(Request $req){

        $reminder = DB::table('reminders')
        ->where('rm_user_id', Auth::user()->id)
        ->where('rm_id', (int)$req->rm_id)
        ->first();

        $get_tasks = DB::table('reminders_tasks')
        ->where('reminder_id',$reminder->rm_id)
        ->get();

        foreach($get_tasks as $task){
            $task_status  = $task->task_complete==1? true:false;
            $task->select = $task_status;
        }

        $json_response = json_encode(array('reminder_id'=>$reminder->rm_id,'reminder_title'=>$reminder->rm_title,'task_list'=>$get_tasks));
        return  $json_response;
    }

    public function getTaskListForDocView(Request $req){

        $reminder = DB::table('reminders')
        ->where('rm_user_id', Auth::user()->id)
        ->where('rm_doc_id', $req->rm_id)
        ->first();

        $get_tasks = DB::table('reminders_tasks')
        ->where('reminder_id',$reminder->rm_id)
        ->get();

        foreach($get_tasks as $task){
            $task_status  = $task->task_complete==1? true:false;
            $task->select = $task_status;
        }

        $json_response = json_encode(array('reminder_id'=>$reminder->rm_id,'reminder_title'=>$reminder->rm_title,'task_list'=>$get_tasks));
        return  $json_response;
    }

    // return create reminder page
    public function newReminder(){
    	return view('pages/reminders_create');
    }

    // save reminder
    public function makeReminder(Request $req){
        //save reminder
        try {
            date_default_timezone_set(Auth::user()->user_timezone);
            $save_reminder = DB::table('reminders')
            ->insertGetId([
                 'rm_user_id'=>Auth::user()->id,
                 'rm_doc_id'=>$req->reminder_doc_id,
                 'rm_title'=>$req->reminder_title,
                 'created_at' =>  \Carbon\Carbon::now(), # \Datetime()
                 'updated_at' =>  \Carbon\Carbon::now(),  # \Datetime()
            ]);
            //if succcess save task list
            if($save_reminder>0){
                try {
                    //create new key value pair for reminder_id
                    $arr_tasks = $req->reminder_tasks;
                    foreach($arr_tasks as $key=>$task){
                           $arr_tasks[$key]['reminder_id'] = $save_reminder;
                    }
                    //save task list 
                    $new_task = DB::table('reminders_tasks')
                    ->insert($arr_tasks);

                    session()->flash('reminder_created', 'New reminder created.');

                    //success
                    return "success_task_saved";
                    //--------------------------
                }catch(\Illuminate\Database\QueryException $err){
                   // Note any method of class PDOException can be called on $err.
                    return "error_tasks_not_save";
                }
            }
        }catch(\Illuminate\Database\QueryException $err){
           // Note any method of class PDOException can be called on $err.
            return "error_reminder_not_saved";
        }     

    }

    // delete reminder
    public function deleteReminder(Request $req){
            
            $del_reminder = DB::table('reminders')
            ->where('rm_user_id', Auth::user()->id)
            ->where('rm_id', $req->rm_id)
            ->delete();

            if(count($del_reminder)>0){
                $del_task = DB::table('reminders_tasks')
                ->where('reminder_id', $req->rm_id)
                ->delete();
                return "reminder_deleted";
            }
    }

    // update reminder
    public function updateReminder(Request $req){
        // update reminder title
        $upd_reminder = DB::table('reminders')
        ->where('rm_user_id', Auth::user()->id)
        ->where('rm_id', $req->reminder_id)
        ->update(['rm_title'=>$req->reminder_title]);   

        if(count($upd_reminder)>0){

            $arr = $req->reminder_tasks;
            foreach($arr as $key=>$task){
                //if task id exist update name
                if(isset($task['task_id'])){
                    $upd = DB::table('reminders_tasks')
                    ->where('task_id', $task['task_id'])
                    ->update(['task_name'=>$task['task_name'] ]);
                }
                //else it is a new task. save
                else{
                    $save = DB::table('reminders_tasks')
                    ->insert([
                        'reminder_id'=>$req->reminder_id, 
                        'task_name'=>$task['task_name'] 
                    ]);
                }
            }

            if(isset($req->reminder_tasks_delete)){
                $remove_tasks = DB::table('reminders_tasks')->whereIn('task_id', $req->reminder_tasks_delete)->delete();
            }

            return "reminder_updated";
        }

    }

    public function taskComplete(Request $req){
        $update_task = DB::table('reminders_tasks')
        ->where('task_id', $req->task_id)
        ->update(['task_complete'=>$req->task_status]);
    }   

    // ====================== REMINDERS SEARCH ===============================================================================================



     // return autocomplete
    public function autoComplete(Request $req){


        // get folder names like keyword----------------------------------------------------------------
        $folders = DB::table('folders')
        ->where('folder_user_id', Auth::user()->id)
        ->where('folder_name', 'LIKE', '%' . $req->doc_keyword . '%')
        ->select('folders.folder_name')
        ->get();

        $folders = count($folders)>0?$folders:"not_found";

        //get tags like keyword --------------------------------------------------------------------------
        $array_tags = [];
        $tags = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->where('tags', 'LIKE', '%' . $req->doc_keyword . '%')
        ->select('documents.tags')
        ->get();

        if(count($tags)>0){
            foreach($tags as $key=>$t){
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
 
        //get document text like keyword -------------------------------------------------------------------
        $filter_text = [];
        //get user documents ids
        $user_doc = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->select('documents.doc_id')->get();

        if(count($user_doc)>0){
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

        //return tags,folders,fulltexts.---------------------------------------------------------------------------
        $json_response = json_encode(array('folders'=>$folders,'tags'=>$unique_array_tags,'fulltext'=>$clean_text));
        // # Return the response
        return $json_response;
    }

    //====================================================================================================================

    // search for documents based on filter
    public function searchDocuments(Request $req){

        // $req->doc_keyword
        // $req->doc_filter
   
        //return all documents when no keyword specified
        if($req->doc_filter=="no_filter" && empty($req->doc_keyword)){
            $docs = $this->get_documents_details();
        }

        // search documents
        if($req->doc_filter=="no_filter" && !empty($req->doc_keyword)){
             
             $oprs = ['=','>','<'];
             if(count(array_intersect($oprs,str_split($req->doc_keyword)))>0){
                //run customsearch
                $bin_operators = ['and','or','not','xor'];
                $kw = explode(" ",$req->doc_keyword);
                if(count(array_intersect($bin_operators, $kw))>0){
                     $docs = $this->advance_customSearch($req->doc_keyword);
                }else{
                     $docs = $this->basic_customSearch($req->doc_keyword);
                }
             }else{
                //run basic search
                $docs = $this->no_filter_search($req->doc_keyword);
            }
        }

        if($req->doc_filter=="folder"){
            $docs = $this->folders_search($req->doc_keyword);
        }
        
        if($req->doc_filter=="tag") {
            $docs = $this->tags_search($req->doc_keyword);
        }

        if($req->doc_filter=="fulltext"){
            $docs = $this->fulltext_search($req->doc_keyword);
        }


        if(count($docs)>0 && $docs!="invalid_format"){
            // generate download format
            $docs = $this->generateDownloadFormat($docs);
            // change date format based on client need
            $docs = $this->newDateFormat($docs);
            // get documents images
            $docs = $this->getDocumentsImages($docs);
             
            $json_response = json_encode($docs);
            return $json_response;
        }
        else if($docs=="invalid_format"){
            return "invalid_format";
        }
        else{
            return "error";
        }
    }


    //====================================================================================================================

    public function getDocumentsImages($docs){
          foreach($docs as $doc){
                $doc_images = DB::table('document_pages')
                ->where('doc_id',$doc->doc_id)
                ->select('doc_page_image_preview')
                ->get();

                $doc->image_list = $doc_images;
          }
          return $docs;
    }

    // return documents based on keyword
    public function no_filter_search($keyword){

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
         //return documents with status
        $pr_status = ['ocred_final','ocred_final_failed']; 
        // remove duplicate ids
        $find_all_ids = array_unique($docs_ids);

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->whereNotNull('reminder')  
        ->whereIn('documents.doc_id',$find_all_ids)  
        ->whereIn('process_status', $pr_status)
        ->leftJoin('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->leftJoin('folders','documents.doc_folder_id','=','folders.folder_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.approved',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.reminder',
            'documents.tax_relevant',
            'documents.process_status',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name'
        )
        ->orderBy('doc_id','desc')
        ->get();

        return $documents;

    }

    //====================================================================================================================

    // return documents details using array of ids
    public function get_documents_details(){
        
        //return documents with status
        $pr_status = ['ocred_final','ocred_final_failed']; 
        // remove duplicate ids
      
        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1)
        ->whereNotNull('reminder')    
        ->whereIn('process_status', $pr_status)
        ->leftJoin('document_pages','documents.doc_id','=','document_pages.doc_id')
        ->leftJoin('folders','documents.doc_folder_id','=','folders.folder_id')
        ->groupBy('document_pages.doc_id')
        ->select(
            'documents.doc_id',
            'documents.doc_ocr',
            'documents.sender',
            'documents.receiver',
            'documents.approved',
            'documents.date',
            'documents.tags',
            'documents.category',
            'documents.reminder',
            'documents.tax_relevant',
            'documents.process_status',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name'
        )
        ->orderBy('doc_id','desc')
        ->get();

        return $documents;

    }

   //=============================================== SELECT AUTOCOMPLETE SEARCH ===========================================

    // return documents with tags filter
    public function tags_search($keyword){

        $pr_status = ['ocred_final','ocred_final_failed']; 
        
        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->where('tags', 'LIKE', '%' . $keyword . '%')
        ->whereIn('process_status', $pr_status)
        ->whereNotNull('reminder')  
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
            'documents.process_status',
            'documents.approved',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name'
        )
        ->orderBy('doc_id','desc')
        ->get();
        
        return $documents;
    }

    // return documents with folder filter
    public function folders_search($keyword){

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
            ->whereNotNull('reminder')    
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
                'documents.process_status',
                'documents.approved',
                'documents.note',
                'documents.origin',
                'document_pages.doc_page_image_preview',
                'document_pages.doc_page_thumbnail_preview',
                'folders.folder_name'
            )
            ->orderBy('doc_id','desc')
            ->get();
            
            //documents found in folder
            return $documents;
        }else{
            return $folderID;;
        }

    }

    // return documents with specified fulltext
    public function fulltext_search($keyword){

        $pr_status = ['ocred_final','ocred_final_failed']; 

        $documents = DB::table('documents')
        ->where('doc_user_id', Auth::user()->id)
        ->where('is_archive', 1) 
        ->whereIn('process_status', $pr_status)
        ->whereNotNull('reminder')     
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
            'documents.process_status',
            'documents.approved',
            'documents.note',
            'documents.origin',
            'document_pages.doc_page_image_preview',
            'document_pages.doc_page_thumbnail_preview',
            'folders.folder_name' 
        )
        ->orderBy('doc_id','desc')
        ->get();
        
        return $documents;

    }

    //================================================ FUNCTIONS OTF ========================================================

    // format document date based on client needs.
    public function newDateFormat($datas){
        
        // format date
        foreach($datas as $d){

            if($d->date!=null){
                $n_date = new \DateTime($d->date);
                $short_date = date_format($n_date,"d.m.Y");
                $d->date = $short_date;
            }
            if($d->reminder!=null){
                $n_date = new \DateTime($d->reminder);
                $short_date = date_format($n_date,"d.m.Y");
                $d->reminder = $short_date;
            }
        }
        return $datas;
    }

    // generate download format on the fly
    public function generateDownloadFormat($datas){

        $format = "";
        $ext    = ".pdf";
        $dash   = "-";
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


    //============================================  CUSTOM SEARCH =========================================================
    
    // custom search for documents eg.  sender=john. 
    public function basic_customSearch($keyword){


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
           
           return $this->get_documents_details($custom_srch_basic);
    
        }else{
           return "invalid_format";
        }

    }

    // custom search with binary operations sender=John or receiver=Till
    public function advance_customSearch($keyword){

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
                  }  

              }
              // ---------------------------------------------------------------------------------------------
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
      
           return $this->get_documents_details($custom_srch_advance);
     
        }else{
           return "invalid_format";
        }
 
    }

}