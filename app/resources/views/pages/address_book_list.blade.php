@extends('layouts.app')

@section('page_title', 'Address book')

@section('custom_style')
<link href="{{ asset('static/css/address_book.css') }}" rel="stylesheet">
<style type="text/css" media="screen">

/* ------------------paperyard custom button ---------------------------*/

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

.notify_w_tx {
  color:#7e7e7e; font-size:22px;
}

.notify_ico {
  color:#b1d5ff; font-size:100px;
}

.notify_pos {
  margin-top:50px;
}

/* --------------------------------  breadcrumb nav -----------------------------------*/
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


/*--------------------------------------------------------------------------------*/

.ad_input {
   margin-top:-25px;
}


.ab_details {
  margin-top:-10px;
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

/* ---------------material floating button -----------------------------------*/
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
      background-color:#b1d5ff !important;
}
/* ---------------material floating button -----------------------------------*/

/*.card:hover {
-webkit-box-shadow: 0px 1px 3px 0px rgba(0, 154, 255, 0.75);
-moz-box-shadow:    0px 1px 3px 0px rgba(0, 154, 255, 0.75);
box-shadow:         0px 1px 3px 0px rgba(0, 154, 255, 0.75);
}
*/
</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Address book</a></li>
  </ul>
@endsection

@section('content')
<div class="row clearfix" ng-controller="address_book_controller">
    @if($address_books>0)
    @if (session()->has('address_book_created'))
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert bg-light-blue alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>{!! session('address_book_created') !!}</p>
        </div>
    </div>
    @endif
    @if (session()->has('chil_address_created'))
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert bg-light-blue alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>{!! session('chil_address_created') !!}</p>
        </div>
    </div>
    @endif
    <!-- search filter -->
    <div class="col-md-3">Search Address book
        <input type="text" ng-model="search" ng-change="filter()" placeholder="Enter keyword" class="form-control" />
    </div>
    <!-- show total rows -->
    <div class="col-md-2" >Number of rows
        <select ng-model="entryLimit" class="form-control">
            <option>5</option>
            <option>10</option>
            <option>20</option>
            <option>50</option>
            <option>100</option>
        </select>
    </div>
    <div class="col-md-12"><br></div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card-hvr" ng-repeat="data in filtered = (addressBookList | filter:search | orderBy : sort )  | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index">
        <div class="card">
            <div class="header" style="height:61px; z-index:5">
                <input type="checkbox" id="ab<#data.ab_id#>" class="filled-in chk-col-blue" ng-click="possibleRecipient(data.select,data.ab_id)" ng-model="data.select"/>
                <label for="ab<#data.ab_id#>">
                    <span style="font-size:15px">Possible recipient</span>
                </label>
                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="/address_book/create_child/<# data.ab_id #>">Create child address </a></li>
                            <li><a href="/address_book/edit/<# data.ab_id #>">Edit</a></li>
                            <li><a ng-click="deleteAddressBook(data.ab_id)">Delete </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row">
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
                </div> <!-- body -->
                </div> <!-- card -->
                </div> <!-- col -->
                <!-- show if document does not exist -->
                <div class="col-md-12" ng-show="filteredItems == 0">
                    <div class="col-md-12">
                        <h4>No address book found</h4>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="col-md-6 ng-hide" ng-show="filteredItems > 0" style="margin-top:-20px">
                    <div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>
                </div>
                <!-- show total number of found row -->
                <div class="col-md-6 ng-hide" ng-show="filteredItems > 0">
                    <p style="color:#999" class="pull-right">Filtered <# filtered.length #> of <# totalItems #> Address books</p>
                </div>
                <!-- FAB -->
            <nav mfb-menu position="br" resting-icon="fa fa-plus"  active-icon="fa fa-plus" onclick="window.location='{{ url('/address_book/create') }}'"></nav>
            @else
            <div>
                <center>
                <div class="notify_pos">
                    <div><i class="fa fa-address-book notify_ico"></i></div><br>
                    <div>
                        <p class="notify_w_tx">
                            You don't have any address book<br>
                            but you are free to create some
                        </p>
                    </div><br>
                    <div>
                        <button onclick="window.location='{{ url('address_book/create') }}'" class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">Create Address book</span></button>
                    </div>
                </div>
                </center>
            </div>
            @endif
        </div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/address_book.js') }}"></script>
<script type="text/javascript">


//inject this app to rootApp
var app = angular.module('app', ['ui.bootstrap','ngSanitize','ng-mfb']);

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

app.filter('default_nd', function(){
   return function(data){
       if(data==null){
           data = "N/D";
           return data;
       }
       return data;
   }
});

app.controller('address_book_controller', function($scope, $http, $timeout, $q) {

    //get address books
    $scope.addressBooks = function(){
        $http.get('/address_book/list').success(function(data){
            console.log(data);
            $scope.addressBookList = data;
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 5; //max no of items to display in a page
            $scope.filteredItems = $scope.addressBookList.length; //Initially for no filter
            $scope.totalItems = $scope.addressBookList.length;

        });
    }
    $scope.addressBooks();

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

    $scope.possibleRecipient = function(stat,id){
        data = { ab_id:id, ab_status:stat }
        $http({method:'POST',url:'/address_book/possible_recipient', data}).success(function(data){
             if(stat==true){
                $scope.showNotification("Possible recipient selected","bg-blue");
             }else{
                $scope.showNotification("Possible recipient unselected","bg-red");
             }   
        });
    }

    $scope.deleteAddressBook = function(ab_id){
        data = { ab_id:ab_id }
        $http({method:'POST',url:'/address_book/delete', data}).success(function(data){
              $scope.addressBooks();
              $scope.showNotification("Address book deleted","bg-red");
        });
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
