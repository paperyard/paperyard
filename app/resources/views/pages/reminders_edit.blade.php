@extends('layouts.app')

@section('page_title', 'Edit reminder')

@section('active_reminder', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/reminders.css') }}" rel="stylesheet">
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

/*------------------------------------------------------------------------*/

.notify_w_tx {
  color:#7e7e7e; font-size:22px;
}

.notify_ico {
  color:#b1d5ff; font-size:100px;
}

.notify_pos {
  margin-top:50px;
}

.card:hover {
    color:#017cff;
    -webkit-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
    -moz-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
    box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
    cursor: pointer;
}

.notify-edit-icon:hover {
    color:#017cff !important;
    cursor: pointer;
}

.list-group-autocomplete{
   position:absolute !important;
   z-index:5 !important;
}

.preload_custm_loc {
    margin-top:-20px;
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
/*---------------------------------------------------*/

/* --------- autocomplete ---------------------------*/
.th-t {
  background-color: #4ddb9f;
}
.th-f {
  background-color: #ef5c8f;
}
.th-ft {
  background-color: #fade45;
}
/*---------------------------------------------------*/

.cstm_input {
  background-color:#ebedf8;
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

.cstm_input {
  background-color:#ebedf8;
  outline: none;
  border: none !important;
  -webkit-box-shadow: none !important;
  -moz-box-shadow: none !important;
  box-shadow: none !important;
}

.list_btn_container {
  position: absolute;
  cursor: pointer;
  right:0%;
  top:0;
}
.inside_close {  
   position: relative;
   padding: 10.5px 13.5px;
   background-color:#999;
}

.ic_trash {
   background-color:#ff9c1c !important;
   color:#fff;
}

.ic_trash:hover {
   background-color:#fca535 !important;
   color:#fff;
}

.list-group-item:hover {
  background-color:#b1d5ff !important;
}


</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Reminders</a></li>
  </ul>
@endsection

@section('content')
<div class="row clearfix" ng-controller="reminder_controller" ng-click="clear()"><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="card">
           
            <div class="body">

                <form enctype="multipart/form-data" name="reminderForm" >
                    <!-- REMINDER TITLE -->
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="reminder_title" ng-model="reminder.rm_title"  class="form-control" placeholder="Reminder title." required>
                        </div>
                    </div>
                    <div class="list-group">
                         <a  class="list-group-item" ng-repeat="task in reminder.task_list track by $index" style=" word-wrap: break-word; margin-top:10px">
                            <span style="padding-right:30px"><b style="margin-right:10px"><# $index+1#></b><# task.task_name #></span>
                            <span class="list_btn_container" ng-click="removeTask($index,task.task_id)">
                                  <span class="inside_close ic_trash waves-effect"><i class="fa fa-trash"></i></span>
                            </span>
                         </a>
                    </div>  
                    <!-- NEW TASK INPUT -->
                     <div class="input-group">
                         <div class="form-line">
                            <input type="text" name="new_task" ng-model="reminder.new_task"  class="form-control" placeholder="New task.." ng-keydown="keypressNewTask($event)">
                         </div>
                         <span class="input-group-addon">
                              <button class="btn btn-primary active-red" type="button" style="margin-top:-7px" ng-click="addTask()"><i class="fa fa-plus" style="margin-bottom:6px"></i></button>
                         </span>
                         <br>
                    </div>
                    <!-- SUBMIT BUTTOn -->
                     <div class="form-group">
                        <div class="pull-right">
                             <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 ng-hide" type="button" ng-show="save_rm_btn" ng-click="check_saveReminder(); "><span class="lg-btn-tx">Update reminder</span></button>
                         </div>
                         <br>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/reminders.js') }}"></script>
<script type="text/javascript">


//inject this app to rootApp
var app = angular.module('app', []);

app.filter('default', function(){
   return function(data){
       if(data==null){
           data = "N/D";
           return data;
       }
       return data;
   }
});


app.controller('reminder_controller', function($scope, $http, $timeout, $q) {

//save button model, true = show.
$scope.save_rm_btn = true;


$scope.reminder = [];
//selected documents datas
$scope.reminder.selectedDocument = [];
//array where reminder task list stored
$scope.reminder.task_list = [];
$scope.reminder.task_delete = [];


//create reminder==================================


//add new task
$scope.addTask = function(){
  if($scope.reminder.new_task!=null && $scope.reminder.new_task!="")
  {
    $scope.reminder.task_list.push({'task_name':$scope.reminder.new_task});
    $scope.reminder.new_task = null;
  }
  else{
    swal("eror", "Please add a task", "error");
  }
}

//remove created task
$scope.removeTask = function(index, task_id){
   $scope.reminder.task_list.splice(index, 1);
   if(task_id!=undefined){
        $scope.reminder.task_delete.push(task_id);
   }
   console.log($scope.reminder.task_delete);
}


$scope.getToEditDocument = function(){
  data = {
     rm_id:'{{$rm_id->rm_id}}'
  }
  $http.post('/reminders/get_to_edit', data).success(function(data){
        $scope.reminder.rm_title = data.reminder_title;
        $scope.reminder.rm_id = data.reminder_id;
        $scope.reminder.task_list = data.task_list;
        console.log(data);
  });
}

$scope.getToEditDocument();

//save new reminder
$scope.saveReminder = function(){

    data = {
       reminder_title  : $scope.reminder.rm_title,
       reminder_id     : $scope.reminder.rm_id,
       reminder_tasks  : $scope.reminder.task_list,
       reminder_tasks_delete : $scope.reminder.task_delete
    }
    $http({method:'POST',url:'/reminders/update', data}).success(function(data){
       swal("Success", "Reminder updated", "success");
    });
}

// check if all required field filled.
$scope.check_saveReminder = function(){


    //user must add task for the reminder
    if($scope.reminder.task_list.length==0){
       swal("eror", "Please add a task", "error");
    }
    //all good, save reminder
    else{
       $scope.saveReminder();
    }
}

// on keypress check key
$scope.keypressNewTask = function(keyEvent) {
  //if key == 13 == ENTER  search document.
  if (keyEvent.which === 13){
      $scope.addTask();
  }

};



$scope.wait = function(){
    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });
}



}); //end controller

</script>
@endsection
