@extends('layouts.app')

@section('page_title', 'Notifications')

@section('active_notification', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/notifications.css') }}" rel="stylesheet">

<style type="text/css" media="screen">

/*----------------------paperyard custom button--------------------*/

.lg-btn-tx {
	font-size:18px;
	color:#017cff;
	font-weight:bold
}
.lg-btn_x2 {
	width:270px;
	height:35px;
	border:none;
	border-radius:5px
}
.btn_color{
	background-color:#b1d5ff;
}

/* ----------------------------------------------------------------*/

.notify_w_tx {
	color:#7e7e7e; font-size:22px;
}
.notify_ico {
	color:#b1d5ff; font-size:100px;
}
.notify_pos {
	margin-top:50px;
}

.bootstrap-tagsinput {
    width:100%;
}
.bootstrap-tagsinput .tag {
   background-color:#017cff !important;
   font-size:13px !important;
}
.invalid_inp {
   color:red !important;
}
.bootstrap-tagsinput {
   padding:0px !important;
}

.chbox {
    margin-top:5px !important;
}

/*---------------------- breadcrumb nav ------------------------*/

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

@section('content')

<div class="row clearfix" ng-controller="notification_controller"><br>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="block-header">
            <h2>
                Note !
                <small>Notifications will be executed if a document has the specified keywords. you are free to select actions.</small>
            </h2>
        </div>

        <form enctype="multipart/form-data" novalidate name="notification_base"  ng-submit="newNotication(); $event.preventDefault();">
        <!-- specify keywords to execute notification -->
        <div class="card">
            <div class="header">
                <h2>
                    Execute this notification when ocred document has the specified words.
                </h2>
            </div>
            <div class="body">
                <div class="form-group">
                    <div class="form-line frm-input focused">
                      <input name="notification_keywords" style="text-indent:0px !important; float:left !important" ng-model="base_notify.notification_keywords" type="text" class="form-control"  data-role="tagsinput" placeholder="eg. Tax, Bill, Till." value="" required>
                    </div>
                     <div ng-show="notification_base.$submitted || notification_base.notification_keywords.$touched">
                       <span ng-show="notification_base.notification_keywords.$error.required" class="invalid_inp">This field is required!</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="notify_title"  ng-model="base_notify.notify_title" class="form-control" placeholder="Notification Title" required>
                    </div>
                    <div ng-show="notification_base.$submitted  || notification_base.notify_title.$touched">
                       <span ng-show="notification_base.notify_title.$error.required" class="invalid_inp">This field is required!</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="block-header">
            <h2>
                Please select atleast 1 action.
                <small>This actions will be executed if a document has the specified keywords.</small>
            </h2>
        </div>
        <!-- Actions to select when notification is true -->
        <div class="card">
            <!-- mark task relevant -->
            <div class="header">
                <input type="checkbox" id="tax_rel" name="tax_relevant" ng-model="base_notify.tax_relevant" class="filled-in chk-col-blue" >
                <label for="tax_rel" class="chbox">Mark document as Tax relevant.</label>
            </div>
            <!-- add tags on document -->
            <div class="header">
                <input type="checkbox" id="for_tags" name="add_tags" ng-model="base_notify.add_tags" class="filled-in chk-col-blue" >
                <label for="for_tags" class="chbox">Add tags to document.</label>
            </div>
            <div class="body ng-hide" ng-show="base_notify.add_tags==true">
                  <div class="form-group">
                    <div class="form-line frm-input" ng-class="{'focused':base_notify.add_tags==true}">
                      <input name="tags" ng-model="base_notify.tags" type="text" class="form-control"  data-role="tagsinput" placeholder="Enter tags here." value="" ng-required="base_notify.add_tags==true">
                    </div>
                    <div ng-show="notification_base.$submitted || notification_base.tags.$touched">
                       <span ng-show="notification_base.tags.$error.required && base_notify.add_tags==true" class="invalid_inp">This field is required!</span>
                    </div>
                  </div>
            </div>
        </div><!-- Card-->

        <div class="card">
              <!-- Send email. -->
            <div class="header">
                <input type="checkbox" id="for_send_email" class="filled-in chk-col-blue " ng-model="base_notify.send_email">
                <label for="for_send_email" class="chbox">Send email.</label>
            </div>
            <div class="body ng-hide" ng-show="base_notify.send_email==true">

                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="email_subject" ng-model="base_notify.email_subject" class="form-control" placeholder="Subject" ng-required="base_notify.send_email==true">
                        </div>
                        <div ng-show="notification_base.$submitted || notification_base.email_subject.$touched">
                           <span ng-show="notification_base.email_subject.$error.required && base_notify.send_email==true" class="invalid_inp">This field is required!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="email_receiver_name" ng-model="base_notify.email_receiver_name" class="form-control" placeholder="Receiver name" ng-required="base_notify.send_email==true">
                        </div>
                        <div ng-show="notification_base.$submitted || notification_base.email_receiver_name.$touched">
                           <span ng-show="notification_base.email_receiver_name.$error.required && base_notify.send_email==true" class="invalid_inp">This field is required!</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                               <input type="email" name="email_receiver_email" ng-model="base_notify.email_receiver_email" class="form-control" placeholder="Receiver email" ng-required="base_notify.send_email==true">
                        </div>
                         <div ng-show="notification_base.$submitted || notification_base.email_receiver_email.$touched">
                               <span ng-show="notification_base.email_receiver_email.$error.required && base_notify.send_email==true" class="invalid_inp">This field is required!</span>
                               <span ng-show="notification_base.email_receiver_email.$error.required && notification_base.email_receiver_email.$error.email && base_notify.send_email==true" class="invalid_inp">
                                   Invalid email format.
                               </span>
                        </div>

                    </div>

                     <div class="form-group">
                        <div class="form-line">
                            <textarea rows="1" class="form-control no-resize auto-growth" name="email_message" ng-model="base_notify.email_message" placeholder="Enter message. Press Enter key to create new line." ng-required="base_notify.send_email==true"></textarea>
                        </div>
                         <div ng-show="notification_base.$submitted || notification_base.email_message.$touched">
                           <span ng-show="notification_base.email_message.$error.required && base_notify.send_email==true" class="invalid_inp">This field is required!</span>
                        </div>

                    </div>

            </div> <!-- Body -->
        </div>


        <div class="btn-block ng-hide" ng-show="submit_notify">
              <div class="form-group">
                <div class="pull-right">
                    <button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">Create notification</span></button>
                 </div>
                 <br>
            </div>
        </div>
        </form>


    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/notifications.js') }}"></script>
<script type="text/javascript">

$(function () {
    //Textare auto growth
    autosize($('textarea.auto-growth'));
});

//inject this app to rootApp
var app = angular.module('app', []);

app.controller('notification_controller', function($scope, $http, $timeout) {

$scope.base_notify   = [];
$scope.submit_notify = true;

$scope.wait = function(){
    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });
}

$scope.newNotication = function(){

    // check if submitted form is valid
    if($scope.notification_base.$valid){
          //check if user selected any actions
          if( $scope.base_notify.tax_relevant==true || $scope.base_notify.add_tags==true || $scope.base_notify.send_email==true){

                swal("Success", "Creating new notification.", "success");
                $scope.wait();
                $scope.submit_notify = false;
                //data to post http.
                data = {
                    'save_notification':true,
                    'keywords':$scope.base_notify.notification_keywords,
                    'notification_title':$scope.base_notify.notify_title
                }
                // if tax relevant is selected. pass bool true.
                if($scope.base_notify.tax_relevant==true){
                    data.tax_relevant = true;
                }
                // if tags is selectecd. add tags to data
                if($scope.base_notify.add_tags==true){
                    data.tags = $scope.base_notify.tags;
                }
                // if send email is selected. add datas to data.
                if($scope.base_notify.send_email==true){
                    data.send_email     = 1;
                    data.subject        = $scope.base_notify.email_subject
                    data.receiver_name  = $scope.base_notify.email_receiver_name
                    data.receiver_email = $scope.base_notify.email_receiver_email
                    data.message        = $scope.base_notify.email_message
                }

                $http.post('/notifications/save_update', data).success(function(data){
                      window.location.replace('/notifications');
                });

          }else{
              swal("Error", "Please select atleast 1 action for this notification.", "error");
          }

    }else{
        swal("Error", "Please check the required fields .", "error");
    }
} // end function



});

</script>
@endsection
