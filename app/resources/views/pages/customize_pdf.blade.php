@extends('layouts.app')

@section('page_title', 'Customize pdf')

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

/* --------------------- paperyard custom button ---------------------------------*/
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
/* ----------------------------------------------------------------------------------*/

.w_folder {
  margin-top:50px
}

.w_folder_icon {
  color:#b1d5ff; font-size:100px
}

.w_folder_tx {
  color:#7e7e7e; font-size:22px
}

/* --------------------- breadcrumb nav --------------------------------------------*/

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

/* ---------------------------------------------------------------------------------*/

/* --------------------- material floating button ----------------------------------*/
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
/* --------------------------------------------------------------------------------*/

.image{
    position:relative;
    overflow:hidden;
    padding-bottom:100%;

}
.image img{
    position:absolute;
    max-height:100%;
    border:1px solid #b1d5ff;
    border-radius: 5px;
}

.image:hover img:hover{
    border:2px solid #017cff;
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


</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Customize pdf</a></li>
  </ul>
@endsection

@section('content')
<div class="row" ng-app="customize_app" ng-controller="customize_controller">

    <span class="ng-hide" ng-show="document_pages">
      <!-- documents pages -->
      <div class="col-md-3 col-sm-6 cstm hvr-grow" ng-repeat="doc_p in doc_pages">
          <div class="caption ">
               <input type="checkbox" class="filled-in chk-col-blue"  id="docp<#doc_p.doc_page_num#>" ng-model="doc_p.select" />
               <label for="docp<#doc_p.doc_page_num#>">Page <#doc_p.doc_page_num#></label>
           </div>
          <div class="image ">
              <img ng-src="/static/documents_images/<#doc_p.doc_page_image_preview#>" class="img img-responsive full-width"/>
          </div>
      </div>
     <!-- Material floating button -->
      <nav mfb-menu position="br" effect="zoomin"
        active-icon="fa fa-times" resting-icon="fa fa-plus"
        toggling-method="click" >
        <button mfb-button icon="fa fa fa-trash" label="Remove page"  ng-click="removePages()"></button>
        <button mfb-button icon="fa fa-chevron-left" label="Rotate left 90*"    ng-click="rotatePages('rl90')"></button>
        <button mfb-button icon="fa fa-chevron-right" label="Rotate right 90*"  ng-click="rotatePages('rr90')"></button>
        <button mfb-button icon="fa fa-refresh" label="Rotate 180*"   ng-click="rotatePages('rf180')"></button>
      </nav>

    </span>

    <!-- preloader -->
    <div class="col-md-12">
        <center>
          <div class="preloader ng-hide center-block" ng-show="preloader" style="margin-top:150px">
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

</div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/customize_pdf.js') }}"></script>
<script type="text/javascript">
//used angular interpolate for syntax compatibility
var app = angular.module('customize_app', ['ngSanitize','ng-mfb'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<#');
    $interpolateProvider.endSymbol('#>');
});


app.controller('customize_controller', function($scope, $http, $timeout) {

$scope.document_pages = true;
$scope.preloader = false;

$scope.showPreloader = function(){
  $scope.document_pages = false;
  $scope.preloader = true;
}

$scope.hidePreloader = function(){
  $scope.document_pages = true;
  $scope.preloader = false;
}

$scope.getDocument = function(){
  $scope.showPreloader();
  data = {
     doc_id:'{{$doc_id}}'
  }
  $http.post('/getDocPages', data).success(function(data){
      $scope.doc_pages = data;
      $scope.hidePreloader();
  });
}

$scope.getDocument();

$scope.removePages = function(){

    //if select pages < total pages continue remove page.
    var selectedPagesCount = $scope.doc_pages.filter(el => el.select===true).length;
    var totalPages = $scope.doc_pages.length;

    //user selected page. selected page less than to total page.
    if(selectedPagesCount<totalPages&&selectedPagesCount!=0){
          swal({
              title: "Remove document pages",
              text:  "Are you sure you want to delete this pages?",
              type:  "warning",
              showCancelButton: true,
              confirmButtonColor: "#017cff",
              confirmButtonText: "Yes, delete it!",
              cancelButtonText: "No, cancel please!",
              closeOnConfirm: true,
              closeOnCancel: false
          }, function (isConfirm) {
              if (isConfirm) {
                  $scope.showPreloader();
                  $scope.selectedDocPages = [];
                  $scope.docName;
                  angular.forEach($scope.doc_pages, function(data) {
                    if(data.select == true){
                        $scope.selectedDocPages.push(data.doc_page_num);
                        $scope.docName = data.doc_ocr;
                    }
                  });
                  data = {
                      doc_id:'{{$doc_id}}',
                      doc_pages: $scope.selectedDocPages,
                      doc_name: $scope.docName
                  }
                  $http.post('/cstm_removeDocPages', data).success(function(data){
                      $scope.getDocument();
                  });
              } else {
                  swal("Cancelled", "All page are safe.", "error");
              }
          });
    }if(selectedPagesCount==totalPages){
        swal("Error", "Removing all pages is not allowed", "error");
    }if(selectedPagesCount==0){
        swal("Error", "Please select a page", "error");
    }

}

$scope.rotatePages = function(rotation){

    var selectedPagesCount = $scope.doc_pages.filter(el => el.select===true).length;

    if(selectedPagesCount>=1){

        $scope.showPreloader();
        $scope.selectedDocPages = [];
        $scope.docName;
        angular.forEach($scope.doc_pages, function(data) {
          if(data.select == true){
              $scope.selectedDocPages.push(data.doc_page_num);
              $scope.docName = data.doc_ocr;
          }
        });
        //rr90,rl90,rf80
        data = {
            doc_id:    '{{$doc_id}}',
            doc_pages: $scope.selectedDocPages,
            doc_name:  $scope.docName,
            rotation:  rotation
        }
        $http.post('/cstm_rotateDocPages', data).success(function(data){
            $scope.getDocument();
        });

    }else{
       swal("Error", "Please select a page", "error");
    }
}

});

</script>
@endsection



