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

/* ---------------- breadcrumb nav ----------------------*/

.arrow-on-m {
  width:40px !important;
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
<div class="row clearfix" ng-controller="notification_controller">


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
                       <div class="row clearfix" style="margin-bottom: -25px">

                          <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label>{{ $notify->notif_title }}</label>
                          </div>
                          <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 ">
                
                               <table style="width:100%;">
                                   <tr>
                                       <td >
                                          <div style="height:80px">
                                                <canvas id="myChart{{$notify->notif_id}}" style="width:100%"></canvas>
                                          </div>
                                       </td>
                                       <td class="arrow-on-m">
                                            <span class="pull-right"  onclick="window.location='{{ url('notifications/edit/'.$notify->notif_id)   }}'">
                                                <i class="fa fa-chevron-right notify-edit-icon" style="font-size:30px; color:#7e7e7e;"></i>
                                            </span>
                                       </td>
                                   </tr>
                               </table>
                      
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

<script src="{{ asset('static/js/folders.js') }}"></script>
<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', []);

app.controller('notification_controller', function($scope, $http, $timeout) {

$scope.getNotificationsLCD = function(){
    $http.get('/notifications/line_chart_datas').success(function(data){
         $scope.linechart_datas = data;
         $scope.createChart();
    });    
}
$scope.getNotificationsLCD();

$scope.createChart = function(){
  
  var lineCanvas  = [];
  var makeDatas   = [];
  var lineDatas   = [];
  var lineChart   = [];
  var maxVal      = [];

  angular.forEach($scope.linechart_datas, function(value, key) {
       lineCanvas[value.notification_id] = document.getElementById("myChart"+value.notification_id);
       makeDatas[value.notification_id] = {
          label: "",
          data: value.notif_counts,
          lineTension: 0.3,
          fill: false,
          borderColor: 'rgba(0, 119, 255, 1)',
          backgroundColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius:0,
          borderWidth:4
      };
      lineDatas[value.notification_id] = {
          labels: makeDatas[value.notification_id].data,
          datasets: [makeDatas[value.notification_id]],
      };
      maxVal = Math.max.apply(Math, value.notif_counts);

      lineChart[value.notification_id] = new Chart(lineCanvas[value.notification_id], {
          type: 'line',
          data: lineDatas[value.notification_id],
          // options --------------------------------
          options: {
                  maintainAspectRatio: false,
                  legend: {
                      display: false,
                  },
                  tooltips: {
                      enabled: false
                  },
                   scales: {
                      yAxes: [{
                        ticks: {
                          beginAtZero: true,
                          autoSkip: false,
                          display:false,
                          max: maxVal+0.2
   
                        },
                         gridLines: {
                          display: false,
                          color: "white",
                          zeroLineColor: "white"
                        },
                      }],
                      xAxes: [{
                        ticks: {
                          beginAtZero: true,
                          autoSkip: false,
                          display:false,
                            
                        },
                        gridLines: {
                          display: false,
                          color: "white",
                          zeroLineColor: "white"
                        },
                        categoryPercentage: 1,

                      }]
                    }
          }
          // options --------------------------------
      });
      console.log('linechart data created');
  });
}

});

</script>
@endsection
