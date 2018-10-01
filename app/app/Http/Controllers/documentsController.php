<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
// storage/file facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use DB;

class documentsController extends Controller
{

   // VIEW DOCUMENT
   public function viewDocument($doc_id){
           
   	      // session()->put('lst_opened_doc', $doc_id);
   	      $document = DB::table('documents')->where([
             ['doc_id', '=', $doc_id],
             ['doc_user_id', '=', Auth::user()->id]
   	      ])->get();

   	      if(count($document)>0){

                  //STORE TO PREVIEWS VIEWD TABLE
                  $pv = DB::table('documents_viewed')->where([
                      ['view_user_id', '=', Auth::user()->id]
                  ])->count();
                  //if > 8 delete oldest;
                  if($pv>8){
                      $v_docs = DB::table('documents_viewed')->where('view_user_id', Auth::user()->id)->orderBy('view_id', 'asc')->first();
                      DB::table('documents_viewed')->where('view_id', $v_docs->view_id)->delete();
                  }else{
                      //save to previously viewed doc
                      DB::table('documents_viewed')->where([
                          ['view_user_id', '=', Auth::user()->id]
                      ])->insert([
                            'view_doc_id'=>$doc_id,
                            'view_user_id'=>Auth::user()->id,
                            "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
                            "updated_at" => \Carbon\Carbon::now(),  # \Datetime()
                      ]);
                  }
                  foreach($document as $d){
                      if($d->is_archive==1){
                        //convert DateTime to d.m.Y format
                        if($d->date=="0000-00-00 00:00:00"){
                            $d->date = "";
                        }else{
                          $n_date = new \DateTime($d->date);
                          $short_date = date_format($n_date,"d.m.Y");
                          $d->date = $short_date;
                        }

                        if($d->reminder=="0000-00-00 00:00:00"){
                            $d->reminder = "";
                        }else{
                          $n_date = new \DateTime($d->date);
                          $short_date = date_format($n_date,"d.m.Y");
                          $d->reminder = $short_date;
                        }

                      }  
                  }
                  // get document pages image
                  $document_pages = DB::table('document_pages')->where([
    		             ['doc_id', '=', $doc_id]
    		   	      ])->get();

                  // RETURN DOCUMENTS DATAS AND IMAGES
		              return view('pages/document_view')->with(compact('document','document_pages'));
   	      }else{
   	      	 return redirect('/dashboard');
   	      }
   }

  // DELETE DOCUMENT
  public function deleteDocument(Request $req){

             $doc = DB::table('documents')
             ->where('doc_user_id', Auth::user()->id)
             ->whereIn('doc_id', $req->doc_id)
             ->get();

             $doc_page = DB::table('document_pages')
             ->whereIn('doc_id', $req->doc_id)
             ->get();

             // DELETE DOCUMENTS FILES
             foreach($doc as $d){
             	   $file1 = storage_path('app/documents_new') . '/' . $d->doc_org;
                 File::delete((string)$file1);
                 $file2 = storage_path('app/documents_processing') . '/' . $d->doc_prc;
                 File::delete((string)$file2);
                 $file3 = storage_path('app/documents_ocred') . '/' . $d->doc_ocr;
                 File::delete((string)$file3);
             }
             // DELETE DOCUMENTS IMAGES
             foreach($doc_page as $dp){
                 $file1 = storage_path('app/documents_images') . '/' . $dp->doc_page_image_preview;
                 File::delete((string)$file1);
                 $file2 = storage_path('app/documents_images') . '/' . $dp->doc_page_thumbnail_preview;
                 File::delete((string)$file2);
             }
             // DELETE DOCUMENT FROM DATABASE
             DB::table('documents')
             ->where('doc_user_id', Auth::user()->id)
             ->whereIn('doc_id', $req->doc_id)
             ->delete();

             // DELETE DOCUMENT PAGES FROM DATABASE
             DB::table('document_pages')
             ->whereIn('doc_id', $req->doc_id)
             ->delete();

             return "deleted_01";
    }


    //approve document.
    public function approveDocument(Request $req){

             $approve = DB::table('documents')
             ->where([
                 ['doc_user_id','=',Auth::user()->id],
                 ['doc_id','=',$req->doc_id]
             ])->update(['approved'=>1]);

             if(count($approve)>=1){
                  //approve updated successfully.
                  //proceed deleteing original doc.
                  $file = storage_path('app/documents_new') . '/' . $req->doc_org;
                  File::delete((string)$file);
                  return "success";
             }

    }


    // UPDATE DOCUMENTS DATAS.
    public function updateDocument(Request $req){

        try {

          // set user time zone.
          date_default_timezone_set(Auth::user()->user_timezone);
          

          $req->reminder = $this->formatDate($req->reminder);
         

          $update = DB::table('documents')->where([
               ['doc_id', '=',$req->doc_id],
               ['doc_user_id', '=', Auth::user()->id]
            ])->update([
            	'sender'=>$req->doc_sender,
            	'receiver'=>$req->doc_receiver,
            	'date'=> $this->formatDate($req->doc_date),
            	'tags'=>$req->doc_tags,
            	'category'=>$req->doc_category,
            	'reminder'=>$this->formatDate($req->doc_reminder),
            	'tax_relevant'=>$req->doc_tax_r,
            	'note'=>$req->doc_notes,
              'is_archive'=>1
            ]);
          //if update is succes check if there is still doc to edit.
          if($update>0){
              //check if documents is ocred or ocred_failed
              $check_ocr = DB::table('documents')->where('doc_id', $req->doc_id)->select('is_ocred')->first();
              
              //return next ocred documents
              if($check_ocr->is_ocred==1){
                  $to_edit_docs = DB::table('documents')->where([
                     ['doc_user_id','=',Auth::user()->id],
                     ['is_archive','=',0],
                     ['is_ocred','=',1]
                  ])->select('documents.doc_id')->first();
                  if(count($to_edit_docs)>0){
                      return $to_edit_docs->doc_id;
                  }else{
                      return "nothing_to_edit";
                  }
              }
              //return next ocred failed document
              else{
                  $to_edit_docs = DB::table('documents')->where([
                     ['doc_user_id','=',Auth::user()->id],
                     ['is_archive','=',0],
                     ['is_ocred','=',0]
                  ])->select('documents.doc_id')->first();
                  if(count($to_edit_docs)>0){
                      return $to_edit_docs->doc_id;
                  }else{
                      return "nothing_to_edit";
                  }
              }

          }else{
              return "something went wrong";
          }

        } catch(\Illuminate\Database\QueryException $err){
           return "error";
         // Note any method of class PDOException can be called on $err.
        }

    }



    public function formatDate($date){

       if(!empty($date)){
          $d_date = $date;
          $dateTime = \DateTime::createFromFormat('d.m.Y', $d_date);
          $newDate = $dateTime->format('Y-m-d 00:00:00');
          return $newDate;
        }else{
          $newDate = "0000-00-00 00:00:00";
          return $newDate; 
        }
    }

    // share documents public
    public function shareDocument(Request $req){

        try {
            // update doc share bool to 1
            DB::table('documents')->where([
               ['doc_id', '=',$req->doc_id],
               ['doc_user_id', '=', Auth::user()->id]
            ])->update([
              'shared'=>1
            ]);

            // make share data.
            // default expiry 1 day.
            $expire = time()+86400;
            DB::table('shared_documents')->insert([
                'share_user_id' =>  Auth::user()->id,
                'share_hash'    =>  str_random(15),
                'share_doc_ids' =>  $req->doc_id,
                'share_expiry'  =>  $expire
            ]);

            return "shared";

        } catch(\Illuminate\Database\QueryException $err){
           return "error";
         // Note any method of class PDOException can be called on $err.
        }

    }

    // return edited documents
    public function archives(){
        return view('pages/documents_archive');
    }

    public function returnArchives(){
        // return folder for this user.
         $folders = DB::table('folders')->where('folder_user_id', Auth::user()->id)->select('folders.folder_id','folders.folder_name')->get();
        //return documents finished editing.
         if(count($folders)>0){

         }else{
            $folders = '';
         }
         
         $prs_stat = ['ocred_final','ocred_final_failed'];

         $archive_docs = DB::table('documents')->where([
             ['doc_user_id','=',Auth::user()->id],
             ['is_archive','=',1]
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

          $format = "";
          $ext = ".pdf";
          $dash = "-";
          $d_date = new \DateTime();
          $date   = date_format($d_date, "ymd");
          
          //download format
          $arrFormat = explode(',',Auth::user()->download_filename_format);
          foreach($archive_docs as $key=>$d){
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
            if($d->date=="0000-00-00 00:00:00"){
                $d->date = "N/D";
            }else{
              $n_date = new \DateTime($d->date);
              $short_date = date_format($n_date,"d.m.Y");
              $d->date = $short_date;
            }
          }
                 
          $json_response = json_encode(array('archive_docs' => $archive_docs,'folders'=>$folders));
          // # Return the response
          return $json_response;
    }


    public function moveFolders(Request $request){

        try {

           //update documents doc folder id.
           $move = DB::table('documents')
           ->where('doc_user_id', Auth::user()->id)
           ->whereIn('doc_id', $request->documents)
           ->update(['doc_folder_id'=>$request->folder]);

           return $move;

        }catch(\Illuminate\Database\QueryException $err){
           return "error";
          // Note any method of class PDOException can be called on $err.
        }

    }



}
