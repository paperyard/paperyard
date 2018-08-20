@extends('layouts.app')

@section('page_title', 'Edit reminder')

@section('active_reminder', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/reminders.css') }}" rel="stylesheet">
<link href="{{ asset('static/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<style type="text/css" media="screen">

/* --------------------------paperyard custom button -----------------------------*/

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

/*----------------------------------------------------------------------------------*/

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


.list-group-autocomplete{
   position:absolute !important;
   z-index:5 !important;
}

.preload_custm_loc {
    margin-top:-20px;
}


/* --------------------------------breadcrumb nav --------------------------------*/
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
<div class="row clearfix" ng-app="reminder_app" ng-controller="reminder_controller" ng-click="clear()"><br>


    @if (session()->has('reminder_updated'))
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert bg-light-blue alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <p>{!! session('reminder_updated') !!}</p>
        </div>
    </div>
    @endif
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Edit reminder
                </h2>
                <div>
                   <div class="preloader pull-right pl-size-xs preload_custm_loc ng-hide" ng-show="search_preloader">
                      <div class="spinner-layer pl-blue">
                          <div class="circle-clipper left">
                              <div class="circle"></div>
                          </div>
                          <div class="circle-clipper right">
                              <div class="circle"></div>
                          </div>
                      </div>
                  </div>
                </div>
            </div>

            <div class="body">

                <form enctype="multipart/form-data" name="reminderForm"  ng-submit="updateReminder(); $event.preventDefault();">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text"
                            name="reminder_document"
                            ng-model="reminders.reminder_document"
                            ng-init="reminders.reminder_document='{{$reminder->doc_ocr}}'"
                            ng-value="'{{$reminder->doc_ocr}}'"
                            ng-model-options='{ debounce: 500 }'
                            ng-change="attachDocument()"
                            class="form-control"
                            placeholder="Attach document.(optional)..search name here.">
                        </div>
                         <div class="row">
                          <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                              <div class="list-group ">
                                 <a  class="list-group-item d_ac_list" ng-click="selectAttach(doc.doc_id,doc.doc_ocr)" ng-repeat="doc in documents track by $index" ng-show="documents!=null && documents.length>0">
                                      <# doc.doc_ocr #>
                                </a>
                                 <a  class="list-group-item d_ac_list ng-hide" ng-show="no_doc_found">
                                      No document found..
                                </a>
                             </div>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text"
                            name="reminder_title"
                            ng-model="reminders.reminder_title"
                            ng-init="reminders.reminder_title='{{$reminder->reminder_title}}'"
                            ng-value="'{{$reminder->reminder_title}}'"
                            class="form-control"
                            placeholder="Reminder Title."
                            required>
                        </div>
                    </div>

                     <div class="form-group">
                        <div class="form-line">
                            <textarea
                            rows="1"
                            class="form-control no-resize auto-growth"
                            name="reminder_message"
                            ng-model="reminders.reminder_message"
                            ng-init="reminders.reminder_message='{{$reminder->reminder_message}}'"
                            ng-value="'{{$reminder->reminder_message}}'"
                            id="notification_message"
                            placeholder="Enter notification message...press ENTER to create new line."
                            required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <input type="text"
                            name="reminder_time"
                            ng-model="reminders.reminder_time"
                            ng-init="reminders.reminder_time='{{$reminder->reminder_schedule}}'"
                            ng-value="'{{$reminder->reminder_schedule}}'"
                            class="datetimepicker form-control"
                            placeholder="Schedule this reminder."
                            required>
                        </div>
                    </div>
                     <div class="form-group">
                        <div class="pull-right">
                            <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 ng-hide" type="submit" ng-show="save_rm_btn"><span class="lg-btn-tx">Update reminder</span></button>
                         </div>
                         <br>
                    </div>
                </form>

            </div>

        </div>
    </div>
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

//used angular interpolate for syntax compatibility
var app = angular.module('reminder_app', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<#');
    $interpolateProvider.endSymbol('#>');
});

app.controller('reminder_controller', function($scope, $http, $timeout, $q) {

$scope.canceler = $q.defer();
$scope.reminders = [];
$scope.search_preloader = false;
$scope.no_doc_found = false;
$scope.save_rm_btn = true;
$scope.attach_doc_id = '{{$reminder->reminder_document_id or null }}';

console.log($scope.attach_doc_id);

$scope.updateReminder = function(){

    $scope.wait();
    $scope.save_rm_btn = false;

    data = {
        update_reminder:true,
        rm_id:'{{$reminder->reminder_id}}',
        rm_title:$scope.reminders.reminder_title,
        rm_message:$scope.reminders.reminder_message,
        rm_time:$scope.reminders.reminder_time
    }

    if($scope.attach_doc_id != '' && $scope.attach_doc_id != null && $scope.attach_doc_id != undefined){
        data.attach_doc_id = $scope.attach_doc_id;
    }

    $http.post('/reminder_save_update', data).success(function(data){
           window.location.reload();
    });

}

$scope.attachDocument = function(){
    // cancel all previous http request
    $scope.canceler.resolve();
    // reinit canceler. new request can be made.
    $scope.canceler = $q.defer();
    $scope.clearAttach();
    $scope.search_preloader = true;
    data = {
         doc_keyword: $scope.reminders.reminder_document
    }
    $http({method:'POST',url:'/reminder_documents', data, timeout: $scope.canceler.promise}).success(function(data){
         $scope.documents = data;
         $scope.search_preloader = false;
         if(data.length<=0){
            $scope.no_doc_found = true;
         }
    });
}

$scope.wait = function(){

    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });

}

$scope.selectAttach = function(doc_id, doc_name){
   $scope.reminders.reminder_document = doc_name;
   $scope.attach_doc_id = doc_id;
   $scope.clearAttach();
}

$scope.clear = function(){
   if($scope.search_preloader==false){
      $scope.clearAttach();
   }
}

$scope.clearAttach = function(){
   $scope.documents = null;
   $scope.no_doc_found = false;
}

// on keypress check key
$scope.searchKeyPress = function(keyEvent) {
  //if key === 8 === backspace. clear autocomplete
  if (keyEvent.which === 8){
     $scope.clearAttach();
  }
}; // end searchKeyPress..


});

</script>
@endsection
