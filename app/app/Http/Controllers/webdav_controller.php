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

# WEBDAV
use Sabre\DAV\Client as sabre_client;
use League\Flysystem\WebDAV\WebDAVAdapter;
use League\Flysystem\Filesystem;

class webdav_controller extends Controller
{
    // return webdav create credentials/list credentials page
    public function index(){
    	return view('pages/WEBDAV/webdav_create_credentials');
    }

    // return webdav connect page
    public function connectWEBDAV($webdav_id){
    	//check if valid webdav_id
    	$webd = DB::table('webdav_credentials')
    	->where([
    	   ['webdav_id','=',$webdav_id],
    	   ['webdav_user_id','=',Auth::user()->id]
    	])->first();

    	if(count($webd)>0){
    		return view('pages/WEBDAV/webdav_connect')->with(compact('webd'));
    	}else{
    		return redirect('error_404');
    	}
    }


   // save new webdav credential
    public function saveNewCredentails(Request $request){
       try { 
	        $settings = array(
	          'baseUri'      =>  $request->webdav_baseuri,
	          'userName'  	 =>  $request->webdav_username,
	          'password'  	 =>  $request->webdav_password,
	          'timeout'   	 =>  3600
	        );
	        // Bootstrap
	        $client     = new sabre_client($settings);
	        $adapter    = new WebDAVAdapter($client);
	        $flysystem  = new Filesystem($adapter);

	        // test connect if credentials and pathprefix is valid 
	        $directories = $flysystem->listContents($request->webdav_pathprefix);

            // no error save credentials
            DB::table('webdav_credentials')->insert([
           	 	'webdav_user_id' 	=>  Auth::user()->id,
           	 	'webdav_baseuri' 	=> 	$request->webdav_baseuri,
           	    'webdav_username'   => 	$request->webdav_username,
                'webdav_password'   => 	encrypt($request->webdav_password),
                'webdav_pathprefix' => 	$request->webdav_pathprefix,
            ]);
            return "success";

       }
       catch(\Exception $err){
       	  //return error.
          return "error";
       }  
    }


    // return user webdav credentials
    public function returnWEBDAVCredentials(){
    	$webdav_cred = DB::table('webdav_credentials')
    	->where('webdav_user_id', Auth::user()->id)
    	->get();
    	return json_encode($webdav_cred);
    }

    // delete webdav credentials
    public function deleteWEBDAVCredentials(Request $request){
    	
    	$delWEBDAVCred = DB::table('webdav_credentials')->where([
    	   ['webdav_id','=',$request->webdav_id],
    	   ['webdav_user_id','=',Auth::user()->id]
    	])->delete();

    	if(count($delWEBDAVCred)>0){
    		return "webdav_cred_deleted";
    	}
    }


     // connect to webdav server , return files datas.
    public function WEBDAV_connect_get_files(Request $request){
    	
    	$webd = DB::table('webdav_credentials')
    	->where([
    	   ['webdav_id','=',$request->webdav_id],
    	   ['webdav_user_id','=',Auth::user()->id]
    	])->first();

    	if(count($webd)>0){
			 try { 
				    $settings = array(
			          'baseUri'      =>  $webd->webdav_baseuri,
			          'userName'  	 =>  $webd->webdav_username,
			          'password'  	 =>  decrypt($webd->webdav_password),
			          'timeout'   	 =>  3600
			        );
			        // Bootstrap
			        $client     = new sabre_client($settings);
			        $adapter    = new WebDAVAdapter($client);
			        $flysystem  = new Filesystem($adapter);

		            $directories = $flysystem->listContents($request->dir, 0);

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
    	$webd = DB::table('webdav_credentials')
    	->where([
    	   ['webdav_id','=',$request->webdav_id],
    	   ['webdav_user_id','=',Auth::user()->id]
    	])->first();

    	if(count($webd)>0){
			try { 
	       	    $settings = array(
			          'baseUri'      =>  $webd->webdav_baseuri,
			          'userName'  	 =>  $webd->webdav_username,
			          'password'  	 =>  decrypt($webd->webdav_password),
			          'timeout'   	 =>  3600
			        );
			        // Bootstrap
			        $client     = new sabre_client($settings);
			        $adapter    = new WebDAVAdapter($client);
			        $flysystem  = new Filesystem($adapter);

	            $directories = $flysystem->listContents($webd->webdav_pathprefix, 0);
	           
	            foreach($request['files'] as $key=>$file){

	           	    //$file[0]=filename_no_extension, $file[1]=file_extension
                    $fileNameExt = explode(".",$file["filename"]);
                    //add random string to filename.
                    $random_n = str_random(5);

                    $doc_org =  $fileNameExt[0]  ."-".  $random_n  ."-".  "org"  .".".  $fileNameExt[1];
                    $doc_prc =  $fileNameExt[0]  ."-".  $random_n  ."-".  "prc"  .".".  "pdf";
                    $doc_ocr =  $fileNameExt[0]  ."-".  $random_n  ."-".  "ocr"  .".".  "pdf";

                    // copy file to storage
                    file_put_contents(storage_path('app/documents_new/' . $doc_org), $flysystem->read($file["path"]));

                    date_default_timezone_set(Auth::user()->user_timezone);
                    
                    $doc_datas = array(
                        "doc_user_id"    =>   Auth::user()->id,
                        "doc_org"        =>   $doc_org,
                        "doc_prc"        =>   $doc_prc,
                        "doc_ocr"        =>   $doc_ocr,
                        "t_process"      =>   time(),
                        "process_status" =>   'new',
                        "origin"         =>   'WEBDAV',
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
