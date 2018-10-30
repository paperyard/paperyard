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

class ocr_force extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:force';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run when artisan ocr:new failed, execute command [decrypt,ocr_force,remove_blank_pages]';

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

      // get failed documents.
      $time = time();
      $documents = DB::table('documents')
      ->where("t_process", '<=', $time)
      ->where("process_status", "failed")
      ->get();

      // If failed documents found. rerun ocr with force
      if(count($documents)>0){

            // store failed docs ids to array
            $rerun_doc_ids = [];
            foreach($documents as $upd){
              array_push($rerun_doc_ids, $upd->doc_id);
            }
            // change status of "failed" docs to "rerun_failed" 
            DB::table('documents')
            ->whereIn('doc_id', $rerun_doc_ids)
            ->update(['process_status' => 'rerun_failed']);

            // rerun force ocr on each docs
            foreach($documents as $key=>$d){

                //location of original doc. ------------------------------------------------------------------------------------
                $document_src[$key] = "storage/app/documents_new/$d->doc_org";
                //location for where the document with applied ocr will be stored.
                $document_dst[$key] = "storage/app/documents_ocred/$d->doc_ocr";
                //decrypted doc
                $decrypted_doc                = "decrypted-$d->doc_org";
                $decrypted_document_src[$key] = "storage/app/documents_processing/$decrypted_doc";
                //---------------------------------------------------------------------------------------------------------------

                //flags for ocrmypdf. flags are available ata ocrmypdf documentation.
                $flag_ocr[$key]        = "ocrmypdf --output-type pdf -l deu+eng --tesseract-timeout 3600 --skip-big 5000 --force-ocr --rotate-pages --deskew";
                //qpdf decrypt flag
                $flag_decrypt[$key]    = "qpdf --decrypt";

                //decrypt document
                $process_decrypt[$key] = "$flag_decrypt[$key] $document_src[$key] $decrypted_document_src[$key]";
                //process param for running ocr with force
                $process_ocr[$key]     = "$flag_ocr[$key] $decrypted_document_src[$key] $document_dst[$key]";
                //---------------------------------------------------------------------------------------------------------------

                //decrypt pdf using QPDF
                $this->decrypt_QPDF($process_decrypt[$key]);

                //check if file is decrypted
                if(file_exists($decrypted_document_src[$key])){
                    //remove blank pages.
                    $this->remove_blank_pages($decrypted_document_src[$key]);
                    //force ocr doc
                    $this->force_ocr($process_ocr[$key],$d->doc_org);
                    //delete temporary decrypted doc
                    File::delete((string)$decrypted_document_src[$key]);
                }
                else{
                    DB::table('documents')->where('doc_org', $d->doc_org)->update(['process_status'=>'password_protected']);
                }//end if file exist
                
           }//end foreach

        }//end if count > 0

    }//end handle function

    // decrypt document using qpdf ==========================================================================================
    
    public function decrypt_QPDF($prc_decrypt){
        // start decryption process
        $decrypt_process = new Process($prc_decrypt);
        $decrypt_process->setTimeout(86400);
        $decrypt_process->enableOutput();
        $decrypt_process->start();
        $decrypt_process->wait();
    }

    // force ocr  ===========================================================================================================
    
    public function force_ocr($prc_ocr, $doc_org){

        //run ocr force on decrypted doc
        $force_process = new Process($prc_ocr);
        $force_process->setTimeout(86400);
        $force_process->enableOutput();
        $force_process->start();
        $force_process->wait();
        //check if getErrorOuput has ERROR. (getErrorOutput will ouput 'INFO','WARNING' and 'ERROR').
        //stripos is case-insensitive.
        $check_if_has_error = stripos($force_process->getErrorOutput(), 'error');
    
        //if "error" string found. process has error.
        if ($check_if_has_error !== false){
            //error found. update process status to failed.
            DB::table('documents')->where([
              ['doc_org', '=', $doc_org]
            ])->update(['process_status'=>'failed_force']);
            //forced+location+name+time of error log
            $error_log = "storage/app/symfony_process_error_logs/" ."forced-". $doc_org . "-" . time();
            //outpout error
            echo $force_process->getErrorOutput();
            //store error log in file
            file_put_contents($error_log, $force_process->getErrorOutput());
        }else{
            //process success
            DB::table('documents')->where([
               ['doc_org', '=', $doc_org]
            ])->update(['process_status'=>'ocred','is_ocred'=>1]);
        }
    }
    //-------------------------------------------------------------------------------------------------------------------------

    public function remove_blank_pages($file){

      echo "remove page \n";
      #------------------------------------------------------------ 
      $pdf = new Pdf($file);
      //get total number of document page
      $total_pages = $pdf->getNumberOfPages();

      //storage pages to be remove.
      $remove_pages = [];
      #----------------------------------------------------------------------------------------------
      $doc_output =   "storage/app/documents_processing".'/'."rm_output-" . str_random(5) . ".pdf";

      //check each page for empty pages.
      for($pageNum = 1; $pageNum<=$total_pages; $pageNum ++){
            //make temporary image from pdf to check if it is blank.
            $tempo_name  =  time() . str_random(5) . ".png";
            #-------------------------------------------------------------
            $tempo_image =  "storage/app/documents_processing/$tempo_name";
            //make image per doc page
            $pdf->setPage($pageNum)->saveImage($tempo_image);

            //image magic flag, check amount of devaition.
            //number less than deviation is consider a blank page.
            $img_magic_flag = "identify -format %[standard-deviation] $tempo_image";
            $deviation = 4000;

            $process = new Process($img_magic_flag);
            $process->enableOutput();
            $process->setTimeout(86400);
            $process->start();
            $process->wait();

            if((int)$process->getOutput()<=$deviation){
                array_push($remove_pages, $pageNum);
            }
            //remove temporary image.
            File::delete((string)$tempo_image);
      }

      //start removing empty pages.
      if($total_pages>count($remove_pages)){

            $page_range  =  range(1, $total_pages);
            //the difference in array will not be deleted.
            $safe_pages  =  array_diff($page_range, $remove_pages);
            $safe_pages  =  implode(" ",$safe_pages);
            //pdf toolkit flags
            $rm_pages_flags = "pdftk $file cat $safe_pages output $doc_output && mv $doc_output $file";
            $process = new Process($rm_pages_flags);
            $process->enableOutput();
            $process->setTimeout(86400);
            $process->start();
            $process->wait();
      }

    }

}
