@extends('layouts.app')

@section('page_title', 'Merge pdf')

@section('custom_style')

<link href="{{ asset('static/css/customize_pdf.css') }}" rel="stylesheet">
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

/* ----------------------- breadcrumb nav -----------------------------*/

.arrows li {
    background-color:#b1d5ff;
    display: inline-block;
    line-height: 33px;
    padding: 0 20px 1px 10px;
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
/*---------------------------------------------------*/



@media screen and (max-width:500px) {
   .merge_sm_chevron {
       margin-left:-30px !important;
   }
}


.preload_custm_loc{
   margin-top:-7px;
}


.list-group-autocomplete{
   position:absolute !important;
   z-index:5 !important;

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
  outline: none;
  border: none !important;
  -webkit-box-shadow: none !important;
  -moz-box-shadow: none !important;
  box-shadow: none !important;
}

.ocr_success {
    color:#017cff;
}

.ocr_failed {
     color:red;
}

.mg-icon_1 {
   color: #017cff;
   font-size:30px;
   margin-right:10px;

}

.mg-icon_2 {
   color: red;
   font-size:30px;
   margin-right:10px;

}




</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Merge pdf</a></li>
  </ul>
@endsection

@section('content')

<!-- Hover Rows -->
<div class="row" ng-controller="merge_controller">

      <div class="col-md-12">
          @if (session()->has('merge_success'))
              <div class="alert bg-light-blue alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <p>{!! session('merge_success') !!}</p>
              </div>
          @endif
          <div class="block-header">
              <h2>
                  Note !
                  <small>Document 2 will be merge into document 1.</small>
              </h2>
          </div>
      </div>

      <div class="col-md-12" ng-click="clearAutoComplete1()">
          <div class="card">

            <div class="header">
                <h2 style="color:#017cff;">
                    Search documents
                </h2>
                 <div>
                   <div class="preloader pull-right pl-size-xs preload_custm_loc ng-hide" ng-show="ac_preloader1">
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
                <!-- INPUT SEARCH ---------------------------- -->
                <div class="">
                  <input type="text" class="form-control  input-lg search_inp  cstm_input" placeholder="@lang('dashboard.input_search_p_holder')"  ng-model-options='{ debounce: 1000 }' ng-change="onChangeInput()" ng-model="doc_keyword" ng-keydown="searchKeyPress($event)">

                  <div class="row cleafix">
                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                      <div class="list-group ">
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
                </div>
                <br>
                <!-- TABLE ------------------------------------ -->

                <!-- Documents table search result -->
                <div class="table-responsive" ng-click="clear_autocomplete()">
                  <table class="table table-hover ng-hide table-striped" ng-show="documents_table">
                    <thead style="background-color:#ebedf8; color:#000; font-size:13px; ">
                      <th>#</th>
                      <th>Recipient</th>
                      <th>Sender</th>
                      <th>Category</th>
                      <th>OCRED</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </thead>
                    <tbody style="font-size:13px;">
                      <tr ng-repeat="data in documents track by $index">
                        <td><#$index+1#></td>
                        <td><# data.receiver | default #></td>
                        <td><# data.sender   | default #></td>
                        <td><# data.category | default #></td>
                        <td ng-bind-html="data.process_status  | ocr_status "></td>
                        <td><# data.date     | default  #></td>    
                        <td style="width:200px">
                            <!-- set document 1 -->
                            <button ng-click="selectDocument1(data)" type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" style="height:26px" data-toggle="tooltip" title="" data-original-title="Set as Document 1" tooltip-top id="d1<#doc.doc_id#>">
                                  Doc 1
                            </button>
                            <!-- set document 2 -->
                            <button ng-click="selectDocument2(data)" type="button" class="btn btn-default waves-effect cstm_icon_btn doc-upd-btn" style="height:26px" data-toggle="tooltip" title="" data-original-title="Set as Document 2" tooltip-top id="d2<#doc.doc_id#>">
                                  Doc 2
                            </button>
                            <!-- View Document in PDF viewer -->
                            <a ng-href="/files/ocr/<#data.doc_ocr#>" style="text-decoration: none" target="_blank">
                                <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="View document" tooltip-top>
                                  <i class="material-icons cstm_icon_btn_ico">remove_red_eye</i>
                                </button>
                            </a>
                         
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <center>
                    <div class="preloader ng-hide center-block" ng-show="merge_docs_preloader" style="margin-top:100px">
                      <div class="spinner-layer pl-blue">
                        <div class="circle-clipper left">
                          <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                          <div class="circle"></div>
                        </div>
                      </div>
                    </div>

                    <div class="center-block ng-hide" ng-show="not_found" style="margin-top:50px">
                      <h4 style="color:red">No document found.</h4>
                    </div>
                  </center>
                </div>


            </div><!-- /body -->
         </div> <!-- /card-->
       </div> <!-- /col -->

       <!-- Selected documents -->
       <div class="col-md-12" ng-show="doc1.length>0 || doc2.length>0">
          <div class="card">

            <div class="header">
                <h2>
                    Selected documents to be merge
                </h2>
            </div>

            <div class="body">


                <div class="table-responsive" ng-click="clear_autocomplete()">
                  <table class="table table-hover ng-hide table-striped" ng-show="documents_table">
                    <thead style="background-color:#ebedf8; color:#000; font-size:13px; ">
                      <th>#</th>
                      <th>Recipient</th>
                      <th>Sender</th>
                      <th>Category</th>
                      <th>OCRED</th>
                      <th>Date</th>
                      <th style="width:200px">Actions</th>
                    </thead>
                    <tbody style="font-size:13px;">
                      <tr ng-repeat="data in doc1 track by $index">
                        <td><#$index+1#></td>
                        <td><# data.receiver | default #></td>
                        <td><# data.sender   | default #></td>
                        <td><# data.category | default #></td>
                        <td ng-bind-html="data.process_status  | ocr_status "></td>
                        <td><# data.date     | default  #></td>    
                        <td style="width:100px">
                             <a ng-href="/files/ocr/<#data.doc_ocr#>" style="text-decoration: none" target="_blank">
                                <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="View document" tooltip-top>
                                  <i class="material-icons cstm_icon_btn_ico">remove_red_eye</i>
                                </button>
                            </a>    
                        </td>
                      </tr>
                      <tr ng-repeat="data in doc2 track by $index">
                        <td><#$index+2#></td>
                        <td><# data.receiver | default #></td>
                        <td><# data.sender   | default #></td>
                        <td><# data.category | default #></td>
                        <td ng-bind-html="data.process_status  | ocr_status "></td>
                        <td><# data.date     | default  #></td>    
                        <td style="width:100px">
                             <a ng-href="/files/ocr/<#data.doc_ocr#>" style="text-decoration: none" target="_blank">
                                <button type="button" class="btn btn-default waves-effect cstm_icon_btn" data-toggle="tooltip" title="" data-original-title="View document" tooltip-top>
                                  <i class="material-icons cstm_icon_btn_ico">remove_red_eye</i>
                                </button>
                            </a>    
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

            </div><!--body -->
         </div>  <!--card --> 
      </div> <!-- col -->


      <div class="col-md-12" ng-show="doc1.length>0 || doc2.length>0">
          <div class="card">

                <div class="header">
                    <h2>
                        Select merge options
                    </h2>
                </div>

                <div class="body row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                         <input name="merge_rule" type="radio"  id="r_append"  class="radio-col-blue" ng-model="merge_rule" ng-value="'append'">
                          <label for="r_append">APPEND</label><br>
                          <i class="fa fa-file-text-o mg-icon_1"></i>
                          <i class="fa fa-file-text-o mg-icon_1"></i>
                          <i class="fa fa-file-text-o mg-icon_2"></i>
                          <i class="fa fa-file-text-o mg-icon_2"></i>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                          <input name="merge_rule" type="radio" id="r_interleave" class="radio-col-blue" ng-model="merge_rule" ng-value="'interleave'">
                          <label for="r_interleave">INTERLEAVE</label><br>
                          <i class="fa fa-file-text-o mg-icon_1"></i>
                          <i class="fa fa-file-text-o mg-icon_2"></i>
                          <i class="fa fa-file-text-o mg-icon_1"></i>
                          <i class="fa fa-file-text-o mg-icon_2"></i>
                    </div>
                </div>
            </div>
         </div>


      <div class="col-md-12 ng-hide" style="padding-bottom:80px" ng-show="merge_btn">
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="button" ng-click="checkSelectedDocs()"><span class="lg-btn-tx">Merge documents</span></button>
      </div>
     
</div>


@endsection

@section('scripts')
<script src="{{ asset('static/js/customize_pdf.js') }}"></script>
<script type="text/javascript">


//inject this app to rootApp
var app = angular.module('app', ['ngSanitize']);


app.directive('tooltipTop', function() {
      return function(scope, element, attrs) {
      element.tooltip({
        trigger:"hover",
        placement: "top",
      });

    };
});

app.filter('default', function(){
   return function(data){
       if(data==null){
           data = "N/D";
           return data;
       }
       return data;
   }
});

app.filter('ocr_status', function(){
   return function(data){
       if(data=="ocred_final"){
           data = "<b class='ocr_success'>"+"YES"+"</b>";
           return data;
       }else{
           data = "<b class='ocr_failed'>"+"NO"+"</b>";
           return data;
       }
   }
});



app.controller('merge_controller', function($scope, $http, $timeout, $q) {

// cancel previous http request
// eg running autocomplete. if user press enter search. cancel all previous running http request.
$scope.canceler = $q.defer();
$scope.search_canceler = $q.defer();


$scope.merge_docs_preloader = false;
//show/hide not found div.
$scope.not_found =           false;
//show hide documents table result.
$scope.documents_table =     false;

$scope.merge_btn = true;

//docs data 
$scope.doc1 = [];
$scope.doc2 = [];


// clear autocomplete on search bar
$scope.clear_autocomplete = function(){
    $scope.ac_tags     = null;
    $scope.ac_folders  = null;
    $scope.ac_fulltext = null;
    $scope.no_result_found = false;
}

// show preloader when user select keyword to search
$scope.show_preloader = function(){
    $scope.not_found =           false;
    $scope.merge_docs_preloader = true;
    $scope.documents_table =     false;
}
// hide preloader when user got result.
$scope.hide_preloader = function(){
    $scope.merge_docs_preloader = false;
    $scope.not_found =           false;
    $scope.documents_table =     false;
}
// show not found div when search has no result.
$scope.doc_not_found = function(){
    $scope.merge_docs_preloader = false;
    $scope.not_found =           true;
    $scope.documents_table =     false;
}



// on keypress check key
$scope.searchKeyPress = function(keyEvent) {

  //if key == 13 == ENTER  search document.
  if (keyEvent.which === 13){
      //delay function for 1 second
      $timeout( function()
      {
        // method to be executed;
            $scope.searchDocuments($scope.doc_keyword,'no_filter')
      }, 1000); //end timeout.
  }
  // key 8 = backspace. clear autocomplete
  if (keyEvent.which === 8){
    $scope.clear_autocomplete();
    if($scope.doc_keyword==""){
        $scope.hide_preloader();
    }

  }
};


// show autocomplete
$scope.onChangeInput = function(){


    //cancel previous autocomplete post request.
    $scope.canceler.resolve();
    //reinit $q.defer make new autocomplete post request
    $scope.canceler = $q.defer();
    // check if search input has value
    if($scope.doc_keyword!="" && $scope.doc_keyword!=null && $scope.doc_keyword!=undefined){
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

        });
    }
}



// search documents function
$scope.searchDocuments = function(keyword,filter){
    //clear autocomplete
    $scope.clear_autocomplete();
    //cancel previous autocomplete post request.
    $scope.canceler.resolve();
    //reinit $q.defer make new autocomplete post request
    //$scope.canceler = $q.defer();
    $scope.doc_keyword = keyword;  
    //-------------------------------------------------
    //cancel previous selectSearch post request
    $scope.search_canceler.resolve();
    //reinit $q.defer to make new post request.
    $scope.search_canceler = $q.defer();
    $scope.show_preloader();

    data = {
        doc_keyword: keyword,
        doc_filter: filter
    }
 
    $http({method:'POST',url:'/common_search/search', data, timeout: $scope.search_canceler.promise}).success(function(data){        
         if(data=="error"){
             $scope.doc_not_found();
         }else{
             //pass result to scope documents to be rendered in table
             $scope.documents = data;
             //make documents table visible
             $scope.documents_table = true;
             //hide preloader
             $scope.merge_docs_preloader = false;
             console.log(data);
         }

    }); //end http


}//end searchDocuments.


// -------------------------------------------------------------------

$scope.wait = function(){

    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });

}

$scope.selectDocument1 = function(data){
   $scope.doc1 = [];
   $scope.doc1.push(data);
   console.log($scope.doc1);
}

$scope.selectDocument2 = function(data){
   $scope.doc2 = [];
   $scope.doc2.push(data);
   console.log($scope.doc2);
}

//check if selected docs met the conditions
$scope.checkSelectedDocs = function(){

    if($scope.doc1.length>0 && $scope.doc2.length>0){

         if($scope.doc1[0]['doc_id']!=$scope.doc2[0]['doc_id']){
  
             if($scope.merge_rule!=undefined){
                   $scope.mergeDocuments();
             }else{
                swal("Error", "Please select merge option", "error");
             }
         }else{
             swal("Error", "Cant merge the same document", "error");
         }
    }else{
       swal("Error", "Please search and set 2 documents to be merge.", "error");
    }
    
}

//merge document
$scope.mergeDocuments = function(){

    data = {
      doc1_name:  $scope.doc1[0]['doc_ocr'],
      doc2_name:  $scope.doc2[0]['doc_ocr'],
      doc2_org:   $scope.doc2[0]['doc_org'],
      doc1_id:    $scope.doc1[0]['doc_id'],
      doc2_id:    $scope.doc2[0]['doc_id'],
      merge_rule: $scope.merge_rule
    }
    
    $scope.merge_btn = false;
    $scope.wait();
    
    $http.post('/mergeDocuments', data).success(function(data){
        window.location.reload();
    });
            
}




});

</script>

@endsection



