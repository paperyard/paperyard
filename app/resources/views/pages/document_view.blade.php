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
	width:180px;
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



.ad_input, .input-grou, .input-group-addon, .material-icons {
    color: #017cff !important;
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


<div class="row" ng-controller="doc_view_controller" style="padding-bottom: 3%">

  <!-- Document images -->
  <div class="col-md-4" style="margin-top:-20px">
    <div class="doc_pages f_cover">
      @foreach($document_pages as $dp)
      <div class="f_cover_img"><img src="{{  url('/files/image') .'/'. $dp->doc_page_image_preview }}"></div>
      @endforeach
    </div>
  </div>
 
  <!-- Data Fields -->
  <div class="col-md-8 doc-upd-input" ng-click="reInitAddressList()"> <!-- CLear Autocomplete address book-->
    <br>
    <!--  Form -->
    <form  enctype="multipart/form-data" id="doc_upd_form" name="doc_upd_form"  ng-submit="updateDocument(); $event.preventDefault();">
      
      <div class="row clearfix">

          <!-- Document ID -->
          <input name="doc_id" type="hidden" class="form-control" placeholder="" value="{{$document->doc_id}}">
          

          <!-- SENDER ==================================== -->
          <div class="col-md-6">
            <div class="input-group form-group-lg form-g-label">
              <div class="form-line frm-input">
                <input name="doc_sender" type="text" class="form-control" placeholder="" value="" autocomplete="off"
                ng-model-options='{ debounce: 1000 }' ng-change="onChangeInput('sender',sender_address)" ng-model="sender_address" ng-keydown="searchKeyPress($event)" required>
              </div>
              <span class="input-group-addon" data-toggle="modal" data-target="#senderAddressModal" ng-show="sender_datas.length>0 && sender_datas!=null">
                <button class="btn btn-primary waves-effect doc_view_input_icon" type="button"><i class="fa fa-address-book"></i></button>
              </span>
            </div>
            <!-- Autocomplete --> <!-- update to bloodhound-->
            <div class="row cleafix">
              <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 list-group-autocomplete " >
                <div class="list-group ">
                  <!-- AUTO COMPLETE SENDER ADDRESS BOOK -->
                  <a ng-click="searchAddressBook(ab.ab_id,ab.ac_keyword,'sender')" class="list-group-item" ng-repeat="ab in sender_list track by $index" ng-show="sender_list.length>0 && sender_list!=null">
                    <# ab.ac_keyword #>
                  </a>
                  <!-- NO RESULT FOUND -->
                  <a  class="list-group-item ng-hide" ng-show="sender_no_result">
                    No record found...
                  </a>
                </div>
              </div>
            </div>
            <!--/ Autocomplete -->
            <span class="reminder_span">Sender</span>
          </div>
          <!--/ Sender -->

          <!-- RECEIVER ==================================== -->
          <div class="col-md-6">
            <div class="input-group form-group-lg form-g-label">
              <div class="form-line frm-input">
                <input name="doc_receiver" type="text" class="form-control" placeholder="" value="" autocomplete="off"
                ng-model-options='{ debounce: 1000 }' ng-change="onChangeInput('receiver',receiver_address)" ng-model="receiver_address" ng-keydown="searchKeyPress($event)" required="">
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
                    No record found...
                  </a>
                </div>
              </div>
            </div>
            <!-- Autocomplete -->
            <span class="reminder_span">Receiver</span>
          </div>
          <!--/ Receiver -->

      </div><!--/ Clear Fix -->
      <br>
         
      <div class="row clearfix">

        <!-- Date --> 
        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label masked-input">
            <div class="form-line frm-input">
              <input name="doc_date" type="text" class="form-control date" placeholder="Ex: 24.01.2018 (D.M.Y)" value="{{$document->date}}" >
            </div>
            <span>Date</span>
          </div>
        </div>

        <!-- -Tags -->
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

        <!-- Category -->
        <div class="col-md-6">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_category" type="text" class="form-control" placeholder="" value="{{$document->category}}" >
            </div>
            <span>Category</span>
          </div>
        </div>

        <!-- Reminder -->
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

      <!--/ Tax relevant -->
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
      
      <!--/ Note -->
      <div class="row">
        <div class="col-md-12">
          <div class="form-group form-group-lg form-g-label">
            <div class="form-line frm-input">
              <input name="doc_notes" type="text" class="form-control" placeholder="Notes" value="{{$document->note}}" >
            </div>
          </div>
        </div>
      </div>

      <!-- Button -->
      <div class="row" style="padding-bottom:50px">


        <div class="col-md-12" ng-init="submit_button=true; please_wait=false">
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 pull-right" ng-show="submit_button" type="submit" ng-model="submit_button"><span class="lg-btn-tx">Save & Done</span></button>
          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 pull-right" ng-show="please_wait" type="button" ng-model="please_wait"><span class="lg-btn-tx">Please wait</span></button>

          <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 pull-right" type="button" data-toggle="modal" data-target="#newRecordModal" style="margin-right:20px"><span class="lg-btn-tx">Create record</span></button>
      
        </div>
      </div>

    </form>


  </div>
  <!-- Data fields -->

  <div class="col-md-12 hidden-xs hidden-sm">
    <div style="position:absolute; bottom:0; right:0">
      <i>TAB / Enter: a field wieter / save | Arrow keys left / right: Scroll</i>
    </div>
  </div>

</div> <!--/ ROW  -->


<!-- Reminders task list modal -->
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



<!-- CREATE RECORD MODAL  -->
<div class="modal fade new_record" id="newRecordModal" tabindex="-1" role="dialog" style="margin-top:40px" >
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content" >

      <div class="modal-header">
        <h4 class="modal-title" id="largeModalLabel">New record</h4>
      </div>

      <form enctype="multipart/form-data"  id="address_book_form" name="address_book_form"  ng-submit="saveNewRecord(); $event.preventDefault();">
      <div class="modal-body">

            <!--  SHORTNAME -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">person</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Shortname" name="shortname" >
                </div>
              </div>
            </div>

            <!--  SALUTATION -->
            <div class="col-md-8 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">loyalty</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Salutation" name="salutation" >
                </div>
              </div>
            </div>

            <!--  FIRST NAME -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">person</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="First name" name="firstname" >
                </div>
              </div>
            </div>

            <!--  LAST NAME -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">person</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Last name" name="lastname" >
                </div>
              </div>
            </div>

            <!--  COMPANY -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group" >
                <span class="input-group-addon ">
                  <i class="material-icons">business</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Company" name="company" >
                </div>
              </div>
            </div>

            <!--  ADDRESS LINE 1 -->
            <div class="col-md-12 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">location_on</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Address line 1" name="addressline1" >
                </div>
              </div>
            </div>

            <!--  ADDRESS LINE 2  -->
            <div class="col-md-12 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">location_on</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Address line 2" name="addressline2" >
                </div>
              </div>
            </div>

            <!--  ZIPCODE -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">code</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="ZIP CODE" name="zipcode" >
                </div>
              </div>
            </div>

            <!--  Town -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">location_city</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Town" name="town" >
                </div>
              </div>
            </div>

            <!--  Country -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">public</i>
                </span>
                <div class="form-line">
                  <select class="form-control" id="sel1" style="font-weight:bold;" name="country">
                    <option value="Afganistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bonaire">Bonaire</option>
                    <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Canary Islands">Canary Islands</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Channel Islands">Channel Islands</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos Island">Cocos Island</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote DIvoire">Cote D'Ivoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Curaco">Curacao</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="East Timor">East Timor</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands">Falkland Islands</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Ter">French Southern Ter</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany" selected>Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Great Britain">Great Britain</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Hawaii">Hawaii</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Isle of Man">Isle of Man</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea North">Korea North</option>
                    <option value="Korea Sout">Korea South</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau">Macau</option>
                    <option value="Macedonia">Macedonia</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Midway Islands">Midway Islands</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Nambia">Nambia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherland Antilles">Netherland Antilles</option>
                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                    <option value="Nevis">Nevis</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau Island">Palau Island</option>
                    <option value="Palestine">Palestine</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Phillipines">Philippines</option>
                    <option value="Pitcairn Island">Pitcairn Island</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                    <option value="Republic of Serbia">Republic of Serbia</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="St Barthelemy">St Barthelemy</option>
                    <option value="St Eustatius">St Eustatius</option>
                    <option value="St Helena">St Helena</option>
                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                    <option value="St Lucia">St Lucia</option>
                    <option value="St Maarten">St Maarten</option>
                    <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
                    <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
                    <option value="Saipan">Saipan</option>
                    <option value="Samoa">Samoa</option>
                    <option value="Samoa American">Samoa American</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Tahiti">Tahiti</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Erimates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States of America">United States of America</option>
                    <option value="Uraguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City State">Vatican City State</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                    <option value="Wake Island">Wake Island</option>
                    <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zaire">Zaire</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                  </select>

              </div>
            </div>
          </div>
          <!--  telephone -->
          <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
            <div class="input-group " >
              <span class="input-group-addon ">
                <i class="material-icons">call</i>
              </span>
              <div class="form-line">
                <input type="text" class="form-control" placeholder="Telephone" name="telephone">
              </div>
            </div>
          </div>
          <!--  email -->
          <div class="col-md-8 col-sm-12 col-xs-12 ad_input">
            <div class="input-group " >
              <span class="input-group-addon ">
                <i class="material-icons">email</i>
              </span>
              <div class="form-line">
                <input type="email" class="form-control" placeholder="Email" name="email" >
              </div>
            </div>
          </div>
          <!--  notes -->
          <div class="col-md-12 col-sm-12 col-xs-12 ad_input">
            <div class="input-group " >
              <span class="input-group-addon ">
                <i class="material-icons">notes</i>
              </span>
              <div class="form-line">
                <input type="text" class="form-control" placeholder="Notes" name="notes" >
              </div>
            </div>
          </div>
          <div class="col-md-12 ng-hide" ng-show="submit_notify">
            <div class="form-group">
              <button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">Create  Record</span></button>
            </div>
          </div>
      </div>    

      <div class="modal-footer">
          <button type="submit" class="btn btn-primary waves-effect" style="width:100px">SAVE</button>
          <button type="button" class="btn btn-warning waves-effect" data-dismiss="modal" style="width:100px">CLOSE</button>
      </div>

      </form>

    </div>
  </div>
</div>


<!-- Sender address book modal -->
<div class="modal fade" id="senderAddressModal" tabindex="-1" role="dialog" style="margin-top:40px" >
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content" ng-repeat="data in sender_datas">
      <div class="modal-header">
        <h4 class="modal-title" id="largeModalLabel">Sender record</h4>
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
        <!-- Remove address book attach to document-->
        <!-- <button type="button" class="btn btn-danger waves-effect"  ng-click="removeAddressBook(data.ab_id,'sender','senderAddressModal')">REMOVE ADDRESS BOOK</button> -->
        <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>


<!-- Receiver address book modal -->
<div class="modal fade" id="receiverAddressModal" tabindex="-1" role="dialog" style="margin-top:40px" >
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content" ng-repeat="data in receiver_datas">
      <div class="modal-header">
        <h4 class="modal-title" id="largeModalLabel">Receiver record</h4>
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

        <!-- Remove address book attach to document-->
        <!-- <button type="button" class="btn btn-danger waves-effect"  ng-click="removeAddressBook(data.ab_id,'receiver','receiverAddressModal')">REMOVE ADDRESS BOOK</button> -->
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
      
       //Masked Input --------------------------------------------------------------
	    var $masInput = $('.masked-input');
	    
      // set document pages. -------------------------------------------------------
      
      var min_page = 1;
	    var max_page = '{{ count($document_pages) }}';
      
      $('#min-page').html(min_page);
      $('#max-page').html(max_page);

	    //Date mask input  -----------------------------------------------------------
	   
      $masInput.find('.date').inputmask('dd.mm.yyyy', { placeholder: '__/__/____' });

      
      // change current page value  ------------------------------------------------
		  
      $('.doc_pages').on('afterChange', function(event, slick, currentSlide){
          $('#min-page').html(currentSlide+1);
		  });
      
      // initialize slick carousel on doc pages
	    $('.doc_pages').slick({
          infinite:false,
          initialSlide:0
	    });

      // check if key is press. -----------------------------------------------------
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

      //Datetimepicker plugin  -------------------------------------------------------
      $('.datepicker2').bootstrapMaterialDatePicker({
          format: 'DD.MM.YYYY',
          clearButton: true,
          weekStart: 1,
          time:false
      });

});


// inject this app to rootApp  --------------------------------------------------------

var app = angular.module('app', []);

// Custom filters ---------------------------------------------------------------------

app.filter('default_nd', function(){
   return function(data){
       if(data==null){
           data = "N/D";
           return data;
       }
       return data;
   }
});

// -------------------------------------------------------------------------------------

app.controller('doc_view_controller', function($scope, $http, $timeout, $rootScope, $q) {

  
  // Reminders -------------------------------------------------------------------------
  
  $scope.reminderCheck = '{{$document->reminder}}';
  $rootScope.task_list = [];


  // search address book ---------------------------------------------------------------
  
  $scope.canceler = $q.defer();
  $scope.search_canceler = $q.defer();
  
  //------------------------------------------------------------------------------------

  $scope.sender_address      = "{{$document->sender}}";
  $scope.sender_list         = [];
  $scope.sender_no_result    = false;
  $rootScope.sender_datas    = [];
  $scope.sender_address_id   = "{{$document->sender_address_id}}";


  //------------------------------------------------------------------------------------

  $scope.receiver_address    = "{{$document->receiver}}";
  $scope.receiver_list       = [];
  $scope.receiver_no_result  = false;
  $rootScope.receiver_datas  = [];
  $scope.receiver_address_id ="{{$document->receiver_address_id}}";


  //------------------------------------------------------------------------------------

  $scope.getAddressBookDatas = function(address_id,type){

      if(address_id!=null && address_id!="" && address_id!=undefined){

          data = { address_id:address_id}

          $http.post('/address_book/search_address', data).success(function(data){

               if(type=="sender"){
                  $rootScope.sender_datas   = data;
               }else{
                  $rootScope.receiver_datas = data;
               }

          });

      }
  }
  
  // type sender -----------------------------------------------------
  $scope.getAddressBookDatas($scope.sender_address_id,"sender");

  // type receiver ---------------------------------------------------
  $scope.getAddressBookDatas($scope.receiver_address_id,"receiver");



   
  // Remove address book from attached sender/receiver -----------------------------  
  $rootScope.removeAddressBook = function(address_id,type,modalID){
     $('#'+modalID).modal('hide');
     if(type=="sender"){
        $rootScope.sender_datas     = [];
        $scope.sender_address_id    = null;
        $scope.sender_address       = "";
     }
     else{
        $rootScope.receiver_datas   = [];
        $scope.receiver_address_id  = null;
        $scope.receiver_address     = "";
     }
  }


  
  // get task list from reminder  ---------------------------------------------------
  $scope.getTaskList = function(){
    if($scope.reminderCheck!=""){
        data = {
           rm_id: '{{$document->doc_id}}'
        }
        $http.post('/reminders/doc_view', data).success(function(data){
              $rootScope.task_list = data.task_list;
        });
     }   
  }
  $scope.getTaskList();


  

  // Set task status -----------------------------------------------------------------
  $rootScope.taskComplete = function(task_id,status){
    data = {
       task_id     : task_id,
       task_status : status,
    }
    $http({method:'POST',url:'/reminders/task_complete', data}).success(function(data){
      //
    });
  }




  // Update document details  ----------------------------------------------------------------------------------------------
  $scope.updateDocument = function() {

      // hide submit button
      $scope.submit_button = false;
      // show please wait button 
      $scope.please_wait   = true;


      // Form data ----------------------------------------------------------------------------------------------------------
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

          }
      }); //end ajax

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
              
              $scope.reInitAddressList();
              
              if(type=="sender"){

                 if(data!="not_found"){
                    $scope.sender_list  = data;
                 }else{
                    $scope.sender_no_result  = true;
                 }

              }
              else{
                 
                 if(data!="not_found"){
                    $scope.receiver_list = data;
                 }else{
                    $scope.receiver_no_result = true;
                 }

              }
          });

      }    
  }



  //param addressbook id(ab_id),autocomplete_keyword, type(sender,receiver)
  // ab = address book

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



  // clear autocomplete address book
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


 
  $scope.wait = function(){
    $('.new_record').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });
  }



  $scope.createNewRecord = function(){

      $("#newRecordModal").modal('show');
      $("#address_book_form")[0].reset();
  
  }

  $rootScope.saveNewRecord = function(){


      var form = $('#address_book_form');
      var formdata = false;
      if (window.FormData) {
        formdata = new FormData(form[0]);
      } 

      // tell addressbook controller function that this post new record is from document view. 
      formdata.append('for', 'doc_view');


      

      $.ajax({
        url: '/address_book/save',
        data: formdata ? formdata : form.serialize(),
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data) { 

            $("#address_book_form")[0].reset();
            $("#newRecordModal").modal('hide');
            $scope.showNotification('New record created','bg-blue');

        }
      }); //end ajax

  }


  $scope.showNotification = function(Text,bg_color){
  
        var colorName      =  bg_color;
        var placementAlign =  "right";
        var placementFrom  =  "bottom";
        var text           =  Text;
        var animateEnter   =  "animated fadeInDown";
        var animateExit    =  "animated fadeOutUp";
        var allowDismiss   =  true;

        $.notify({
            message: text
        },
        {
            type: colorName,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            delay: 100,
            timer: 1700,
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
