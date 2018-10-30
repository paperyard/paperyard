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

use Webklex\IMAP\Client;
use Illuminate\Support\Facades\Crypt;

class imap_import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imap:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'IMAP import documents from mail server';

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
        $imap_process_ids = [];
        // set limit of IMAP account import per cronjob
        $limit = 10;

        #get imaps credentials 
        $imap_credentials = DB::table('imap_credentials')
        ->where('imap_process_status', 'idle')
        ->join('users','imap_user_id','=','users.id')
        ->select('imap_credentials.*','users.id','users.user_timezone','imap_credentials.created_at as start_at')
        ->limit($limit)
        ->get();

        if(count($imap_credentials)>0){

            foreach($imap_credentials as $imap_ids){
                array_push($imap_process_ids, $imap_ids->imap_id);
            }

            DB::table('imap_credentials')->whereIn('imap_id', $imap_process_ids)   
            ->update([ 'imap_process_status' => 'importaing_emails' ]);

            foreach($imap_credentials as $imap){
                // decrypt password to be used in connection
                $password = decrypt($imap->imap_password);
                // if imap last rune = null.. start at user created at
                $last_run = $imap->last_run != null? $imap->last_run:$imap->start_at;
                // set default timezone
                date_default_timezone_set($imap->user_timezone);
                // update last_run credentails
                DB::table('imap_credentials')->where('imap_id', $imap->imap_id)   
                ->update(['last_run' =>  \Carbon\Carbon::now() ]);
                // @var /host/email/password/user id/timezone/last_run/imap_id
                $this->IMAP_Connect($imap->imap_host,$imap->imap_username,$password, $imap->id, $imap->user_timezone, $last_run, $imap->imap_id);

                // set imap process status to IDLE.
                DB::table('imap_credentials')->where('imap_id', $imap->imap_id)   
                ->update([ 'imap_process_status' => 'idle' ]);
            }
        }
    }

    // @var /host/email/password/user id/timezone/last_run
    public function IMAP_Connect($host, $email, $password, $user_id, $timezone, $last_run, $imap_id){
        
        date_default_timezone_set($timezone);
        //globalize userid to be able to access in mutli foreach.
        $GLOBALS['g_user_id']  = $user_id;
        $GLOBALS['g_imap_id']  = $imap_id;
        $GLOBALS['g_last_run'] = $last_run;
                      
        $oClient = new Client([
            'host'          =>   $host,
            'port'          =>   993,
            'encryption'    =>   'ssl', // Supported: false, 'ssl', 'tls'
            'validate_cert' =>   true,
            'username'      =>   $email,
            'password'      =>   $password,
            'protocol'      =>   'imap'
        ]);

        //Connect to the IMAP Server
        $oClient->connect();
        //Get all Mailboxes
        /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
        $aFolder = $oClient->getFolders();

        //Loop through every Mailbox
        /** @var \Webklex\IMAP\Folder $oFolder */
        foreach($aFolder as $oFolder){
            //Get all Messages of the current Mailbox $oFolder
            /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
            $aMessage = $oFolder->query()->setFetchFlags(false)->since($last_run)->leaveUnread()->get();

            /** @var \Webklex\IMAP\Message $oMessage */
            foreach($aMessage as $oMessage){

                 #check if message has attachment 
                if($oMessage->hasAttachments()){
                  
                    //check message date.
                    if($oMessage->getDate() >= $GLOBALS['g_last_run']){
                    
                        // get message attachments
                        $aAttachment = $oMessage->getAttachments();

                        // save each attachments
                        $aAttachment->each(function ($oAttachment) {
                            
                            // check if valid filetype
                            if ($this->checkValidFileType($oAttachment->getExtension())==1) {

                                # @var /attachment_filename/user_id
                                $checkExist = $this->checkImportFile($oAttachment->getName(),$GLOBALS['g_user_id']);
                                
                                if($checkExist==0){
                                    //$file[0]=filename_no_extension, $file[1]=file_extension
                                    $file = explode(".",$oAttachment->getName());
                                    //add random string to filename.
                                    $random_n = str_random(5);
                                    $doc_org =  $file[0]  ."-".  $random_n  ."-".  "org"  .".".  $file[1];
                                    $doc_prc =  $file[0]  ."-".  $random_n  ."-".  "prc"  .".".  "pdf";
                                    $doc_ocr =  $file[0]  ."-".  $random_n  ."-".  "ocr"  .".".  "pdf";

                                    // copy file to storage
                                    file_put_contents(storage_path('app/documents_new/' . $doc_org), $oAttachment->content);

                                    $doc_datas = array(
                                        "doc_user_id"    =>   $GLOBALS['g_user_id'],
                                        "doc_org"        =>   $doc_org,
                                        "doc_prc"        =>   $doc_prc,
                                        "doc_ocr"        =>   $doc_ocr,
                                        "t_process"      =>   time(),
                                        "process_status" =>   'new',
                                        "origin"         =>   'IMAP',
                                        "created_at"     =>   \Carbon\Carbon::now(),
                                        "updated_at"     =>   \Carbon\Carbon::now(),
                                    );

                                    DB::table('documents')->insert($doc_datas);
                                    # @var /filename/user_id
                                    $this->saveImportFilename($oAttachment->getName(),$GLOBALS['g_user_id']);

                                    echo "success";
                                }            
                            }

                        }); #foreachAttachment
                    }#if document date > lastrun

                }#hasAttachment  
            }#message
        }#folder
    }

    // check if file is already imported
    public function checkImportFile($filename,$user_id){
        $find = DB::table('imap_import_docs')
        ->where([
            ['imap_import_user_id',  '=', $user_id ],
            ['imap_import_filename', '=', $filename]
        ])->get();

        if(count($find)>0){
            return 1;
        }
        return 0;
    }
    // new file save import filename
    public function saveImportFilename($filename,$user_id){
        $save = DB::table('imap_import_docs')
        ->insert([
            'imap_import_user_id'   =>  $user_id,
            'imap_import_filename'  =>  $filename
        ]);
    }
    // check valid filetype
    public function checkValidFileType($FileType){
        $validFileType = "pdf png jpeg";
        if (stripos($validFileType, $FileType) !== false) {
            return 1;
        }
        return 0;
    }

}
