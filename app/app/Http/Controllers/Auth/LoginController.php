<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
      public function redirectTo()
      {
        if(Auth::user()->privilege=="user"){
            return '/dashboard';
        }
        if(Auth::user()->privilege=="admin"){
            return '/admin_dashboard';
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function credentials(Request $request)
    {
        // // check if verified
        // return [
        //     'email' => $request->email,
        //     'password' => $request->password,
        //     'verified' => 1,
        // ];

        return [
            'email' => $request->email,
            'password' => $request->password
        ];
    }
}
