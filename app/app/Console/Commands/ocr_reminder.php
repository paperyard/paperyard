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
use App\Mail\reminder;

class ocr_reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check for reminders, notify users through email';

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
        
        // rm_process_status, [new_reminder,processing_reminder,reminded]
        // get all users that has reminders
        $reminders = DB::table('reminders')
        ->where('rm_process_status','new_reminder')
        ->join('users','reminders.rm_user_id','=','users.id')
        ->leftJoin('documents','reminders.rm_doc_id','documents.doc_id')
        ->select('reminders.*','users.user_timezone','users.id','users.email','users.name','documents.doc_ocr','documents.reminder')
        ->get();

        if(count($reminders)>0){
                // get all ids to be change in status
                $rm_ids = [];
                foreach($reminders as $rm){
                     array_push($rm_ids, $rm->rm_id);
                     // attach related task in reminder
                     $task_list = DB::table('reminders_tasks')
                     ->where('reminder_id', $rm->rm_id)
                     ->get();
                     if(count($task_list)>0){
                         $rm->task_list = $task_list;
                     }
                }

                DB::table('reminders')->whereIn('rm_id', $rm_ids)
                ->update(['rm_process_status'=>'processing_reminder']);


                foreach($reminders as $reminder){

                       // set user time zone.
                       date_default_timezone_set($reminder->user_timezone);
                       // set reminder shedule.
                       $schedule_reminder = $reminder->reminder;
                       $user_datetime = date("Y-m-d H:i:s");

                       if($schedule_reminder <= $user_datetime){
                            // send email
                            // update reminder status to activated.
                            $rm_datas = array(
                                "user_name"=>$reminder->name,
                                "reminder_title"=>$reminder->rm_title,
                                "reminder_document"=>$reminder->doc_ocr,
                                "reminder_task_list"=>$reminder->task_list
                            );
                            // send mail
                            Mail::to($reminder->email)->send(new reminder($rm_datas));
                            // update reminder to activated
                            DB::table('reminders')->where('rm_id', $reminder->rm_id)
                            ->update(['rm_process_status'=>'reminded']);
                       }else{
                            // update reminder status back to standby;
                            DB::table('reminders')->where('rm_id', $reminder->rm_id)
                            ->update(['rm_process_status'=>'new_reminder']);

                       }
                }
        } //end if count

    }
}
