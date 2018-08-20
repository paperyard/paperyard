@extends('layouts.app')

@section('page_title', 'Notifications')

@section('active_notification', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('static/css/notifications.css') }}" rel="stylesheet">
<style type="text/css" media="screen">

/*-------------- paperyard button---------------*/

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

/*-----------------------------------------------*/


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

/* ---------------- breadcrumb nav ----------------------*/
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
     <li class="li2"><a href="#" >Notifications</a></li>
  </ul>
@endsection

@section('new_notification_btn')

@if(count($my_notifications)>=1)
<div class="pull-right">
    <button onclick="window.location='{{ url('notifications/create') }}'" class="btn-flat btn_color main_color waves-effect lg-btn_x2 hidden-xs hidde-sm pull-right" type="submit"><span class="lg-btn-tx">@lang('notifications.notify_f_btn_tx')</span></button>
</div>

<div>
    <button onclick="window.location='{{ url('notifications/create') }}'" class="btn-flat btn_color main_color waves-effect lg-btn_x2 hidden-md hidden-lg" type="submit"><span class="lg-btn-tx">@lang('notifications.notify_f_btn_tx')</span></button>
</div>
@endif

@endsection

@section('content')

@if(count($my_notifications)>=1)
<div class="row clearfix">


        @if (session()->has('notif_save_success'))
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
            <div class="alert bg-light-blue alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <p>{!! session('notif_save_success') !!}</p>
            </div>
        </div>
        @endif

        @if (session()->has('notif_deleted'))
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
            <div class="alert bg-red alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <p>{!! session('notif_deleted') !!}</p>
            </div>
        </div>
        @endif


        <div style="margin-top:20px">
        @foreach($my_notifications as $notify)

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:-15px">
                <div class="card">
                    <div class="body">
                       <div class="row clearfix">
                          <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <p>{{ $notify->notif_title }}</p>
                          </div>
                          <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <span class="pull-right"  onclick="window.location='{{ url('notifications/edit/'.$notify->notif_id)   }}'">
                                    <i class="fa fa-chevron-right notify-edit-icon" style="font-size:30px; color:#7e7e7e; margin-top: 15px"></i>
                                </span>
                                <br>
                          </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
        </div>

</div><!--row -->
@else

<div>
    <center>
        <div class="notify_pos">
            <div><i class="fa fa-bell notify_ico"></i></div><br>
            <div>
                <p class="notify_w_tx">
                    @lang('notifications.notify_f_m_1')<br>
                    @lang('notifications.notify_f_m_2')
                </p>
            </div><br>
            <div>
                <button onclick="window.location='{{ url('notifications/create') }}'" class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">@lang('notifications.notify_f_btn_tx')</span></button>
            </div>
        </div>
    </center>
</div>

@endif


<!-- Vertical Layout -->
@endsection

@section('scripts')
<script src="{{ asset('static/js/notifications.js') }}"></script>
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

//used angular interpolate for syntax compatibility
var app = angular.module('notification_app', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<#');
    $interpolateProvider.endSymbol('#>');
});

app.controller('notification_controller', function($scope, $http, $timeout) {

$scope.saveNotification = function(){
    var form = $('#notification_form');
    var formdata = false;
    if (window.FormData) {
        formdata = new FormData(form[0]);
    }

    $.ajax({
        url: '/notifications/create',
        data: formdata ? formdata : form.serialize(),
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) {
            console.log(data);
        }
    }); //end ajax
}

});

</script>
@endsection
