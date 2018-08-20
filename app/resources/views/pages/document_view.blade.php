@extends('layouts.app')

@section('page_title', 'Document')


@section('custom_style')
<link href="{{ asset('static/css/document.css') }}" rel="stylesheet">
<style type="text/css" media="screen">
.lg-btn-tx {
	font-size:18px;
	color:#017cff;
	font-weight:bold
}
.lg-btn_x2 {
	width:220px;
	height:35px;
	border:none;
	border-radius:5px
}

.btn_color{
	background-color:#b1d5ff;
}

.notify_w_tx {
	color:#7e7e7e; font-size:22px;
}

.notify_ico {
	color:#b1d5ff; font-size:100px;
}

.notify_pos {
	margin-top:50px;
}

.f_cover {
	border: 3px solid #b1d5ff; border-radius: 4px; margin-top: 10px;
}

.f_cover div img {
	width:100%;
}

.form-g-label span{
   color:#017cff;
   margin-left:5px;
}
.form-g-label p{
   color:#017cff;
   margin-left:5px;
}
.frm-input {
	margin-bottom:8px;
}

.doc-upd-input {
	margin-top:-30px;
}

@media screen and (max-width:500px) {
    .doc-upd-input { margin-top: 0px !important; }
}

</style>
@endsection

@section('doc_pages')
  <p class="pull-right" style="font-size:25px">
  	  <span id="min-page"></span>
  	  <span> von </span>
  	  <span id="max-page"></span>
  </p>
@endsection

@section('content')
<div class="row" ng-app="document" ng-controller="doc_controller">

  <div class="col-md-4" style="margin-top:-20px">
    <div class="doc_pages f_cover">
      @foreach($document_pages as $dp)
      <div><img src="{{ asset('static/documents_images/') .'/'. $dp->doc_page_image_preview }}"></div>
      @endforeach
    </div>
  </div>

  <div class="col-md-8 doc-upd-input">
    <br>
    @foreach($document as $doc)
    <form  enctype="multipart/form-data" id="doc_upd_form" name="doc_upd_form"  ng-submit="updateDocument(); $event.preventDefault();">
      <div class="row clearfix">

        <!-- Document ID -->
        <input name="doc_id" type="hidden" class="form-control" placeholder="" value="{{$doc->doc_id}}">

        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_sender" type="text" class="form-control" placeholder="" value="{{$doc->sender}}" required>
            </div>
            <span>Sender</span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_receiver" type="text" class="form-control" placeholder="" value="{{$doc->receiver}}" required>
            </div>
            <span>Receiver</span>
          </div>
        </div>
      </div>

      <div class="row clearfix">
        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label masked-input">
            <div class="form-line frm-input">
              <input name="doc_date" type="text" class="form-control date" placeholder="Ex: 24.01.2018" value="{{$doc->date}}" required>
            </div>
            <span>Date</span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label" style="margin-top:15px">
            <div class="form-line frm-input">
              <input name="doc_tags" type="text" class="form-control"  data-role="tagsinput" placeholder="" value="{{$doc->tags}}">
            </div>
            <p>Tags</p>
          </div>
        </div>
      </div>

      <div class="row clearfix">
        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_category" type="text" class="form-control" placeholder="" value="{{$doc->category}}" required>
            </div>
            <span>Category</span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_memory" type="text" class="form-control" placeholder="" value="{{$doc->memory}}" required>
            </div>
            <span>Memory</span>
          </div>
        </div>
      </div>

      @if($doc->tax_relevant=="on")
      <div class="row clearfix">
        <div class="col-md-12">
          <input type="checkbox" name="doc_tax_r" id="doc_tax_r" class="filled-in chk-col-light-blue" checked>
          <label for="doc_tax_r" >Tax relevant </label>
        </div>
      </div>
      @else
      <div class="row clearfix">
        <div class="col-md-12">
          <input type="checkbox" name="doc_tax_r" id="doc_tax_r" class="filled-in chk-col-light-blue">
          <label for="doc_tax_r" >Tax relevant </label>
        </div>
      </div>
      @endif

      <div class="row">
        <div class="col-md-12">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_notes" type="text" class="form-control" placeholder="Notes" value="{{$doc->note}}" required>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 pull-right ng-hide doc-upd-btn" ng-show="sub_btn_p" type="submit"><span class="lg-btn-tx">Save & Done</span></button>
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 pull-right ng-hide" ng-show="sub_btn_d"><span class="lg-btn-tx">Please wait</span></button>
        </div>
      </div>
    </form>
    @endforeach

  </div>
  <!-- div 8 -->

  <div class="col-md-12 hidden-xs hidden-sm">
    <div style="position:absolute; bottom:0; right:0">
      <i>TAB / Enter: a field wieter / save | Arrow keys left / right: Scroll</i>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/document.js') }}"></script>
<script type="text/javascript">

$(function () {
       //Masked Input ============================================================================================================================
	    var $masInput = $('.masked-input');
	    // set document pages.
      var min_page = 1;
	    var max_page = '{{ count($document_pages) }}';
      $('#min-page').html(min_page);
      $('#max-page').html(max_page);

	    //Date mask input
	    $masInput.find('.date').inputmask('dd.mm.yyyy', { placeholder: '__/__/____' });

        // change current page value
		  $('.doc_pages').on('afterChange', function(event, slick, currentSlide){
            $('#min-page').html(currentSlide+1);
		  });
        // initialize slick carousel on doc pages
	    $('.doc_pages').slick({
            infinite:false,
            initialSlide:0
	    });

        // check if key is press.
	    document.onkeydown = checkKey;
		function checkKey(e) {
		    e = e || window.event;
		    if (e.keyCode == '37') {
		       // left arrow
		       $('.doc_pages').slick('slickPrev');
		    }
		    if (e.keyCode == '39') {
		       // right arrow
		       $('.doc_pages').slick('slickNext');
		    }

}
});

//used angular interpolate for syntax compatibility
var app = angular.module('document', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<#');
    $interpolateProvider.endSymbol('#>');
});

app.controller('doc_controller', function($scope, $http, $timeout) {

  $scope.sub_btn_p = true;
  $scope.sub_btn_d = false;
  $scope.upd_state="";

  $scope.updateDocument = function() {

        if($scope.upd_state!="clicked"){
        	    console.log('click');
				$scope.sub_btn_p = false;
				$scope.sub_btn_d = true;
				$('.doc-upd-btn').attr("disabled", "disabled");

		        var form = $('#doc_upd_form');
		        var formdata = false;
		        if (window.FormData) {
		            formdata = new FormData(form[0]);
		        }

		        $.ajax({
		            url: '/document/update',
		            data: formdata ? formdata : form.serialize(),
		            cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST',
		            success: function(data) {
		                if(data=="nothing_to_edit"){
                        swal("Success", "Document datas updated", "success");
                        window.location.replace('/dashboard');
                    }
                    if(parseInt(data)>=0){
                        swal("Success", "Document datas updated", "success");
                        window.location.replace('/document/'+data);
                    }else{
                        window.location.replace('/dashboard');
                    }

		            }
		        }); //end ajax
         }
         $scope.upd_state="clicked";
    }; // end save product function


});
</script>

@endsection
