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

use Illuminate\Support\Facades\Crypt;

class ftp_controller extends Controller
{
    // return ftp page
    public function index(){
    	return view('pages/FTP/ftp_create_credentials');
    }

    // return ftp connect page
    public function connectFTP($ftp_id){
    	//check if valid ftp_id
    	$ftp = DB::table('ftp_credentials')
    	->where([
    	   ['ftp_id','=',$ftp_id],
    	   ['ftp_user_id','=',Auth::user()->id]
    	])->first();

    	if(count($ftp)>0){
    		return view('pages/FTP/ftp_connect')->with(compact('ftp'));
    	}else{
    		return redirect('error_404');
    	}
    }

    // save new ftp credential
    public function saveNewCredentails(Request $request){
       try { 
       	   //create ftp disk instance
           $ftp = Storage::createFtpDriver([
                'host' 	    => 	$request->ftp_host,
                'username'  => 	$request->ftp_username,
                'password'  => 	$request->ftp_password,
                'port' 	    => 	$request->ftp_port,
                'timeout'   => 	3600
           ]);
           // test get list of files/dir in root
           $directories = $ftp->listContents('/', 1);

           // no error save credentials
           DB::table('ftp_credentials')->insert([
           	 	'ftp_user_id' 	=>  Auth::user()->id,
           	 	'ftp_host' 	  	=> 	$request->ftp_host,
           	    'ftp_username'  => 	$request->ftp_username,
                'ftp_password'  => 	encrypt($request->ftp_password),
                'ftp_port' 	    => 	$request->ftp_port,
           ]);
           return "success";
       }
       catch(\Exception $err){
       	  //return error.
          return "error";
       }  
    }

    // return user ftp credentials @var user_id 
    public function returnFTPCredentials(){
    	$ftp_cred = DB::table('ftp_credentials')
    	->where('ftp_user_id', Auth::user()->id)
    	->get();
    	return json_encode($ftp_cred);
    }

    // delete ftp credentials @var ftp_id,user_id
    public function deleteFTPCredentials(Request $request){
    	
    	$delFTPCred = DB::table('ftp_credentials')->where([
    	   ['ftp_id','=',$request->ftp_id],
    	   ['ftp_user_id','=',Auth::user()->id]
    	])->delete();

    	if(count($delFTPCred)>0){
    		return "ftp_cred_deleted";
    	}
    }

    // connect to ftp server , return files datas.
    public function FTP_connect_get_files(Request $request){
    	
    	$ftp = DB::table('ftp_credentials')
    	->where([
    	   ['ftp_id','=',$request->ftp_id],
    	   ['ftp_user_id','=',Auth::user()->id]
    	])->first();

    	if(count($ftp)>0){
			 try { 
		       	   //create ftp disk instance
		           $ftp = Storage::createFtpDriver([
		                'host' 	    => 	$ftp->ftp_host,
		                'username'  => 	$ftp->ftp_username,
		                'password'  => 	decrypt($ftp->ftp_password),
		                'port' 	    => 	$ftp->ftp_port,
		                'timeout'   => 	3600
		           ]);
		           // test get list of files/dir in root
		    	   // @var directory, recursive bool.
		    	   // true 1 = show files inside directories
		    	   // false 0 = dont show 
		           $directories = $ftp->listContents($request->dir, 0);

		           foreach($directories as $key=>$dir){
		              if(isset($dir['size'])){
		                  $directories[$key]['custom_size'] = $this->FileSizeConvert($dir['size']);
		              }
		           }
		           return json_encode($directories);
		       }
		       catch(\Exception $err){
		       	  //return error.
		          return "error";
		       }
    	}
    }

    public function downloadFiles(Request $request){
    	$ftp = DB::table('ftp_credentials')
    	->where([
    	   ['ftp_id','=',$request->ftp_id],
    	   ['ftp_user_id','=',Auth::user()->id]
    	])->first();

    	if(count($ftp)>0){
			try { 
	       	   //create ftp disk instance
	           $ftp = Storage::createFtpDriver([
	                'host' 	    => 	$ftp->ftp_host,
	                'username'  => 	$ftp->ftp_username,
	                'password'  => 	decrypt($ftp->ftp_password),
	                'port' 	    => 	$ftp->ftp_port,
	                'timeout'   => 	3600
	           ]);

	           $directories = $ftp->listContents('/', 0);
	           
	           foreach($request['files'] as $key=>$file){

	           	    //$file[0]=filename_no_extension, $file[1]=file_extension
                    $fileNameExt = explode(".",$file["filename"]);
                    //add random string to filename.
                    $random_n = str_random(5);

                    $doc_org =  $fileNameExt[0]  ."-".  $random_n  ."-".  "org"  .".".  $fileNameExt[1];
                    $doc_prc =  $fileNameExt[0]  ."-".  $random_n  ."-".  "prc"  .".".  "pdf";
                    $doc_ocr =  $fileNameExt[0]  ."-".  $random_n  ."-".  "ocr"  .".".  "pdf";

                    // copy file to storage
                    file_put_contents(storage_path('app/documents_new/' . $doc_org), $ftp->get($file["path"]));

                    date_default_timezone_set(Auth::user()->user_timezone);
                    
                    $doc_datas = array(
                        "doc_user_id"    =>   Auth::user()->id,
                        "doc_org"        =>   $doc_org,
                        "doc_prc"        =>   $doc_prc,
                        "doc_ocr"        =>   $doc_ocr,
                        "t_process"      =>   time(),
                        "process_status" =>   'new',
                        "origin"         =>   'FTP',
                        "created_at"     =>   \Carbon\Carbon::now(),
                        "updated_at"     =>   \Carbon\Carbon::now(),
                    );

                    DB::table('documents')->insert($doc_datas);
   
	           }
	       }
	       catch(\Exception $err){
	       	  //return error.
	          return "error";
	       }
    	}
    }



       //convert file size to human readable.  
    public function FileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "TB",
                    "VALUE" => pow(1024, 4)
                ),
                1 => array(
                    "UNIT" => "GB",
                    "VALUE" => pow(1024, 3)
                ),
                2 => array(
                    "UNIT" => "MB",
                    "VALUE" => pow(1024, 2)
                ),
                3 => array(
                    "UNIT" => "KB",
                    "VALUE" => 1024
                ),
                4 => array(
                    "UNIT" => "B",
                    "VALUE" => 1
                ),
            );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }


}
