@extends('layouts.auth')

@section('style')

.lg-box {
    margin-top:100px
}

@endsection

@section('content')

<div class="signup-box col-md-6 col-md-offset-3 lg-box">
    <div class="alert alert-info">
        Paperyard sent a link to verify your email address. <br>
        Please login to your email and click the link to verify your account.
    </div>
</div>
@endsection
