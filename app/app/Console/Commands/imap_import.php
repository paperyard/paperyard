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
        
        // storage for imap ids for process.
        $imap_process_ids = [];


        // serialize value of folder selected
        // boolean = 1
        $selected_folder = "b:1";




        #get imaps credentials 
        $imap_credentials = DB::table('imap_credentials')

        // select imap with 'idle' status
        ->where('imap_process_status', 'idle')

        // select imap with selected folders
        ->where('imap_folders',    'LIKE', '%' . $selected_folder . '%')

        // join imap credential with users
        ->join('users','imap_user_id','=','users.id')


        ->select('imap_credentials.*','users.id','users.user_timezone','imap_credentials.imap_folders')

        ->get();




        /*
        if imap credentials found

           = set status of found imap credentials to "busy".
             -imap_credentials with status "busy" will not be included in next cron job run.
             -status will set to "idle" once importing it is done importing docs. 

           = loop through each credentials 

           = decrypt password 

           = set default timezone

           = unserialize imap_folders
              -imap folders is serialized and stored to database.
           = 

        */


        if(count($imap_credentials)>0){


            // loop through collections to get imap credential ids
            foreach($imap_credentials as $imap_ids){
                array_push($imap_process_ids, $imap_ids->imap_id);
            }

            // update status from "idle" => "busy"
            DB::table('imap_credentials')

            ->whereIn('imap_id', $imap_process_ids)  

            ->update([ 'imap_process_status' => 'busy' ]);




            // loop through collections
            foreach($imap_credentials as $imap){

                // decrypt password to be used in connection
                $password = decrypt($imap->imap_password);

                // set default timezone
                date_default_timezone_set($imap->user_timezone);


                // store only selected folders
                $selected_folders = [];
                
                // unserialized folders;
                $imap_folders =  unserialize($imap->imap_folders);


                // imap_folders contains parent and child folders.
                foreach($imap_folders as $parent_folder){

                    
                    if($parent_folder['selected'] == "true"){

                         array_push($selected_folders, $parent_folder['folder_fullname']);
                    
                    }//if parent is selected


                    if(!empty($parent_folder['childrens'])){

                        foreach($parent_folder['childrens'] as $child_folder){

                            if($child_folder['selected'] == "true"){

                                 array_push($selected_folders, $child_folder['child_folder_fullname']);

                            }

                        }

                    }//if has childrens

                }//foreach


                // @var imap_host -> imap_username -> imap_password -> imap_id -> imap_timezone -> imap_lastrun -> imap_id
                $this->IMAP_Connect
                (
                     $imap->imap_host, 

                     $imap->imap_username,  

                     $password,   

                     $imap->imap_user_id,

                     $imap->user_timezone,  

                     $imap->imap_id,

                     $selected_folders
                );


                // update imap latest_run_date and  status
                DB::table('imap_credentials')

                ->where('imap_id', $imap->imap_id)   

                ->update([ 
                    'imap_process_status' => 'idle'
                ]);


            }//foeach

        }//if
    
    }//handle 

    // @var /host/email/password/user id/timezone/last_run
    public function IMAP_Connect($host, $email, $password, $user_id, $timezone, $imap_id, $selected_folders){
        
        date_default_timezone_set($timezone);

        //globalize userid to be able to access in mutli foreach.
        $GLOBALS['user_id']  = $user_id;
                      
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

        if($oClient->isConnected()){
   
            foreach($selected_folders as $_selected_folder){

                // access each specific folder using fullname of folder.
                // $oFolder = $oClient->getFolder('INBOX.name');
                // https://github.com/Webklex/laravel-imap
                $aFolder = $oClient->getFolder($_selected_folder);

                /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */

                // \Carbon\Carbon::now(),
                $aMessage = $aFolder->query()->setFetchFlags(false)->since(\Carbon\Carbon::now()->subDays(3))->leaveUnread()->get();
            
                foreach($aMessage as $oMessage){
      
                    #check if message has attachment 
                    #we only need messages that has attachments.
                    if($oMessage->hasAttachments()){

                            // get message attachments
                            $aAttachment = $oMessage->getAttachments();

                            // Loop through each attachments.
                            $aAttachment->each(function ($oAttachment){

                                // check attachment if has a valid filetype @var $oAttachment->getExtension() eg. .pdf .doc .bin
                                if ($this->checkValidFileType($oAttachment->getExtension())==1){

                                    # @var /attachment_filename/user_id
                                    $checkExist = $this->checkImportFile($oAttachment->getName(),$GLOBALS['user_id']);
                                    
                                    if($checkExist==0){

                                        //  $file[0]=filename
                                        //  $file[1]=file_extension
                                        
                                        $file = explode(".",$oAttachment->getName());

                                        //add random string to filename.
                                        $random_n = str_random(5);
                                        $doc_org =  $file[0]  ."-".  $random_n  ."-".  "org"  .".".  $file[1];
                                        $doc_prc =  $file[0]  ."-".  $random_n  ."-".  "prc"  .".".  "pdf";
                                        $doc_ocr =  $file[0]  ."-".  $random_n  ."-".  "ocr"  .".".  "pdf";

                                        // copy file to storage
                                        file_put_contents(storage_path('app/documents_new/' . $doc_org), $oAttachment->content);

                                        $doc_datas = array(
                                            "doc_user_id"    =>   $GLOBALS['user_id'],
                                            "doc_org"        =>   $doc_org,
                                            "doc_prc"        =>   $doc_prc,
                                            "doc_ocr"        =>   $doc_ocr,
                                            "t_process"      =>   time(),
                                            "process_status" =>   'new',
                                            "origin"         =>   'IMAP',
                                            "created_at"     =>   \Carbon\Carbon::now(),
                                            "updated_at"     =>   \Carbon\Carbon::now(),
                                        );

                                        // save new document to be ocred
                                        DB::table('documents')->insert($doc_datas);


                                        # @var /filename/user_id
                                        // save imported filename for file checking.
                                        $this->saveImportFilename($oAttachment->getName(),$GLOBALS['user_id']);

                                        echo "success";


                                    }  


                                }//check if valid filetype


                            }); // loop attachments    


                    }//has Attachments    


                }//foreach aMessage
                     
                
            }//foreach selected_folders

        }//if connected

    }//IMAP_connect




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
