@extends('layouts.app')

@section('page_title', 'Reminders')

@section('active_reminder', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/reminders.css') }}" rel="stylesheet">
<link href="{{ asset('static/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<style type="text/css" media="screen">

/*---------- paperyard custom button ---------------------------*/

.lg-btn-tx {
	font-size:18px;
	color:#017cff;
	font-weight:bold
}
.lg-btn_x2 {
	width:210px;
	height:35px;
	border:none;
	border-radius:5px
}
.btn_color{
	background-color:#b1d5ff;
}

/*---------------------------------------------------------------*/

.notify_w_tx {
	color:#7e7e7e; font-size:22px;
}

.notify_ico {
	color:#b1d5ff; font-size:100px;
}

.notify_pos {
	margin-top:50px;
}

.card:hover {
    color:#017cff;
    -webkit-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
    -moz-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
    box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
    cursor: pointer;
}

.notify-edit-icon:hover {
    color:#017cff !important;
    cursor: pointer;
}

/* ---------------material floating button -----------------------------------*/
.mfb-component__button--main, .mfb-component__button--child {
    background-color:#017cff; !important;
      -webkit-transition: all .25s;
         -moz-transition: all .25s;
          -ms-transition: all .25s;
           -o-transition: all .25s;
              transition: all .25s;
}

.mfb-component__button--main:hover, .mfb-component__button--child:hover {
     color:#fff !important;
      background-color:#b1d5ff !important;
}

/*--------------------------------------------------------------------------------*/
.rm_activated {
  background-color:#b1d5ff;
}

/* ----------------------------  breadcrumb nav -----------------------------------*/

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
     <li class="li2"><a href="#" >Reminders</a></li>
  </ul>
@endsection

@section('content')
<div class="row" ng-controller="reminders_controller">

@if(count($reminder_exist)>=1)

@if (session()->has('reminder_saved'))
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="alert bg-light-blue alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <p>{!! session('reminder_saved') !!}</p>
    </div>
</div>
@endif

<div class="ng-hide" ng-show="rmds">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" ng-repeat="rm in reminders_list track by $index">
        <div class="card" ng-class="{'rm_activated':rm.reminder_status=='activated'}">
            <div class="header" >
                <h2>
                    <# rm.reminder_title #>
                    <small ng-if="rm.doc_ocr!=null">for document: <# rm.doc_ocr | limitTo:27 #>..</small>
                </h2>

                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li ><a ng-href="/reminders/edit/<#rm.reminder_id#>">Edit reminder</a></li>
                            <li ng-click="deleteReminder(rm.reminder_id)"><a >Delete reminder</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <# rm.reminder_message | limitTo:184 #>...
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 ">

      <center>
            <div class="preloader ng-hide center-block" ng-show="reminder_preloader" style="margin-top:100px">
                <div class="spinner-layer pl-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
      </center>
</div>


<nav mfb-menu position="br" effect="zoomin"
 active-icon="fa fa-times" resting-icon="fa fa-plus"
 toggling-method="click" >
 <button mfb-button icon="fa fa-calendar-plus-o" label="Create new reminder"  onclick="window.location='{{ url('/reminder/create') }}'"></button>

</nav>

@else
<div>
    <center>
        <div class="notify_pos">
            <div><i class="fa fa-calendar-check-o notify_ico"></i></div><br>
            <div>
                <p class="notify_w_tx">
                    You don't have any reminders<br>
                    but you are free to create some
                </p>
            </div><br>
            <div>
                <button onclick="window.location='{{ url('reminder/create') }}'" class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">Create reminder</span></button>
            </div>
        </div>
    </center>
</div>
@endif


</div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/reminders.js') }}"></script>
<script src="{{ asset('static/js/bootstrap-material-datetimepicker.js') }}"></script>
<script type="text/javascript">

$(function () {
    //Textare auto growth
    autosize($('textarea.auto-growth'));

    //Datetimepicker plugin
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        clearButton: true,
        shortTime: true,
        weekStart: 1
    });

});

//inject this app to rootApp
var app = angular.module('app', ['ng-mfb']);

app.controller('reminders_controller', function($scope, $http, $timeout) {


$scope.getReminders = function(){
    $http.get('/get_reminders').success(function(data){
           $scope.hide_preloader();
           $scope.reminders_list = data;
    });
}


$scope.show_preloader = function(){
    $scope.reminder_preloader = true;
    $scope.rmds = false;
}

$scope.hide_preloader = function(){
    $scope.reminder_preloader = false;
    $scope.rmds = true;
}

$scope.show_preloader();
$scope.getReminders();

$scope.deleteReminder = function(rm_id){

    swal({
        title: "Delete reminder",
        text: "Are you sure you want to delete this reminder?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        $scope.show_preloader();
        if (isConfirm) {
            data = {
                rm_id:rm_id
            }
            $http.post('/reminder_delete', data).success(function(data){
                  $scope.getReminders();
            });

        } else {
            swal("Cancelled", "Delete canceled", "error");
        }
    });

}


});

</script>
@endsection
