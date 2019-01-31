@extends('layouts.app')

@section('page_title', 'WEBDAV connect')

@section('custom_style')
<link href="{{ asset('static/css/imap.css') }}" rel="stylesheet">
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
/* -------------------- paperyard custom button ----------------------------*/

.lg-btn-tx {
	font-size:18px;
	color:#017cff;
	font-weight:bold
}
.lg-btn_x2 {
    padding-left:30px;
    padding-right:30px;
	height:35px;
	border:none;
	border-radius:5px
}
.btn_color{
	background-color:#b1d5ff
}

/*---------------------------------------------------------------------------*/
.w_folder {
	margin-top:50px
}
.w_folder_icon {
	color:#b1d5ff; font-size:100px
}
.w_folder_tx {
	color:#7e7e7e; font-size:22px
}

/* ----------------------------- breadcrumb nav -------------------------------*/
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
/* ------------------------------------------------------------------------*/

.ftp_input {
	margin-top:-15px;
}

/* ------------table custom design -----------------*/

.table-striped>tbody>tr:nth-child(odd)>td,
.table-striped>tbody>tr:nth-child(odd)>th {
      line-height: 25px;
   min-height: 25px;
   height: 25px;
 }

.table-striped>thead>tr:nth-child(odd)>th {
   background-color: #ebedf8;
}

.table-hover tbody tr:hover td{
   background-color: #b1d5ff !important;
   cursor: pointer;
}

/*.table-striped>tbody>tr:nth-child(even)>td,
.table-striped>tbody>tr:nth-child(even)>th {
   background-color: #ebedf8;
}*/
/*---------------------------------------------------*/

.table tr td {
   margin-bottom:-5px;
}

.DisplayDir:hover {
	cursor: pointer;
	color:#017cff;
}
.pdf_file {
	color:#017cff !important;
	font-size:15px;
}
</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#">WEBDAV</a></li>
  </ul>
@endsection

@section('content')
<!-- Hover Rows -->
<div class="row" ng-controller="webdav_controller">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="block-header">
            <h2>
            WEBDAV connected to: {{ $webd->webdav_baseuri }}
            </h2>
            <br>
            <label style="color:orange">Select path here.</label>
            <h2>
            <span ng-repeat="dir in displayDir track by $index" ng-click="gotoSelectedDir($index)" class="DisplayDir">
                /<# dir #>
            </span>
            </h2>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 waitme">
        <!-- DOWNLOAD BUTTON -->
        <button type="button" class="btn btn-primary waves-effect btn-lg" ng-click="gotoSelectedDir(0)"><b style="font-size:16px">Home</b></button>
        <button type="button" class="btn btn-primary waves-effect btn-lg" ng-click="downloadFiles()"><b style="font-size:16px">Import</b></button>
        <!-- TABLE FILENAMES -->
        <div class="" style="margin-top:15px">
            <table class="table table-hover">
                <thead style="background-color:#ebedf8; color:#000; font-size:13px; ">
                    <th style="width:40px">
                        <input type="checkbox" id="webdavSelectAll" class="filled-in chk-col-blue"  ng-model="selectAll" ng-click="checkAll()" />
                        <label for="webdavSelectAll" style="margin-bottom:-12px" ></label>
                    </th>
                    <th>Filename</th>
                    <th style="width:150px">File size</th>
                </thead>
                <tbody style="font-size:13px;" >
                    <tr ng-repeat="data in webdav_datas track by $index">
                        <td >
                            <span ng-if="data.extension=='pdf'">
                                <input type="checkbox" id="webdav<#$index#>" class="filled-in chk-col-blue"  ng-model="data.select" ng-click="selectSingleFile()">
                                <label for="webdav<#$index#>" style="height:10px"></label>
                            </span>
                            <span ng-if="data.extension!='pdf'">
                                <i class="fa fa-hashtag" style="color:#ccc; font-size:20px;"></i>
                            </span>
                        </td>
                        <td>
                            <span>
                                <!-- Folder icon -->
                                <i class="fa fa-folder" style="font-size:20px; color:orange; position: absolute;" ng-if="data.type=='dir'"></i>
                                <!-- File icon except pdf -->
                                <i class="fa fa-file" style="font-size:17px; color:#999; position: absolute; margin-top:1px"   ng-if="data.type!='dir' && data.extension!='pdf'"></i>
                                <!-- Pdf icon -->
                                <i class="fa fa-file-pdf-o" ng-class="{true: 'pdf_file'}[data.extension=='pdf']" style="font-size:17px; position: absolute; margin-top:1px" ng-if="data.type!='dir' && data.extension=='pdf'"></i>
                            </span>
                            <span style="margin-left:28px">
                                <span ng-click="getFiles(data.path)" ng-if="data.type=='dir'"><# data.basename #></span>
                                <span ng-if="data.type!='dir'" ng-class="{true: 'pdf_file'}[data.extension=='pdf']"><# data.basename #></span>
                            </span>
                        </td>
                        <td>
                            <label ng-class="{true: 'pdf_file'}[data.extension=='pdf']" ng-if="data.extension=='pdf'"><# data.custom_size #></label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="ng-hide" ng-show="webdav_datas.length==0 && init_empty==1" style="margin-top:20px">
                <label style="color:red; margin-left:10px">Empty Directory</label>
            </div>
            </div><!-- table-responsive-->
            </div><!--/col -->
        </div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/imap.js') }}"></script>
<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', []);

app.controller('webdav_controller', function($scope, $http, $timeout) {

$scope.wait = function(){
    $('.waitme').waitMe({
        effect: 'facebook',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.8)',
        color: '#017cff'
    });
}

$scope.webdav_datas = [];
$scope.webdav_id  = '{{$webd->webdav_id}}';
$scope.init_empty = 0;
$scope.currentDir = ['{{$webd->webdav_pathprefix}}'];
$scope.displayDir = ['root'];

//------------------------------------------------------------------------------------
$scope.connectWEBDAV = function(dir){
    $scope.wait();
	data = { 
		 webdav_id:$scope.webdav_id,
		 dir:dir
    }
    $http({method:'POST',url:'/webdav_connect/webdav_files', data}).success(function(data){
    	$scope.webdav_datas = data;
    	$('.waitme').waitMe("hide");
    	$scope.init_empty = 1;

    	console.log(data);
    });
}
//------------------------------------------------------------------------------------
$scope.getFiles = function(dir){
	// store new opened directory.
	$scope.currentDir.push(dir);
    if($scope.currentDir.length>1){ 
    	$scope.displayDir = ['root'];
		$scope.displayDir = $scope.displayDir.concat(dir.split('/'));
    }
	$scope.connectWEBDAV(dir);

}
$scope.getFiles('{{$webd->webdav_pathprefix}}');
//------------------------------------------------------------------------------------
// change directory by selecting breadcrumb.
$scope.gotoSelectedDir = function(index){

	   // store current directory based on index of displayed directory.
       var goto = $scope.currentDir[index];
       // remove path beyond current path.
       $scope.currentDir.splice(index+1);

       if($scope.currentDir.length==1){
       	  	$scope.displayDir = ['root'];
       }else{
	    	$scope.displayDir = ['root'];
			$scope.displayDir = $scope.displayDir.concat(goto.split('/'));
	   }
	   $scope.connectWEBDAV(goto);	
}
//------------------------------------------------------------------------------------



$scope.selectedDocs = [];
// select all documents
$scope.checkAll = function() {
    //re init array of select doc.
    $scope.selectedDocs = [];
    angular.forEach($scope.webdav_datas, function(data) {
	      data.select = $scope.selectAll;
	      if(data.select==true && data.extension=="pdf"){
	           $scope.selectedDocs.push({'path':data.path,'filename':data.basename});
	      }

    });
    console.log($scope.selectedDocs);
};

$scope.selectSingleFile = function(){
   
    $scope.selectedDocs = [];
    angular.forEach($scope.webdav_datas, function(data) {
        if(data.select==true && data.extension=="pdf"){
            $scope.selectedDocs.push({'path':data.path,'filename':data.basename});
        }
    });
    console.log($scope.selectedDocs);

}

$scope.downloadFiles = function(){

	console.log($scope.selectedDocs);
    if($scope.selectedDocs.length>0){
        $scope.wait();
    	data = { 
    		webdav_id : $scope.webdav_id,
    		files     : $scope.selectedDocs
        }
        $http({method:'POST',url:'/webdav_connect/download_files', data}).success(function(data){
        	$('.waitme').waitMe("hide");

        	$scope.showNotification("Files successfully imported.","bg-blue")
        	console.log(data);

        });
    }else{
         swal("Error", "Please select a PDF file.", "error");
    }
}


 $scope.showNotification = function(Text,bg_color){
  
        var colorName      = bg_color;
        var placementAlign = "right";
        var placementFrom  = "bottom";
        var text           =  Text;
        var animateEnter   = "animated fadeInDown";
        var animateExit    = "animated fadeOutUp";
        var allowDismiss   = true;

        $.notify({
            message: text
        },
        {
            type: colorName,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            delay: 100,
            timer: 700,
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
    }


});

</script>
@endsection

