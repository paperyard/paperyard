@extends('layouts.app')

@section('page_title', 'FTP')

@section('custom_style')
<link href="{{ asset('static/css/imap.css') }}" rel="stylesheet">
<style type="text/css" media="screen">
.table_gl tr td {
	padding:0px !important;
	margin:0px !important;
	border:0px !important;
}
.inp_share:hover {
	background-color:#fff;
	cursor: grab !important;
}
/* -------------------- paperyard custom button ----------------------------*/

.lg-btn-tx {
	font-size:18px;
	color:#017cff;
	font-weight:bold
}
.lg-btn_x2 {
    padding-left:30px;
    padding-right:30px;
	height:35px;
	border:none;
	border-radius:5px
}
.btn_color{
	background-color:#b1d5ff
}

/*---------------------------------------------------------------------------*/
.w_folder {
	margin-top:50px
}
.w_folder_icon {
	color:#b1d5ff; font-size:100px
}
.w_folder_tx {
	color:#7e7e7e; font-size:22px
}

/* ----------------------------- breadcrumb nav -------------------------------*/
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
/* ------------------------------------------------------------------------*/

.ftp_input {
	margin-top:-15px;
}
</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#">IMAP</a></li>
  </ul>
@endsection

@section('content')
<!-- Hover Rows -->
<div class="row" ng-controller="ftp_controller">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="block-header">
			<h2>
			1. Creating new FTP credentials.
			<small>System will do a <span style="color:#017cff">test connect</span> before saving your credentials, please make sure you have inputed correct information.</small>
			</h2>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="body" >
				<!-- Nav tabs -->
				<ul class="nav nav-tabs tab-nav-right" role="tablist">
					<li role="presentation" class="active"><a href="#new_ftp" data-toggle="tab">NEW FTP CREDENTIALS</a></li>
					<li role="presentation"><a href="#list_ftp" data-toggle="tab">FTP CREDENTIALS</a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="new_ftp"  style="margin-bottom:-50px"><br><br>
						<div class="row">
							<form enctype="multipart/form-data"  id="ftp_credentials_form" name="ftp_credentials_form"  ng-submit="newFtpCredentials(); $event.preventDefault();">
								<!--  HOST -->
								<div class="col-md-12 col-sm-12 col-xs-12 ftp_input">
									<div class="input-group " >
										<span class="input-group-addon ">
											<i class="material-icons">dns</i>
										</span>
										<div class="form-line">
											<input type="text" class="form-control" placeholder="Host" name="ftp_host" ng-model="ftp_form['host']"  required>
										</div>
									</div>
								</div>
								<!--  USERNAME -->
								<div class="col-md-12 col-sm-12 col-xs-12 ftp_input">
									<div class="input-group " >
										<span class="input-group-addon ">
											<i class="material-icons">person</i>
										</span>
										<div class="form-line">
											<input type="text" class="form-control" placeholder="Username" name="ftp_username"  ng-model="ftp_form['email']" required>
										</div>
									</div>
								</div>
								<!--  PASSWORD -->
								<div class="col-md-12 col-sm-12 col-xs-12 ftp_input">
									<div class="input-group " >
										<span class="input-group-addon ">
											<i class="material-icons">lock</i>
										</span>
										<div class="form-line">
											<input type="password" class="form-control" placeholder="Password" name="ftp_password" ng-model="ftp_form['password']" required>
										</div>
									</div>
								</div>
								<!--  PORT -->
								<div class="col-md-12 col-sm-12 col-xs-12 ftp_input">
									<div class="input-group " >
										<span class="input-group-addon ">
											<i class="material-icons">swap_vertical_circle</i>
										</span>
										<div class="form-line">
											<input type="text" class="form-control" placeholder="Port" name="ftp_port"  ng-model="ftp_form['port']" required>
										</div>
									</div>
								</div>
								<div class="col-md-12 ">
									<div class="form-group">
										<button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">Save Credentials</span></button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="list_ftp">
						<div class="card" ng-repeat="data in ftp_cred_list track by $index" style="margin-bottom:20px;">
							<div class="header" style="z-index:5">
								<label><span style="color:#017cff">Host &nbsp&nbsp:</span> <# data.ftp_host #></label><br>
								<label><span style="color:#017cff">Email :</span> <# data.ftp_username #></label><br>
								<label><span style="color:#017cff">Port &nbsp&nbsp&nbsp:</span> <# data.ftp_port #></label>
								<ul class="header-dropdown m-r--5">
									<li class="dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
											<i class="material-icons">more_vert</i>
										</a>
										<ul class="dropdown-menu pull-right">
											<li><a ng-click="deleteFTPCredential(data.ftp_id)">Delete </a></li>
										</ul>
									</li>
								</ul>
							</div>
							<div class="body" style="height:70px">
								<div class="row"  >
									<div class="col-md-12" >
										<a href="/ftp_connect/<#data.ftp_id#>"><button type="button" class="btn btn-primary waves-effect">Connect</button></a>
									</div>
								</div>
							</div>
							</div> <!-- card -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/imap.js') }}"></script>
<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', []);



app.controller('ftp_controller', function($scope, $http, $timeout) {

$scope.ftp_form = [];
$scope.ftp_cred_list = [];

//get list of imap credentials
$scope.listOfCredentials = function(){
    $http.get('/ftp_create_credentials/list_of_credentials').success(function(data){
    	 $scope.ftp_cred_list = data;
    	 console.log(data);
    });
}
$scope.listOfCredentials();

$scope.wait = function(){
    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });
}

//save ftp credentials
$scope.newFtpCredentials = function(){

	$scope.wait();
    var form = $('#ftp_credentials_form');
    var formdata = false;
	if (window.FormData) {
	  formdata = new FormData(form[0]);
	}

	$.ajax({
	  url: '/ftp_create_credentails/new_credential',
	  data: formdata ? formdata : form.serialize(),
	  cache: false,
	  contentType: false,
	  processData: false,
	  type: 'POST',
	  success: function(data) {

	  	   $('.card').waitMe("hide");
	  	   if(data=="success"){
	  	   	  $scope.ftp_form = [];
	  	   	  $scope.listOfCredentials();
	  	   	  swal("Success", "New FTP credentials added", "success");
	  	   }else{
	  	   	  swal("Error", "Cannot connect to server", "error");
	  	   }
	  }
	}); //end ajax
}

$scope.deleteFTPCredential = function(ftp_id){
	data = { ftp_id:ftp_id }
    $http({method:'POST',url:'/ftp_create_credentails/delete', data}).success(function(data){
    	$scope.listOfCredentials();
        swal("Success", "FTP credentials deleted", "success");
    });
}


});

</script>
@endsection

