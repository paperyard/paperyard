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


class foldersController extends Controller
{
    // FOLDER VIEWS
    public function index(){

      $folder_stat = DB::table('folders')->where('folder_user_id', Auth::user()->id)->get();
      return view('pages/folders')->with(compact('folder_stat'));
    }


    public function returnFolders(){

            try{

              $folders = DB::table('folders')->where('folder_user_id', Auth::user()->id)
              ->leftJoin('documents', 'folders.folder_id','=','documents.doc_folder_id')
              ->leftJoin('document_pages', 'documents.doc_id', 'document_pages.doc_id')
              ->select('folders.folder_name',
                       'folders.folder_id',
                       'folders.folder_user_id',
                       'folders.created_at as folder_date_created',
                       'documents.doc_id',
                       'documents.doc_org',
                       'documents.approved',
                       DB::raw('max(document_pages.doc_page_thumbnail_preview) as thumb'),
                       DB::raw('count(documents.doc_folder_id) as total_c'),
                       DB::raw('max(documents.doc_id) as latest_id'),
                       DB::raw('max(documents.created_at) as latest_date')
                       )
              ->groupBy('folders.folder_name')
              ->get();

                if(count($folders)>=1){
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

                $file1 = 'static/documents_new/' .        $dd->doc_org;
                File::delete((string)$file1);
                $file2 = 'static/documents_processing/' . $dd->doc_prc;
                File::delete((string)$file2);
                $file3 = 'static/documents_ocred/' .      $dd->doc_ocr;
                File::delete((string)$file3);
            }

            // DELETE IMAGE PREVIEWS
            $docs_pp = DB::table('document_pages')->whereIn('doc_id', $doc_idz)->get();
            foreach($docs_pp as $dp){
                $file1 = 'static/documents_images/' . $dp->doc_page_image_preview;
                File::delete((string)$file1);
                $file2 = 'static/documents_images/' . $dp->doc_page_thumbnail_preview;
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
        $json_response = json_encode(array('archive_docs' => $archive_docs,'folders'=>$folders));
        // # Return the response
        return $json_response;

    }


    public function serverTest(){

         // DASHBOARD
         // $test  = DB::table('documents')
         // ->select(
         //   'documents.created_at',
         //    DB::raw('count(`doc_id`) as documents'),
         //    DB::raw('DAYNAME(`created_at`) as day')
         // )
         // ->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(),\Carbon\Carbon::now()->endOfWeek()])
         // ->groupBy(DB::raw('WEEKDAY(created_at)'))
         // ->get();

         // var_dump($test);


         // SEARCH

         // $years = DB::table('documents')
         // ->select(
         //     DB::raw('count(`doc_id`) as num_of_documents'),
         //     DB::raw('MONTHNAME(`created_at`) as months'),
         //     DB::raw('YEAR(created_at) as year')
         // )
         // ->groupBy(DB::raw('MONTH(created_at)'))
         // ->groupBy(DB::raw('YEAR(created_at)'))
         // ->get();

         // $year_range = [];
         // //get years
         // $years = DB::table('documents')
         // ->select(DB::raw('YEAR(created_at) as year'))
         // ->groupBy(DB::raw('YEAR(created_at)'))
         // ->get();

         // foreach($years as $year){
         //      array_push($year_range, $year->year);
         // }

         // foreach($year_range as $yr){
         //      echo $yr . "<br>";
         // }

         // var_dump($years);


        // $img = Image::make('static/documents_images/2-1-0IH1l.png');

        // // rotate image 45 degrees clockwise
        // $img->rotate(+90);
        // $img->save('static/documents_images/xxxx-1.png');

        // echo "success";

        $folder_id = 1;
        $qwe = $this->returnResult($folder_id);

    }

    public function returnResult($param){
        $test = DB::table('documents')
        ->where('doc_folder_id', $param)
        ->select('documents.doc_ocr')
        ->get();

        return $test;
    }



}
