<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
// storage/file facade
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Response;
use DB;


class filesController extends Controller
{
    //

    public function index($type,$filename){
        
        $file_types = ['image','org','ocr']; 
        //check if filetype is valid                  
        if(count(array_intersect($file_types,[$type]))>0){
            // images 

            $t_image = false; $t_org   = false; $t_ocr   = false;

			if(strtolower($type)=="image"){
				$t_image = $filename;
			    $path = storage_path('app/documents_images') . '/' . $filename;
			    $default_path = storage_path('app/documents_images') . '/' . 'default.png';
			}
			// original documents
			if(strtolower($type)=="org"){
				$t_org   = $filename;
			    $path = storage_path('app/documents_new') . '/' . $filename;
			    $default_path = storage_path('app/documents_new') . '/' . 'default.pdf';
			}
			// ocred documents 
			if(strtolower($type)=="ocr"){
				$t_ocr   = $filename;
				$path = storage_path('app/documents_ocred') . '/' . $filename;
				$default_path = storage_path('app/documents_ocred') . '/' . 'default.pdf';
			}

		    $validate = DB::table('documents')
		    ->where('doc_user_id', Auth::user()->id)
		    ->leftJoin('document_pages','documents.doc_id','=','document_pages.doc_id')
		    //when type is image
		    ->when($t_image, function ($query, $t_image) {
	            return $query->where('document_pages.doc_page_image_preview', $t_image)
	            ->orWhere('document_pages.doc_page_thumbnail_preview', $t_image);;
	        })
	        //when type is org
	        ->when($t_org, function ($query, $t_org) {
	            return $query->where('documents.doc_org', $t_org);
	        })
	        //when type is ocr
	        ->when($t_ocr, function ($query, $t_ocr) {
	            return $query->where('documents.doc_ocr', $t_ocr);
	        })
	        ->select('documents.doc_id')
	        ->get();

            if(count($validate)>0){
		    	$file = File::get($path);
				$type = File::mimeType($path);
				$response = Response::make($file, 200);
				$response->header("Content-Type", $type);
				return $response; 
            }else{
		    	$file = File::get($default_path);
				$type = File::mimeType($default_path);
				$response = Response::make($file, 200);
				$response->header("Content-Type", $type);
				return $response; 
            }

        }
    }
    
    public function publicFiles($type, $filename){
        
        /*
 		    add security features.
 		    *copy shared files to different directory where user can request the files.
        */

        $file_types = ['image','org','ocr']; 
        //check if filetype is valid                  
        if(count(array_intersect($file_types,[$type]))>0){
            // images 
			if(strtolower($type)=="image"){
			    $path = storage_path('app/documents_images') . '/' . $filename;
			    $default_path = storage_path('app/documents_images') . '/' . 'default.png';
			}
			// original documents
			if(strtolower($type)=="org"){
			    $path = storage_path('app/documents_new') . '/' . $filename;
			    $default_path = storage_path('app/documents_new') . '/' . 'default.pdf';
			}
			// ocred documents 
			if(strtolower($type)=="ocr"){
				$path = storage_path('app/documents_ocred') . '/' . $filename;
				$default_path = storage_path('app/documents_ocred') . '/' . 'default.pdf';
			}

	    	$file = File::get($path);
			$type = File::mimeType($path);
			$response = Response::make($file, 200);
			$response->header("Content-Type", $type);
			return $response; 

        }

    }


 
}
