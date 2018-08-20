<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class userController extends Controller
{
    // RETURN ACCOUNT SETTINGS
    public function accountSettings(){
          $userData = DB::table('users')->where('id', Auth::user()->id)->first();
    	  return view('pages/user_account_settings')->with(compact('userData'));
    }

    // UPDATE EMAIL ADDRESS
    public function emailUpdate(Request $req){
		try {
            // check if password match
            $check = DB::table('users')->where([
              ['id','=',Auth::user()->id],
            ])->first();
            if (Hash::check($req->e_password, $check->password)) {
                 // The password match... update email
                 DB::table('users')->where('id', Auth::user()->id)->update(['email'=>$req->email]);
                 session()->flash('email_update', 'Your email has been updated.');
                 return "success_updated";
            }
            session()->flash('email_update_failed', 'Invalid password.');
            return "failed";
		}catch(\Illuminate\Database\QueryException $err){
			session()->flash('Failed', 'Something went wrong.');
		    return $err;
		    // Note any method of class PDOException can be called on $err.
		}
        //end tryCatch
    }

    // CHANGE PASSWORD
    public function passwordUpdate(Request $req){
    	try {
            // check if password match
            $check = DB::table('users')->where([
              ['id','=',Auth::user()->id],
            ])->first();
            if (Hash::check($req->old_password, $check->password)) {
                 // The password match... update password
                 DB::table('users')->where('id', Auth::user()->id)->update(['password'=>bcrypt($req->new_password)]);
                 session()->flash('password_updated', 'Your password has been updated.');
                 return "success_updated";
            }
            session()->flash('password_updated_failed', 'Invalid old password.');
            return "failed";
		}catch(\Illuminate\Database\QueryException $err){
			session()->flash('Failed', 'Something went wrong.');
		    return $err;
		    // Note any method of class PDOException can be called on $err.
		}
		//end tryCatch
    }

}
