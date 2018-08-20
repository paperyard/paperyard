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
        return view('pages/admin_dashboard');
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
            "updated_at" =>  \Carbon\Carbon::now(),  # \Datetime()
         ]);
         echo "admin succesfully created.";
    }


        // try {

        // }catch(\Illuminate\Database\QueryException $err){
        //    return "error";
        //   // Note any method of class PDOException can be called on $err.
        // }


}
