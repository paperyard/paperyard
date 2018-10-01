<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use DB;


class remindersController extends Controller
{
    // return list of reminders
    public function index(){

    	$reminder_exist = DB::table('reminders')->where('reminder_user_id', Auth::user()->id)->get();
    	return view('pages/reminders')->with(compact('reminder_exist'));
    }

    // request for reminders
    public function getReminders(){
    	$reminder = DB::table('reminders')
    	->where([
           ['reminder_user_id','=',Auth::user()->id]
    	])
    	->leftJoin('documents', 'reminders.reminder_document_id','=','documents.doc_id')
    	->select('reminders.*','documents.doc_ocr')
    	->get();

    	$json_response = json_encode($reminder);
        // # Return the response
        return $json_response;
    }

    // return create reminder page
    public function createReminder(){
    	return view('pages/reminders_create');
    }

    //autocomplete docs attach
    public function reminderDocuments(Request $req){
    	$docs = DB::table('documents')
    	->where([
           ['doc_user_id','=',Auth::user()->id],
           ['process_status','=','ocred_final']
    	])
      ->where('doc_ocr', 'LIKE', '%' . $req->doc_keyword . '%')
    	->select('documents.doc_ocr','documents.doc_id')->get();

    	$json_response = json_encode($docs);
        // # Return the response
      return $json_response;
    }

    // save reminder
    public function save_updateReminder(Request $req){


        // set user time zone.
        date_default_timezone_set(Auth::user()->user_timezone);

        if($req->has('attach_doc_id')){
       	   $doc_id = $req->attach_doc_id;
        }else{
       	   $doc_id = null;
        }

        $message =  str_replace('↵','<br>', $req->rm_message);

        if($req->has('save_reminder')){

	        $new_rm = DB::table('reminders')
	        ->insert([
	            'reminder_user_id'      =>  Auth::user()->id,
	            'reminder_document_id'  =>  $doc_id,
	            'reminder_title'        =>  $req->rm_title,
	            'reminder_message'      =>  $message,
	            'reminder_schedule'     =>  $req->rm_time,
	            "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
	            "updated_at" =>  \Carbon\Carbon::now(),  # \Datetime()
	        ]);

	        if(count($new_rm)>=1){
	            session()->flash('reminder_saved', 'New reminder created.');
	            return "success";
	        }else{
	            return "failed";
	        }
        }

        if($req->has('update_reminder')){

            $update_rm = DB::table('reminders')
            ->where([
               ['reminder_user_id','=',Auth::user()->id],
               ['reminder_id','=',$req->rm_id]
            ])
	        ->update([
	            'reminder_document_id'  =>  $doc_id,
	            'reminder_title'        =>  $req->rm_title,
	            'reminder_message'      =>  $message,
	            'reminder_schedule'     =>  $req->rm_time,
              'reminder_status'       =>  'standby',
	            "updated_at" => \Carbon\Carbon::now(),  # \Datetime()
	        ]);

	        if(count($update_rm)>=1){
	            session()->flash('reminder_updated', 'Reminder successfully updated.');
	            return "success";
	        }else{
	            return "failed";
	        }

        }

    }

    // delete reminder
    public function deleteReminder(Request $req){

          $del_rm = DB::table('reminders')
          ->where([
              ['reminder_user_id','=',Auth::user()->id],
              ['reminder_id','=',$req->rm_id]
          ])->delete();

          if(count($del_rm)>0){
          	 return "success";
          }else{
          	 return  "failed";
          }
    }

    public function editReminder($reminder_id){

    	 $reminder = DB::table('reminders')
    	 ->where([
            ['reminder_user_id','=',Auth::user()->id],
            ['reminder_id','=',$reminder_id]
    	 ])
       ->leftJoin('documents','reminders.reminder_document_id','=','documents.doc_id')
       ->select('reminders.*','documents.doc_ocr')
    	 ->first();

    	 if(count($reminder)>=1){
    	 	return view('pages/reminders_edit')->with(compact('reminder'));
    	 }else{
    	 	return redirect('error_404');
    	 }
    }

    
}
