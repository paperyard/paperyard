@extends('layouts.app')

@section('page_title', 'Upload documents')

@section('active_upload', 'p_active_nav')

@section('custom_style')
<!-- FINE UPLOADER -->
<link href="{{ asset('static/css/document_uploader.css') }}" rel="stylesheet">
<style type="text/css" media="screen">

/* --------------------paperyard custom button ------------------------*/

.lg-btn-tx {
  font-size:18px;
  color:#017cff;
  font-weight:bold
}
.lg-btn_x2 {
  width:270px;
  height:40px;
  border:none;
  border-radius:5px;
}
.btn_color{
  background-color:#b1d5ff;
}

/*----------------------------------------------------------------------*/

.cstm_up_btn {
  width:130px;
  font-size:20px;
}

.f-upload-icon {
  font-size: 50px !important;
  color:#999;
}

/* ----------------------------- breadcrumb nav main ---------------------*/

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
     <li class="li2"><a href="#" >Upload Documents</a></li>
  </ul>
@endsection

@section('content')

<div style="margin-top:50px" ng-controller="uploadController">
<form action="/upload_documents" class="dropzone" id="my-awesome-dropzone" style="background-color: white !important; border-style: dashed !important; border-color:#ccc !important;" >
@csrf
<div class="dz-message" data-dz-message>
	<i class="fa fa-cloud-upload f-upload-icon"></i><br>
	<span>Click here to select files or drag and drop file here.</span>
    </div>
</form>
</div>

@endsection

@section('scripts')
<script src="{{ asset('static/js/document_uploader.js') }}"></script>
<script type="text/javascript">

$(function(){

var animateEnter = '';
var animateExit = '';

Dropzone.options.myAwesomeDropzone = {
  paramName: "file", // The name that will be used to transfer the file
  maxFilesize: 100, // MB
  timeout: 0,
  acceptedFiles: ".png,.jpg,.bmp,.jpeg,.pdf",
  init: function() {
    this.on("success", function(file, response) {
       showNotification('bg-blue', 'File uploaded succesfully.', 'bottom', 'right', animateEnter, animateExit);
    });
  }
};

function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
    if (colorName === null || colorName === '') { colorName = 'bg-black'; }
    if (text === null || text === '') { text = 'Turning standard Bootstrap alerts'; }
    if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
    if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
    var allowDismiss = true;

    $.notify({
        message: text
    },
      {
        type: colorName,
        allow_dismiss: allowDismiss,
        newest_on_top: true,
        timer: 1000,
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


var app = angular.module('app', []);
app.controller('uploadController', function($scope) {

});

</script>
@endsection
