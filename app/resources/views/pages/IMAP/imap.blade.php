@extends('layouts.app')

@section('page_title', 'IMAP')

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

.imap_input {
	margin-top:-20px;
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
<div class="row" ng-controller="imap_controller">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="block-header">
			<h2>
			1. Creating new credentials.
			<small>System will do a <span style="color:#017cff">test connect</span> before saving your credentials, please make sure you have inputed correct information.</small>
			</h2><br>
			<h2>
			2. Host configuration.
			<small>* Enable <span style="color:#017cff">IMAP Forwarding</span> from your mail provider settings. Some mail provider imap forwarding is auto enabled. </small>
			<small>* Enable <span style="color:#017cff"> Less Secure Apps</span> from your mail provider settings. Mail providers like Gmail will not let <span style="color:#017cff">non-google apps to connect</span>.</small>
			</h2><br>
			<h2>
			3. Notes.
			<small>IMAP will start importing your emails attachments from the date you created the credentials.</small>
			</h2>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="body" >
				<!-- Nav tabs -->
				<ul class="nav nav-tabs tab-nav-right" role="tablist">
					<li role="presentation" class="active"><a href="#new_imap" data-toggle="tab">ADD IMAP CREDENTIALS</a></li>
					<li role="presentation"><a href="#list_imap" data-toggle="tab">IMAP CREDENTIALS</a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="new_imap"  style="margin-bottom:-50px"><br><br>
						<div class="row">
							<form enctype="multipart/form-data"  id="imap_credentials_form" name="imap_credentials_form"  ng-submit="newImapCredentials(); $event.preventDefault();">
								<!--  HOST -->
								<div class="col-md-12 col-sm-12 col-xs-12 imap_input">
									<div class="input-group " >
										<span class="input-group-addon ">
											<i class="material-icons">dns</i>
										</span>
										<div class="form-line">
											<input type="text" class="form-control" placeholder="Host. (E.g. imap.gmail.com, imap.mail.yahoo.com)" name="imap_host" ng-model="imap_form['host']"  required>
										</div>
									</div>
								</div>
								<!--  EMAIL -->
								<div class="col-md-12 col-sm-12 col-xs-12 imap_input">
									<div class="input-group " >
										<span class="input-group-addon ">
											<i class="material-icons">email</i>
										</span>
										<div class="form-line">
											<input type="email" class="form-control" placeholder="Email" name="imap_email"  ng-model="imap_form['email']" required>
										</div>
									</div>
								</div>
								<!--  PASSWORD -->
								<div class="col-md-12 col-sm-12 col-xs-12 imap_input">
									<div class="input-group " >
										<span class="input-group-addon ">
											<i class="material-icons">lock</i>
										</span>
										<div class="form-line">
											<input type="password" class="form-control" placeholder="Password" name="imap_password" ng-model="imap_form['password']" required>
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
					<div role="tabpanel" class="tab-pane fade" id="list_imap">
						<div class="card" ng-repeat="data in imap_cred_list track by $index">
							<div class="header" style="z-index:5">
								<label><span style="color:#017cff">Host &nbsp&nbsp:</span> <# data.imap_host #></label><br>
								<label><span style="color:#017cff">Email :</span> <# data.imap_username #></label>
								<ul class="header-dropdown m-r--5">
									<li class="dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
											<i class="material-icons">more_vert</i>
										</a>
										<ul class="dropdown-menu pull-right">
											<li><a ng-click="deleteImapCredential(data.imap_id)">Delete </a></li>
										</ul>
									</li>
								</ul>
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



app.controller('imap_controller', function($scope, $http, $timeout) {

$scope.imap_form = [];
$scope.imap_cred_list = [];

//get list of imap credentials
$scope.listOfCredentials = function(){
    $http.get('/imap/list_of_credentials').success(function(data){
    	 $scope.imap_cred_list = data;
    	 console.log(data);
    });
}
// run function
$scope.listOfCredentials();


$scope.wait = function(){
    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });
}

//save imap credentials
$scope.newImapCredentials = function(){

	$scope.wait();

    var form = $('#imap_credentials_form');
    var formdata = false;
	if (window.FormData) {
	  formdata = new FormData(form[0]);
	}

	$.ajax({
	  url: '/imap/save_new_credentials',
	  data: formdata ? formdata : form.serialize(),
	  cache: false,
	  contentType: false,
	  processData: false,
	  type: 'POST',
	  success: function(data) {

	  	   $('.card').waitMe("hide");
	  	   if(data=="success"){
	  	   	  //clear form input
	  	   	  $scope.imap_form = [];
	  	   	  $scope.listOfCredentials();
	  	   	  swal("Success", "New credentials added and connected", "success");
	  	   }else{
	  	   	  swal("Error", "Please check inputed information and mail provider configuration.", "error");
	  	   }
	  }
	}); //end ajax
}

$scope.deleteImapCredential = function(imap_id){
	
	data = { imap_id:imap_id }
    $http({method:'POST',url:'/imap/delete_credentials', data}).success(function(data){
    	$scope.listOfCredentials();
        swal("Success", "IMAP credentials deleted", "success");
    });
}



});

</script>
@endsection

