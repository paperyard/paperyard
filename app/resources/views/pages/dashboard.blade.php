@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('active_dashboard', 'p_active_nav')

@section('custom_style')
<style type="text/css" media="screen">
.dashboard_card {
  height:150px;
}

.mg-icon_1 {
   color: #017cff;
   font-size:90px;
   margin-top:10px;
}
.mg-icon_2 {
   color: #b1d5ff;
   font-size:90px;
   margin-top:10px;
}

@media screen and (max-width:500px) {
   .merge_sm_chevron {
       margin-left:-30px !important;
   }
}

.dashboard_card:hover {
  -webkit-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
  -moz-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
  box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
  cursor: pointer;
}

.search_inp {
  text-align:center
}

.cstm_list_g {
  padding-top:20px;
}

.cstm_knob_div {
  padding-left:0px
}

.prev_viewed_doc_div {
  width:110px; height:100%; border-right:3px solid #b1d5ff;
}

.prev_viewd_doc_div_img {
  height:120px !important; border:1px solid #b1d5ff;
}

.prev_viewed_docs {
  border: 1px solid #b1d5ff; padding:0px; width:58px; margin:3px
}

.prev_viewd_docs img {
  height:85px;
}

.prev_viewed_docs_m {
  border: 1px solid #b1d5ff; padding:0px; width:40px; margin:3px
}

.prev_viewed_docs_m img {
  height:60px;
}

.doc_num_stat {
  font-size:15px !important;
  background-color:#017cff !important;
  margin-top:-6px;
}

.preload_custm_loc{
   margin-top:-7px;
}

.chart_tbl tr th{
   margin:0px !important;
   padding:0px !important;
}
.d_ac_list{
   cursor: pointer;
   background-color:#b1d5ff;
   color:#000 !important;
}
.d_ac_list:hover{
   cursor: pointer;
   background-color:#017cff !important;
   color:#fff !important;
}

.list-group-autocomplete{
   position:absolute !important;
   z-index:5 !important;

}

.stat_ready {
  color:#017cff;
}
.stat_failed {
  color:red;
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

/* ------------table custom design -----------------*/

.table-striped>tbody>tr:nth-child(odd)>td,
.table-striped>tbody>tr:nth-child(odd)>th {
   background-color: #fff;
 }

.table-hover tbody tr:hover td{
  background-color: #b1d5ff !important;
}

.table-striped>tbody>tr:nth-child(even)>td,
.table-striped>tbody>tr:nth-child(even)>th {
   background-color: #ebedf8;
}
/*---------------------------------------------------*/

</style>
@endsection

@section('show_localization')

@endsection

@section('content')
<div class="row" ng-app="dashboard_app" ng-controller="dashboard_controller" ng-click="clear_autocomplete()">

  <div>
    <div class="col-md-6  col-md-offset-3">
      <input type="text" class="form-control search_inp text-center" placeholder="@lang('dashboard.input_search_p_holder')" ng-change="onChangeInput()" ng-model="doc_name" ng-keydown="searchKeyPress($event)">
      <div class="row cleafix">
          <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
              <div class="list-group ">
                 <a  ng-click="searchSpecificDocument(doc.doc_id,doc.doc_ocr)" class="list-group-item d_ac_list" ng-repeat="doc in ac_doc_names track by $index" ng-show="ac_doc_names!=null && ac_doc_names.length>0">
                      <# doc.doc_ocr #>
                </a>
                 <a  class="list-group-item d_ac_list ng-hide" ng-show="no_result_found">
                      No result found...
                </a>
             </div>
          </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
     <br>
  </div>

  <div ng-show="dashboard_grid">
          <!-- DOCUMENTS TO EDIT / ARCHIVED -->
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="card dashboard_card">
              <div class="body ">
                <ul class="list-group" >

                  <a ng-href="/new_documents" style="text-decoration: none !important">
                    <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue">@lang('dashboard.to_edit_tx')
                          <span class="badge bg-light-blue doc_num_stat ng-hide" ng-show="num_to_edit"><# num_edit #></span>
                          <div class="preloader ng-hide pull-right pl-size-xs preload_custm_loc" ng-show="to_edit_preloader">
                              <div class="spinner-layer pl-blue">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>
                    </button>
                  </a>

                  <a ng-href="/archives" style="text-decoration: none !important">
                    <button type="button" class="list-group-item cstm_list_g waves-effect waves-blue">@lang('dashboard.This_whole_week_tx')
                         <span class="badge bg-light-blue doc_num_stat ng-hide" ng-show="num_to_archive"><# archive #></span>
                         <div class="preloader ng-hide pull-right pl-size-xs preload_custm_loc" ng-show="archive_preloader">
                              <div class="spinner-layer pl-blue">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>
                    </button>
                  </a>

                </ul>
              </div>
            </div>
          </div>

            <!-- EMPTY -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
               <a ng-href="/merge_pdf" style="text-decoration: none !important">
                      <div class="card dashboard_card">
                           <div class="container-fluid " style="padding-top:10px">
                              <div style="width:100%">
                                    <table style="width:100% !important; border:0px">
                                       <tr>
                                          <th colspan="3">
                                             <span style="float:right; top:0; color:#000">Merge documents </span>
                                          </th>
                                       </tr>
                                       <tr>
                                           <td>
                                             <i class="fa fa-file-text-o mg-icon_1" style="margin-right:10px"></i>
                                             <i class="fa fa-file-text-o mg-icon_2 hidden-xs hidden-sm"></i>

                                           </td>
                                           <td>
                                              <i class="fa fa-chevron-left merge_sm_chevron" style="font-size:50px !important; color:#ccc"></i>
                                           </td>
                                           <td>
                                             <span style="float:right; top:0">
                                               <i class="fa fa-file-text-o mg-icon_1 hidden-xs hidden-sm" ></i>
                                               <i class="fa fa-file-text-o mg-icon_2" style="margin-left:10px"></i>
                                             </span>
                                           </td>
                                       </tr>
                                    </table>
                              </div>
                          </div>
                      </div>
                  </a>
            </div>

            <!-- THIS WEEK EDITED KNOB-BARCHART -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div class="container-fluid " style="padding-top:10px">
                      <div style="width:100%">
                       <table style="width:100% !important; border:0px">
                        <tr>
                          <th rowspan="2">
                                <input type="text" class="knob cstm_knob_div" data-linecap="round" value="80" data-width="130" data-height="130" data-thickness="0.25" data-angleoffset="-180"
                                data-fgColor="#017cff" data-bgColor="#b1d5ff" >
                          </th>
                          <th height="5"><label class="pull-right">diese Woche</label></th>
                        </tr>
                        <tr>
                          <td>
                              <canvas id="myChart" style="width:100% !important; height:78px; padding:0px !important"></canvas>
                          </td>
                        </tr>
                      </table>
                     </div>
                </div>
              </div>
            </div>

            <!-- LAST OPENED DOCUMENTS -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div style="padding:17px">

                     @if(count($latest_opened)>=1)
                      <div class="col-xs-5 col-sm-3 " style="padding-left:0px;">
                        <div class="prev_viewed_doc_div">
                          <img src="{{ asset('static/documents_images/') .'/'. $latest_opened->thumbnail }}" class="img-responsive prev_viewd_doc_div_img">
                        </div>
                      </div>
                     @endif

                    <!-- Last opened desktop  -->
                    <div class="col-xs-7 col-sm-9 hidden-xs hidden-sm hidden-md">
                       @if(count($last_opened)>=1)
                          <div class="row" >
                            <label class="pull-right">@lang('dashboard.last_opened_tx')</label>
                          </div>
                          <div class="row pull-right" style="margin-top:4px">
                                @foreach($last_opened as $doc)
                                <div class="col-xs-2 col-sm-1 prev_viewed_docs">
                                  <img src="{{ asset('static/documents_images/') .'/'. $doc->thumbnail }}" class="img-responsive">
                                </div>
                                @endforeach
                          </div>
                        @endif
                    </div>

                    <!-- Last opened mobile  -->
                    <div class="col-xs-7 col-sm-9 visible-xs visible-sm">
                      <div class="row" >
                        <label class="pull-right">zuletzt geöffnet</label>
                      </div>
                      <div class="row pull-right" style="margin-top:24px">

                        <div class="col-xs-2 col-sm-1 prev_viewed_docs_m">
                          <img src="{{ asset('static/img/docs/doc1.png') }}" class="img-responsive">
                        </div>
                        <div class="col-xs-2 col-sm-1 prev_viewed_docs_m">
                          <img src="{{ asset('static/img/docs/doc2.png') }}" class="img-responsive">
                        </div>
                        <div class="col-xs-2 col-sm-1 prev_viewed_docs_m">
                          <img src="{{ asset('static/img/docs/doc3.png') }}" class="img-responsive">
                        </div>
                      </div>
                    </div>

                </div>
              </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div class="body">
                </div>
              </div>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card dashboard_card">
                <div class="body">
                </div>
              </div>
            </div>
    </div> <!-- grid row-->

    <div class="col-md-12" ng-click="clear_autocomplete()">
       <table class="table table-hover ng-hide" ng-show="documents_table">
           <thead style="background-color:#ebedf8; color:#000; font-size:13px; ">
               <th>#</th>
               <th>Document name</th>
               <th>Status</th>
               <th>Actions</th>
           </thead>
           <tbody style="font-size:11px;">
             <tr ng-repeat="data in documents track by $index">
                 <td><#$index+1#></td>
                 <td><#data.doc_ocr#></td>
                 <td ng-bind-html="data.process_status | ocred_status"></td>
                 <td style="width:300px">
                    <span ng-if="data.process_status=='ocred_final'">
                        <!-- Edit document -->
                        <a ng-href="/document/<#data.doc_id#>" style="text-decoration: none">
                          <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="Edit document" tooltip-top>
                              <i class="material-icons cstm_icon_btn_ico">edit</i>
                          </button>
                        </a>
                        <!-- Download ocred document -->
                        <a ng-href="/static/documents_ocred/<#data.doc_ocr#>" style="text-decoration: none" download>
                          <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="View document" tooltip-top>
                              <i class="material-icons cstm_icon_btn_ico">remove_red_eye</i>
                          </button>
                        </a>

                                <span ng-if="data.approved==0">
                                <!-- Download original document. -->
                                  <a ng-href="/static/documents_new/<#data.doc_org#>" style="text-decoration: none" download>
                                    <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="Download original file" tooltip-top>
                                        <i class="material-icons cstm_icon_btn_ico">file_download</i>
                                    </button>
                                  </a>
                                  <!-- Approved document. delete original -->
                                  <button ng-click="approveDocument(data.doc_id,data.doc_org)" type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" data-toggle="tooltip" title="" data-original-title="Approve document" tooltip-top id="deleteDocBtn<#doc.doc_id#>">
                                      <i class="material-icons cstm_icon_btn_ico">check</i>
                                  </button>
                                </span>
                                 <!-- customize document -->
                                <button type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" data-toggle="tooltip" title="" data-original-title="Customize document" tooltip-top>
                                    <i class="material-icons cstm_icon_btn_ico">build</i>
                                </button>
                    </span>

                    <button ng-click="deleteDocument(data.doc_id)" type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" data-toggle="tooltip" title="" data-original-title="Delete document" tooltip-top id="deleteDocBtn<#doc.doc_id#>">
                        <i class="material-icons cstm_icon_btn_ico">delete_forever</i>
                    </button>

                 </td>
             </tr>
            </tbody>
       </table>
      <center>
        <div class="preloader ng-hide center-block" ng-show="dashboard_preloader" style="margin-top:100px">
            <div class="spinner-layer pl-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>

        <div class="center-block ng-hide" ng-show="not_found" style="margin-top:100px">
           <h4 style="color:red">No document found.</h4>
        </div>
      </center>
    </div>


</div> <!-- / main row -->
@endsection


@section('scripts')
<script src="{{ asset('static/js/dashboard.js') }}"></script>
<script type="text/javascript">

var app = angular.module('dashboard_app', ['ngSanitize'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<#');
    $interpolateProvider.endSymbol('#>');
});

app.directive('tooltipTop', function() {
      return function(scope, element, attrs) {

      element.tooltip({
        trigger:"hover",
        placement: "top",
      });

    };
});

app.filter('ocred_status', function(){
    return function(data)
      {
          if(data=='ocred_final'){
              data = "<b class='stat_ready'>"+"Ready"+"</b>";
              return data;
          }
          else
          {
              data = '<b class="stat_failed">'+'Failed'+'</b>';
              return data;
          }
      }
});


app.controller('dashboard_controller', function($scope, $http, $timeout, $q) {

// cancel previous http request
// eg running autocomplete. if user press enter search. cancel all previous running http request.
$scope.canceler = $q.defer();

$scope.num_to_edit =         false;
$scope.num_to_archive =      false;
$scope.archive_preloader =   true;
$scope.to_edit_preloader =   true;

$scope.dashboard_grid =      true;
$scope.dashboard_preloader = false;
$scope.not_found =           false;
$scope.documents_table =     false;


// clear autocomplete on search bar
$scope.clear_autocomplete = function(){
    $scope.ac_doc_names =    null;
    $scope.no_result_found = false;
}

// show preloader
$scope.show_preloader = function(){
    $scope.dashboard_grid =      false;
    $scope.not_found =           false;
    $scope.dashboard_preloader = true;
    $scope.documents_table =     false;
}

$scope.hide_preloader = function(){
    $scope.dashboard_grid =      true;
    $scope.dashboard_preloader = false;
    $scope.not_found =           false;
    $scope.documents_table =     false;
}

$scope.doc_not_found = function(){
    $scope.dashboard_grid =      false;
    $scope.dashboard_preloader = false;
    $scope.not_found =           true;
    $scope.documents_table =     false;
}


$scope.getNumToEditArchive = function(){
  $http.get('/get_docs_edit_archive').success(function(data){
       $scope.num_edit = data.num_to_edit;
       $scope.to_edit_preloader = false;
       $scope.num_to_edit = true;

       $scope.archive =  data.num_archived;
       $scope.archive_preloader = false;
       $scope.num_to_archive = true;
  });
}
$scope.getNumToEditArchive();


//if dom is ready run invterval function
angular.element(document).ready(function () {
    //check for document status
    setInterval(function() {
         // method to be executed;
         $scope.getNumToEditArchive();

    },20000);
});


// search documents function
$scope.searchDocuments = function(){
    $scope.canceler.resolve();
    //clear pop autocomplete.
    $scope.clear_autocomplete();
    //check if search input has value
    if($scope.doc_name=='' || $scope.doc_name==null || $scope.doc_name == undefined){
        //if no user input or input is not valid
        //do nothing. show dashboard grid if hidden
        $scope.hide_preloader();
        $scope.canceler = $q.defer();
    }
    else{
      // user has valid input. show preloader.
      $scope.show_preloader();
      console.log($scope.doc_name);
      data = {
         doc_name: $scope.doc_name
      }
      $http.post('/dashboard_search_documents', data).success(function(data){
           //if return is 0=not found or something went wrong. show not found dom.
           if(data==0 || data=="" || data==null || data==undefined){
              $scope.doc_not_found();
              $scope.clear_autocomplete();
           }
           else{
               //search found matches. show results.
               $scope.documents = data;
               $scope.dashboard_preloader = false;
               $scope.documents_table = true;
               $scope.clear_autocomplete();
           }
           //reinit defer so autocomplete work again
           $scope.canceler = $q.defer();
      }); //end http
    }//end if

}//end searchDocuments.

// search specific document using doc id
$scope.searchSpecificDocument = function(doc_id,doc_name){
    $scope.doc_name = doc_name;
    $scope.clear_autocomplete();
    $scope.show_preloader();
    $scope.canceler.resolve();
    data = {
       doc_id: doc_id
    }
    $http.post('/dashboard_search_specific_doc', data).success(function(data){
         $scope.documents = data;
         $scope.dashboard_preloader = false;
         $scope.documents_table = true;
         $scope.canceler = $q.defer();
    });
}

// show autocomplete
$scope.onChangeInput = function(){

        $scope.canceler.resolve();
        //reinit defer so autocomplete work again
        $scope.canceler = $q.defer();
        // check if search input has value
        if($scope.doc_name=="" || $scope.doc_name==null || $scope.doc_name==undefined){
            //if no value or invalid input . do nothing.
        }
        else{
            $scope.clear_autocomplete();
            console.log('autocomplete..');
            data = {
                doc_name: $scope.doc_name
            }
            $http({method:'POST',url:'/dashboard_show_autocomplete', data, timeout: $scope.canceler.promise}).success(function(data){
                if(data.doc_names == 0){
                   $scope.clear_autocomplete();
                   $scope.no_result_found = true;
                }else{
                   $scope.clear_autocomplete();
                   $scope.ac_doc_names = data.doc_names;
                }
            });
        }
}


// on keypress check key
$scope.searchKeyPress = function(keyEvent) {
  //if key == 13 == ENTER  search document.
  if (keyEvent.which === 13){
    // method to be executed;
      $scope.searchDocuments();

  }
  //if key === 8 === backspace. clear autocomplete
  if (keyEvent.which === 8){
      $scope.clear_autocomplete();
      console.log('back-spacing');
      if($scope.doc_name==""){
          $scope.hide_preloader();
      }
  }
}; // end searchKeyPress..


// delete document
$scope.deleteDocument = function(doc_id){

    swal({
        title: "Delete document?",
        text: "You will not be able to recover this document after you delete.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
             //ajax send post delete with id.
             $('.doc-upd-btn').attr("disabled", "disabled");
             $.ajax({
                url: '/document/delete',
                data: {
                    doc_id: doc_id
                },
                type: 'POST',
                success: function(data) {
                    swal("Deleted!", "Document has been deleted.", "success");
                    $scope.searchDocuments();
                    $('.doc-upd-btn').removeAttr('disabled');
                }
            }); //end ajax
        } else {
            swal("Cancelled", "Your document is safe :)", "error");
        }
    });
}



$scope.approveDocument = function(doc_id,doc_org){

    swal({
        title: "Approve this document?",
        text: "Approving document will delete the original file from our server",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#b1d5ff",
        confirmButtonText: "Yes, Approve it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
             //ajax send post delete with id.
             $('.doc-upd-btn').attr("disabled", "disabled");
             $.ajax({
                url: '/document/approve',
                data: {
                    doc_id: doc_id,
                    doc_org: doc_org
                },
                type: 'POST',
                success: function(data) {
                    swal("Success!", "Document has been approved.", "success");
                    $scope.searchDocuments();
                    $('.doc-upd-btn').removeAttr('disabled');
                }
            }); //end ajax
        } else {
            swal("Cancelled", "Your document is safe :)", "error");
        }
    });
}

//end controller
});


// BAR CHART ==================================================
// CUSTOM BARCHART

randomScalingFactor = function() {
  return Math.round(Math.random() * 100);
}

function getData() {
  var dataSize = 7;
  var evenBackgroundColor = 'rgba(0, 119, 255, 1)';
  var oddBackgroundColor = 'rgba(177,213,255, 1)';
  var weeks = ["Sun","Mon","Tue","Wed",'Thu','Fir','Sat'];
  var labels = [];

  var scoreData = {
    label: 'Documents:',
    data: [],
    backgroundColor: [],
    borderColor: [],
    borderWidth: 1,
    hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
    hoverBorderColor: 'rgba(200, 200, 200, 1)',
  };

  for (var i = 0; i < dataSize; i++) {
    scoreData.data.push(window.randomScalingFactor());
    labels.push(weeks[i]);

    if (i % 2 === 0) {
      scoreData.backgroundColor.push(evenBackgroundColor);
    } else {
      scoreData.backgroundColor.push(oddBackgroundColor);
    }
  }

  return {
    labels: labels,
    datasets: [scoreData],
  };
};

window.onload = function() {
  var chartData = getData();
  console.dir(chartData);

  var myBar = new Chart(document.getElementById("myChart").getContext("2d"), {
    type: 'bar',
    data: chartData,
    options: {
      maintainAspectRatio: false,
      title:{
        display: false
      },
      legend: {
        display: false
      },

      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            autoSkip: false,
            display:false
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
            display:false
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
  });
};



// modefiy bars add border radius
Chart.elements.Rectangle.prototype.draw = function() {
    var ctx = this._chart.ctx;
    var vm = this._view;
    var left, right, top, bottom, signX, signY, borderSkipped, radius;
    var borderWidth = vm.borderWidth;
    // Set Radius Here
    // If radius is large enough to cause drawing errors a max radius is imposed
    var cornerRadius = 5;

    if (!vm.horizontal) {
        // bar
        left = vm.x - vm.width / 2;
        right = vm.x + vm.width / 2;
        top = vm.y;
        bottom = vm.base;
        signX = 1;
        signY = bottom > top? 1: -1;
        borderSkipped = vm.borderSkipped || 'bottom';
    } else {
        // horizontal bar
        left = vm.base;
        right = vm.x;
        top = vm.y - vm.height / 2;
        bottom = vm.y + vm.height / 2;
        signX = right > left? 1: -1;
        signY = 1;
        borderSkipped = vm.borderSkipped || 'left';
    }

    // Canvas doesn't allow us to stroke inside the width so we can
    // adjust the sizes to fit if we're setting a stroke on the line
    if (borderWidth) {
        // borderWidth shold be less than bar width and bar height.
        var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
        borderWidth = borderWidth > barSize? barSize: borderWidth;
        var halfStroke = borderWidth / 2;
        // Adjust borderWidth when bar top position is near vm.base(zero).
        var borderLeft = left + (borderSkipped !== 'left'? halfStroke * signX: 0);
        var borderRight = right + (borderSkipped !== 'right'? -halfStroke * signX: 0);
        var borderTop = top + (borderSkipped !== 'top'? halfStroke * signY: 0);
        var borderBottom = bottom + (borderSkipped !== 'bottom'? -halfStroke * signY: 0);
        // not become a vertical line?
        if (borderLeft !== borderRight) {
            top = borderTop;
            bottom = borderBottom;
        }
        // not become a horizontal line?
        if (borderTop !== borderBottom) {
            left = borderLeft;
            right = borderRight;
        }
    }

    ctx.beginPath();
    ctx.fillStyle = vm.backgroundColor;
    // ctx.strokeStyle = vm.borderColor;
    ctx.lineWidth = borderWidth;

    // Corner points, from bottom-left to bottom-right clockwise
    // | 1 2 |
    // | 0 3 |
    var corners = [
        [left, bottom],
        [left, top],
        [right, top],
        [right, bottom]
    ];

    // Find first (starting) corner with fallback to 'bottom'
    var borders = ['bottom', 'left', 'top', 'right'];
    var startCorner = borders.indexOf(borderSkipped, 0);
    if (startCorner === -1) {
        startCorner = 0;
    }

    function cornerAt(index) {
        return corners[(startCorner + index) % 4];
    }

    // Draw rectangle from 'startCorner'
    var corner = cornerAt(0);
    ctx.moveTo(corner[0], corner[1]);

    for (var i = 1; i < 4; i++) {
        corner = cornerAt(i);
        nextCornerId = i+1;
        if(nextCornerId == 4){
            nextCornerId = 0
        }

        nextCorner = cornerAt(nextCornerId);

        width = corners[2][0] - corners[1][0];
        height = corners[0][1] - corners[1][1];
        x = corners[1][0];
        y = corners[1][1];

        var radius = cornerRadius;

        // Fix radius being too large
        if(radius > height/2){
            radius = height/2;
        }if(radius > width/2){
            radius = width/2;
        }

        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);

    }

    ctx.fill();
    if (borderWidth) {
        ctx.stroke();
    }
};





</script>
@endsection
