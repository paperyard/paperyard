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
		return json_encode($imap_cred);

	}

	public function saveCredentials(Request $request){
        
		  $host 	= strtolower($request->imap_host);
		  $email 	= $request->imap_email;
		  $password = $request->imap_password;

	      if($this->testImapConnection($host,$email,$password)=="success"){

	      	    $save = DB::table('imap_credentials')
				->insert([
					'imap_user_id'	=>	Auth::user()->id,
					'imap_host'		=>	$host,
					'imap_username'	=>	$email,
					'imap_password'	=>	encrypt($password)
				]);
				return "success";
	      }else{
	      		return "failed";
	      }

	}

    public function testImapConnection($host,$email,$password){

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
            if($oClient->isConnected()){ return "success"; }
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

}
