<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Hash;
use Image;
use DB;

# IMAP
use Webklex\IMAP\Client;
use Illuminate\Support\Facades\Crypt;


class imap_controller extends Controller
{
    //
	public function index(){
		 return view('pages/IMAP/imap');
	}

	public function returnCredentials(){

		$imap_cred = DB::table('imap_credentials')
		->where('imap_user_id', Auth::user()->id)
		->get();

        foreach($imap_cred as $credentials){
            // unserialize folders and child folders 
            $credentials->imap_folders = unserialize($credentials->imap_folders);
        }    
        
		return json_encode($imap_cred);

	}

	public function saveCredentials(Request $request){

        // check if emails exist
        
        $check =  DB::table('imap_credentials')->where([
            'imap_user_id'  => Auth::id(),
            'imap_username' => $request->imap_email
        ])
        ->first();

        if(empty($check)){    
    		  $host 	= strtolower($request->imap_host);
    		  $email 	= $request->imap_email;
    		  $password = $request->imap_password;

              $imap_connection = $this->testImapConnection($host,$email,$password);

    	      if($imap_connection=="failed"){
                    return "failed";
    	      }else{
                    DB::table('imap_credentials')
                    ->insert([
                        'imap_user_id'  =>  Auth::user()->id,
                        'imap_host'     =>  $host,
                        'imap_username' =>  $email,
                        'imap_password' =>  encrypt($password),
                        'imap_folders'  =>  serialize($imap_connection)
                    ]);
                    return "success";
    	      }
        }else{
            return "email_taken";
        }      

	}

    public function refreshCredentials(Request $request){

          $imap_credentials =  DB::table('imap_credentials')
          ->where([
            ['imap_id',      '=',  $request->imap_id ],
            ['imap_user_id', '=',  Auth::user()->id  ]
          ])
          ->first();

          $host     = $imap_credentials->imap_host;
          $email    = $imap_credentials->imap_username;
          $password = decrypt($imap_credentials->imap_password);

          $new_folders = $this->testImapConnection($host,$email,$password);

          DB::table('imap_credentials')
          ->where([
            ['imap_id',      '=',  $request->imap_id ],
            ['imap_user_id', '=',  Auth::user()->id  ]
          ])
          ->update([
             'imap_folders' => serialize($new_folders)
          ]);

          
    }

    public function testImapConnection($host,$email,$password){

        // imap user credentials
        $oClient = new Client([
            'host'      	=>   $host,
            'port'      	=>   993,
            'encryption'  	=>   'ssl', // Supported: false, 'ssl', 'tls'
            'validate_cert' =>   true,
            'username'    	=>   $email,
            'password'    	=>   $password,
            'protocol'      =>   'imap'
        ]);

        try{
            //Connect to the IMAP Server
            $oClient->connect();

            //Check if connected
            if($oClient->isConnected())
            { 

                $folder_storage = [];

                // get all folders 
                $aFolder = $oClient->getFolders();

                // loop through folders to get folder names and child folders 
                foreach($aFolder as $key=>$folder){

                   array_push($folder_storage, array("folder_name"=>$folder->name,"folder_fullname"=>$folder->fullName,"selected"=>"false"));

                   $child_storage = [];

                   if($folder->has_children){

                        foreach($folder->children as $child_folder)
                        {
                               array_push($child_storage, array("child_folder_name"=>$child_folder->name, "child_folder_fullname"=>$child_folder->fullName,"selected"=>"false"));
                        }

                        $folder_storage[$key]['childrens'] = $child_storage;
                   }

                }
                return $folder_storage; 
            }
        }
        catch(\Webklex\IMAP\Exceptions\ConnectionFailedException $err){
            return "failed";
        }

    }

    public function removeCredentials(Request $request){

    	$rm = DB::table('imap_credentials')
    	->where([
    		['imap_id','=',$request->imap_id],
    		['imap_user_id','=',Auth::user()->id]
    	])
    	->delete();
    	return "deleted_imap_credentials";
    }

    public function selectFolder(Request $request){

          // get selected folders as array

          $selected_folders = DB::table('imap_credentials')
          ->where([
             'imap_user_id' => Auth::id(),
             'imap_id'      => $request->imap_id
          ])
          ->select('imap_folders')
          ->first();

          $arr_folders = unserialize($selected_folders->imap_folders);


          // return json_encode($arr_folders[1]['folder_fullname']);

          foreach($arr_folders as $key=>$parent_folders){
                

                // check if folder is selected.
                if((string)$parent_folders['folder_fullname'] ===  (string)$request->folder_fullname){
                      $arr_folders[$key]['selected'] = $request->selected;
                      break;
                } 
                // end if

                /*
                if parent folder has childfolder, 
                loop through and check if child folder is selected.
                */
                if(!empty($parent_folders['childrens'])){

                     foreach($parent_folders['childrens'] as $key2=>$child_folder){

                          if((string)$child_folder['child_folder_fullname'] == (string)$request->folder_fullname){
                             $arr_folders[$key]['childrens'][$key2]['selected'] = $request->selected;
                             break;
                          }

                     } // end foreach

                } // end if


          } // end foreach

 

          // update selected folders 
          DB::table('imap_credentials')
          ->where([
             'imap_user_id' => Auth::id(),
             'imap_id'      => $request->imap_id
          ])
          ->update([ 'imap_folders' => serialize($arr_folders)]);


    }

}
