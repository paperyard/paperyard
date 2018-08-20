@extends('layouts.app')

@section('active_search', 'p_active_nav')

@section('custom_style')
<style type="text/css" media="screen">

.tab_view_icon {
	color:#7e7e7e;
	font-size:30px;
}

.view1_div {
	padding:0px; margin:0px; max-height:600px
}

.view1_div img {
	border: 3px solid #b1d5ff; border-radius: 4px;;
}

.view2_div {
	border: 2px solid #017cff; border-radius: 4px; margin-top: 10px; cursor: pointer;
}

.view2_div img {
	width:100%;
}

.f_cover {
	border: 2px solid #017cff; border-radius: 7px; margin-top: 10px;
}

.f_cover img {
	width:100%;
	border-radius: 7px;
}

.view3_container {
	padding:10px;
}

.view3_div img {
	width:130px;
	border: 2px solid #017cff; border-radius: 2px;
}
.view3_card_d {
	top:0; position:absolute;
}
.view3_card_d li {
	margin:10px;
}
.view_date {
	top:0; right:0; position:absolute; margin:10px
}
.view_tags {
	bottom:0; position:absolute; margin:10px
}

.doc_hvr {
   cursor:pointer;
}

.th-t {
  background-color: #4ddb9f;
}
.th-f {
  background-color: #ef5c8f;
}
.th-ft {
  background-color: #fade45;
}

.typeHeadCstm {
	color:#000 !important;
	font-size:15px;
}

.list-group-autocomplete{
   position:absolute !important;
   z-index:100 !important;
}
.hideBarChart {
	visibility: hidden;
}

.activeView {
	color:#017cff;
}

#radio_1+label:before {
  background-color: #fff !important;
  border:2px solid #fade45 !important;
  border-radius:90% !important;
}


#radio_2+label:before {
  background-color: #fff !important;
  border:2px solid #4ddb9f !important;
  border-radius:90% !important;
}


#radio_3+label:before {
  background-color: #fff !important;
  border:2px solid #ef5c8f !important;
  border-radius:90% !important;
}

/* ===================== breadcrumb nav ======================*/
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
     <li class="li2"><a href="#" >Search</a></li>
  </ul>
@endsection


@section('content')
<div class="row" ng-app="search_doc" ng-controller="searc_doc_controller" ng-cloack ng-click="clearTypeHead()">


	<div class="col-md-12">

	   <h3 style="margin:0px; padding:0px" class="pull-left">Search</h3>

	   <ul class="list-inline pull-right">
			<li>
                <input ng-model="filter" ng-value="'no_filter'" name="no_filter" type="radio" id="radio_0" class="radio-col-grey"/>
				<label for="radio_0">= No filter</label>

				<input ng-model="filter" ng-value="'full_text'" name="filter_g"  type="radio" id="radio_1" class="radio-col-yellow "/>
				<label for="radio_1">= @lang('search_doc.full_text_tx')</label>

				<input ng-model="filter" ng-value="'tag'" name="filter_g" type="radio" id="radio_2"  class="radio-col-green" />
				<label for="radio_2">= @lang('search_doc.tag_tx')</label>

				<input ng-model="filter" ng-value="'folder'" name="filter_g" type="radio" id="radio_3"  class="radio-col-red"/>
				<label for="radio_3">= @lang('search_doc.f_folder_tx')</label>
			</li>
		</ul>

	</div>

	<div class="col-md-12">
		<input type="text" class="form-control form-control-lg" id="usr" ng-model="keyword" ng-model-options='{ debounce: 1000 }' ng-change="autoComplete()" ng-keypress="myFunct($event)">
        <div class="row cleafix">
	        <div class="col-md-3 ">
		        <div class="list-group list-group-autocomplete">
		            <a  ng-click="selectTH(tag,'tag')" class="list-group-item th-t typeHeadCstm" ng-repeat="tag in find_tags track by $index" ng-show="find_tags!=null && find_tags.length>0">
			            <# tag #>
			        </a>
			        <a  ng-click="selectTH(f.folder_name,'folder')" class="list-group-item th-f typeHeadCstm" ng-repeat="f in find_folders track by $index" ng-show="find_folders!=null && find_folders.length>0">
                        <# f.folder_name #>
			        </a>
			        <a  ng-click="selectTH(text,'full_text')" class="list-group-item th-ft typeHeadCstm" ng-repeat="text in find_fText track by $index" ng-show="find_fText!=null && find_fText.length>0">
			            <# text #>
			        </a>
			    </div>
		    </div>
		</div>
		<br>
	</div>
    	  <!-- preloader -->
    <div class="col-md-12 ">

      <center>
        <div class="preloader ng-hide center-block" ng-show="doc_loader" style="margin-top:100px">
            <div class="spinner-layer pl-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>

        <div class="center-block ng-hide" ng-show="not_found" style="margin-top:100px">
        	 <h3 style="color:red">No document found.</h3>
        </div>
      </center>

    </div>

   <div class="col-md-12" style="max-height:140px !important">
   	    <canvas  id="myChart" style="width:100% !important; height:130px; padding:0px !important; margin-left:-10px !important;"></canvas>
   </div>

<div class="clearfix ng-hide" ng-show="doc_view">

	<!-- VIEW NAVIGATIONS -->
	<div class="col-md-12">
		<ul class="list-inline ng-hide" ng-show="doc_view">
			<li ng-click="selected='lg_view'">
				<a href="#home_only_icon_title"     data-toggle="tab"><i class="fa  fa-th-large tab_view_icon" ng-class="{activeView: selected=='lg_view'}"></i></a>
			</li>
			<li ng-click="selected='md_view'">
				<a href="#profile_only_icon_title"  data-toggle="tab"><i class="fa  fa-th tab_view_icon" ng-class="{activeView: selected=='md_view'}"></i></a>
			</li>
			<li ng-click="selected='grid_view'">
				<a href="#messages_only_icon_title" data-toggle="tab"><i class="fa  fa-th-list tab_view_icon" ng-class="{activeView: selected=='grid_view'}"></i></a>
			</li>
			<li ng-click="selected='table_view'">
				<a href="#settings_only_icon_title" data-toggle="tab"><i class="fa  fa-align-justify tab_view_icon" ng-class="{activeView: selected=='table_view'}"></i></a>
			</li>
		</ul>
	</div>

	<div class="col-md-12">
		<div class="tab-content">
			<!-- VIEW - FULL DOC VIEW  -->
			<div role="tabpanel" class="tab-pane fade in active" id="home_only_icon_title" >
				<div class="row " >
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 doc_hvr" ng-repeat="doc1 in list1 track by $index">
						<a ng-href="/document/<#doc1.doc_id#>">
							<div class="f_cover">
								<img ng-src="/static/documents_images/<# doc1.doc_page_image_preview #>">
							</div>
						</a>
					</div>
				</div>
			</div>
			<!-- GRID VIEW  -->
			<div role="tabpanel" class="tab-pane fade" id="profile_only_icon_title">
				<div class="row">

					<div class="col-md-2 col-sm-6 col-xs-6 doc_hvr" ng-repeat="doc2 in list2 track by $index">
					    <a ng-href="/document/<#doc2.doc_id#>">
							<div class="view2_div">
								<img ng-src="/static/documents_images/<# doc2.doc_page_thumbnail_preview #>">
							</div>
						</a>
					</div>

				</div>
			</div>
			<!-- VIEW - TILE LIST VIEW -->
			<div role="tabpanel" class="tab-pane fade" id="messages_only_icon_title">
				<div class="row">

					<div class="col-md-6 doc_hvr" ng-repeat="doc3 in list3 track by $index">
					  <a ng-href="/document/<#doc3.doc_id#>">
						<div class="card view3_container">
							<table clas="table-responsive">
								<tr>
									<td>
										<div class="view3_div">
											<img ng-src="/static/documents_images/<# doc3.doc_page_thumbnail_preview #>">
										</div>
									</td>
									<td>
										<div class="view_date">05.07.17</div>
										<ul class="list-unstyled view3_card_d">
											<li><b>e-plus DE </b></li>
											<li>gefunden in rechnung@jannikkramer.de</li>
											<li>Rechnung </li>
											<li>Jannik Kramer </li>
										</ul>
										<div class="view_tags">
											<span class="badge bg-green">Steuern 2017</span>
											<span class="badge bg-pink">Rechnungen</span>
											<span class="badge bg-green">e-plus</span>
										</div>

									</td>
								</tr>
							</table>
						</div>
					   </a>
					</div>

				</div>
			</div>
			<!-- VIEW - TABLE VIEW -->
			<div role="tabpanel" class="tab-pane fade" id="settings_only_icon_title">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="body table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Kontakt</th>
										<th>Empfänger</th>
										<th>Kategorie</th>
										<th>Tags</th>
										<th>Größe</th>
										<th>empfangen am</th>
										<th><span class="pull-right">Actions</span></th>
									</tr>
								</thead>
								<tbody>
										 <tr ng-repeat="doc4 in list4 track by $index" >
											<td><# doc4.sender #></td>
											<td><# doc4.receiver #></td>
											<td><# doc4.category #></td>
											<td><# doc4.tags #></td>
											<td><# doc4.size #></td>
											<td><# doc4.date | default #></td>
											<td>
												<ul class="list-unstyled pull-right">
							                        <li class="dropdown">
							                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							                                <i class="fa fa-edit fa-2x" style="color:#7e7e7e"></i>
							                            </a>
							                            <ul class="dropdown-menu pull-right">
							                                <li ><a ng-href="/document/<#doc4.doc_id#>">View</a></li>
							                                <li ng-click="deleteFolder(data.doc_id)"><a href="javascript:void(0);">Delete</a></li>
							                            </ul>
							                        </li>
							                    </ul>
											</td>
										</tr>
								      </a>
								</tbody>
							</table>
						</div>
					</div>
					<!-- end able -->
				</div>
			</div>
		</div>
	</div> <!-- // end row -->

</div>
<!-- CLEARFIX  -->


@endsection

@section('scripts')
<script src="{{ asset('static/js/search_documents.js') }}"></script>

<!-- ======================ANGUALR=========================  -->
<script type="text/javascript" differ>
	//used angular interpolate for syntax compatibility
var app = angular.module('search_doc', ['ui.bootstrap'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<#');
    $interpolateProvider.endSymbol('#>');
});

app.filter('default', function(){
    return function(data)
      {
        if(data!=undefined || data!=null || data!=""){
            return data;
        }
        else
        {
            data = "---";
            return data;
        }
      }
});

app.controller('searc_doc_controller', function($scope, $http, $timeout) {

//active tab.
$scope.selected='lg_view';
// $scope.filter = 'folder';
$scope.filter  = 'no_filter';
$scope.keyword = '';
$scope.list1   = '';
$scope.list2   = '';
$scope.list3   = '';
$scope.list4   = '';

$scope.doc_view =   false;
$scope.doc_loader = false;
$scope.not_found =  false;


$scope.selectTH = function(keyW,filTer){
// empty typehead
$scope.clearTypeHead();
$scope.keyword = keyW;
$scope.filter = filTer;
$scope.doc_loader = true;
$scope.not_found = false;
$scope.searchDocumentWithFilter();

}

$scope.clearTypeHead = function(){
	$scope.find_tags = '';
	$scope.find_fText = '';
	$scope.find_folders = '';
}
// on keypress=enter search doc
$scope.myFunct = function(keyEvent) {
  if (keyEvent.which === 13){
	$scope.doc_loader = true;
	$scope.not_found = false;
	$scope.searchDocumentWithFilter();
  }
};

// search document with filter
$scope.searchDocumentWithFilter = function(){

    $('#myChart').addClass('hideBarChart');
    $scope.clearTypeHead();
    $scope.doc_view = false;
    $scope.list1 = '';
	$scope.list2 = '';
	$scope.list3 = '';
	$scope.list4 = '';
	$scope.bar_chart = false;

    data = {
   	  filter:$scope.filter,
   	  keyword:$scope.keyword
    }
    $http.post('/search/documentsWithFilter', data).success(function(data){

          $('#myChart').removeClass('hideBarChart');

          $scope.list1 = data;
          $scope.list2 = data;
          $scope.list3 = data;
          $scope.list4 = data;

          $scope.bar_chart = true;
          $scope.doc_view = true;
          $scope.doc_loader = false;

          if(data.length>=1){
          	 $('#myChart').removeClass('hideBarChart');
          	 $scope.not_found = false;
          	 $scope.doc_view = true;
             barChart();
          }else{
          	 $('#myChart').addClass('hideBarChart');
          	 $scope.not_found = true;
          	 $scope.doc_view = false;
          }
          $scope.clearTypeHead();

   });
}


$scope.autoComplete = function(){

    // empty typehead
    $scope.clearTypeHead();

    if($scope.filter=='no_filter'){
    	console.log('finding all---keyword:'+$scope.keyword+'---filter'+$scope.filter);
		$scope.autoCompleteFolders();
		$scope.autoCompleteTags();
		$scope.autoCompleteFullText();
	}
	if($scope.filter=='full_text'){
	    console.log('finding full text---keyword:'+$scope.keyword+'---filter'+$scope.filter);
       $scope.autoCompleteFullText();
	}
	if($scope.filter=='tag'){
	   console.log('finding tag---keyword:'+$scope.keyword+'---filter'+$scope.filter);
       $scope.autoCompleteTags();
	}
	if($scope.filter=='folder'){
		 console.log('finding folder---keyword:'+$scope.keyword+'---filter'+$scope.filter);
		$scope.autoCompleteFolders();
	}

}


// typehead folder
$scope.autoCompleteFolders = function(){
   // method to be executed;
   if($scope.keyword!=''){
       data = {
	   	  keyword:$scope.keyword,
	   	  acf:'folder'
	   }
	   $http.post('/search/typhead', data).success(function(data){
	          $scope.find_folders = data.folders;
	          console.log('succes');
	          console.log(data.folders);
	   });
   }
}

// typehead folder
$scope.autoCompleteTags = function(){
   // method to be executed;
   if($scope.keyword!=''){
       data = {
	   	  keyword:$scope.keyword,
	   	  acf:'tag'
	   }
	   $http.post('/search/typhead', data).success(function(data){
	          $scope.find_tags = data.tags;
	          console.log('succes');
	          console.log(data.tags);
	   });
   }
}

// typehead folder
$scope.autoCompleteFullText = function(){
   // method to be executed;
   if($scope.keyword!=''){
       data = {
	   	  keyword:$scope.keyword,
	   	  acf:'full_text'
	   }
	   $http.post('/search/typhead', data).success(function(data){

                if(typeof data.text === 'object'){

                     $scope.find_fText = Object.keys(data.text).map(function(key){
                          return data.text[key];
                     });

                }else{
                	$scope.find_fText = data.text;
                }
                console.log('success');
                console.log(data.text);

	   });
   }
}


//-- ================================================== BARCHART ================================================== -->

// CUSTOM BARCHART
randomScalingFactor = function() {
  return Math.round(Math.random() * 100);
}

function getData() {
  var dataSize = 27;
  var evenBackgroundColor = 'rgba(0, 119, 255, 1)';
  var oddBackgroundColor = 'rgba(177,213,255, 1)';
  var months = ["January","Febuary","March","April","May","June","July","August","September","Octobor","November","December","January","Febuary","March","April","May","June","July","August","September","Octobor","November","December","January","Febuary","March","April","May","June","July","August","September","Octobor","November","December"];
  var labels = [];

  var scoreData = {
    label: 'Documents:',
    data: [],
    backgroundColor: [],
    borderColor: [],
    borderWidth: 1,
    hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
    hoverBorderColor: 'rgba(200, 200, 200, 1)',
  };

  for (var i = 0; i < dataSize; i++) {
    scoreData.data.push(window.randomScalingFactor());
    labels.push(months[i]);

    if (i % 2 === 0) {
      scoreData.backgroundColor.push(evenBackgroundColor);
    } else {
      scoreData.backgroundColor.push(oddBackgroundColor);
    }
  }

  return {
    labels: labels,
    datasets: [scoreData],
  };
};


barChart = function(){

  var chartData = getData();
  console.dir(chartData);

  if (this.myBar) {
	this.myBar.destroy();
  }

  myBar = new Chart(document.getElementById("myChart").getContext("2d"), {
    type: 'bar',
    data: chartData,
    options: {
      maintainAspectRatio: false,
      title:{
        display: false
      },
      legend: {
        display: false
      },

      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            autoSkip: false,
            display:false
          },
           gridLines: {
          display: false,
          color: "white",
            zeroLineColor: "white"
        },
        }],
        xAxes: [{
          ticks: {
            beginAtZero: true,
            autoSkip: false,
            display:false
          },
          gridLines: {
          display: false,
          color: "white",
            zeroLineColor: "white"
        },
        categoryPercentage: 1,

        }]
      }
    }
  });
};



// modefiy bars add border radius
Chart.elements.Rectangle.prototype.draw = function() {
    var ctx = this._chart.ctx;
    var vm = this._view;
    var left, right, top, bottom, signX, signY, borderSkipped, radius;
    var borderWidth = vm.borderWidth;
    // Set Radius Here
    // If radius is large enough to cause drawing errors a max radius is imposed
    var cornerRadius = 5;

    if (!vm.horizontal) {
        // bar
        left = vm.x - vm.width / 2;
        right = vm.x + vm.width / 2;
        top = vm.y;
        bottom = vm.base;
        signX = 1;
        signY = bottom > top? 1: -1;
        borderSkipped = vm.borderSkipped || 'bottom';
    } else {
        // horizontal bar
        left = vm.base;
        right = vm.x;
        top = vm.y - vm.height / 2;
        bottom = vm.y + vm.height / 2;
        signX = right > left? 1: -1;
        signY = 1;
        borderSkipped = vm.borderSkipped || 'left';
    }

    // Canvas doesn't allow us to stroke inside the width so we can
    // adjust the sizes to fit if we're setting a stroke on the line
    if (borderWidth) {
        // borderWidth shold be less than bar width and bar height.
        var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
        borderWidth = borderWidth > barSize? barSize: borderWidth;
        var halfStroke = borderWidth / 2;
        // Adjust borderWidth when bar top position is near vm.base(zero).
        var borderLeft = left + (borderSkipped !== 'left'? halfStroke * signX: 0);
        var borderRight = right + (borderSkipped !== 'right'? -halfStroke * signX: 0);
        var borderTop = top + (borderSkipped !== 'top'? halfStroke * signY: 0);
        var borderBottom = bottom + (borderSkipped !== 'bottom'? -halfStroke * signY: 0);
        // not become a vertical line?
        if (borderLeft !== borderRight) {
            top = borderTop;
            bottom = borderBottom;
        }
        // not become a horizontal line?
        if (borderTop !== borderBottom) {
            left = borderLeft;
            right = borderRight;
        }
    }

    ctx.beginPath();
    ctx.fillStyle = vm.backgroundColor;
    // ctx.strokeStyle = vm.borderColor;
    ctx.lineWidth = borderWidth;

    // Corner points, from bottom-left to bottom-right clockwise
    // | 1 2 |
    // | 0 3 |
    var corners = [
        [left, bottom],
        [left, top],
        [right, top],
        [right, bottom]
    ];

    // Find first (starting) corner with fallback to 'bottom'
    var borders = ['bottom', 'left', 'top', 'right'];
    var startCorner = borders.indexOf(borderSkipped, 0);
    if (startCorner === -1) {
        startCorner = 0;
    }

    function cornerAt(index) {
        return corners[(startCorner + index) % 4];
    }

    // Draw rectangle from 'startCorner'
    var corner = cornerAt(0);
    ctx.moveTo(corner[0], corner[1]);

    for (var i = 1; i < 4; i++) {
        corner = cornerAt(i);
        nextCornerId = i+1;
        if(nextCornerId == 4){
            nextCornerId = 0
        }

        nextCorner = cornerAt(nextCornerId);

        width = corners[2][0] - corners[1][0];
        height = corners[0][1] - corners[1][1];
        x = corners[1][0];
        y = corners[1][1];

        var radius = cornerRadius;

        // Fix radius being too large
        if(radius > height/2){
            radius = height/2;
        }if(radius > width/2){
            radius = width/2;
        }

        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);

    }

    ctx.fill();
    if (borderWidth) {
        ctx.stroke();
    }
};


// END CONTROLLER
});
</script>

@endsection
