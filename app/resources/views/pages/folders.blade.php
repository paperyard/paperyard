@extends('layouts.app')

@section('page_title', 'Folders')

@section('active_folder', 'p_active_nav')

@section('custom_style')

<style type="text/css" media="screen">

/*---------paperyard custom button ----------------------*/

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
/* -------------------------------------------------------*/

.w_folder {
	margin-top:50px
}

.w_folder_icon {
	color:#b1d5ff; font-size:100px
}

.w_folder_tx {
	color:#7e7e7e; font-size:22px
}

.tab_view_icon {
	color:#7e7e7e;
	font-size:30px;
}

.tab_view_icon:hover {
	color:#017cff !important;
}

.view3_container {
	padding:10px;
}
.view3_container:hover {

	color:#017cff;
	-webkit-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
	-moz-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
	box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
	cursor: pointer;
}

.view3_div img {
	width:130px;
	height:100%;
}

.view3_card_d {
	top:0; position:absolute;
	margin-top:40px;
  color:#000 !important;
}

.view3_card_d li {
	margin:10px;
}

.view_date {
	top:0; right:0; position:absolute; margin:10px
}



.img1 {
  position:relative;
  top:0;
  left:0;
}

.img2 {
  position:absolute;
  top:0;
  margin-top:23px;
  margin-left:9px;
  max-height:168px;
  max-width:117px;
  border-radius: 2px;
}

.activeView {
	color:#017cff;
}
.viewIcons td{
   padding-right:25px;
}

.cstm_icon_btn {
  padding:2px !important;
  padding-left:5px !important;
  padding-right:5px !important;
  padding-top:0px !important;
  margin-right:7px;
}
.cstm_icon_btn:hover {
  background-color:  #017cff !important;
  -webkit-transition: all .3s;
     -moz-transition: all .3s;
      -ms-transition: all .3s;
       -o-transition: all .3s;
          transition: all .3s;
  color:#fff;
}


/* ----------------- breadcrumb nav ------------------------------*/
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
/*--------------------------------------------------------------------*/

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
/*---------------------------------------------------*/


.canvasStyle {
   width:100%;
}
</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Folders</a></li>
  </ul>
@endsection

@section('content')
<div class="row" ng-controller="folders_controller">

   @if(count($folder_stat)>=1)

   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<table>
		<tr class="viewIcons">
			<!-- CREATE NEW FOLDER -->
			<td>
				<a href="#" data-toggle="tab" ng-click="newFolder()">
					<i class="material-icons btn_no_folders tab_view_icon" style="font-size:36px; margin-top:2px">create_new_folder</i>
				</a>
			</td>
			<!-- FOLDER VIEW 1 -->
			<td >
				<a href="#grid_view"  data-toggle="tab" class="active"  ng-click="selected='grid_v'">
					<i ng-class="{activeView: selected=='grid_v'}" class="fa fa-th-list tab_view_icon"></i>
				</a>
			</td>
			<!-- FOLDER VIEW 2 -->
			<td  style="border-right:2px solid #ccc;">
				<a href="#table_view" data-toggle="tab" ng-click="selected='table_v'">
					<i ng-class="{activeView: selected=='table_v'}" class="fa fa-align-justify tab_view_icon"></i>
				</a>
			</td>
			<!-- FOLDER SORT -->
			<td style="padding-left:18px">
				<a href="#messages_only_icon_title" data-toggle="tab" ng-click="sortByAlpha('folder_name')">
					<i class="fa tab_view_icon" ng-class="alphaSortIcon" style="font-size:24px;"></i>
				</a>
			</td>
			<td>
				<a href="#settings_only_icon_title" data-toggle="tab" ng-click="sortByNum('total_c')">
					<i class="fa  tab_view_icon"  ng-class="numSortIcon" style="font-size:24px;"></i>
				</a>
			</td>
		</tr>
	</table><br>
	</div>


	<div class="col-md-12 ">

      <center>
        <div class="preloader ng-hide center-block" ng-show="folder_loader" style="margin-top:100px">
            <div class="spinner-layer pl-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>

        <div class="center-block ng-hide" ng-show="folder_not_found" style="margin-top:100px">
        	 <h2 style="color:red">No folder found. create one.</h2>
        </div>
      </center>

    </div>


     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    	<div class="row">
   			<!-- Tab panes -->
   			<div class="tab-content ng-hide" ng-show="folderz">

                <!-- GRID TAB -->
   				<div role="tabpanel" class="tab-pane fade in active" id="grid_view">

					<div class="col-md-6"  ng-repeat="folder in gridDatas | orderBy:propertyName:reverse track by $index " ng-init="$last && finished()">
						<div class="card view3_container "  >
							<table>
								<tr>
									<!-- FOLDER IMAGE CONTAINING DOCUMENT -->
									<td>
										<a ng-href="/folder/<#folder.folder_id#>">
											<div class="view3_div">
												<img class="img1" src="{{ asset('static/img/folder_img_holder.jpg') }}">
												<span ng-if=" folder.thumb != null ">
												    <img class="img2" ng-src="/files/image/<#folder.thumb#>">
												</span>
											</div>
									    </a>
									</td>
									<!-- FOLDER DETAILS -->
									<td style="width:100%">
										<div class="view_date">
											<ul class="list-unstyled header-dropdown m-r--5 list-inline">
												<li><p style="font-size:18px; padding-top:-20px;"><b><# folder.folder_name #></b></p></li>
												<li class="dropdown" >
													<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" >
														<i class="material-icons" style="padding:0px;">more_vert</i>
													</a>
													<ul class="dropdown-menu pull-right">
														<li ng-click="deleteFolder(folder.folder_id)"><a href="javascript:void(0);">Delete folder</a></li>
														<li ng-click="renameFolder(folder.folder_id,folder.folder_name)"><a href="javascript:void(0);">Rename folder</a></li>
													</ul>
												</li>
											</ul>
										</div>
										<a ng-href="/folder/<#folder.folder_id#>">
											<ul class="list-unstyled view3_card_d" >
												<li><p><# folder.total_c #> Dokumente</p></li>
												<li ng-if="folder.latest_date!=null"><p>neuestes Dokument vom <# folder.short_date#></p></li>
											</ul>
									   </a>
                     <!-- LINECHART -->
                    <div style="height:70px; margin-top:120px; padding:8px;">
                        <canvas id="myChart<#folder.folder_id#>" class="canvasStyle"></canvas>
                    </div> 
										
									</td>
								</tr>
							</table>
						</div>

           
					</div>

				</div>

   				<!--  TABLE TAB-->
   				<div role="tabpanel" class="tab-pane fade" id="table_view">

                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				        	<div class="body table-responsive">
				        		<table class="table table-striped table-hover">
				        			<thead>
				        				<tr>
				        					<th>Folder name</th>
				        					<th>Documents</th>
				        					<th>Latest document</th>
				        					<th>Date created</th>
				        					<th><span class="pull-right">Actions</span></th>
				        				</tr>
				        			</thead>
				        			<tbody>
				        				<tr  ng-repeat="folder in tableDatas | orderBy:propertyName:reverse track by $index">
				        					<td><# folder.folder_name #></td>
				        					<td><# folder.total_c     | default #></td>
				        					<td><# folder.latest_date | default #></td>
				        					<td><# folder.folder_date_created #></td>
				        					<td>
				        						<span class="pull-right">
				        							<a ng-href="/folder/<#folder.folder_id#>" style="text-decoration: none !important">
					        							<button type="button" class="btn btn-default waves-effects cstm_icon_btn">
					        								<i class="material-icons">folder</i>
					        							</button>
				        						    </a>
				        							<button type="button" class="btn btn-default  waves-effect cstm_icon_btn" ng-click="renameFolder(folder.folder_id,folder.folder_name)">
				        								<i class="material-icons">mode_edit</i>
				        							</button>
				        							<button type="button" class="btn btn-default waves-effects cstm_icon_btn" ng-click="deleteFolder(folder.folder_id)">
				        								<i class="material-icons">delete_forever</i>
				        							</button>
				        						</span>
				        					</td>
				        				</tr>

				        			</tbody>
				        		</table>
				        	</div>
				        </div>

   				</div>

   			</div>
   		</div>
   	</div>

   @else
	<!-- SHOW IF NO FOLDER EXIST -->
	<center>
		<div class="w_folder">
			<div><i class="fa fa-folder-open w_folder_icon"></i></div><br>
			<div><p class="w_folder_tx">
				@lang('folders.w_folder_tx1')<br>
				@lang('folders.w_folder_tx2')
			</p></div><br>
			<div>
				<button class="btn-flat btn_color main_color waves-effect lg-btn_x2 btn_no_folders" type="submit" ng-click="newFolder()"><span class="lg-btn-tx">@lang('folders.w_folder_btn_tx')</span></button>
			</div>
		</div>
	</center>
  @endif


</div>
@endsection

@section('scripts')

<script src="{{ asset('static/js/folders.js') }}"></script>
<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', []);
//custom filter for empty fields
app.filter('default', function(){
    return function(data)
      {
        if(data!=undefined && data!=null && data!=""){
            return data;
        }
        else
        {
            data = "N/D";
            return data;
        }
      }
});
app.controller('folders_controller', function($scope, $http, $timeout, $compile) {

  $scope.propertyName = 'folder_name';
  $scope.reverse = true;
  $scope.alphaSortIcon = 'fa-sort-alpha-asc';
  $scope.numSortIcon = 'fa-sort-numeric-asc';


  $scope.show_preloader = function(){
     $scope.folder_loader = true;
	   $scope.folderz = false;
  }

  $scope.hide_preloader = function(){
	 $scope.folder_loader = false;
	 $scope.folderz = true;
  }


  $scope.sortByAlpha = function(filterName) {
    $scope.reverse = ($scope.propertyName === filterName) ? !$scope.reverse : false;
    $scope.propertyName = filterName;
    $scope.reverse == false? $scope.alphaSortIcon = 'fa-sort-alpha-asc' : $scope.alphaSortIcon = 'fa-sort-alpha-desc';
    $timeout( function()
    {
        $scope.createLineChart();
    }, 1000);  
  };

  $scope.sortByNum = function(filterNum) {
    $scope.reverse = ($scope.propertyName === filterNum) ? !$scope.reverse : false;
    $scope.propertyName = filterNum;
    $scope.reverse == false? $scope.numSortIcon = 'fa-sort-numeric-asc' : $scope.numSortIcon = 'fa-sort-numeric-desc';
    $timeout( function()
    {
        $scope.createLineChart();
    }, 1000); 
  };

  //get folder datas
  $scope.getFolders = function(){
    $scope.show_preloader();
    $http.get('/folders/return').success(function(data){
          $scope.folder_names = [];
          $scope.folder_ids = [];
          $scope.gridDatas = data;
          $scope.tableDatas = data;
          $scope.hide_preloader();

          angular.forEach(data, function(value, key) {
			      this.push(value.folder_name.toUpperCase());
		      }, $scope.folder_names);

          console.log(data);
    });
  }

  $scope.getFolders();

  $scope.finished = function(){
    $scope.createCanvas();
  }

  $scope.createCanvas = function(){
    $timeout( function()
    {
        $scope.createLineChart();
    }, 1000);   
  }
  
  $scope.deleteFolder = function(f_id){

    swal({
        title: "Delete folder?",
        text: "You will not be able to recover this folder and documents inside",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: true,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            //ajax send post delete with id.
             $.ajax({
	            url: '/folders/delete',
	            data: {
	                folder_id: f_id
	            },
	            type: 'POST',
	            success: function(data) {
	            	if(data=="success_deleted"){
	                	swal("Deleted!", "Your folder has been deleted.", "success");
	                	$scope.getFolders();
	                }
	            }
	        }); //end ajax
        } else {
            swal("Cancelled", "Your folder is safe :)", "error");
        }
    });

  }

  $scope.renameFolder = function(f_id,f_name){

  	swal({
        title: "Rename folder",
        text: "New folder name:",
        type: "input",
        input: 'new_folder_name',
        inputValue: f_name,
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "New folder name"
    }, function (new_folder_name) {
        if (new_folder_name === false) return false;
        if (new_folder_name === "") {
            swal.showInputError("You need to write something!"); return false;
        }
        if($scope.folder_names.includes(new_folder_name.toUpperCase())==true ){
            swal.showInputError("Folder name exist"); return false
        }
        $scope.show_preloader();
        $.ajax({
            url: '/folders/rename',
            data: {
            	folder_id: f_id,
                folder_name: new_folder_name
            },
            type: 'POST',
            success: function(data) {
            	if(data=="success_renamed"){
                	swal("Success", "new folder name is: " + new_folder_name, "success");
                	$scope.getFolders();
                }
            }
        }); //end ajax
    });

  }

  $scope.newFolder = function(){
    swal({
        title: "Create new folder",
        text: "Name your folder:",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Folder name"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("You need to write something!"); return false
        }
        if($scope.folder_names.includes(inputValue.toUpperCase())==true ){
            swal.showInputError("Folder name exist"); return false
        }
        $scope.show_preloader();
        $.ajax({
            url: '/folders/new_folder',
            data: {
                folder_name: inputValue
            },
            type: 'POST',
            success: function(data) {
            	if(data=="success"){
                	swal("Success", "new folder created with name: " + inputValue, "success");
                	$scope.getFolders();
                	if($scope.gridDatas.length<=0 || $scope.gridDatas==null){
                        window.location.reload();
                	}
                }
            }
        }); //end ajax

    });
  }

$scope.createLineChart = function(){

  var lineCanvas  = [];
  var makeDatas   = [];
  var lineDatas   = [];
  var lineChart   = [];
  var maxVal      = [];

  angular.forEach($scope.gridDatas, function(value, key) {
       lineCanvas[value.folder_id] = document.getElementById("myChart"+value.folder_id);
       makeDatas[value.folder_id] = {
          label: "",
          //number of documents group by date.           //group notifications id by date
          data: value.line_chart_datas,
          lineTension: 0.3,
          fill: false,
          borderColor: 'rgba(0, 119, 255, 1)',
          backgroundColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius:0,
          borderWidth:4
      };
      lineDatas[value.folder_id] = {
          labels: makeDatas[value.folder_id].data,
          datasets: [makeDatas[value.folder_id]],
      };
      maxVal = Math.max.apply(Math, value.line_chart_datas);

      lineChart[value.folder_id] = new Chart(lineCanvas[value.folder_id], {
          type: 'line',
          data: lineDatas[value.folder_id],
          // options --------------------------------
          options: {
                  maintainAspectRatio: false,
                  legend: {
                      display: false,
                  },
                  tooltips: {
                      enabled: false
                  },
                   scales: {
                      yAxes: [{
                        ticks: {
                          beginAtZero: true,
                          autoSkip: false,
                          display:false,
                          max: maxVal+0.1
   
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
                          display:false,
                            
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
          // options --------------------------------
      });

      console.log('linechart data created');
  });
} 


}); //end controller

</script>


@endsection
