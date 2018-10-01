@extends('layouts.app')

@section('page_title', 'Share')

@section('active_share', 'p_active_nav')

@section('custom_style')
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
	width:270px;
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

</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Share</a></li>
  </ul>
@endsection

@section('content')

<!-- Hover Rows -->
<div class="row" ng-controller="share_controller">

@if(count($check)>=1)
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-hover table-striped">

				<thead>
					<tr>
						<th>#</th>
						<th>DOCUMENT NAME</th>
						<th>SHARED LINK</th>
						<th>PASSWORD</th>
						<th><span class="pull-right">ACTIONS</span></th>
					</tr>
				</thead>

	              	  <tr ng-repeat="doc in shared_docs track by $index">
						<td scope="row"><# $index + 1 #></td>
						<td><# doc.doc_ocr #></td>
						<td>

							<table class="table_gl">
								<tr>
									<td>
										<input type="text" class="form-control inp_share" placeholder="Search" id="gl<#$index#>" value="{{url('share/'.Auth::user()->name)}}<#'/'+doc.share_hash#>" style="height:34px; width:400px" >
									</td>
									<td >
									  <div class="notify">
										<button type="button" class="btn bg-light-blue waves-effects" data-link-class="<#'gl'+$index#>" style="margin-left:-5px" data-text="Share link copied." data-placement-from="bottom" data-placement-align="right" data-animate-enter="" data-animate-exit="" data-color-name="bg-blue">
											<i class="fa fa-clipboard"></i>
										</button>
								      </div>
									</td>
								</tr>
							</table>

						</td>
						<td><# doc.share_password | pass #></td>
						<td>
							<span class="pull-right">
		                        <button type="button" class="btn bg-light-blue waves-effects shareBtn" ng-click="generatePassword(doc.share_id,doc.doc_id)">
									<i class="material-icons">lock</i>
								</button>
		                         <button type="button" class="btn bg-deep-orange waves-effects" ng-click="removeShared(doc.share_id,doc.doc_id)">
									<i class="material-icons">delete_forever</i>
								</button>
						    </span>
	                    </td>
					</tr>

			</table>
		</div>

		<!-- PRELOAD / NOT FOUND STATUS -->
		<center>
			<div class="preloader ng-hide center-block" ng-show="doc_loader" style="margin-top:50px">
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
				<label style="color:red">You have no shared documents.</label>
			</div>
		</center>

	</div>
@else
   <center>
		<div class="w_folder">
			<div><i class="fa fa-share-alt w_folder_icon"></i></div><br>
			<div><p class="w_folder_tx">
				You have no shared documents.
			</p></div><br>
		</div>
	</center>
@endif

</div>


@endsection

@section('scripts')

<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', ['ngSanitize']);

app.filter('pass', function(){
    return function(data)
      {
         if(data==null){
         	 data = "none";
         	 return data;
         }
        return data;
      }
});

app.controller('share_controller', function($scope, $http, $timeout) {

$scope.doc_loader = true;
$scope.not_found = false;


$scope.getSharedDocuments = function(){

    $scope.doc_loader = true;
    $scope.shared_docs = '';

    $http.get('/share/get_shared_documents').success(function(data){
        $scope.shared_docs = data;
        $('.shareBtn').removeAttr("disabled");
        $scope.doc_loader = false;
        if(data==""){
        	$scope.not_found = true;
        }else{
        	$scope.not_found = false;
        }
    });
}
$scope.getSharedDocuments();

$scope.generatePassword = function(share_id,doc_id){

    $('.shareBtn').attr("disabled", "disabled");
    data = {
   	  shared_id:share_id,
   	  doc_id:doc_id
    }
    $http.post('/share/generate_password', data).success(function(data){
       $scope.getSharedDocuments();

    });
}

$scope.removeShared = function(share_id,doc_id){

    $('.shareBtn').attr("disabled", "disabled");

    swal({
        title: "Unshare document?",
        text: "Are you sure you want to unshare this document?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, unshare it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            //ajax send post remove shared
            data = {
			   	  shared_id:share_id,
			   	  doc_id:doc_id
			    }
			$http.post('/share/remove_shared', data).success(function(data){
			       $scope.getSharedDocuments();
			 });


        } else {
            swal("Cancelled", "Nothing has change:)", "error");
        }
    });

}

});
//end controller

//copy clipboard
$(document).on("click", ".notify button", function() {

	//your code here...
	var placementFrom = $(this).data('placement-from');
	var placementAlign = $(this).data('placement-align');
	var animateEnter = $(this).data('animate-enter');
	var animateExit = $(this).data('animate-exit');
	var colorName = $(this).data('color-name');
	var text = $(this).data('text');
	var l_class = $(this).data('link-class');
	/* Get the text field */
	var copyText = document.getElementById(l_class);
	/* Select the text field */
	copyText.select();
	/* Copy the text inside the text field */
	document.execCommand("copy");
	// notify user
	showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

});


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

</script>

@endsection

