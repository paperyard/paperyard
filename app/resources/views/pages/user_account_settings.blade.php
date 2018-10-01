@extends('layouts.app')

@section('page_title', 'Account settings')

@section('custom_style')
<style type="text/css" media="screen">

.lg-btn-tx {
	font-size:18px;
	color:#017cff;
	font-weight:bold
}
.lg-btn_x2 {
	width:220px;
	height:35px;
	border:none;
	border-radius:5px
}
.btn_color{
	background-color:#b1d5ff;
}

.input_label {
	color:#017cff;
    padding-left:5px; margin-top:10px;
}

/* --------------------------- breadcrumb nav--------------------------------*/
.arrows li {
    background-color:#b1d5ff;
    display: inline-block;
    line-height: 35px;
    padding: 0 15px 1px 10px;
    position: relative;
    z-index:5;
    border-radius:5px;
}

.li2 {
   margin-left:-10px; z-index:-1 !important; padding-left:20px !important;
}

/* arrows */
.arrows li::before,
.arrows li::after {
    border-right: 4px solid #017cff;
    content: '';
    display: block;
    height: 50%;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    transform: skewX(25deg);
}

.arrows li::after {
    bottom: 0;
    top: auto;
    transform: skewX(-25deg);

}

.arrows li:last-of-type::before,
.arrows li:last-of-type::after {
    display: none;
}

.arrows li a {
   font: bold 17px Sans-Serif;
   text-decoration: none;
   color:#017cff;
}


</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Account Settings</a></li>
  </ul>
@endsection

@section('content')

<!-- Vertical Layout -->
<div class="row clearfix" ng-controller="user_settings">

    @if(count($userData)>=1)
    <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:10px">
        <div class="card">
            <div class="header">
                <h2>
                    CHANGE EMAIL
                </h2>
            </div>
            <div class="body">

	           @if(session()->has('email_update'))
	                  <div class="alert alert-info">
	                    <p>{!! session('email_update') !!}</p>
	                  </div>
	           @endif

	           @if(session()->has('email_update_failed'))
	                  <div class="alert alert-danger">
	                    <p>{!! session('email_update_failed') !!}</p>
	                  </div>
	           @endif

                <form id="change_email_form" enctype="multipart/form-data" ng-submit="updateEmail(); $event.preventDefault();">
                    @csrf
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="email_address" value="{{ $userData->email }}" name="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <input type="password" id="e_password" name="e_password" class="form-control" placeholder="Password" required>
                        </div>
                        <p class="input_label">For verification, we need you to enter your password.</p>
                    </div>

                    <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 ng-hide" type="submit" ng-show="upd_btn">
                    	 <span class="lg-btn-tx" >Update</span>
                    </button>

                    <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 ng-hide" disabled ng-show="pw_btn">
                    	 <span class="lg-btn-tx" >Please wait</span>
                    </button>

                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-12 col-sm-12 col-xs-12">
   	    <br>
        <div class="card">
            <div class="header">
                <h2>
                    CHANGE PASSWORD
                </h2>
            </div>
            <div class="body">

	           @if (session()->has('password_updated'))
	                  <div class="alert alert-info">
	                    <p>{!! session('password_updated') !!}</p>
	                  </div>
	           @endif

	           @if (session()->has('password_updated_failed'))
	                  <div class="alert alert-danger">
	                    <p>{!! session('password_updated_failed') !!}</p>
	                  </div>
	           @endif

                <form  id="change_pass_form" enctype="multipart/form-data" ng-submit="updatePassword()">

                    <div class="form-group">
                        <div class="form-line">
                            <input type="password" ng-model="old_password" name="old_password" class="form-control" placeholder="Old password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <input type="password"  ng-keyup="checkPasswordMatch()" ng-model="new_password" name="new_password" class="form-control" placeholder="New password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <input type="password"  ng-keyup="checkPasswordMatch()" ng-model="confirm_new_password" name="confirm_new_password" class="form-control" placeholder="Confirm new password" required>
                        </div>
                        <p class="input_label ng-hide" style="color:red" ng-show="p_not_match">Password does not match</p>
                    </div>

                   <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 ng-hide" id="upd_pass_btn" type="submit" ng-show="upd_btn">
                    	 <span class="lg-btn-tx" >Update</span>
                    </button>

                    <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 ng-hide" disabled ng-show="pw_btn">
                    	 <span class="lg-btn-tx" >Please wait</span>
                    </button>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')

<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', []);

app.controller('user_settings', function($scope, $http, $timeout) {

   // show/hide submit buttons
   $scope.pw_btn = false;
   $scope.upd_btn = true;
   $scope.p_not_match = false;

   // update email function
   $scope.updateEmail = function(){

	    $scope.pw_btn = true;
	    $scope.upd_btn = false;
	    $scope.p_not_match = false;

        var form = $('#change_email_form');
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }

        $.ajax({
            url: '/account_settings/email_update',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                window.location.reload();
            }
        }); //end ajax
   }

   // change password
   $scope.updatePassword = function(){

   	    $scope.pw_btn = true;
	    $scope.upd_btn = false;

        var form = $('#change_pass_form');
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }

        $.ajax({
            url: '/account_settings/passowrd_update',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                window.location.reload();
            }
        }); //end ajax

   }

   //check input password match
   $scope.checkPasswordMatch = function(){

      	if($scope.new_password!=$scope.confirm_new_password){
      		$scope.p_not_match = true;
   	    	$('#upd_pass_btn').attr("disabled", "disabled");
	    }else{
	    	$scope.p_not_match = false;
   	    	$('#upd_pass_btn').removeAttr("disabled");
	    }
   }


});

</script>
@endsection

