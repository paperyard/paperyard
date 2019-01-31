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

class ocr_new extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run OCRMYPDF on newly added documents';

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
          // run ocrmypdf less than current time();
          $time = time();
          $documents = DB::table('documents')->where([
              ['t_process', '<=', $time],
              ['process_status', '=', 'new']
          ])->get();

          // iF DOCUMENTS FOUND RUN
          if(count($documents)>0){

              // UPDATE FOUND DOCUMENTS STATUS TO PROCESSING.
              $proc_doc_ids = [];
              foreach($documents as $upd){
                array_push($proc_doc_ids, $upd->doc_id);
              }
              DB::table('documents')
              ->whereIn('doc_id', $proc_doc_ids)
              ->update(['process_status' => 'processing']);

              // APPLY OCR ON EACH DOCUMENT
              foreach($documents as $key=>$d){

                    //location of original doc. --------------------------------------------------------
                    $document_src[$key] = "storage/app/documents_new/$d->doc_org";
                    //location for where the document with applied ocr will be stored.
                    $document_dst[$key] = "storage/app/documents_ocred/$d->doc_ocr";
                    //location of processing documents
                    $document_prc[$key] = "storage/app/documents_processing/$d->doc_prc";
                    //----------------------------------------------------------------------------------

                    //flags for ocrmypdf. flags are available at ocrmypdf documentation.----------------
                    $flags_ocr[$key] = "ocrmypdf --output-type pdf -l deu+eng --rotate-pages --deskew";
                     //img2pdf flags
                    $flags_img[$key] = "img2pdf --output";
                    //-----------------------------------------------------------------------------------

                    //apply ocr
                    $process_ocr[$key]     = "$flags_ocr[$key] $document_src[$key] $document_dst[$key]";
                    //convert image to pdf
                    $process_img[$key]     = "$flags_img[$key] $document_prc[$key] $document_src[$key]";
                    //apply ocr to converted image
                    $process_img_ocr[$key] = "$flags_ocr[$key] $document_prc[$key] $document_dst[$key]";
                    //-----------------------------------------------------------------------------------

                    //check file type
                    $fileType = $this->check_file($document_src[$key]);
                    
                    //file is pdf
                    if($fileType == "PDF"){
                        //@var process ocr,doc_org
                        $this->ocr_pdf($process_ocr[$key],$d->doc_org);
                    }
                    //file is image
                    elseif($fileType == "PNG" || $fileType == "JPE"){
                        //@var process ocr, process_img, doc_ocr, doc_org
                        $this->ocr_img($process_img_ocr[$key],$process_img[$key],$d->doc_org,$d->doc_ocr);
                    }
                    //not a valid file
                    else{
                        DB::table('documents')->where('doc_org', $d->doc_org)->update(['process_status'=>'not_pdf']);
                    }
                    
              }//end foreach

          }//end if count > 0

    }//end handle function

    
    // valid files (PDF, IMAGES) ==================================================================================
    public function check_file($file){

        //check file type
        $file_check = "file -b $file";
        //initiate new symfony process
        $process = new Process($file_check);
        $process->enableOutput();
        $process->setTimeout(86400);
        $process->start();
        $process->wait();

        //return file type, valid PNG,PDF,JPE
        return strtoupper(substr($process->getOutput(), 0,3));
    }

    // process pdf ================================================================================================
    public function ocr_pdf($prc_ocr,$doc_org){

        $process = new Process($prc_ocr);
        $process->enableOutput();
        $process->setTimeout(86400);
        $process->start();
        $process->wait();
        //check if getErrorOuput has ERROR. (getErrorOutput will ouput 'INFO','WARNING' and 'ERROR').
        //we will only store ERROR logs.
        //stripos is case-insensitive.
        $check_if_has_error = stripos($process->getErrorOutput(), 'error');
        //if "error" string found. process has error.
        if ($check_if_has_error !== false){
            //error found. update process status to failed.
            DB::table('documents')->where([
              ['doc_org', '=', $doc_org]
            ])->update(['process_status'=>'failed']);

            //localtion+name+time of error log
            $error_log = "storage/app/symfony_process_error_logs/" ."new-". $doc_org . "-" . time();
            //outpout error
            echo $process->getErrorOutput();
            //store error log in file
            file_put_contents($error_log, $process->getErrorOutput());
        }else{
            //process success
            DB::table('documents')->where([
               ['doc_org', '=', $doc_org]
            ])->update(['process_status'=>'ocred','is_ocred'=>1]);
        }

        echo "success\n";
    }



    // process img =================================================================================================
    public function ocr_img($prc_ocr,$prc_img,$doc_org,$doc_ocr){

        //convert images to pdf
        $process_img = new Process($prc_img);
        $process_img->enableOutput();
        $process_img->setTimeout(86400);
        $process_img->start();
        //code will wait until images are converted to pdf.
        $process_img->wait();
        //apply ocr
        $process_ocr = new Process($prc_ocr);
        $process_ocr->enableOutput();
        $process_ocr->setTimeout(86400);
        $process_ocr->start();
        $process_ocr->wait();

        if(file_exists('storage/app/documents_ocred/'.$doc_ocr)){
            // file found. no error
            DB::table('documents')->where([
               ['doc_org', '=', $doc_org]
            ])->update(['process_status'=>'ocred','is_ocred'=>1]);
        }else{
            //file not found error occured
            DB::table('documents')->where([
               ['doc_org', '=', $doc_org]
            ])->update(['process_status'=>'failed']);
        }

    }


   
}
