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

                          //location of original doc.
                          $document_src[$key] = "storage/app/documents_new/" . $d->doc_org;
                          //location for where the document with applied ocr will be stored.
                          $document_dst[$key] = "storage/app/documents_ocred/" . $d->doc_ocr;


                          //decrypted doc
                          $decrypted_doc = "decrypted-" . $d->doc_org;
                          $decrypted_document_src[$key] = "storage/app/documents_new/" . $decrypted_doc;

                          //flags for ocrmypdf. flags are available ata ocrmypdf documentation.
                          $params1[$key] = "ocrmypdf --output-type pdf -l deu+eng --tesseract-timeout 3600 --skip-big 5000 --force-ocr --rotate-pages --deskew";
                           //img2pdf flags
                          $params2[$key] = "img2pdf --output";
                          //qpdf dycrpy flag
                          $params3[$key] = "qpdf --decrypt";

                          //process param for running ocr with force
                          $process_ocr[$key] = $params1[$key]     . " " . $decrypted_document_src[$key] . " " . $document_dst[$key];
                          $p_id1[$key] = (string)$process_ocr[$key];
                          
                          //decrypt document
                          $process_decrypt[$key] = $params3[$key] . " " . $document_src[$key] . " " . $decrypted_document_src[$key];
                          $p_id4[$key] = (string)$process_decrypt[$key];

                        // PDF
                        if(strtoupper(substr($d->doc_org, -3))=="PDF"){

                            // start decryption process
                            $dec_process[$key] = new Process($p_id4[$key]);
                            $dec_process[$key]->disableOutput();
                            $dec_process[$key]->start();
                            $dec_process[$key]->wait();

                            //check if file is decrypted
                            if(file_exists($decrypted_document_src[$key])){
                                    //run ocr force on decrypted doc
                                    $process[$key] = new Process($p_id1[$key]);
                                    $process[$key]->setTimeout(9000000);
                                    $process[$key]->enableOutput();
                                    $process[$key]->start();
                                    $process[$key]->wait();

                                    //check if getErrorOuput has ERROR. (getErrorOutput will ouput 'INFO','WARNING' and 'ERROR').
                                    //we will only store ERROR logs.
                                    //stripos is case-insensitive.
                                    $check_if_has_error = stripos($process[$key]->getErrorOutput(), 'error');
                                    
                                    //if "error" string found. process has error.
                                    if ($check_if_has_error !== false){
                                        //error found. update process status to failed.
                                        DB::table('documents')->where([
                                          ['doc_org', '=', $d->doc_org]
                                        ])->update(['process_status'=>'failed_force']);
                                        //forced+location+name+time of error log
                                        $error_log = "storage/app/symfony_process_error_logs/" ."forced-". $d->doc_org . "-" . time();
                                        //outpout error
                                        echo $process[$key]->getErrorOutput();
                                        //store error log in file
                                        file_put_contents($error_log, $process[$key]->getErrorOutput());
                                    }else{
                                        //process success
                                        DB::table('documents')->where([
                                           ['doc_org', '=', $d->doc_org]
                                        ])->update(['process_status'=>'ocred','is_ocred'=>1]);
                                    }
                            }
                            else{
                                DB::table('documents')->where([
                                   ['doc_org', '=', $d->doc_org]
                                ])->update(['process_status'=>'failed_decrypting']);
                                echo "failed decrypting";
                            }//end if file exist
                        
                        }//end if strtoupper

                   }//end foreach

              }//end if count > 0

    }//end handle function
  
}
