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
        //get all users that has reminders
        $reminders = DB::table('reminders')
        ->where('reminder_status','standby')
        ->join('users','reminders.reminder_user_id','=','users.id')
        ->leftJoin('documents','reminders.reminder_document_id','documents.doc_id')
        ->select('reminders.*','users.user_timezone','users.id','users.email','users.name','documents.doc_ocr')
        ->get();

        $rm_ids = [];
        foreach($reminders as $rm){
             array_push($rm_ids, $rm->reminder_id);
        }

        DB::table('reminders')->whereIn('reminder_id', $rm_ids)
        ->update(['reminder_status'=>'processing']);


        foreach($reminders as $reminder){

               // set user time zone.
               date_default_timezone_set($reminder->user_timezone);
               // set reminder shedule.
               $schedule_reminder = $reminder->reminder_schedule;
               $user_datetime = date("Y-m-d H:i:s");

               if($schedule_reminder <= $user_datetime){
                    // send email
                    // update reminder status to activated.
                    $rm_datas = array(
                        "user_name"=>$reminder->name,
                        "reminder_title"=>$reminder->reminder_title,
                        "reminder_document"=>$reminder->doc_ocr,
                        "reminder_message"=>$reminder->reminder_message
                    );
                    // send mail
                    Mail::to($reminder->email)->send(new reminder($rm_datas));
                    // update reminder to activated
                    DB::table('reminders')->where('reminder_id', $reminder->reminder_id)
                    ->update(['reminder_status'=>'activated']);
               }else{
                    // update reminder status back to standby;
                    DB::table('reminders')->where('reminder_id', $reminder->reminder_id)
                    ->update(['reminder_status'=>'standby']);

               }
        }


    }
}
