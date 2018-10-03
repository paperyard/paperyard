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
use App\Mail\sendNotification;


// cache controller composer dump-autoload
class foldersController extends Controller
{
    // FOLDER VIEWS
    public function index(){

      $folder_stat = DB::table('folders')->where('folder_user_id', Auth::user()->id)->get();
      return view('pages/folders')->with(compact('folder_stat'));
    }


    public function returnFolders(){

            try{
                /*
                   get users folders, 
                   count number of documents inside folder,
                   get latest id inside folder, get latest date of docs
                */
                $folders = DB::table('folders')->where('folder_user_id', Auth::user()->id)
                ->leftJoin('documents', 'folders.folder_id','=','documents.doc_folder_id')
                // ->leftJoin('document_pages', 'documents.doc_id', 'document_pages.doc_id')
                ->select('folders.folder_name',
                       'folders.folder_id',
                       'folders.folder_user_id',
                       'folders.created_at as folder_date_created',
                       'documents.doc_id',
                       'documents.doc_org',
                       'documents.approved',
                       // DB::raw('max(document_pages.doc_page_thumbnail_preview) as thumb'),
                       DB::raw('count(documents.doc_id) as total_c'),
                       DB::raw('max(documents.doc_id) as latest_id'),
                       DB::raw('max(documents.created_at) as latest_date')
                       )
                ->groupBy('folders.folder_name')
                ->get();

                if(count($folders)>0){

                    foreach($folders as $key=>$f){
                        //change date format
                        $n_date = new \DateTime($f->latest_date);
                        $short_date = date_format($n_date,"y.m.d");
                        $f->short_date = $short_date;
                        //get image thumbnail for each latest document 
                        if($f->latest_id!=null && $f->latest_id != ""){
                            $doc_thumbnail = DB::table('document_pages')
                            ->where('document_pages.doc_id', (int)$f->latest_id)
                            ->select('doc_page_thumbnail_preview','doc_page_num','doc_id')
                            ->orderBy('doc_page_num', 'DESC')
                            ->groupBy('doc_id')
                            ->first();
                            $f->thumb = $doc_thumbnail->doc_page_thumbnail_preview;
                        }else{
                            $f->thumb = null;
                        }
                        
                        $lc_datas = [0.1];
                        //select counts of documents each week for line chart
                        $docs = DB::table('documents')
                        ->where('doc_folder_id', $f->folder_id)
                        ->select(DB::raw('count(`doc_id`) as num_of_documents'))   
                        ->groupBy(DB::raw('WEEK(created_at)'))
                        ->groupBy(DB::raw('YEAR(created_at)'))
                        ->get();

                        if(count($docs)>0){
                            foreach($docs as $d){
                                array_push($lc_datas, $d->num_of_documents);
                            }
                            $f->line_chart_datas = $lc_datas;
                        }else{
                            $f->line_chart_datas = $lc_datas;
                        }

                    }

                    // $docs_linechart_datas = DB::table('')
                    $json_response = json_encode($folders);
                    return $json_response;
                }
                return "";
            } catch(\Illuminate\Database\QueryException $err){
             echo "error";
             // Note any method of class PDOException can be called on $err.
            }

    }

    //CREATE FOLDER
    public function newFolder(Request $req){
        try {

            // set user time zone.
            date_default_timezone_set(Auth::user()->user_timezone);

  	        DB::table('folders')->insert
  	         ([
  	            'folder_user_id'=>Auth::user()->id,
  	            'folder_name'=>$req->folder_name,
  	            'created_at' =>  \Carbon\Carbon::now(), # \Datetime()
  	            'updated_at' =>  \Carbon\Carbon::now(),  # \Datetime()
  	        ]);
  	        return "success";
        } catch(\Illuminate\Database\QueryException $err){
		     echo "error";
		     // Note any method of class PDOException can be called on $err.
		    }
    }

    //DELETE FOLDER
    public function deleteFolder(Request $req){
        try {

            // DELETE FOLDER
            DB::table('folders')->where([
               ['folder_id', '=', $req->folder_id],
               ['folder_user_id', '=', Auth::user()->id]
            ])->delete();

            // DELETE DOCUMENTS INSIDE FOLDER
            $doc_idz = [];
            $docs = DB::table('documents')->where('doc_folder_id', $req->folder_id)->get();

            foreach($docs as $dd){

                array_push($doc_idz, $dd->doc_id);

                $file1 = storage_path('app/documents_new') . '/' .        $dd->doc_org;
                File::delete((string)$file1);
                $file2 = storage_path('app/documents_processing') . '/' . $dd->doc_prc;
                File::delete((string)$file2);
                $file3 = storage_path('app/documents_ocred') . '/' .      $dd->doc_ocr;
                File::delete((string)$file3);
            }

            // DELETE IMAGE PREVIEWS
            $docs_pp = DB::table('document_pages')->whereIn('doc_id', $doc_idz)->get();
            foreach($docs_pp as $dp){
                $file1 = storage_path('app/documents_images') . '/' . $dp->doc_page_image_preview;
                File::delete((string)$file1);
                $file2 = storage_path('app/documents_images') . '/' . $dp->doc_page_thumbnail_preview;
                File::delete((string)$file2);
            }

            // DELETE DOCUMENTS FROM DATABASE
            DB::table('documents')->whereIn('doc_id', $doc_idz)->delete();
            DB::table('document_pages')->whereIn('doc_id', $doc_idz)->delete();

            return "success_deleted";

        } catch(\Illuminate\Database\QueryException $err){
         echo "error";
         // Note any method of class PDOException can be called on $err.
        }
    }

    //RENAME FOLDER
    public function renameFolder(Request $req){
        try {
            DB::table('folders')->where([
               ['folder_id', '=', $req->folder_id],
               ['folder_user_id', '=', Auth::user()->id]
            ])->update(['folder_name'=>$req->folder_name]);
            return "success_renamed";
        } catch(\Illuminate\Database\QueryException $err){
         echo "error";
         // Note any method of class PDOException can be called on $err.
        }
    }

    //OPEN FOLDER
    public function openFolder($folder_id){
         // prevent other users from opening other users folder using Auth.
         $user_folder = DB::table('folders')->where([
              ['folder_id', '=', $folder_id],
              ['folder_user_id', '=', Auth::user()->id]
         ])->select('folders.folder_name')->first();
         if(count($user_folder)>=1){
             return view('pages/inside_folder')->with(compact('user_folder','folder_id'));
         }else {
             return redirect('/');
         }
    }

    // return documents from specific folder
    public function folderDocuments(Request $req){

        // return folder for this user.
        $folders = DB::table('folders')->where('folder_user_id', Auth::user()->id)->select('folders.folder_id','folders.folder_name')->get();
        //return documents finished editing.
        if(count($folders)<=0){
        $folders = '';
        }
        $archive_docs = DB::table('documents')->where([
         ['doc_user_id','=',Auth::user()->id],
         ['process_status','=','ocred_final'],
         ['is_archive','=',1],
         ['doc_folder_id','=',$req->folder_id]
        ])->get();

        $archive_docs = $this->generateDownloadFormat($archive_docs);
        $json_response = json_encode(array('archive_docs' => $archive_docs,'folders'=>$folders));
        // # Return the response
        return $json_response;

    }

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

            if($d->date=="0000-00-00 00:00:00"){
                $d->date = "N/D";
            }else{
              $n_date = new \DateTime($d->date);
              $short_date = date_format($n_date,"d.m.Y");
              $d->date = $short_date;
            }
        }
        return $datas;  
    }





    public function serverTest(){
       

           $update = DB::table('users')->where('id', Auth::user()->id)
           ->update(['name'=>'John']);

           echo $update;
      
    }




}
