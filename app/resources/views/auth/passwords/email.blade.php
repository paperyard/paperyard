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
    width:250px;
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
  <p class="text-center align-middle lg-box-title main_color">Password reset</p>
  <div class="card" >
    <div class="body" >

      @if (session('status'))
      <div class="alert alert-info">
        {{ session('status') }}
      </div>
      @endif
      @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> Something went wrong.<br><br>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
          <div class="form-line">
            <input id="email" type="email" class="customInputStyle form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"  placeholder="@lang('auth.email_p_holder')" required autofocus>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-xs-12 text-center">
            <button class="btn-flat btn_color main_color waves-effect lg-btn" type="submit"><span class="lg-btn-tx">{{ __('Send Password Reset Link') }}</span></button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection

@section('scripts')

@endsection

