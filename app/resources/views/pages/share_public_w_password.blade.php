@extends('layouts.auth')

@section('style')

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

@endsection

@section('content')



<div class="row clearfix lg-box" ng-controller="share_public_controller">


  <h4 style="color:#7e7e7e" class="text-center">This document has been shared with you.</h4>

  <hr>
  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-md-offset-3  col-lg-offset-3 clearfix">

    <div class="col-sm-8 col-xs-8 rm-pm">
      {{ $document->doc_ocr }}
    </div>

    <div class="col-sm-4 col-xs-4 rm-pm">
      <a href="{{ url('/files_public/ocr').'/'.$document->doc_ocr) }}" download>
      <button type="button" class="btn bg-light-blue waves-effects shareBtn"  style="float:right">
        Download
      </button>
      </a>
    </div>

  </div>

</div>

  <div class="row clearfix">

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-md-offset-3 col-lg-offset-3">
      <div class="f_cover">
        <img src="{{ url('/files_public/image').'/'.$document->doc_page_image_preview }}" class="img-responsive">
      </div>
    </div>

  </div>

</div>

</div>

@endsection

@section('scripts')

<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', []);

app.controller('share_public_controller', function($scope, $http) {

   $scope.viewShared = function(){
        var form = $('#viewSharedForm');
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        var redirect_url = window.location.href + "/" + formdata.get('share_password');
        var reload = window.location.href;

        $.ajax({
            url: '/share/public/verify_password',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                 if(data=="failed"){
                   console.log(data);
                   window.location.replace(reload);
                 }else {
                   console.log(data);
                   window.location.replace(redirect_url);
                 }

            }
        }); //end ajax
   }

});

</script>
@endsection
