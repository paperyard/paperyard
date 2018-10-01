@extends('layouts.auth')

@section('style')

.lg-box {
    margin-top:90px
}
.lg-box-title {
    font-size:17px;
    padding-bottom:30px
}
.lg-btn-tx {
  font-size:18px;
  color:#017cff;
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

<div class="login-box col-md-offset-3 col-md-6 lg-box">
  <p class="text-center align-middle lg-box-title main_color">@lang('auth.sign_in_w') </p>
  <div class="card" >
    <div class="body" >
      <form id="sign_in" method="POST" action="{{ route('login') }}"><br>
        @csrf

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="form-group">
          <div class="form-line">
            <input id="email" type="email" class="customInputStyle form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"  placeholder="@lang('auth.email_p_holder')" required autofocus>
          </div>
        </div>
        <div class="input-group">
          <div class="form-line">
            <input id="password" type="password" class="customInputStyle form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="@lang('auth.password_p_holder')" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-xs-12 text-center">
            <button class="btn-flat btn_color main_color waves-effect lg-btn" type="submit"><span class="lg-btn-tx">@lang('auth.login_btn_txt')</span></button>
          </div>
        </div>
        <div class="row m-t-15 m-b--20">
          <div class="col-xs-6">
            <a href="{{ route('register') }}">@lang('auth.register_btn_txt')</a>
          </div>
          <div class="col-xs-6 align-right">
            <!--  <a href="{{ route('password.request') }}">Passwort vergessen?</a> -->
            <a href="{{ route('password.request') }}">@lang('auth.change_password_txt')</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')

@endsection
