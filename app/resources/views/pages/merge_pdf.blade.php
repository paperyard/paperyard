@extends('layouts.app')

@section('page_title', 'Merge pdf')

@section('custom_style')

<link href="{{ asset('static/css/customize_pdf.css') }}" rel="stylesheet">
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

/*------------------paperyard custom button -------------------------*/

.lg-btn-tx {
  font-size:18px;
  color:#017cff;
  font-weight:bold
}

.lg-btn_x2 {
  width:230px;
  height:35px;
  border:none;
  border-radius:5px
}

.btn_color{
  background-color:#b1d5ff
}
/* --------------------------------------------------------------------*/

.w_folder {
  margin-top:50px
}

.w_folder_icon {
  color:#b1d5ff; font-size:100px
}

.w_folder_tx {
  color:#7e7e7e; font-size:22px
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

.image{
    position:relative;
    overflow:hidden;
    padding-bottom:100%;

}
.image2{
    position:relative;
    overflow:hidden;
}
.image img{
    position:absolute;
    max-height:100%;
    border:1px solid #b1d5ff;
    border-radius: 5px;
}
.image .content{
    position:absolute;
    max-height:100%;
    margin-left:45%;
    margin-top:30%;
}
.image .img_content{
    position:absolute;
    max-height:100%;
}
.image:hover .img1:hover{
    border:2px solid #017cff;
   -webkit-transition: all .3s;
      -moz-transition: all .3s;
       -ms-transition: all .3s;
        -o-transition: all .3s;
           transition: all .3s;
   cursor: pointer;
}
.image:hover .img2:hover{
   border:2px solid red;
   -webkit-transition: all .3s;
      -moz-transition: all .3s;
       -ms-transition: all .3s;
        -o-transition: all .3s;
           transition: all .3s;
   cursor: pointer;
}
.cstm {
  margin-top:20px !important;
}

.hvr-grow:hover {
transform: scale(1.03) !important;
}

/*-----mater floating button -------------------------------------------------*/

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
      background-color:#4f93ff !important;
}
/*-----------------------------------------------------------------------------*/

.preload_custm_loc {
    margin-top:-20px;
}


.list-group-autocomplete{
   position:absolute !important;
   z-index:5 !important;
}

.mg-icon_1 {
   color: #017cff;
   font-size:60px;
   margin-right:10px;

}

.mg-icon_2 {
   color: red;
   font-size:60px;
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
<div class="row" ng-app="merge_app" ng-controller="merge_controller">

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
                  <small>Document to 2 will be merge into document 1 and will automatically deleted after merging.</small>
              </h2>
          </div>
      </div>
     <!-- ================================ DOCUMENT 1 ======================================== -->
      <div class="col-md-4" ng-click="clearAutoComplete1()">
          <div class="card">

            <div class="header">
                <h2 style="color:#017cff;">
                    Document 1
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
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" name="doc1_keyword" ng-model="doc1_keyword"  ng-model-options='{ debounce: 300 }' ng-change="doc1Autocomplete()" class="form-control" ng-keydown="backSpace($event,'for_doc1keyword')"  placeholder="Search document name.">
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                          <div class="list-group ">
                             <a  class="list-group-item" ng-click="doc1Select(doc.doc_id,doc.doc_ocr)" ng-repeat="doc in doc1_docs track by $index" ng-show="doc1_docs!=null && doc1_docs.length>0">
                                  <# doc.doc_ocr #>
                            </a>
                             <a  class="list-group-item ng-hide" ng-show="no_doc1_found">
                                  No document found..
                            </a>
                         </div>
                      </div>
                    </div>
                </div>

                <div class="image ">

                     <span ng-repeat="doc_1 in doc1 track by $index">
                        <img ng-src="/static/documents_images/<#doc_1.doc_page_image_preview#>" class="img img1 img-responsive full-width"/>
                     </span>

                     <div class="content ng-hide" ng-show="doc1SelectPreloader">
                        <div class="preloader pull-right pl-size-md" >
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

            </div>
         </div>
       </div>
      <!-- ============================ DOCUMENT 2 ======================================== -->
       <div class="col-md-4" ng-click="clearAutoComplete2()">
          <div class="card">
            <div class="header">
                <h2 style="color:red;">
                    Document 2
                </h2>
                 <div>
                   <div class="preloader pull-right pl-size-xs preload_custm_loc ng-hide" ng-show="ac_preloader2">
                      <div class="spinner-layer pl-red">
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
                <div class="form-group">
                    <div class="form-line error">
                        <input type="text" name="doc2_keyword" ng-model="doc2_keyword"  ng-model-options='{ debounce: 300 }' ng-change="doc2Autocomplete()" class="form-control" ng-keydown="backSpace($event,'for_doc2keyword')"  placeholder="Search document name.">
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                          <div class="list-group ">
                             <a  class="list-group-item" ng-click="doc2Select(doc.doc_id,doc.doc_ocr)" ng-repeat="doc in doc2_docs track by $index" ng-show="doc2_docs!=null && doc2_docs.length>0">
                                  <# doc.doc_ocr #>
                            </a>
                             <a  class="list-group-item ng-hide" ng-show="no_doc2_found">
                                  No document found..
                            </a>
                         </div>
                      </div>
                    </div>
                </div>

                <div class="image">
                     <span ng-repeat="doc_2 in doc2 track by $index">
                        <img ng-src="/static/documents_images/<#doc_2.doc_page_image_preview#>" class="img img2 img-responsive full-width"/>
                     </span>

                     <div class="content ng-hide" ng-show="doc2SelectPreloader">
                        <div class="preloader pull-right pl-size-md" >
                            <div class="spinner-layer pl-red">
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
            </div>
         </div>
       </div>

       <div class="col-md-4">
          <div class="card">

                <div class="header">
                    <h2>
                        Select merge options
                    </h2>
                </div>

                <div class="body">
                    <div class="image2">
                        <div class="img_content">
                              <input name="group4" type="radio" id="radio_12" class="radio-col-blue" ng-model="merge_rule" ng-value="'append'">
                              <label for="radio_12">APPEND</label>
                              <br><br>
                                  <i class="fa fa-file-text-o mg-icon_1"></i>
                                  <i class="fa fa-file-text-o mg-icon_1"></i>
                                  <i class="fa fa-file-text-o mg-icon_2"></i>
                                  <i class="fa fa-file-text-o mg-icon_2"></i>
                              <br><br><br>
                              <input name="group4" type="radio" id="radio_13" class="radio-col-blue" ng-model="merge_rule" ng-value="'interleave'">
                              <label for="radio_13">INTERLEAVE</label>
                              <br><br>
                                  <i class="fa fa-file-text-o mg-icon_1"></i>
                                  <i class="fa fa-file-text-o mg-icon_2"></i>
                                  <i class="fa fa-file-text-o mg-icon_1"></i>
                                  <i class="fa fa-file-text-o mg-icon_2"></i>
                         </div>
                     </div>
                     <br><br><br>
                     <div>
                         <button class="btn-flat btn_color main_color waves-effect lg-btn_x2" ng-click="mergeDocument()"><span class="lg-btn-tx">Merge documents</span></button>
                     </div>
                </div>
            </div>
         </div>



</div>


@endsection

@section('scripts')
<script src="{{ asset('static/js/customize_pdf.js') }}"></script>
<script type="text/javascript">
//used angular interpolate for syntax compatibility
var app = angular.module('merge_app', ['ngSanitize'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<#');
    $interpolateProvider.endSymbol('#>');
});


app.controller('merge_controller', function($scope, $http, $timeout, $q) {

$scope.wait = function(){

    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });

}

$scope.canceler1 = $q.defer();
$scope.canceler2 = $q.defer();

$scope.merge_rule = 'append';

$scope.ac_preloader1 = false;
$scope.ac_preloader2 = false;

$scope.doc1SelectPreloader = false;

$scope.doc1 = null;
$scope.doc2 = null;

$scope.mergeRule = null;

$scope.cancelPrevRequest1 = function(){
  // cancel all previous http request
  $scope.canceler1.resolve();
  // reinit canceler. new request can be made.
  $scope.canceler1 = $q.defer();
}

$scope.cancelPrevRequest2 = function(){
  // cancel all previous http request
  $scope.canceler2.resolve();
  // reinit canceler. new request can be made.
  $scope.canceler2 = $q.defer();
}

// on keypress check key
$scope.backSpace = function(keyEvent,docF) {

  if(keyEvent.which === 8){
      if(docF=='for_doc1keyword'){
         $scope.doc1_docs = null;
         $scope.no_doc1_found = false;
      }
      if(docF=='for_doc2keyword'){
         $scope.doc2_docs = null;
         $scope.no_doc2_found = false;
      }
  }

}; // end backSpace


//======================================== document 1 ============================================

$scope.doc1Autocomplete = function(){
   if($scope.doc1_keyword!="" && $scope.doc1_keyword != null && $scope.doc1_keyword != undefined){
        $scope.ac_preloader1 = true;
        $scope.cancelPrevRequest1();
        $scope.findDoc("for_doc1",$scope.doc1_keyword);
   }
}

$scope.doc1Select = function(doc_id,doc_ocr){

   $scope.cancelPrevRequest1();
   $scope.doc1_keyword = doc_ocr;
   $scope.doc1SelectPreloader = true;
   $scope.doc1 = [];
   $scope.doc1_docs = null;
   $scope.no_doc1_found = false;

   data = {
      doc_id:doc_id
   }

   $http.post('/merge_doc_select', data).success(function(data){
       $scope.doc1 = data;
       $scope.doc1SelectPreloader = false;
   });
}

//========================================== document 2 ==================================================

$scope.doc2Autocomplete = function(){
   if($scope.doc2_keyword!="" && $scope.doc2_keyword != null && $scope.doc2_keyword != undefined){
        $scope.ac_preloader2 = true;
        $scope.cancelPrevRequest2();
        $scope.findDoc("for_doc2",$scope.doc2_keyword);
   }
}

$scope.doc2Select = function(doc_id,doc_ocr){

   $scope.cancelPrevRequest2();
   $scope.doc2_keyword = doc_ocr;
   $scope.doc2SelectPreloader = true;
   $scope.doc2 = [];
   $scope.doc2_docs = null;
   $scope.no_doc2_found = false;

   data = {
      doc_id:doc_id
   }

   $http.post('/merge_doc_select', data).success(function(data){
       $scope.doc2 = data;
       $scope.doc2SelectPreloader = false;
   });
}


//======================================================================================================

$scope.findDoc = function(docF,doc_keyword){

    data = {
         doc_keyword: doc_keyword
    }

    if(docF=='for_doc1'){
        $http({method:'POST',url:'/merge_docs_autocomplete', data, timeout: $scope.canceler1.promise}).success(function(data){
             if(data.length<=0){
                $scope.no_doc1_found = true;
             }else{
                $scope.no_doc1_found = false;
                $scope.doc1_docs = data;
             }
             $scope.ac_preloader1 = false;
        });
    }

    if(docF=='for_doc2'){
        $http({method:'POST',url:'/merge_docs_autocomplete', data, timeout: $scope.canceler2.promise}).success(function(data){
             if(data.length<=0){
                $scope.no_doc2_found = true;
             }else{
                $scope.no_doc2_found = false;
                $scope.doc2_docs = data;
             }
            $scope.ac_preloader2 = false;
        });
    }

}

$scope.clearAutoComplete1 = function(){
   // $scope.doc1_docs = null;
   // $scope.no_doc1_found = false;
}

$scope.clearAutoComplete2 = function(){
   // $scope.doc2_docs = null;
   // $scope.no_doc2_found = false;
}

//========================================================================================================

$scope.mergeDocument = function(){

    var doc1_id   = null;
    var doc1_name = null;
    var doc2_id   = null;
    var doc2_name = null;

    angular.forEach($scope.doc1, function(value, key) {
        doc1_id = value.doc_id;
        doc1_name = value.doc_ocr
    });
    angular.forEach($scope.doc2, function(value, key) {
        doc2_id = value.doc_id;
        doc2_name = value.doc_ocr
    });

    if(doc1_id!=null){

        if(doc2_id!=null){

            if(doc1_id!=doc2_id){

                   $scope.wait();
                   data = {
                      doc1_id:doc1_id,
                      doc1_name:doc1_name,
                      doc2_id:doc2_id,
                      doc2_name:doc2_name,
                      merge_rule: $scope.merge_rule
                   }
                   $http.post('/mergeDocuments', data).success(function(data){
                        window.location.reload();
                   });

            }else{
               swal("Error", "Cannot merge the same document", "error");
            }
        }
        else{
           swal("Error", "Please search and select document 2", "error");
        }
    }else{
       swal("Error", "Please search and select document 1", "error");
    }
}

});

</script>

@endsection



