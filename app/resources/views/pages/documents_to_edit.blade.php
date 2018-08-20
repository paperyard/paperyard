@extends('layouts.app')

@section('page_title', 'To Edit Documents')

@section('custom_style')

<style type="text/css" media="screen">

.lg-box {
    margin-top:90px
}

.f_cover {
  border: 3px solid #b1d5ff; border-radius: 4px; margin-top: 10px;
}

.f_cover img {
  width:100%;
}

.rm-pm {
  margin:0px;
  padding:0px;
}
/*------paperyard custom button -------------------*/

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
  background-color:#b1d5ff
}

/*-------------------------------------------------*/

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

/*----------custom table design--------------------------------*/

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
/* ------------------------------------------------------------*/

</style>
@endsection

@section('content')

<div class="row" ng-app="to_edit_app" ng-controller="to_edit_controller">

    <!-- search filter -->
    <div class="col-md-3">Search document:
      <input type="text" ng-model="search" ng-change="filter()" placeholder="Enter keyword" class="form-control" />
    </div>
    <!-- show total rows -->
    <div class="col-md-2" >Number of rows:
      <select ng-model="entryLimit" class="form-control">
        <option>5</option>
        <option>10</option>
        <option>20</option>
        <option>50</option>
        <option>100</option>
      </select>
    </div>

    <div class="col-md-12">
         <br>
    </div>
     <!-- preloader -->
    <div class="col-md-12">
      <center>
        <div class="preloader ng-hide center-block" ng-show="preloader" style="margin-top:100px">
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

    <!-- table of documents -->
    <div class="col-md-12 ng-hide"  ng-show="doc_table">
        <div class="table-responsive" style="height:100%;">
            <table class="table table-hover table-striped">
                  <thead style="background-color:#ebedf8; color:#000; font-size:13px; ">
                    <th style="width:50px">#</th>
                    <th>Document name</th>
                    <th>Status</th>
                    <th>Date ocred</th>
                    <th style="width:300px"> <span >Actions</span></th>
                  </thead>
                  <!-- | filter: dateRangeFilter('timestamp', dateFromTo) -->
                  <tbody style="font-size:11px;">
                    <tr ng-repeat="data in filtered = (list | filter:search ) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index" >
                       <td><# $index+1 #></td>
                       <td><# data.doc_ocr #></td>
                       <td ng-bind-html="data.process_status | ocred_status"></td>
                       <td ><# data.created_at #></td>
                       <td>
                          <span ng-if="data.process_status=='ocred_final'">
                            <a ng-href="/document/<#data.doc_id#>" style="text-decoration: none">
                              <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="Edit document" tooltip-top>
                                  <i class="material-icons cstm_icon_btn_ico">edit</i>
                              </button>
                            </a>
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
        </div>
    </div>
    <!-- show if document does not exist -->
    <div class="col-md-12" ng-show="filteredItems == 0">
        <div class="col-md-12">
            <h4>No document found</h4>
        </div>
    </div>
    <!-- Pagination -->
    <div class="col-md-6" ng-show="filteredItems > 0" style="margin-top:-20px">
        <div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>
    </div>
            <!-- show total number of found row -->
    <div class="col-md-6" ng-show="filteredItems > 0">
      <p style="color:#999" class="pull-right">Filtered <# filtered.length #> of <# totalItems #> total Documents</p>
    </div>

</div>

@endsection

@section('scripts')

<script type="text/javascript">

//used angular interpolate for syntax compatibility
var app = angular.module('to_edit_app', ['ui.bootstrap','ngSanitize'], function($interpolateProvider) {
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

// CUSTOM FILTERS
app.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
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


app.controller('to_edit_controller', function($scope, $http, $timeout) {

$scope.preloader = true;
$scope.doc_table = false;

$scope.get_to_edit_documents = function(){

   $http.get('/return_new_documents').success(function(data){
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 5; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter
        $scope.totalItems = $scope.list.length;
        $scope.preloader = false;
        $scope.doc_table = true;
    });
}
// set page number
$scope.setPage = function(pageNo){
    $scope.currentPage = pageNo;
};
// filder documents set timeout.
$scope.filter = function(){
    $timeout(function() {
        $scope.filteredItems = $scope.filtered.length;
    }, 1000);
};
//get documents datas
$scope.get_to_edit_documents();

//if dom is ready run invterval function
// angular.element(document).ready(function () {
//     //check for document status
//     setInterval(function() {
//          // method to be executed;
//          $scope.get_to_edit_documents();
//     },10000);
// });

// delete document
$scope.deleteDocument = function(doc_id) {

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
             var ids = [];
             ids.push(doc_id);
             $('.doc-upd-btn').attr("disabled", "disabled");
             $.ajax({
                url: '/document/delete',
                data: {
                    doc_id: ids
                },
                type: 'POST',
                success: function(data) {
                    swal("Deleted!", "Document has been deleted.", "success");
                    $scope.get_to_edit_documents();
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
                    $scope.get_to_edit_documents();
                    $('.doc-upd-btn').removeAttr('disabled');
                }
            }); //end ajax
        } else {
            swal("Cancelled", "Your document is safe :)", "error");
        }
    });
}

// end controller

});

</script>
@endsection
