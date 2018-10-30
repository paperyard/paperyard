@extends('layouts.app')

@section('page_title', 'Child address book')

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

.ad_input {
   margin-top:-25px;
}
.input-group-addo,i {
   color: #017cff !important;
}
</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Address book</a></li>
  </ul>
@endsection

@section('content')
<div class="row clearfix" ng-controller="address_book_controller"><br>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
      <div class="body">
        <div class="row clearfix" style="margin-top:35px">
          <form enctype="multipart/form-data"  id="address_book_form" name="address_book_form"   ng-submit="createChildAddress(); $event.preventDefault();">
            <input type="text" value="{{$parentAddress->ab_id}}" name="parent_id" hidden>
            <!--  SHORTNAME -->
            <div class="col-md-4 col-sm-12 col-xs-12 ad_input">
              <div class="input-group " >
                <span class="input-group-addon ">
                  <i class="material-icons">person</i>
                </span>
                <div class="form-line">
                  <input type="text" class="form-control" placeholder="Shortname" name="shortname"    value="{{ $parentAddress->ab_shortname }}">
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
                  <input type="text" class="form-control" placeholder="Salutation" name="salutation" value="{{ $parentAddress->ab_salutation }}">
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
                  <input type="text" class="form-control" placeholder="First name" name="firstname" value="{{ $parentAddress->ab_firstname }}">
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
                  <input type="text" class="form-control" placeholder="Last name" name="lastname"   value="{{ $parentAddress->ab_lastname }}">
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
                  <input type="text" class="form-control" placeholder="Company" name="company"  value="{{ $parentAddress->ab_company }}">
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
                  <input type="text" class="form-control" placeholder="Address line 1" name="addressline1" value="{{ $parentAddress->ab_address_line1 }}">
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
                  <input type="text" class="form-control" placeholder="Address line 2" name="addressline2" value="{{ $parentAddress->ab_address_line2 }}">
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
                  <input type="text" class="form-control" placeholder="ZIP CODE" name="zipcode" value="{{ $parentAddress->ab_zipcode }}">
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
                  <input type="text" class="form-control" placeholder="Town" name="town" value="{{ $parentAddress->ab_town }}">
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
                  <select class="form-control" id="sel1" style="font-weight:bold" name="country">
                    @if(!empty($parentAddress->ab_country))
                    <option value="{{$parentAddress->ab_country}}" selected>{{$parentAddress->ab_country}}</option>
                    @endif
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
                    <option value="Germany">Germany</option>
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
                <input type="text" class="form-control" placeholder="Telephone" name="telephone" value="{{ $parentAddress->ab_telephone }}">
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
                <input type="email" class="form-control" placeholder="Email" name="email"  value="{{ $parentAddress->ab_email }}">
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
                <input type="text" class="form-control" placeholder="Notes" name="notes"  value="{{ $parentAddress->ab_notes }}">
              </div>
            </div>
          </div>
          <div class="col-md-12 ng-hide" ng-show="submit_notify">
            <div class="form-group">
              <button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">Create child address</span></button>
            </div>
          </div>
        </form>
      </div>
      </div> <!-- body -->
      </div> <!-- card -->
      </div> <!-- col -->
    </div>
@endsection

@section('scripts')
<script src="{{ asset('static/js/address_book.js') }}"></script>
<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', []);

app.controller('address_book_controller', function($scope, $http, $timeout, $q) {

$scope.submit_notify = true;
$scope.ab_datas = [];

$scope.wait = function(){
    $('.card').waitMe({
        effect: 'win8_linear',
        text: 'Please wait...',
        bg: 'rgba(255,255,255,0.90)',
        color: '#555'
    });
}

//save address book
$scope.createChildAddress = function(){

      var form = $('#address_book_form');
      var formdata = false;
      if (window.FormData) {
          formdata = new FormData(form[0]);
      }

      $scope.wait();
      $.ajax({
          url: '/address_book/save_created_child',
          data: formdata ? formdata : form.serialize(),
          cache: false,
          contentType: false,
          processData: false,
          type: 'POST',
          success: function(data) {
             window.location.replace('/address_book');
          }
      }); //end ajax

}




}); //end controller

</script>
@endsection
