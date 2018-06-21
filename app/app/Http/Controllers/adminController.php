<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// symfony process for running sub-process.
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpProcess;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;

//use to access auth in controller.
use Illuminate\Support\Facades\Auth;
use DB;

class adminController extends Controller
{

    // return list of document with applied ocr.
    public function index()
    {
        $ocr_documents = DB::table('test_docs')->orderBy('created_at', 'desc')->get();
        return view('pages/admin_dashboard')->with(compact('ocr_documents'));
    }
    // generate admin account / test only.
    public function generateAdmin(){
         $admin = DB::table('users')->insert
         ([
            'privilege'=>'admin',
            'name'=>'Admin',
            'email'=>'admin@secure.com',
            'password'=>bcrypt('root'),
            "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
            "updated_at" => \Carbon\Carbon::now(),  # \Datetime()
         ]);
         echo "admin succesfully created.";
    }
    // run ocrmypdf on a document with symfony process component.
    public function runOCRMYPDF(Request $req){
        // use try catch for errors.
        try{
            //check if file exist.
            if($req['doc_file']!=NULL)
                {
                    //store request file to variable.
                    $file =  $req['doc_file'];
                    $ext = $file->getClientOriginalExtension();
                    //check if file is pdf .
                    if(strtoupper($ext)=='PDF'){
                        // use timestamps for naming.
                        $time = time();
                        // filename for original document.
                        $filename_org = Auth::User()->id . '-' . $time . '.' .  $ext;
                        // filename for applied ocr
                        $filename_ocr = Auth::User()->id . '-' . $time . '-' . 'ocr' . '.' .  $ext;
                        //upload file diskname->directory->file->filename.
                        //disk configuration can be found at config/filesystem.php.
                        Storage::disk('documents')->putFileAs('documents', $file, $filename_org);
                        //save filenames to database.
                        DB::table('test_docs')->insert
                         ([
                            'doc_origin'=>$filename_org,
                            'doc_ocr'=>$filename_ocr,
                            "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
                            "updated_at" => \Carbon\Carbon::now(),  # \Datetime()
                         ]);
                        //location of original doc.
                        $f_src = "static/documents/" . $filename_org;
                        //location for where the document with applied ocr will be stored.
                        $f_dst = "static/documents/" . $filename_ocr;
                        //flags for ocrmypdf. flags are available ata ocrmypdf documentation.
                        $params = "ocrmypdf --output-type pdf --rotate-pages --remove-background --deskew";
                        //combine ocrmypdf params + source + destination of document. | convert to string
                        //smyfony process accepts only string as parameter.
                        //see symfony process component for more information.
                        $proc = $params . " " . $f_src . " " . $f_dst;
                        $scan = (string)$proc;

                        $process = new Process($scan);
                        $process->start();
                        // do other stuff
                        // ... wait for process to complete
                        $process->wait();

                        return "complete";

                    }elseif(strtoupper($ext)=='JPG' || strtoupper($ext)=='PNG'){
                       // convert images to pdf | apply image processing and ocr
                        // use timestamps for naming.
                        $time = time();
                        // filename for original document img.
                        $filename_img = Auth::User()->id . '-' . $time . '.' .  $ext;
                        // file name for converted img to pdf
                        $filename_org = Auth::User()->id . '-' . $time . '.pdf';
                        // filename for applied ocr
                        $filename_ocr = Auth::User()->id . '-' . $time . '-' . 'ocr' . '.pdf';

                        Storage::disk('documents')->putFileAs('documents', $file, $filename_img);
                        //save filenames to database.
                        DB::table('test_docs')->insert
                        ([
                            'doc_img'=>$filename_img,
                            'doc_origin'=>$filename_org,
                            'doc_ocr'=>$filename_ocr,
                            "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
                            "updated_at" => \Carbon\Carbon::now(),  # \Datetime()
                        ]);

                        $img_src = "static/documents/" . $filename_img;
                        $img_to_pdf_dst = "static/documents/" . $filename_org;
                        $pdf_with_ocr = "static/documents/" . $filename_ocr;
                        //img2pdf flags
                        $param1 = "img2pdf --output";
                        //ocrmypdf flags
                        $param2 = "ocrmypdf --output-type pdf --rotate-pages --remove-background --deskew";

                        //unite for image to pdf conversion
                        $proc1 = $param1 . " " . $img_to_pdf_dst . " " . $img_src;
                        $conver_img_to_pdf = (string)$proc1;
                        //unvite for using ocr on pdf
                        $proc2 = $param2 . " " . $img_to_pdf_dst . " " . $pdf_with_ocr;
                        $apply_ocr_on_pdf = (string)$proc2;
                        //==========================================================
                        //start process
                        $p1 = new Process($conver_img_to_pdf);
                        $p1->start();
                        $p1->wait();

                        $p2 = new Process($apply_ocr_on_pdf);
                        $p2->start();
                        $p2->wait();

                        return "complete";

                    }else{
                        return "file is not a image or pdf";
                    }

                }
        }catch(\Exception $e){
            //something error here
        }

    }

    //remove document names from database and remove from storage.
    public function removeFILE($id,$filename_org,$filename_ocr,$filename_img){
        //remove filesnames from database where id = param id
        DB::table('test_docs')->where('id', $id)->delete();
        //location and filename of documents to be deleted
        $file_org = 'static/documents/' . $filename_org;
        $file_ocr = 'static/documents/' . $filename_ocr;
        $file_img = 'static/documents/' . $filename_img;
        //delete documents
        File::delete((string)$file_org);
        File::delete((string)$file_ocr);
        File::delete((string)$file_img);

        //session flash an alert.
        $msg  = $filename_ocr . 'successfully deleted!';
        session()->flash('fileDeleted', $msg);
        //redirect user to previous route.
        return redirect()->back();
    }


    public function tt(){
         $img_to_pdf = new Process('img2pdf --output static/documents/use_this_img.pdf static/documents/use_this_img.png');
         $pdf_ocr = new Process('ocrmypdf static/documents/use_this_img.pdf static/documents/final_use_this_img.pdf');

         // convert image to pdf then wait for process to finish
         $img_to_pdf->start();
         $img_to_pdf->wait();
         // apply ocr on pdf then wait for process to finish
         $pdf_ocr->start();
         $pdf_ocr->wait();


         echo "success";
    }

}
