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
use Mail;
use Spatie\PdfToImage\Pdf;


class customizePdfController extends Controller
{
    //
    public function index($doc_id){

        //check if document id exist in specific user
    	$check = DB::table('documents')
    	->where('doc_user_id', Auth::user()->id)
    	->where('doc_id', $doc_id)
    	->first();

        if(count($check)>=1){
            return view('pages/customize_pdf')->with(compact('doc_id'));
        }else{
        	return redirect('error.404');
        }
    }

    public function returnCustomizeDoc(Request $req){

    	$doc_pages = DB::table('documents')
    	->where('doc_user_id', Auth::user()->id)
    	->where('documents.doc_id', $req->doc_id)
    	->join('document_pages','documents.doc_id','=','document_pages.doc_id')
    	->select('documents.doc_id','documents.doc_ocr','document_pages.doc_page_num','document_pages.doc_page_image_preview')
        ->orderBy('doc_page_num','ASC')
        ->get();
        return  json_encode($doc_pages);

    }

    public function removeDocPages(Request $req){

        // documents locations
        $docLocal   =  storage_path('app/documents_ocred') . '/' . $req->doc_name;
        $doc_output =  storage_path('app/documents_ocred') . '/' . "output" . str_random(8) . ".pdf";
		$pdf = new Pdf($docLocal);

		// get document total pages.
		$max_page = $pdf->getNumberOfPages();

	    // make array of pages with max page as max num.
		$maxPage = range(1, $max_page);

        //this page will be left from document.
		$safe_page =   array_diff($maxPage, $req->doc_pages);
        $safe_pages =  implode(" ",$safe_page);

        //remove pages
		$flags = "pdftk $docLocal cat $safe_pages output $doc_output && mv $doc_output $docLocal";
		$process = new Process($flags);
		$process->disableOutput();
		$process->start();
		$process->wait();

        //delete images
		$deleteDocPages = DB::table('document_pages')
		->where('doc_id',$req->doc_id)
		->whereIn('doc_page_num',$req->doc_pages)
		->select('document_pages.doc_page_image_preview','document_pages.doc_page_thumbnail_preview')
		->get();
		foreach($deleteDocPages as $dp){
              $file1 = storage_path('app/documents_images') . '/' . $dp->doc_page_image_preview;
              File::delete((string)$file1);
              $file2 = storage_path('app/documents_images') . '/' .$dp->doc_page_thumbnail_preview;
              File::delete((string)$file2);
		}
        //delete datas
		$deleteDocDatas = DB::table('document_pages')
		->where('doc_id',$req->doc_id)
		->whereIn('doc_page_num',$req->doc_pages)
		->delete();

	    return "success";
    }

    public function rotateDocPages(Request $req){

        //qpdf in.pdf out.pdf --rotate=+90:2,4,6
        // documents locations
        $docLocal   = storage_path('app/documents_ocred') . '/' . $req->doc_name;
        $doc_output = storage_path('app/documents_ocred') . '/' . "output" . str_random(8) . ".pdf";


        $doc_pages =  implode(",",$req->doc_pages);
        $degress = null;
        $degreesImgInter = null;

        //image intervention is reversed. +90 becomes counterclockwise. in normal cases +90 = clockwise rotation
        if($req->rotation=='rr90'){
            $degress = '+90';
            $degreesImgInter = '-90';
        }
        if($req->rotation=='rl90'){
            $degress = '-90';
            $degreesImgInter = '+90';
        }
        if($req->rotation=='rf180'){
            $degress = '+180';
            $degreesImgInter = '-180';
        }

        $params = "qpdf $docLocal $doc_output --rotate=$degress:$doc_pages && mv $doc_output $docLocal";

		$process = new Process($params);
		$process->disableOutput();
		$process->start();
		$process->wait();

		$rotate_doc_images = DB::table('document_pages')
		->where('doc_id',$req->doc_id)
		->whereIn('doc_page_num',$req->doc_pages)
		->select('document_pages.doc_page_image_preview','document_pages.doc_id')
		->get();
		foreach($rotate_doc_images as $key=>$dp){

            $new_file_name = str_random(10)."-".$dp->doc_page_image_preview;
            $file1 = storage_path('app/documents_images') . '/' . $dp->doc_page_image_preview;
            $file2 = storage_path('app/documents_images') . '/' . $new_file_name;

			$img = Image::make($file1);
			// rotate image 45 degrees clockwise
			$img->rotate($degreesImgInter);
			$img->save($file2);

            File::delete((string)$file1);

            $updateName = DB::table('document_pages')
            ->where('doc_id',$dp->doc_id)
            ->where('doc_page_image_preview',$dp->doc_page_image_preview)
            ->update(['doc_page_image_preview'=>$new_file_name]);

		}


	    return "success";
    }

}
