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
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <!-- name -->
                <div class="input-group">
                    <div class="form-line">
                        <input id="name" type="text" class="customInputStyle form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"  placeholder="@lang('auth.reg_name_p_holder')" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>
                <!-- email -->
                <div class="input-group">
                    <div class="form-line">
                        <input id="email" type="email" class="customInputStyle form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="@lang('auth.reg_email_p_holder')" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                <!-- password -->
                <div class="input-group">
                    <div class="form-line">
                        <input id="password" type="password" class="customInputStyle form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="@lang('auth.reg_password_p_holder')" name="password" required>
                    </div>
                </div>
                <!-- confirm password -->
                <div class="input-group">
                    <div class="form-line">
                        <input id="password-confirm" type="password" class="customInputStyle form-control" name="password_confirmation" placeholder="@lang('auth.reg_repassword_p_holder')" required>
                    </div>
                </div>

                <!-- terms -->
                <div class="row">
                    <div class="col-xs-12 p-t-5">
                     <!-- checkbox -->
                     <div class="form-group">    
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-light-blue" >
                        <label for="terms">@lang('auth.cbox_t1') <a href="#" data-toggle="modal" data-target="#largeModal">@lang('auth.cbox_t2')</a>.</label>
                     </div>   
                    </div>
                    <!-- register button -->
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

<!-- Terms coniditions large modal -->
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Terms and Conditions</h4>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan
                vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper.
                Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus
                nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla.
                Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.</p>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan
                vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper.
                Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus
                nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla.
                Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.</p>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sodales orci ante, sed ornare eros vestibulum ut. Ut accumsan
                vitae eros sit amet tristique. Nullam scelerisque nunc enim, non dignissim nibh faucibus ullamcorper.
                Fusce pulvinar libero vel ligula iaculis ullamcorper. Integer dapibus, mi ac tempor varius, purus
                nibh mattis erat, vitae porta nunc nisi non tellus. Vivamus mollis ante non massa egestas fringilla.
                Vestibulum egestas consectetur nunc at ultricies. Morbi quis consectetur nunc.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('static/js/register.js') }}"></script>
<script type="text/javascript">
 $(function () {
    $('#sign_up').validate({
        rules: {
            'terms': {
                required: true
            },
            // name of retype password
            'password_confirmation': {
                // name of password input
                equalTo: '[name="password"]'
            }
        },
        highlight: function (input) {
            console.log(input);
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        }
    });
});   
</script>
@endsection

