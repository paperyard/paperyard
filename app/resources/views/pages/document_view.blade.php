@extends('layouts.app')

@section('page_title', 'Document')

@section('custom_style')
<link href="{{ asset('static/css/document.css') }}" rel="stylesheet">
<link href="{{ asset('static/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
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
	margin-top: 10px;
  border: 3px solid #b1d5ff; border-radius: 4px;  
}

.f_cover div img {
	width:100%;

}

.f_cover_img {
  
}

.form-g-label span{
   color:#017cff;
}
.form-g-label p{
   color:#017cff;
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


/*--------------tags input --------------*/
.bootstrap-tagsinput {
    width:100%;
}
.bootstrap-tagsinput .tag {
   background-color:#017cff !important;
   font-size:13px !important;
   color:#fff !important;
}

.bootstrap-tagsinput span {
   color:#fff !important;
   margin-left:0px;
}

.reminder_span {
   position:absolute !important;
   margin-top:-25px !important;
   color:#017cff;
}

.strikethrough {
  text-decoration: #017cff line-through;
  color: #017cff ;
}

.doc_view_input_icon {
  padding:6px !important;
  padding-left:4px !important;
  padding-right:4px !important;
  padding-top:1px !important;
}

.list-group-autocomplete{
   position:absolute !important;
   z-index:5 !important;
   margin-top:-32px;
}


.ab_details {
  margin-top:10px;
  word-wrap: break-word;
}
.ab_details span {
  border-bottom: 1px solid #aaa;
  width:100%;
  display: block;
}

.ab_details label {
  color:#017cff;
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
<div class="row" ng-controller="doc_view_controller">


  <div class="col-md-4" style="margin-top:-20px">
    <div class="doc_pages f_cover">
      @foreach($document_pages as $dp)
      <div class="f_cover_img"><img src="{{  url('/files/image') .'/'. $dp->doc_page_image_preview }}"></div>
      @endforeach
    </div>
  </div>

  <div class="col-md-8 doc-upd-input" ng-click="reInitAddressList()">

    <br>
    <form  enctype="multipart/form-data" id="doc_upd_form" name="doc_upd_form"  ng-submit="updateDocument(); $event.preventDefault();">
      <div class="row clearfix">
          <!-- Document ID -->
          <input name="doc_id" type="hidden" class="form-control" placeholder="" value="{{$document->doc_id}}">
          <!-- SENDER ==================================== -->
          <div class="col-md-6">
            <div class="input-group form-group-lg form-g-label">
              <div class="form-line frm-input">
                 <input name="doc_sender" type="text" class="form-control" placeholder="" value="" autocomplete="off"
                 ng-model-options='{ debounce: 1000 }' ng-change="onChangeInput('sender',sender_address)" ng-model="sender_address" ng-keydown="searchKeyPress($event)" >
              </div>
               <span class="input-group-addon" data-toggle="modal" data-target="#senderAddressModal" ng-show="sender_datas.length>0 && sender_datas!=null">
                    <button class="btn btn-primary waves-effect doc_view_input_icon" type="button"><i class="fa fa-address-book"></i></button>
               </span>
            </div>
            <!-- Autocomplete -->
            <div class="row cleafix">
              <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                <div class="list-group ">
                  <!-- AUTCOMPLETE SENDER ADDRESS BOOK -->
                  <a ng-click="searchAddressBook(ab.ab_id,ab.ac_keyword,'sender')" class="list-group-item" ng-repeat="ab in sender_list track by $index" ng-show="sender_list.length>0 && sender_list!=null">
                    <# ab.ac_keyword #>
                  </a>
                  <!-- NO RESULT FOUND -->
                  <a  class="list-group-item ng-hide" ng-show="sender_no_result">
                    No result found...
                  </a>
                </div>
              </div>
            </div>
            <!-- Autocomplete -->
            <span class="reminder_span">Sender</span>
          </div>
          <!-- RECEIVER ==================================== -->
          <div class="col-md-6">
            <div class="input-group form-group-lg form-g-label">
              <div class="form-line frm-input">
                 <input name="doc_receiver" type="text" class="form-control" placeholder="" value="" autocomplete="off"
                  ng-model-options='{ debounce: 1000 }' ng-change="onChangeInput('receiver',receiver_address)" ng-model="receiver_address" ng-keydown="searchKeyPress($event)">
              </div>
               <span class="input-group-addon" data-toggle="modal" data-target="#receiverAddressModal" ng-show="receiver_datas.length>0 && receiver_datas!=null">
                    <button class="btn btn-primary waves-effect doc_view_input_icon" type="button"><i class="fa fa-address-book"></i></button>
               </span>
            </div>
             <!-- Autocomplete -->
            <div class="row cleafix">
              <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                <div class="list-group ">
                  <!-- AUTCOMPLETE SENDER ADDRESS BOOK -->
                  <a ng-click="searchAddressBook(ab.ab_id,ab.ac_keyword,'receiver')" class="list-group-item" ng-repeat="ab in receiver_list track by $index" ng-show="receiver_list.length>0 && receiver_list!=null">
                    <# ab.ac_keyword #>
                  </a>
                  <!-- NO RESULT FOUND -->
                  <a  class="list-group-item ng-hide" ng-show="receiver_no_result">
                    No result found...
                  </a>
                </div>
              </div>
            </div>
            <!-- Autocomplete -->
            <span class="reminder_span">Receiver</span>
          </div>
      </div><br>

      <div class="row clearfix">
        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label masked-input">
            <div class="form-line frm-input">
              <input name="doc_date" type="text" class="form-control date" placeholder="Ex: 24.01.2018 (D.M.Y)" value="{{$document->date}}" >
            </div>
            <span>Date</span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label" style="margin-top:15px;">
            <div class="form-line frm-input">
              <input name="doc_tags" type="text" class="form-control"  data-role="tagsinput" placeholder="" value="{{$document->tags}}" >
            </div>
            <p>Tags</p>
          </div>
        </div>
      </div>

      <div class="row clearfix">
        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_category" type="text" class="form-control" placeholder="" value="{{$document->category}}" >
            </div>
            <span>Category</span>
          </div>
        </div>

        <div class="col-md-6">
          <div class="input-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_reminder" type="text" class="form-control datepicker2" placeholder="" value="{{$document->reminder}}">
            </div>
             <span class="input-group-addon" data-toggle="modal" data-target="#largeModal" ng-show="task_list!=null && task_list.length>0">
                  <button class="btn btn-primary waves-effect doc_view_input_icon" type="button"><i class="fa fa-calendar-check-o"></i></button>
             </span>
          </div>
             <span class="reminder_span">Reminder</span>
        </div>
      </div>

      @if($document->tax_relevant=="on")
      <div class="row clearfix">
        <div class="col-md-12">
          <input type="checkbox" name="doc_tax_r" id="doc_tax_r" class="filled-in chk-col-blue" checked>
          <label for="doc_tax_r" >Tax relevant </label>
        </div>
      </div>
      @else
      <div class="row clearfix">
        <div class="col-md-12">
          <input type="checkbox" name="doc_tax_r" id="doc_tax_r" class="filled-in chk-col-blue">
          <label for="doc_tax_r" >Tax relevant </label>
        </div>
      </div>
      @endif

      <div class="row">
        <div class="col-md-12">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_notes" type="text" class="form-control" placeholder="Notes" value="{{$document->note}}" >
            </div>
          </div>
        </div>
      </div>
      <div class="row" style="padding-bottom:50px">
        <div class="col-md-12">
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 pull-right ng-hide doc-upd-btn" ng-show="sub_btn_p" type="submit"><span class="lg-btn-tx">Save & Done</span></button>
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 pull-right ng-hide" ng-show="sub_btn_d"><span class="lg-btn-tx">Please wait</span></button>
        </div>
      </div>
    </form>


  </div>
  <!-- div 8 -->

  <div class="col-md-12 hidden-xs hidden-sm">
    <div style="position:absolute; bottom:0; right:0">
      <i>TAB / Enter: a field wieter / save | Arrow keys left / right: Scroll</i>
    </div>
  </div>

</div>


<!-- Search Guide Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" style="margin-top:40px" >
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Reminder task list</h4>
            </div>
            <div class="modal-body" style="text-overflow: auto">
                    <ul class="list-unstyled" >
                    <li class="" ng-repeat="task in task_list track by $index" style=" word-wrap: break-word; margin-top:10px">
                         <div>
                                <input type="checkbox" id="arch<#task.task_id#>" class="filled-in chk-col-blue"  ng-model="task.select" ng-click="taskComplete(task.task_id,task.select)"/>
                                <label for="arch<#task.task_id#>">
                                      <span style="font-size:15px"  ng-class="{true: 'strikethrough'}[task.select == true]" ><# task.task_name #> </span>
                                </label>
                               </div>
                          </li>
                    </ul>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>


<!-- Search Guide Modal -->
<div class="modal fade" id="senderAddressModal" tabindex="-1" role="dialog" style="margin-top:40px" >
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" ng-repeat="data in sender_datas">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Sender address book</h4>
            </div>
            <div class="modal-body">
                <div class="row" >
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Shortname         : &nbsp</label><span><# data.ab_shortname      | default_nd #></span></div>
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Salutation        : &nbsp</label><span><# data.ab_salutation     | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>First name        : &nbsp</label><span><# data.ab_firstname      | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Last name         : &nbsp</label><span><# data.ab_lastname       | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Company           : &nbsp</label><span><# data.ab_company        | default_nd #></span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 ab_details"><label>Address line 1    : &nbsp</label><span><# data.ab_address_line1  | default_nd #></span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 ab_details"><label>Address line 2    : &nbsp</label><span><# data.ab_address_line2  | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>ZIPCODE           : &nbsp</label><span><# data.ab_zipcode        | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Town              : &nbsp</label><span><# data.ab_town           | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Country           : &nbsp</label><span><# data.ab_country        | default_nd #></span></div>
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Telephone         : &nbsp</label><span><# data.ab_telephone      | default_nd #></span></div>
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Email             : &nbsp</label><span><# data.ab_email          | default_nd #></span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 ab_details"><label>Notes             : &nbsp</label><span><# data.ab_notes          | default_nd #></span></div>
                </div> 
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-danger waves-effect"  ng-click="removeAddressBook(data.ab_id,'sender','senderAddressModal')">REMOVE ADDRESS BOOK</button>
                 <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- Search Guide Modal -->
<div class="modal fade" id="receiverAddressModal" tabindex="-1" role="dialog" style="margin-top:40px" >
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content" ng-repeat="data in receiver_datas">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Receiver address book</h4>
            </div>
            <div class="modal-body">
                <div class="row" >
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Shortname         : &nbsp</label><span><# data.ab_shortname      | default_nd #></span></div>
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Salutation        : &nbsp</label><span><# data.ab_salutation     | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>First name        : &nbsp</label><span><# data.ab_firstname      | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Last name         : &nbsp</label><span><# data.ab_lastname       | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Company           : &nbsp</label><span><# data.ab_company        | default_nd #></span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 ab_details"><label>Address line 1    : &nbsp</label><span><# data.ab_address_line1  | default_nd #></span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 ab_details"><label>Address line 2    : &nbsp</label><span><# data.ab_address_line2  | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>ZIPCODE           : &nbsp</label><span><# data.ab_zipcode        | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Town              : &nbsp</label><span><# data.ab_town           | default_nd #></span></div>
                    <div class="col-md-4 col-sm-12 col-xs-12 ab_details"> <label>Country           : &nbsp</label><span><# data.ab_country        | default_nd #></span></div>
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Telephone         : &nbsp</label><span><# data.ab_telephone      | default_nd #></span></div>
                    <div class="col-md-6 col-sm-12 col-xs-12 ab_details"> <label>Email             : &nbsp</label><span><# data.ab_email          | default_nd #></span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 ab_details"><label>Notes             : &nbsp</label><span><# data.ab_notes          | default_nd #></span></div>
                </div> 
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-danger waves-effect"  ng-click="removeAddressBook(data.ab_id,'receiver','receiverAddressModal')">REMOVE ADDRESS BOOK</button>
                 <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="{{ asset('static/js/document.js') }}"></script>
<script src="{{ asset('static/js/bootstrap-material-datetimepicker.js') }}"></script>
<script type="text/javascript">


$(function () {
       //Masked Input =============================================================
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

      //Datetimepicker plugin
      $('.datepicker2').bootstrapMaterialDatePicker({
          format: 'DD.MM.YYYY',
          clearButton: true,
          weekStart: 1,
          time:false
      });

});


//inject this app to rootApp
var app = angular.module('app', []);

app.filter('default_nd', function(){
   return function(data){
       if(data==null){
           data = "N/D";
           return data;
       }
       return data;
   }
});


app.controller('doc_view_controller', function($scope, $http, $timeout, $rootScope, $q) {


  $scope.sub_btn_p = true;
  $scope.sub_btn_d = false;
  $scope.upd_state = "";
  $scope.reminderCheck = '{{$document->reminder}}';
  $rootScope.task_list = [];

  // search address book ----------------------
  $scope.canceler = $q.defer();
  $scope.search_canceler = $q.defer();
  //-------------------------------------------
  $scope.sender_address      = "{{$document->sender}}";
  $scope.sender_list         = [];
  $scope.sender_no_result    = false;
  $rootScope.sender_datas    = [];
  $scope.sender_address_id   = "{{$document->sender_address_id}}";
  //-------------------------------------------
  $scope.receiver_address    = "{{$document->receiver}}";
  $scope.receiver_list       = [];
  $scope.receiver_no_result  = false;
  $rootScope.receiver_datas  = [];
  $scope.receiver_address_id ="{{$document->receiver_address_id}}";
  //------------------------------------------- 

  $scope.getAddressBookDatas = function(address_id,type){
      // if address is not null get address book
      if(address_id!=null && address_id!="" && address_id!=undefined){
          data = { address_id:address_id}
          $http.post('/address_book/search_address', data).success(function(data){
               if(type=="sender"){
                  $rootScope.sender_datas   = data;
                  console.log(data);
               }else{
                  $rootScope.receiver_datas = data;
                  console.log(data);
               }
          });
      }
  }

  $scope.getAddressBookDatas($scope.sender_address_id,"sender");
  $scope.getAddressBookDatas($scope.receiver_address_id,"receiver");

  console.log($scope.sender_address_id);
  
  $rootScope.removeAddressBook = function(address_id,type,modalID){
     $('#'+modalID).modal('hide');
     console.log(address_id,type);
     if(type=="sender"){
        $rootScope.sender_datas     = [];
        $scope.sender_address_id    = null;
        $scope.sender_address       = "";
        console.log($scope.sender_address_id);
     }
     else{
        $rootScope.receiver_datas   = [];
        $scope.receiver_address_id  = null;
        $scope.receiver_address     = "";
     }
  }

  $scope.getTaskList = function(){
    if($scope.reminderCheck!=""){
        data = {
           rm_id: '{{$document->doc_id}}'
        }
        $http.post('/reminders/doc_view', data).success(function(data){
              console.log(data);
              $rootScope.task_list = data.task_list;
        });
     }   
  }

  $scope.getTaskList();

  $rootScope.taskComplete = function(task_id,status){
    data = {
       task_id     : task_id,
       task_status : status,
    }
    $http({method:'POST',url:'/reminders/task_complete', data}).success(function(data){
        console.log(data);
    });
  }

  $scope.updateDocument = function() {

        if($scope.upd_state!="clicked"){
				$scope.sub_btn_p = false;
				$scope.sub_btn_d = true;
				$('.doc-upd-btn').attr("disabled", "disabled");

		        var form = $('#doc_upd_form');
		        var formdata = false;

		        if (window.FormData) {
		            formdata = new FormData(form[0]);
		        }

            if($scope.sender_address_id != null && $scope.sender_address_id != "" && $scope.sender_address_id != undefined){
              formdata.append('sender_address_id',  $scope.sender_address_id);
            }else{
              formdata.append('sender_address_id',   "");
            }

            if($scope.receiver_address_id != null && $scope.receiver_address_id != "" && $scope.receiver_address_id != undefined ){
              formdata.append('receiver_address_id', $scope.receiver_address_id);
            }else{
              formdata.append('receiver_address_id', "");
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
                    else if(parseInt(data)>=0){
                          swal("Success", "Document datas updated", "success");
                          $timeout(function() { 
                              window.location.replace('/document/'+data);
                          }, 1500);
                    }else{
                        window.location.replace('/dashboard');
                    }

                    console.log(data);
		            }
		        }); //end ajax
         }
         $scope.upd_state="clicked";
    }; // end save product function

  // address book search =========================================================================================================

  // return auto complete.
  $scope.onChangeInput = function(type,keyword){

      //cancel previous autocomplete post request.
      $scope.canceler.resolve();
      //reinit $q.defer make new autocomplete post request
      $scope.canceler = $q.defer();

      if(keyword!=null && keyword !="" && keyword != undefined){
          data = {
            type    : type,
            keyword : keyword
          }
          $http({method:'POST',url:'/address_book/auto_complete', data,  timeout:$scope.canceler.promise}).success(function(data){
              console.log(data);
              $scope.reInitAddressList();
              if(type=="sender"){
                 if(data!="not_found"){
                    $scope.sender_list  = data;
                 }else{
                    $scope.sender_no_result  = true;
                 }
              }else{
                 if(data!="not_found"){
                    $scope.receiver_list = data;
                 }else{
                    $scope.receiver_no_result = true;
                 }
              }
          });
      }    
  }

  //@param addressbook id(ab_id),autocomplete_keyword, type(sender,receiver)
  $scope.searchAddressBook = function(ab_id,keyword,type){

      $scope.reInitAddressList();
      if(type=="sender"){
         $scope.sender_address   = keyword;
      }else{
         $scope.receiver_address = keyword;
      }

      data = { address_id:ab_id }

      $http({method:'POST',url:'/address_book/search_address', data}).success(function(data){
          if(type=="sender"){
             $rootScope.sender_datas   = data;
             $scope.sender_address_id   = data[0]['ab_id']; 
          }else{
             $rootScope.receiver_datas = data;
             $scope.receiver_address_id = data[0]['ab_id']; 
          }
      });

  }

  $scope.reInitAddressList = function(){

       $scope.receiver_no_result = false;
       $scope.sender_no_result   = false;
       $scope.receiver_list = [];
       $scope.sender_list   = [];       
  }

  // on keypress check key
  $scope.searchKeyPress = function(keyEvent) {
    // key 8 = backspace. clear autocomplete
    if (keyEvent.which === 8){
        $scope.reInitAddressList();
    }
  };



});
</script>

@endsection
