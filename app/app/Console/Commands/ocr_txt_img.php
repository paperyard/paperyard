<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
use DB;
use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Image;
use Mail;
use App\Mail\sendNotification;


class ocr_txt_img extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:txt_img';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'extract images and text content from PDF to be used for displaying and searching keywords';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // GET DOCUMENTS WITH OCR
        $time = time();
        $documents = DB::table('documents')->where([
            ['t_process', '<=', $time]
        ])
        ->whereIn('process_status', ['ocred','failed_force'])
        ->get();

        if(count($documents)>0){

                // get documents ids to process.
                $prcs = [];
                foreach($documents as $p){
                  array_push($prcs, $p->doc_id);
                }
                // CHANGE STATUS FOR IMAGE CONVERTION
                DB::table('documents')
                ->whereIn('doc_id', $prcs)
                ->update(['process_status' => 'final_process']);

                foreach($documents as $d){


                   //check if file exist
                    $filename = 'storage/app/documents_ocred/' . $d->doc_ocr;

                    if (file_exists($filename)) {

                            $datas = [];
                            //convert pdf pages to images.
                            //get text from images
                            //save to database
                            $pdf = new Pdf($filename);
                            $num_p = $pdf->getNumberOfPages();

                                for($pageNum = 1; $pageNum<=$num_p; $pageNum ++){
                                    //page name.
                                    $str_r = str_random(5);
                                    $page_name = $d->doc_id . '-' . $pageNum . '-' . $str_r . '.png';
                                    $page_thumbnail_name = $d->doc_id . '-' . $pageNum . '-' . $str_r . '-' . 'thumbnail' . '.png';
                                    //page location.
                                    $page_loc = 'storage/app/documents_images/' . $page_name;
                                    $page_thumb_loc = 'storage/app/documents_images/' . $page_thumbnail_name;
                                    //convert pdf to page number.
                                    $pdf->setPage($pageNum)->saveImage($page_loc);
                                    //get text from converted image.
                                    $page_text = (new TesseractOCR($page_loc))->run();
                                    //make image thumbnail
                                    $image = Image::make($page_loc);
                                    $image->resize(300, 420);
                                    $image->save($page_thumb_loc);

                                    //save database.
                                    $data = array(
                                        "doc_id"=>$d->doc_id,
                                        "doc_page_num"=>$pageNum,
                                        "doc_page_image_preview"=>$page_name,
                                        "doc_page_thumbnail_preview"=>$page_thumbnail_name,
                                        "doc_page_text"=>$page_text,
                                        "created_at"=>\Carbon\Carbon::now(),
                                        "updated_at"=>\Carbon\Carbon::now()
                                    );

                                    array_push($datas, $data);
                                }
                                    //save & update, save doc pages -----------------------------------------------------------------
                                    try{


                                        // save doc page. check if success
                                        $save_doc_page = DB::table('document_pages')->insert($datas);


                                        if(count($save_doc_page)>=1){
                                             //save success.

                                             //check for barcodes---------------------------------------------------------
                                             //check if user has notifications for this document -------------------------
                                             $this->CheckNotifications($d->doc_user_id, $d->doc_id, $d->doc_ocr);

                                        }

                                    } catch(\Illuminate\Database\QueryException $err){
                                     echo "error saving datas";
                                     // Note any method of class PDOException can be called on $err.
                                    }
                                    //save & update, save doc pages ----------------------------------------------------------------------

                            // === UPDATE PROCESS STATUS ===============================================================================================================

                            try {

                                // check for barcodes in pdf pages, return pages of detected pages with barcode
                                $br_pages = $this->barcodeSeparator($datas,$d);
                                $current_num_pages = $this->countNumberOfPages($d->doc_ocr);


                                // no barcode detected | update process status 
                                if(count($br_pages)==0){

                                    $this->updatePRstatus($d);

                                }


                                // remove pages from document | update process status 
                                if( count($br_pages) < $current_num_pages && count($br_pages) > 0 ){

                                    $this->removeDocPages($d->doc_ocr, $br_pages);

                                    $this->updatePRstatus($d); 

                                }

                                // if detected doc pages with barcode == document pages.
                                // delete entire doc
                                if( count($br_pages) == $current_num_pages ){

                                    // delete document
                                    $file_del = "storage/app/documents_ocred" . "/" . $d->doc_ocr;  
                                    File::delete((string)$file_del);
                                    // delete document datas from database
                                    DB::table('documents')->where("doc_id", $d->doc_id)->delete();

                                }




                            } catch(\Illuminate\Database\QueryException $err){
                             echo "error updating status";
                             // Note any method of class PDOException can be called on $err.
                            }

                            // === UPDATE PROCESS STATUS =============================================================================================================== 



                    } //if file exist

                }//foreach

        }//end if count

    } 
    
    
    public function CheckNotifications($doc_user_id, $doc_id, $doc_ocr){

            //doc_user_id
            //doc_id

            //get notifications of this user.
            $user_notifications = DB::table('notifications')
            ->where('notif_user_id', $doc_user_id)
            ->get();
            //loop through the notifications keyword execution.
            if(count($user_notifications)>=1){

                foreach($user_notifications as $notify){
                    //string notif_keywords to array convert
                    $keywordsArray = explode(",", $notify->notif_keywords);
                    // find if notification keyword has match in doc page text.
                    $match = DB::table('document_pages')
                    ->where('doc_id','=',$doc_id)
                    ->Where(function ($query) use($keywordsArray) {
                     for ($i = 0; $i < count($keywordsArray); $i++){
                            $query->orwhere('doc_page_text', 'like',  '%' . $keywordsArray[$i] .'%');
                         }
                    })
                    ->get();
                    //if match found something.
                    if(count($match)>=1){

                               //execute notifications actions.
                               //ACTIONS [tax_relevent,tags,send mail]
                               if($notify->tax_relevant==1){
                                    DB::table('documents')->where([
                                       ['doc_id', '=', $doc_id]
                                    ])->update(['tax_relevant'=>'on']);
                               }
                               if($notify->tags!=null){
                                    DB::table('documents')->where([
                                       ['doc_id', '=', $doc_id]
                                    ])->update(['tags'=>$notify->tags]);
                               }
                               if($notify->send_email==1){
                                    //send email
                                    //save database.
                                    $se_data = array(
                                        "subject"       =>  $notify->se_subject,
                                        "receiver_name" =>  $notify->se_receiver_name,
                                        "message"       =>  $notify->se_message,
                                        "document_name" =>  $doc_ocr,
                                        "keywords"      =>  $notify->notif_keywords
                                    );

                                    Mail::to($notify->se_receiver_email)->send(new sendNotification($se_data));
                               }
                               //====== Save notification history ========
                                //get set user timezone
                                $user_timezone = DB::table('users')->where('id', $doc_user_id)->select('user_timezone')->first();
                                date_default_timezone_set($user_timezone->user_timezone);

                                DB::table('notifications_history')
                                ->insert([
                                    'notification_id'=>$notify->notif_id,
                                    'notification_user_id'=>$notify->notif_user_id,
                                    "created_at" =>  \Carbon\Carbon::now(),
                                    "updated_at" =>  \Carbon\Carbon::now()
                                ]);
                               //====================

                    } // if notification keywords has match found in document.
                } // foreach loop notification

            } // if user has notification.

    }




    // ====================== BARCODE ===============================================================================


    // #1
    public function barcodeSeparator($doc_pages_datas,$doc_data){



        // DB::table('documents')->where('doc_id', $doc_data->doc_id)->update(['process_status'=>'barcode_check']);
        //doc_pages_datas['key']
        //doc_data->key

        $detected_barcode_pages_num = [];

        foreach($doc_pages_datas as $key=>$doc_page){

            // check each doc page for barcode     
            $document_page = storage_path('/app/documents_images') . "/" . $doc_page['doc_page_image_preview'];

            // check if document page has a barcode
            $barCode = $this->checkForBarcodes($document_page);

            if($barCode){

                // store document page with barcode.. will be deleted later
                array_push($detected_barcode_pages_num, $doc_page['doc_page_num']);

                // ocred document to be processed-----------------------------------------
                $process_doc = "storage/app/documents_ocred" . "/" . $doc_data->doc_ocr;
                
                // temp extracted page to pdf
                $temp_doc_name = time() ."-". str_random(8) . ".pdf";
                $temp_doc    = "storage/app/documents_ocred" . "/" . $temp_doc_name;
                //--------------------------------------------------------------------------

                $this->getPageMakePdf($process_doc, $temp_doc, $doc_page['doc_page_num']);

                //#######################################################################

                // check if barcode exist in other documents
                // process status
                $barcode_exist = DB::table('documents')->where('barcode', $barCode)->first();

                // append --------------------------
                if(!empty($barcode_exist->barcode)){

                    $this->appendPageToDocument($barcode_exist, $temp_doc, $doc_page);

                }
                // new documents -------------------
                else{

                    // make new documents | return inserted id ----------
                    $inserted_id    =   $this->newDocument($doc_data,$barCode,$temp_doc_name); 

                    // update document page set id to new inserted id  ----------
                    $this->newDocumentPage($doc_page,$inserted_id);

                    //update process status
                    $new_doc_data = DB::table('documents')->where('doc_id', $inserted_id)->first();
                    $this->updatePRstatus($new_doc_data);

                }

            }
        }
        #==== REMOVE PAGES - DELETE DOCUMENT =====

        return $detected_barcode_pages_num;



    }

    // #5 save new document with barcode that has no matched doc barcode
    public function newDocument($doc_datas,$barcode,$temp_name){


        $new_doc = DB::table('documents')->insertGetId(
            [
                "doc_user_id"    =>     $doc_datas->doc_user_id,
                "doc_org"        =>     "nothing",
                "doc_prc"        =>     "nothing",
                "doc_ocr"        =>     $temp_name,
                "t_process"      =>     $doc_datas->t_process,
                "process_status" =>     "final_process",
                "is_ocred"       =>     $doc_datas->is_ocred,
                "origin"         =>     $doc_datas->origin,
                "barcode"        =>     $barcode,
            ]
        );

        // return inserted id 
        return $new_doc;
    }


    // #8 
    // update process status 
    // count number of pages 
    public function updatePRstatus($doc_data){


        if($doc_data->is_ocred==1){
            DB::table('documents')->where([
               ['doc_id', '=', $doc_data->doc_id]
            ])->update(['process_status'=>'ocred_final']);
        }else{
            DB::table('documents')->where([
               ['doc_id', '=', $doc_data->doc_id]
            ])->update(['process_status'=>'ocred_final_failed']);
        }
        
        //get total pages of document
        $total_doc_pages = DB::table('document_pages')->where('doc_id', $doc_data->doc_id)->pluck('doc_id');

        //update total number of pages
        $update_doc_pages = DB::table('documents')->where('doc_id', $doc_data->doc_id)->update(['number_of_pages'=>count($total_doc_pages)]);

    }


    // #7
    // append doc page with barcode to exising document to match document barcode.
    public function appendPageToDocument($parent_doc, $temp_doc, $doc_page){


         // temp append to => doc1 

        $doc1       = "storage/app/documents_ocred" . "/" . $parent_doc->doc_ocr;   

        $output_doc = 'storage/app/documents_ocred' . '/' . str_random(10) . ".pdf";
            
        $params = "pdftk $doc1 $temp_doc cat output $output_doc && mv $output_doc $doc1";
        $process = new Process($params);
        $process->enableOutput();
        $process->setTimeout(86400);
        $process->start();
        $process->wait();


        $parent_pages = DB::table('document_pages')->where("doc_id", $parent_doc->doc_id)->count();

        DB::table('document_pages')->where([
            ["doc_page_num", "=",  $doc_page['doc_page_num'] ],
            ["doc_id",       "=",  $doc_page['doc_id']]
        ])->update([
            "doc_id"       =>  $parent_doc->doc_id,
            "doc_page_num" =>  $parent_pages+1,
        ]);


        // remove temp doc
        File::delete((string)$temp_doc);

    }


    // remove document pages 
    public function removeDocPages($doc, $br_pages){


        $docLocal = "storage/app/documents_ocred" . "/" . $doc;
        $doc_output =  storage_path('app/documents_ocred') . '/' . "output" . str_random(8) . ".pdf";
        // get document total pages.
        $max_page = $this->countNumberOfPages($doc);

        // make array of pages with max page as max num.
        $maxPage = range(1, $max_page);

        //this page will be left from document.
        $safe_page =   array_diff($maxPage, $br_pages);
        $safe_pages =  implode(" ",$safe_page);

        //remove pages
        $flags = "pdftk $docLocal cat $safe_pages output $doc_output && mv $doc_output $docLocal";
        $process = new Process($flags);
        $process->disableOutput();
        $process->start();
        $process->wait();

    }



    // #6 update document page
    public function newDocumentPage($doc_page, $inserted_id){

        DB::table('document_pages')->where([
            ["doc_page_num", "=",  $doc_page['doc_page_num'] ],
            ["doc_id",       "=",  $doc_page['doc_id']]
        ])->update([
            "doc_id"       =>  $inserted_id,
            "doc_page_num" =>  1
        ]);

    }


    // #4
    // get document page that contains barcode, make new pdf
    public function getPageMakePdf($doc, $temp_name, $doc_page_num){

        $params  = "pdftk $doc cat $doc_page_num output $temp_name"; 

        $process = new Process($params);

        $process->enableOutput();
        
        $process->setTimeout(86400);
        
        $process->start();
        
        $process->wait();

    }



    // #3
    /* 
    @params $doc->ocr | ocred document 
    */
    public function countNumberOfPages($doc){

        $filename = 'storage/app/documents_ocred/' . $doc;

        $pdf = new Pdf($filename);

        return $pdf->getNumberOfPages();

    }


    // #2 
    // check doc page for barcode
    /*
       @params document page | $doc_page['doc_page_image_preview']
    */
    public function checkForBarcodes($document_page){

        $params  = "zbarimg $document_page -q"; 

        $process = new Process($params);

        $process->enableOutput();
        
        $process->setTimeout(86400);
        
        $process->start();
        
        $process->wait();

        return $process->getOutput();

    }

    // ====================== BARCODE ===============================================================================


  
}
