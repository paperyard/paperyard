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
    protected $description = 'Command description';
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
            ['t_process', '<=', $time],
            ['process_status', '=', 'ocred']
        ])->get();

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
                        $filename = 'public/static/documents_ocred/' . $d->doc_ocr;
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
                                $page_loc = 'public/static/documents_images/' . $page_name;
                                $page_thumb_loc = 'public/static/documents_images/' . $page_thumbnail_name;
                                //convert pdf to page number.
                                $pdf->setPage($pageNum)->saveImage($page_loc);
                                //get text from converted image.
                                $page_text = (new TesseractOCR($page_loc))->run();
                                //make image thumbnail
                                $image = Image::make($page_loc);
                                $image->resize(100, 150);
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
                            //save & update
                            //save doc pages
                            try {
                                // save doc page. check if success
                                $save_doc_page = DB::table('document_pages')->insert($datas);
                                if(count($save_doc_page)>=1){
                                     //save success.
                                     //check if user has notifications for this document
                                     $this->CheckNotifications($d->doc_user_id, $d->doc_id, $d->doc_ocr);
                                     //remove empty pages.
                                     $this->removeEmptyPages($prcs);

                                } //save doc success

                            } catch(\Illuminate\Database\QueryException $err){
                             echo "error saving datas";
                             // Note any method of class PDOException can be called on $err.
                            }

                            try {
                                DB::table('documents')->where([
                                   ['doc_id', '=', $d->doc_id]
                                ])->update(['process_status'=>'ocred_final']);

                            } catch(\Illuminate\Database\QueryException $err){
                             echo "error updating status";
                             // Note any method of class PDOException can be called on $err.
                            }

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
                                        "subject"=>$notify->se_subject,
                                        "receiver_name"=>$notify->se_receiver_name,
                                        "message"=>$notify->se_message,
                                        "document_name"=>$doc_ocr,
                                        "keywords"=>$notify->notif_keywords
                                    );

                                    Mail::to($notify->se_receiver_email)->send(new sendNotification($se_data));
                               }
                               //====== Save notification history ========
                                DB::table('notifications_history')
                                ->insert([
                                     'notification_id'=>$notify->notif_id,
                                     'notification_user_id'=>$notify->notif_user_id
                                ]);
                               //====================

                    } // if notification keywords has match found in document.
                } // foreach loop notification

            } // if user has notification.

    }

    public function removeEmptyPages($doc_ids){

                $rm_pages = DB::table('document_pages')
                ->whereIn('document_pages.doc_id', $doc_ids)
                ->where('doc_page_text', "")
                 ->join('documents', 'document_pages.doc_id', '=', 'documents.doc_id')
                 ->select(
                    'documents.doc_ocr',
                    'documents.doc_id',
                    'document_pages.doc_page_image_preview',
                    'document_pages.doc_page_thumbnail_preview',
                     DB::raw('group_concat(`doc_page_num`) as remove_pages')
                 )
                 ->groupBy('document_pages.doc_id')
                 ->get();

                //if doc with empty page found.
                if(count($rm_pages)>=1){
                    foreach($rm_pages as $key=>$rm){
                        //document ocr location
                        $doc_ocred =    'public/static/documents_ocred/'  . $rm->doc_ocr;
                        //output
                        $doc_output =   'public/static/documents_ocred/'  . "rm_output-" . str_random(5) . ".pdf";
                        // remove empty page from document ============================
                        // total number of pages..
                        $pdf = new Pdf($doc_ocred);
                        $max_page = $pdf->getNumberOfPages();

                        // doc pages for pdftk cat.
                        $doc_pages = range(1, $max_page);

                        //convert concatenated string to array
                        $arr = explode(",",$rm->remove_pages);

                        //doc_pages eg [1,2,3]  $arr[2,3]
                        $remove_pages = array_diff($doc_pages,$arr);
                        //safe pages
                        $safe_pages =  implode(" ",$remove_pages);
                        //use pdftk to remove doc page then ouput to new pdf. use && to move content of new pdf to orig pdf.
                        $params[$key] = "pdftk $doc_ocred cat $safe_pages output $doc_output && mv $doc_output $doc_ocred";

                        $process[$key] = new Process($params[$key]);
                        $process[$key]->disableOutput();
                        $process[$key]->start();
                        $process[$key]->wait();
                    }
                }

                //delete document images and thumbnail
                $deleteImages = DB::table('document_pages')
                ->whereIn('doc_id', $doc_ids)
                ->where('doc_page_text', "")
                ->select('document_pages.doc_page_image_preview','document_pages.doc_page_thumbnail_preview')
                ->get();

                foreach($deleteImages as $img){
                    //doc page image preview location
                    $doc_image =    'public/static/documents_images/' . $img->doc_page_image_preview;
                    File::delete((string)$doc_image);
                    //doc page image thumbnail location
                    $doc_thumbnail = 'public/static/documents_images/' . $img->doc_page_thumbnail_preview;
                    File::delete((string)$doc_thumbnail);
                }

                //delete doc pages from database.
                $deleteDatas = DB::table('document_pages')
                ->whereIn('doc_id', $doc_ids)
                ->where('doc_page_text', "")
                ->delete();


    }
}
