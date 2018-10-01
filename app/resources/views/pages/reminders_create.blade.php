@extends('layouts.app')

@section('page_title', 'Create new reminder')

@section('active_reminder', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/reminders.css') }}" rel="stylesheet">
<link href="{{ asset('static/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<style type="text/css" media="screen">

/* ------------------paperyard custom button ---------------------------*/

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

/*------------------------------------------------------------------------*/

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

/* --------------------------------  breadcrumb nav -----------------------------------*/
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

/* ------------table custom design -----------------*/

.table-striped>tbody>tr:nth-child(odd)>td,
.table-striped>tbody>tr:nth-child(odd)>th {
   background-color: #fff;
 }

.table-striped>thead>tr:nth-child(odd)>th {
   background-color: #ebedf8;
 }
.table-hover tbody tr:hover td{
   background-color: #b1d5ff !important;
   cursor: pointer;
}

.table-striped>tbody>tr:nth-child(even)>td,
.table-striped>tbody>tr:nth-child(even)>th {
   background-color: #ebedf8;
}
/*---------------------------------------------------*/

/* --------- autocomplete ---------------------------*/
.th-t {
  background-color: #4ddb9f;
}
.th-f {
  background-color: #ef5c8f;
}
.th-ft {
  background-color: #fade45;
}
/*---------------------------------------------------*/

.cstm_input {
  background-color:#ebedf8;
}

.cstm_icon_btn {
  padding:2px !important;
  padding-left:5px !important;
  padding-right:5px !important;
  padding-top:0px !important;
  margin-right:7px;
}
.cstm_icon_btn:hover {
  background-color:  #017cff !important;
  -webkit-transition: all .3s;
     -moz-transition: all .3s;
      -ms-transition: all .3s;
       -o-transition: all .3s;
          transition: all .3s;
           color:#fff;
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
<div class="row clearfix" ng-controller="reminder_controller" ng-click="clear()"><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="block-header">
            <h2>
                Note!
                <small>date/time is based on settings timezone.
                default is Europe/Paris. Go to system settings to change your preferred timezone.</small>
            </h2>
        </div>

        <div class="card">
            <div class="header">
                <h2>
                    Create new reminder
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
                <!-- Search input  -->
                <input type="text" class="form-control search_inp cstm_input" placeholder="Search document."  ng-model-options='{ debounce: 1000 }' ng-change="onChangeInput()" ng-model="doc_keyword" ng-keydown="searchKeyPress($event)">
                <div class="row cleafix" >
                  <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                    <div class="list-group" style="margin-right:10px;">
                      <!-- AUTCOMPLETE TAGS -->
                      <a ng-click="searchDocuments(tag,'tag')" class="list-group-item th-t" ng-repeat="tag in ac_tags track by $index" ng-show="ac_tags!='not_found' && ac_tags!=null">
                        <# tag #>
                      </a>
                      <!-- AUTCOMPLETE FOLDERS  -->
                      <a ng-click="searchDocuments(folder.folder_name,'folder')" class="list-group-item  th-f" ng-repeat="folder in ac_folders track by $index" ng-show="ac_folders!='not_found' && ac_folders!=null">
                        <# folder.folder_name #>
                      </a>
                      <!-- AUTCOMPLETE FULLTEXT -->
                      <a ng-click="searchDocuments(fulltext,'fulltext')" class="list-group-item  th-ft" ng-repeat="fulltext in ac_fulltext track by $index" ng-show="ac_fulltext!='not_found' && ac_fulltext!=null">
                        <# fulltext #>
                      </a>
                      <!-- NO RESULT FOUND -->
                      <a  class="list-group-item  ng-hide" ng-show="no_result_found">
                         No result found...
                      </a>
                    </div>
                  </div>
                </div>
                <!-- ------Search results table------- -->
                <div class="table-responsive" style="margin-top:15px">                
                  <table class="table table-hover ng-hide table-striped" ng-show="rm_table">
                      <thead style="background-color:#ebedf8; color:#000; font-size:13px; ">
                        <th>#</th>
                        <th>Date</th>
                        <th>Recipient</th>
                        <th>Sender</th>
                        <th>Category</th>
                        <th>Actions</th>
                      </thead>
                      <tbody style="font-size:13px;">
                        <tr ng-repeat="data in documents track by $index">
                          <td><#$index+1#></td>
                          <td><# data.date     | default #></td>
                          <td><# data.receiver | default #></td>
                          <td><# data.sender   | default #></td>
                          <td><# data.category | default #></td>
                          <td style="width:50px">
                              <!-- View document page -->
                              <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="View document" tooltip-top>
                                <i class="material-icons cstm_icon_btn_ico">remove_red_eye</i>
                              </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <center>
                      <div class="preloader ng-hide center-block" ng-show="rm_tb_preloader" style="margin-top:50px">
                        <div class="spinner-layer pl-blue">
                          <div class="circle-clipper left">
                            <div class="circle"></div>
                          </div>
                          <div class="circle-clipper right">
                            <div class="circle"></div>
                          </div>
                        </div>
                      </div>

                      <div class="center-block ng-hide" ng-show="rm_tb_not_found" style="margin-top:50px">
                        <h4 style="color:red">No document found.</h4>
                      </div>
                    </center>
                </div>

                <form enctype="multipart/form-data" name="reminderForm"  ng-submit="saveReminder(); $event.preventDefault();">
                    <br>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="reminder_title" ng-model="reminders.reminder_title"  class="form-control" placeholder="Reminder Title." required>
                        </div>
                    </div>

                     <div class="form-group">
                        <div class="form-line">
                            <textarea rows="1" class="form-control no-resize auto-growth" name="reminder_message" ng-model="reminders.reminder_message"  id="notification_message" placeholder="Message...press ENTER to create new line." required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="reminder_time" ng-model="reminders.reminder_time" class="datetimepicker form-control" placeholder="Schedule this reminder." required>
                        </div>
                    </div>
                     <div class="form-group">
                        <div class="pull-right">
                            <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 ng-hide" type="submit" ng-show="save_rm_btn"><span class="lg-btn-tx">Save reminder</span></button>
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

//inject this app to rootApp
var app = angular.module('app', []);

app.filter('default', function(){
   return function(data){
       if(data==null){
           data = "____";
           return data;
       }
       return data;
   }
});

app.controller('reminder_controller', function($scope, $http, $timeout, $q) {

$scope.canceler = $q.defer();
$scope.search_canceler = $q.defer();

$scope.reminders = [];
$scope.search_preloader = false;
$scope.rm_table = false;
$scope.rm_tb_preloader = false;
$scope.rm_tb_not_found = false;
$scope.save_rm_btn = true;

$scope.saveReminder = function(){

    $scope.wait();
    $scope.save_rm_btn = false;
    data = {
        save_reminder:true,
        rm_title:$scope.reminders.reminder_title,
        rm_message:$scope.reminders.reminder_message,
        rm_time:$scope.reminders.reminder_time
    }
    if($scope.attach_doc_id != '' && $scope.attach_doc_id != null && $scope.attach_doc_id != undefined){
        data.attach_doc_id = $scope.attach_doc_id;
    }
    $http.post('/reminder_save_update', data).success(function(data){
           window.location.replace('/reminders');
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

// on keypress check key
$scope.searchKeyPress = function(keyEvent) {

  //if key == 13 == ENTER  search document.
  if (keyEvent.which === 13){
      //delay function for 1 second
      $timeout( function()
      {
        // method to be executed;
        $scope.searchDocuments($scope.doc_keyword,'no_filter');
        $scope.search_preloader = false;
      }, 1000); //end timeout.
  }
  // key 8 = backspace. clear autocomplete
  if (keyEvent.which === 8){
    $scope.clear_autocomplete();
    if($scope.doc_keyword==""){
        //hide searching autocomplete preloader
        $scope.search_preloader = false;
        //hide not found 
        $scope.rm_tb_not_found = false;
    }
  }
};

// clear autocomplete on search bar
$scope.clear_autocomplete = function(){
  $scope.ac_tags     = null;
  $scope.ac_folders  = null;
  $scope.ac_fulltext = null;
  $scope.no_result_found = false;
}

$scope.preloader_table_data_show = function(){
  $scope.rm_table = false;
  $scope.rm_tb_preloader = true;
  $scope.rm_tb_not_found = false;
}

$scope.preloader_table_data_hide = function(){
  $scope.rm_table = true;
  $scope.rm_tb_preloader = false;
  $scope.rm_tb_not_found = false;
}

$scope.show_not_found = function(){
  $scope.rm_table = false;
  $scope.rm_tb_preloader = false;
  $scope.rm_tb_not_found = true;
}

//----------------------------------------------------------------------------------------------------------------------

//oninput change search autocomplete.
$scope.onChangeInput = function(){
    //cancel previous autocomplete post request.
    $scope.canceler.resolve();
    //reinit $q.defer make new autocomplete post request
    $scope.canceler = $q.defer();
    // check if search input has value
    if($scope.doc_keyword!="" && $scope.doc_keyword!=null && $scope.doc_keyword!=undefined){
        
      $scope.search_preloader = true;
      //clear dropdown autocomplete
      $scope.clear_autocomplete();
      //store keyword to data to be passed in post request
      data = {
          doc_keyword: $scope.doc_keyword
      }
      //make post request to get if keyword is found in documents tags,folder or page text.
      $http({method:'POST',url:'/common_search/autocomplete', data, timeout: $scope.canceler.promise}).success(function(data){
          //if notthing is found, show not found dropdown result.
          if(data.tags=="not_found" && data.folders=="not_found" && data.fulltext=="not_found"){
            //not found
            $scope.no_result_found = true;
          }else{
            //store result to be displayed in autocomplete.
            $scope.ac_tags     = data.tags;
            $scope.ac_folders  = data.folders;
            $scope.ac_fulltext = data.fulltext;
          }
          $scope.search_preloader = false;
      });
    }
}

// search document by selecting autocomplete
$scope.searchDocuments = function(keyword,filter){
    //hide search autocomplete preloader
    $scope.clear_autocomplete();
    //show table preloader
    $scope.preloader_table_data_show();
    //cancel autocomplete request
    $scope.canceler.resolve(); 
    //cancel previous selectSearch post request
    $scope.search_canceler.resolve();
    //reinit $q.defer to make new post request.
    $scope.search_canceler = $q.defer();
    //put selected autocomplete keyword to search bar
    $scope.doc_keyword = keyword;

    data = {
       doc_keyword: keyword,
       doc_filter:  filter
    }
    //filter = tag,folder,fulltext
    $http({method:'POST',url:'/common_search/select_search', data, timeout: $scope.search_canceler.promise}).success(function(data){
       
       if(data=="error"){
          $scope.show_not_found();
       }else{
          $scope.preloader_table_data_hide();
          //pass result to scope documents to be rendered in table
          $scope.documents = data;
          //make documents table visible
          $scope.documents_table = true;
          //hide preloader
          $scope.dashboard_preloader = false;
          //output result in consolo -> remove this in production
       }
       console.log(data);
    });
}
//---------------------------------------------------------------------------------------------------------------------------






}); //end controller

</script>
@endsection
