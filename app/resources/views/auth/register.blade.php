@extends('layouts.auth')

@section('style')
    .lg-box {
       margin-top:90px
    }
    .lg-box-title {
       font-size:17px;
       padding-bottom:20px
    }
    .lg-btn-tx {
       font-size:18px;
       color:017cff;
       font-weight:bold
    }
    .lg-btn {
      width:200px;
      height:35px;
      border:none;
      border-radius:5px
    }
    .btn_color{
       background-color:#b1d5ff
    }
    .error_crid {
       color:red;
    }
@endsection

@section('content')

<div class="signup-box col-md-6 col-md-offset-3 lg-box">

        <p class="text-center align-middle lg-box-title main_color">@lang('auth.reg_w')</p>
        <div class="card">
            <div class="body">
                <form id="sign_up" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-group">
                         <div class="form-line">
                                <input id="name" type="text" class="customInputStyle form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"  placeholder="@lang('auth.reg_name_p_holder')" name="name" value="{{ old('name') }}" required autofocus>
                         </div>
                    </div>
                    <div class="input-group">
                        <div class="form-line">
                            <input id="email" type="email" class="customInputStyle form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="@lang('auth.reg_email_p_holder')" name="email" value="{{ old('email') }}" required>
                        </div>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong class="error_crid">{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="input-group">
                         <div class="form-line">
                            <input id="password" type="password" class="customInputStyle form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="@lang('auth.reg_password_p_holder')" name="password" required>
                        </div>
                         @if ($errors->has('password'))
                             <span class="invalid-feedback">
                                 <strong class="error_crid">{{ $errors->first('password') }}</strong>
                             </span>
                         @endif
                    </div>
                    <div class="input-group">
                         <div class="form-line">
                            <input id="password-confirm" type="password" class="customInputStyle form-control" name="password_confirmation" placeholder="@lang('auth.reg_repassword_p_holder')" required>
                        </div>
                    </div>

                     <div class="row">
                        <div class="col-xs-12 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-light-blue" required>
                            <label for="rememberme" >@lang('auth.cbox_t1') <a href="#">@lang('auth.cbox_t2')</a>.</label>
                        </div>
                        <div class="col-md-12 col-xs-12 text-center">
                            <button class="btn-flat btn_color main_color waves-effect lg-btn" type="submit"><span class="lg-btn-tx">@lang('auth.reg_btn_tx')</span></button>
                        </div>
                    </div>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="{{ route('login') }}">@lang('auth.membership_tx')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
