@extends('layouts.app')

@section('page_title', 'Settings')

@section('active_settings', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/settings.css') }}" rel="stylesheet">
<style type="text/css" media="screen">

/*---------paperyard custom button ----------------------*/

.lg-btn-tx {
	font-size:18px;
	color:#017cff;
	font-weight:bold
}
.lg-btn_x2 {
	padding-left:15px;
	padding-right:15px;
	height:35px;
	border:none;
	border-radius:5px
}
.btn_color{
	background-color:#b1d5ff
}
/* -------------------------------------------------------*/


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

/***************************** Dropzone Styling *****************************/

/**
 * The dnd-list should always have a min-height,
 * otherwise you can't drop to it once it's empty
 */
.advancedDemo .dropzone ul[dnd-list] {
    min-height: 42px;
    margin: 0px;
    padding-left: 0px;
}

.advancedDemo .dropzone li {
    display: block;
}

/**
 * Reduce opacity of elements during the drag operation. This allows the user
 * to see where he is dropping his element, even if the element is huge. The
 * .dndDragging class is automatically set during the drag operation.
 */


.advancedDemo .dropzone .dndDragging {
    opacity: 0.7;
}

/**
 * The dndDraggingSource class will be applied to the source element of a drag
 * operation.
 */
.advancedDemo .dropzone .dndDraggingSource {
    opacity: 0.5;
}

/**
 * An element with .dndPlaceholder class will be added as child of the dnd-list
 * while the user is dragging over it.
 */
.advancedDemo .dropzone .dndPlaceholder {
    background-color: #ddd !important;
    display: block;
    min-height: 42px;
}

/***************************** Element type specific styles *****************************/

/*.advancedDemo .dropzone .itemlist {
    min-height: 120px !important;
}*/

.advancedDemo .dropzone .itemlist > li {
    background-color: #017cff;
    border: none;
    border-radius: .25em;
    color: #fff;
    float: left;
    font-weight: 700;
    height: 40px;
    margin-right: 10px;
    padding: 10px;
    text-align: center;
    width:120px;
    margin-top:5px;
}

.advancedDemo .dropzone .itemlist > li:hover {
   cursor: pointer;
}

.advancedDemo .dropzone .container-element {
    background-color:#fff !important;
}

.advancedDemo .dropzone{
    background-color:#fff !important;
}

.trashcan ul{
    list-style: none;
    padding-left: 0px;
}

.trashcan .dndPlaceholder {
    display: none;
}

</style>
@endsection

@section('breadcrumb_nav')
 <ul class="arrows">
     <li class="li1"><a href="#">Home</a></li>
     <li class="li2"><a href="#" >Settings</a></li>
  </ul>
@endsection

@section('content')

<div class="row clearfix" ng-controller="settingsController">

	<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
		<h4>General</h4>
	</div>

	<!-- SELECT TIMEZONE -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		<div class="card">
			<div class="body">
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<label>Timezone</label>
						<P>
							Set your time zone so that your documents have the correct date<br>
							get assigned.
						</P>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 notify">
						<span class="pull-right text-right">
							<p class="">Standard: Europe/Paris</p>
							<select class="form-control t-zone" id="sel1" style="width:270px; font-weight:bold"  data-placement-from="bottom" data-placement-align="right" data-animate-enter="" data-animate-exit="" data-color-name="bg-blue">
								<option value="0" disabled>Please, select timezone</option>
								<option value="{{ $timezone->user_timezone }}" selected disabled>{{ $timezone->user_timezone }}</option>
								<option value="Africa/Abidjan">
								UTC/GMT +00:00 - Africa/Abidjan      </option>
								<option value="Africa/Accra">
								UTC/GMT +00:00 - Africa/Accra      </option>
								<option value="Africa/Addis_Ababa">
								UTC/GMT +03:00 - Africa/Addis_Ababa      </option>
								<option value="Africa/Algiers">
								UTC/GMT +01:00 - Africa/Algiers      </option>
								<option value="Africa/Asmara">
								UTC/GMT +03:00 - Africa/Asmara      </option>
								<option value="Africa/Bamako">
								UTC/GMT +00:00 - Africa/Bamako      </option>
								<option value="Africa/Bangui">
								UTC/GMT +01:00 - Africa/Bangui      </option>
								<option value="Africa/Banjul">
								UTC/GMT +00:00 - Africa/Banjul      </option>
								<option value="Africa/Bissau">
								UTC/GMT +00:00 - Africa/Bissau      </option>
								<option value="Africa/Blantyre">
								UTC/GMT +02:00 - Africa/Blantyre      </option>
								<option value="Africa/Brazzaville">
								UTC/GMT +01:00 - Africa/Brazzaville      </option>
								<option value="Africa/Bujumbura">
								UTC/GMT +02:00 - Africa/Bujumbura      </option>
								<option value="Africa/Cairo">
								UTC/GMT +02:00 - Africa/Cairo      </option>
								<option value="Africa/Casablanca">
								UTC/GMT +01:00 - Africa/Casablanca      </option>
								<option value="Africa/Ceuta">
								UTC/GMT +02:00 - Africa/Ceuta      </option>
								<option value="Africa/Conakry">
								UTC/GMT +00:00 - Africa/Conakry      </option>
								<option value="Africa/Dakar">
								UTC/GMT +00:00 - Africa/Dakar      </option>
								<option value="Africa/Dar_es_Salaam">
								UTC/GMT +03:00 - Africa/Dar_es_Salaam      </option>
								<option value="Africa/Djibouti">
								UTC/GMT +03:00 - Africa/Djibouti      </option>
								<option value="Africa/Douala">
								UTC/GMT +01:00 - Africa/Douala      </option>
								<option value="Africa/El_Aaiun">
								UTC/GMT +01:00 - Africa/El_Aaiun      </option>
								<option value="Africa/Freetown">
								UTC/GMT +00:00 - Africa/Freetown      </option>
								<option value="Africa/Gaborone">
								UTC/GMT +02:00 - Africa/Gaborone      </option>
								<option value="Africa/Harare">
								UTC/GMT +02:00 - Africa/Harare      </option>
								<option value="Africa/Johannesburg">
								UTC/GMT +02:00 - Africa/Johannesburg      </option>
								<option value="Africa/Juba">
								UTC/GMT +03:00 - Africa/Juba      </option>
								<option value="Africa/Kampala">
								UTC/GMT +03:00 - Africa/Kampala      </option>
								<option value="Africa/Khartoum">
								UTC/GMT +02:00 - Africa/Khartoum      </option>
								<option value="Africa/Kigali">
								UTC/GMT +02:00 - Africa/Kigali      </option>
								<option value="Africa/Kinshasa">
								UTC/GMT +01:00 - Africa/Kinshasa      </option>
								<option value="Africa/Lagos">
								UTC/GMT +01:00 - Africa/Lagos      </option>
								<option value="Africa/Libreville">
								UTC/GMT +01:00 - Africa/Libreville      </option>
								<option value="Africa/Lome">
								UTC/GMT +00:00 - Africa/Lome      </option>
								<option value="Africa/Luanda">
								UTC/GMT +01:00 - Africa/Luanda      </option>
								<option value="Africa/Lubumbashi">
								UTC/GMT +02:00 - Africa/Lubumbashi      </option>
								<option value="Africa/Lusaka">
								UTC/GMT +02:00 - Africa/Lusaka      </option>
								<option value="Africa/Malabo">
								UTC/GMT +01:00 - Africa/Malabo      </option>
								<option value="Africa/Maputo">
								UTC/GMT +02:00 - Africa/Maputo      </option>
								<option value="Africa/Maseru">
								UTC/GMT +02:00 - Africa/Maseru      </option>
								<option value="Africa/Mbabane">
								UTC/GMT +02:00 - Africa/Mbabane      </option>
								<option value="Africa/Mogadishu">
								UTC/GMT +03:00 - Africa/Mogadishu      </option>
								<option value="Africa/Monrovia">
								UTC/GMT +00:00 - Africa/Monrovia      </option>
								<option value="Africa/Nairobi">
								UTC/GMT +03:00 - Africa/Nairobi      </option>
								<option value="Africa/Ndjamena">
								UTC/GMT +01:00 - Africa/Ndjamena      </option>
								<option value="Africa/Niamey">
								UTC/GMT +01:00 - Africa/Niamey      </option>
								<option value="Africa/Nouakchott">
								UTC/GMT +00:00 - Africa/Nouakchott      </option>
								<option value="Africa/Ouagadougou">
								UTC/GMT +00:00 - Africa/Ouagadougou      </option>
								<option value="Africa/Porto-Novo">
								UTC/GMT +01:00 - Africa/Porto-Novo      </option>
								<option value="Africa/Sao_Tome">
								UTC/GMT +00:00 - Africa/Sao_Tome      </option>
								<option value="Africa/Tripoli">
								UTC/GMT +02:00 - Africa/Tripoli      </option>
								<option value="Africa/Tunis">
								UTC/GMT +01:00 - Africa/Tunis      </option>
								<option value="Africa/Windhoek">
								UTC/GMT +02:00 - Africa/Windhoek      </option>
								<option value="America/Adak">
								UTC/GMT -09:00 - America/Adak      </option>
								<option value="America/Anchorage">
								UTC/GMT -08:00 - America/Anchorage      </option>
								<option value="America/Anguilla">
								UTC/GMT -04:00 - America/Anguilla      </option>
								<option value="America/Antigua">
								UTC/GMT -04:00 - America/Antigua      </option>
								<option value="America/Araguaina">
								UTC/GMT -03:00 - America/Araguaina      </option>
								<option value="America/Argentina/Buenos_Aires">
								UTC/GMT -03:00 - America/Argentina/Buenos_Aires      </option>
								<option value="America/Argentina/Catamarca">
								UTC/GMT -03:00 - America/Argentina/Catamarca      </option>
								<option value="America/Argentina/Cordoba">
								UTC/GMT -03:00 - America/Argentina/Cordoba      </option>
								<option value="America/Argentina/Jujuy">
								UTC/GMT -03:00 - America/Argentina/Jujuy      </option>
								<option value="America/Argentina/La_Rioja">
								UTC/GMT -03:00 - America/Argentina/La_Rioja      </option>
								<option value="America/Argentina/Mendoza">
								UTC/GMT -03:00 - America/Argentina/Mendoza      </option>
								<option value="America/Argentina/Rio_Gallegos">
								UTC/GMT -03:00 - America/Argentina/Rio_Gallegos      </option>
								<option value="America/Argentina/Salta">
								UTC/GMT -03:00 - America/Argentina/Salta      </option>
								<option value="America/Argentina/San_Juan">
								UTC/GMT -03:00 - America/Argentina/San_Juan      </option>
								<option value="America/Argentina/San_Luis">
								UTC/GMT -03:00 - America/Argentina/San_Luis      </option>
								<option value="America/Argentina/Tucuman">
								UTC/GMT -03:00 - America/Argentina/Tucuman      </option>
								<option value="America/Argentina/Ushuaia">
								UTC/GMT -03:00 - America/Argentina/Ushuaia      </option>
								<option value="America/Aruba">
								UTC/GMT -04:00 - America/Aruba      </option>
								<option value="America/Asuncion">
								UTC/GMT -04:00 - America/Asuncion      </option>
								<option value="America/Atikokan">
								UTC/GMT -05:00 - America/Atikokan      </option>
								<option value="America/Bahia">
								UTC/GMT -03:00 - America/Bahia      </option>
								<option value="America/Bahia_Banderas">
								UTC/GMT -05:00 - America/Bahia_Banderas      </option>
								<option value="America/Barbados">
								UTC/GMT -04:00 - America/Barbados      </option>
								<option value="America/Belem">
								UTC/GMT -03:00 - America/Belem      </option>
								<option value="America/Belize">
								UTC/GMT -06:00 - America/Belize      </option>
								<option value="America/Blanc-Sablon">
								UTC/GMT -04:00 - America/Blanc-Sablon      </option>
								<option value="America/Boa_Vista">
								UTC/GMT -04:00 - America/Boa_Vista      </option>
								<option value="America/Bogota">
								UTC/GMT -05:00 - America/Bogota      </option>
								<option value="America/Boise">
								UTC/GMT -06:00 - America/Boise      </option>
								<option value="America/Cambridge_Bay">
								UTC/GMT -06:00 - America/Cambridge_Bay      </option>
								<option value="America/Campo_Grande">
								UTC/GMT -04:00 - America/Campo_Grande      </option>
								<option value="America/Cancun">
								UTC/GMT -05:00 - America/Cancun      </option>
								<option value="America/Caracas">
								UTC/GMT -04:00 - America/Caracas      </option>
								<option value="America/Cayenne">
								UTC/GMT -03:00 - America/Cayenne      </option>
								<option value="America/Cayman">
								UTC/GMT -05:00 - America/Cayman      </option>
								<option value="America/Chicago">
								UTC/GMT -05:00 - America/Chicago      </option>
								<option value="America/Chihuahua">
								UTC/GMT -06:00 - America/Chihuahua      </option>
								<option value="America/Costa_Rica">
								UTC/GMT -06:00 - America/Costa_Rica      </option>
								<option value="America/Creston">
								UTC/GMT -07:00 - America/Creston      </option>
								<option value="America/Cuiaba">
								UTC/GMT -04:00 - America/Cuiaba      </option>
								<option value="America/Curacao">
								UTC/GMT -04:00 - America/Curacao      </option>
								<option value="America/Danmarkshavn">
								UTC/GMT +00:00 - America/Danmarkshavn      </option>
								<option value="America/Dawson">
								UTC/GMT -07:00 - America/Dawson      </option>
								<option value="America/Dawson_Creek">
								UTC/GMT -07:00 - America/Dawson_Creek      </option>
								<option value="America/Denver">
								UTC/GMT -06:00 - America/Denver      </option>
								<option value="America/Detroit">
								UTC/GMT -04:00 - America/Detroit      </option>
								<option value="America/Dominica">
								UTC/GMT -04:00 - America/Dominica      </option>
								<option value="America/Edmonton">
								UTC/GMT -06:00 - America/Edmonton      </option>
								<option value="America/Eirunepe">
								UTC/GMT -05:00 - America/Eirunepe      </option>
								<option value="America/El_Salvador">
								UTC/GMT -06:00 - America/El_Salvador      </option>
								<option value="America/Fort_Nelson">
								UTC/GMT -07:00 - America/Fort_Nelson      </option>
								<option value="America/Fortaleza">
								UTC/GMT -03:00 - America/Fortaleza      </option>
								<option value="America/Glace_Bay">
								UTC/GMT -03:00 - America/Glace_Bay      </option>
								<option value="America/Godthab">
								UTC/GMT -02:00 - America/Godthab      </option>
								<option value="America/Goose_Bay">
								UTC/GMT -03:00 - America/Goose_Bay      </option>
								<option value="America/Grand_Turk">
								UTC/GMT -04:00 - America/Grand_Turk      </option>
								<option value="America/Grenada">
								UTC/GMT -04:00 - America/Grenada      </option>
								<option value="America/Guadeloupe">
								UTC/GMT -04:00 - America/Guadeloupe      </option>
								<option value="America/Guatemala">
								UTC/GMT -06:00 - America/Guatemala      </option>
								<option value="America/Guayaquil">
								UTC/GMT -05:00 - America/Guayaquil      </option>
								<option value="America/Guyana">
								UTC/GMT -04:00 - America/Guyana      </option>
								<option value="America/Halifax">
								UTC/GMT -03:00 - America/Halifax      </option>
								<option value="America/Havana">
								UTC/GMT -04:00 - America/Havana      </option>
								<option value="America/Hermosillo">
								UTC/GMT -07:00 - America/Hermosillo      </option>
								<option value="America/Indiana/Indianapolis">
								UTC/GMT -04:00 - America/Indiana/Indianapolis      </option>
								<option value="America/Indiana/Knox">
								UTC/GMT -05:00 - America/Indiana/Knox      </option>
								<option value="America/Indiana/Marengo">
								UTC/GMT -04:00 - America/Indiana/Marengo      </option>
								<option value="America/Indiana/Petersburg">
								UTC/GMT -04:00 - America/Indiana/Petersburg      </option>
								<option value="America/Indiana/Tell_City">
								UTC/GMT -05:00 - America/Indiana/Tell_City      </option>
								<option value="America/Indiana/Vevay">
								UTC/GMT -04:00 - America/Indiana/Vevay      </option>
								<option value="America/Indiana/Vincennes">
								UTC/GMT -04:00 - America/Indiana/Vincennes      </option>
								<option value="America/Indiana/Winamac">
								UTC/GMT -04:00 - America/Indiana/Winamac      </option>
								<option value="America/Inuvik">
								UTC/GMT -06:00 - America/Inuvik      </option>
								<option value="America/Iqaluit">
								UTC/GMT -04:00 - America/Iqaluit      </option>
								<option value="America/Jamaica">
								UTC/GMT -05:00 - America/Jamaica      </option>
								<option value="America/Juneau">
								UTC/GMT -08:00 - America/Juneau      </option>
								<option value="America/Kentucky/Louisville">
								UTC/GMT -04:00 - America/Kentucky/Louisville      </option>
								<option value="America/Kentucky/Monticello">
								UTC/GMT -04:00 - America/Kentucky/Monticello      </option>
								<option value="America/Kralendijk">
								UTC/GMT -04:00 - America/Kralendijk      </option>
								<option value="America/La_Paz">
								UTC/GMT -04:00 - America/La_Paz      </option>
								<option value="America/Lima">
								UTC/GMT -05:00 - America/Lima      </option>
								<option value="America/Los_Angeles">
								UTC/GMT -07:00 - America/Los_Angeles      </option>
								<option value="America/Lower_Princes">
								UTC/GMT -04:00 - America/Lower_Princes      </option>
								<option value="America/Maceio">
								UTC/GMT -03:00 - America/Maceio      </option>
								<option value="America/Managua">
								UTC/GMT -06:00 - America/Managua      </option>
								<option value="America/Manaus">
								UTC/GMT -04:00 - America/Manaus      </option>
								<option value="America/Marigot">
								UTC/GMT -04:00 - America/Marigot      </option>
								<option value="America/Martinique">
								UTC/GMT -04:00 - America/Martinique      </option>
								<option value="America/Matamoros">
								UTC/GMT -05:00 - America/Matamoros      </option>
								<option value="America/Mazatlan">
								UTC/GMT -06:00 - America/Mazatlan      </option>
								<option value="America/Menominee">
								UTC/GMT -05:00 - America/Menominee      </option>
								<option value="America/Merida">
								UTC/GMT -05:00 - America/Merida      </option>
								<option value="America/Metlakatla">
								UTC/GMT -08:00 - America/Metlakatla      </option>
								<option value="America/Mexico_City">
								UTC/GMT -05:00 - America/Mexico_City      </option>
								<option value="America/Miquelon">
								UTC/GMT -02:00 - America/Miquelon      </option>
								<option value="America/Moncton">
								UTC/GMT -03:00 - America/Moncton      </option>
								<option value="America/Monterrey">
								UTC/GMT -05:00 - America/Monterrey      </option>
								<option value="America/Montevideo">
								UTC/GMT -03:00 - America/Montevideo      </option>
								<option value="America/Montserrat">
								UTC/GMT -04:00 - America/Montserrat      </option>
								<option value="America/Nassau">
								UTC/GMT -04:00 - America/Nassau      </option>
								<option value="America/New_York">
								UTC/GMT -04:00 - America/New_York      </option>
								<option value="America/Nipigon">
								UTC/GMT -04:00 - America/Nipigon      </option>
								<option value="America/Nome">
								UTC/GMT -08:00 - America/Nome      </option>
								<option value="America/Noronha">
								UTC/GMT -02:00 - America/Noronha      </option>
								<option value="America/North_Dakota/Beulah">
								UTC/GMT -05:00 - America/North_Dakota/Beulah      </option>
								<option value="America/North_Dakota/Center">
								UTC/GMT -05:00 - America/North_Dakota/Center      </option>
								<option value="America/North_Dakota/New_Salem">
								UTC/GMT -05:00 - America/North_Dakota/New_Salem      </option>
								<option value="America/Ojinaga">
								UTC/GMT -06:00 - America/Ojinaga      </option>
								<option value="America/Panama">
								UTC/GMT -05:00 - America/Panama      </option>
								<option value="America/Pangnirtung">
								UTC/GMT -04:00 - America/Pangnirtung      </option>
								<option value="America/Paramaribo">
								UTC/GMT -03:00 - America/Paramaribo      </option>
								<option value="America/Phoenix">
								UTC/GMT -07:00 - America/Phoenix      </option>
								<option value="America/Port-au-Prince">
								UTC/GMT -04:00 - America/Port-au-Prince      </option>
								<option value="America/Port_of_Spain">
								UTC/GMT -04:00 - America/Port_of_Spain      </option>
								<option value="America/Porto_Velho">
								UTC/GMT -04:00 - America/Porto_Velho      </option>
								<option value="America/Puerto_Rico">
								UTC/GMT -04:00 - America/Puerto_Rico      </option>
								<option value="America/Punta_Arenas">
								UTC/GMT -03:00 - America/Punta_Arenas      </option>
								<option value="America/Rainy_River">
								UTC/GMT -05:00 - America/Rainy_River      </option>
								<option value="America/Rankin_Inlet">
								UTC/GMT -05:00 - America/Rankin_Inlet      </option>
								<option value="America/Recife">
								UTC/GMT -03:00 - America/Recife      </option>
								<option value="America/Regina">
								UTC/GMT -06:00 - America/Regina      </option>
								<option value="America/Resolute">
								UTC/GMT -05:00 - America/Resolute      </option>
								<option value="America/Rio_Branco">
								UTC/GMT -05:00 - America/Rio_Branco      </option>
								<option value="America/Santarem">
								UTC/GMT -03:00 - America/Santarem      </option>
								<option value="America/Santiago">
								UTC/GMT -04:00 - America/Santiago      </option>
								<option value="America/Santo_Domingo">
								UTC/GMT -04:00 - America/Santo_Domingo      </option>
								<option value="America/Sao_Paulo">
								UTC/GMT -03:00 - America/Sao_Paulo      </option>
								<option value="America/Scoresbysund">
								UTC/GMT +00:00 - America/Scoresbysund      </option>
								<option value="America/Sitka">
								UTC/GMT -08:00 - America/Sitka      </option>
								<option value="America/St_Barthelemy">
								UTC/GMT -04:00 - America/St_Barthelemy      </option>
								<option value="America/St_Johns">
								UTC/GMT -02:30 - America/St_Johns      </option>
								<option value="America/St_Kitts">
								UTC/GMT -04:00 - America/St_Kitts      </option>
								<option value="America/St_Lucia">
								UTC/GMT -04:00 - America/St_Lucia      </option>
								<option value="America/St_Thomas">
								UTC/GMT -04:00 - America/St_Thomas      </option>
								<option value="America/St_Vincent">
								UTC/GMT -04:00 - America/St_Vincent      </option>
								<option value="America/Swift_Current">
								UTC/GMT -06:00 - America/Swift_Current      </option>
								<option value="America/Tegucigalpa">
								UTC/GMT -06:00 - America/Tegucigalpa      </option>
								<option value="America/Thule">
								UTC/GMT -03:00 - America/Thule      </option>
								<option value="America/Thunder_Bay">
								UTC/GMT -04:00 - America/Thunder_Bay      </option>
								<option value="America/Tijuana">
								UTC/GMT -07:00 - America/Tijuana      </option>
								<option value="America/Toronto">
								UTC/GMT -04:00 - America/Toronto      </option>
								<option value="America/Tortola">
								UTC/GMT -04:00 - America/Tortola      </option>
								<option value="America/Vancouver">
								UTC/GMT -07:00 - America/Vancouver      </option>
								<option value="America/Whitehorse">
								UTC/GMT -07:00 - America/Whitehorse      </option>
								<option value="America/Winnipeg">
								UTC/GMT -05:00 - America/Winnipeg      </option>
								<option value="America/Yakutat">
								UTC/GMT -08:00 - America/Yakutat      </option>
								<option value="America/Yellowknife">
								UTC/GMT -06:00 - America/Yellowknife      </option>
								<option value="Antarctica/Casey">
								UTC/GMT +11:00 - Antarctica/Casey      </option>
								<option value="Antarctica/Davis">
								UTC/GMT +07:00 - Antarctica/Davis      </option>
								<option value="Antarctica/DumontDUrville">
								UTC/GMT +10:00 - Antarctica/DumontDUrville      </option>
								<option value="Antarctica/Macquarie">
								UTC/GMT +11:00 - Antarctica/Macquarie      </option>
								<option value="Antarctica/Mawson">
								UTC/GMT +05:00 - Antarctica/Mawson      </option>
								<option value="Antarctica/McMurdo">
								UTC/GMT +12:00 - Antarctica/McMurdo      </option>
								<option value="Antarctica/Palmer">
								UTC/GMT -03:00 - Antarctica/Palmer      </option>
								<option value="Antarctica/Rothera">
								UTC/GMT -03:00 - Antarctica/Rothera      </option>
								<option value="Antarctica/Syowa">
								UTC/GMT +03:00 - Antarctica/Syowa      </option>
								<option value="Antarctica/Troll">
								UTC/GMT +02:00 - Antarctica/Troll      </option>
								<option value="Antarctica/Vostok">
								UTC/GMT +06:00 - Antarctica/Vostok      </option>
								<option value="Arctic/Longyearbyen">
								UTC/GMT +02:00 - Arctic/Longyearbyen      </option>
								<option value="Asia/Aden">
								UTC/GMT +03:00 - Asia/Aden      </option>
								<option value="Asia/Almaty">
								UTC/GMT +06:00 - Asia/Almaty      </option>
								<option value="Asia/Amman">
								UTC/GMT +03:00 - Asia/Amman      </option>
								<option value="Asia/Anadyr">
								UTC/GMT +12:00 - Asia/Anadyr      </option>
								<option value="Asia/Aqtau">
								UTC/GMT +05:00 - Asia/Aqtau      </option>
								<option value="Asia/Aqtobe">
								UTC/GMT +05:00 - Asia/Aqtobe      </option>
								<option value="Asia/Ashgabat">
								UTC/GMT +05:00 - Asia/Ashgabat      </option>
								<option value="Asia/Atyrau">
								UTC/GMT +05:00 - Asia/Atyrau      </option>
								<option value="Asia/Baghdad">
								UTC/GMT +03:00 - Asia/Baghdad      </option>
								<option value="Asia/Bahrain">
								UTC/GMT +03:00 - Asia/Bahrain      </option>
								<option value="Asia/Baku">
								UTC/GMT +04:00 - Asia/Baku      </option>
								<option value="Asia/Bangkok">
								UTC/GMT +07:00 - Asia/Bangkok      </option>
								<option value="Asia/Barnaul">
								UTC/GMT +07:00 - Asia/Barnaul      </option>
								<option value="Asia/Beirut">
								UTC/GMT +03:00 - Asia/Beirut      </option>
								<option value="Asia/Bishkek">
								UTC/GMT +06:00 - Asia/Bishkek      </option>
								<option value="Asia/Brunei">
								UTC/GMT +08:00 - Asia/Brunei      </option>
								<option value="Asia/Chita">
								UTC/GMT +09:00 - Asia/Chita      </option>
								<option value="Asia/Choibalsan">
								UTC/GMT +08:00 - Asia/Choibalsan      </option>
								<option value="Asia/Colombo">
								UTC/GMT +05:30 - Asia/Colombo      </option>
								<option value="Asia/Damascus">
								UTC/GMT +03:00 - Asia/Damascus      </option>
								<option value="Asia/Dhaka">
								UTC/GMT +06:00 - Asia/Dhaka      </option>
								<option value="Asia/Dili">
								UTC/GMT +09:00 - Asia/Dili      </option>
								<option value="Asia/Dubai">
								UTC/GMT +04:00 - Asia/Dubai      </option>
								<option value="Asia/Dushanbe">
								UTC/GMT +05:00 - Asia/Dushanbe      </option>
								<option value="Asia/Famagusta">
								UTC/GMT +03:00 - Asia/Famagusta      </option>
								<option value="Asia/Gaza">
								UTC/GMT +03:00 - Asia/Gaza      </option>
								<option value="Asia/Hebron">
								UTC/GMT +03:00 - Asia/Hebron      </option>
								<option value="Asia/Ho_Chi_Minh">
								UTC/GMT +07:00 - Asia/Ho_Chi_Minh      </option>
								<option value="Asia/Hong_Kong">
								UTC/GMT +08:00 - Asia/Hong_Kong      </option>
								<option value="Asia/Hovd">
								UTC/GMT +07:00 - Asia/Hovd      </option>
								<option value="Asia/Irkutsk">
								UTC/GMT +08:00 - Asia/Irkutsk      </option>
								<option value="Asia/Jakarta">
								UTC/GMT +07:00 - Asia/Jakarta      </option>
								<option value="Asia/Jayapura">
								UTC/GMT +09:00 - Asia/Jayapura      </option>
								<option value="Asia/Jerusalem">
								UTC/GMT +03:00 - Asia/Jerusalem      </option>
								<option value="Asia/Kabul">
								UTC/GMT +04:30 - Asia/Kabul      </option>
								<option value="Asia/Kamchatka">
								UTC/GMT +12:00 - Asia/Kamchatka      </option>
								<option value="Asia/Karachi">
								UTC/GMT +05:00 - Asia/Karachi      </option>
								<option value="Asia/Kathmandu">
								UTC/GMT +05:45 - Asia/Kathmandu      </option>
								<option value="Asia/Khandyga">
								UTC/GMT +09:00 - Asia/Khandyga      </option>
								<option value="Asia/Kolkata">
								UTC/GMT +05:30 - Asia/Kolkata      </option>
								<option value="Asia/Krasnoyarsk">
								UTC/GMT +07:00 - Asia/Krasnoyarsk      </option>
								<option value="Asia/Kuala_Lumpur">
								UTC/GMT +08:00 - Asia/Kuala_Lumpur      </option>
								<option value="Asia/Kuching">
								UTC/GMT +08:00 - Asia/Kuching      </option>
								<option value="Asia/Kuwait">
								UTC/GMT +03:00 - Asia/Kuwait      </option>
								<option value="Asia/Macau">
								UTC/GMT +08:00 - Asia/Macau      </option>
								<option value="Asia/Magadan">
								UTC/GMT +11:00 - Asia/Magadan      </option>
								<option value="Asia/Makassar">
								UTC/GMT +08:00 - Asia/Makassar      </option>
								<option value="Asia/Manila">
								UTC/GMT +08:00 - Asia/Manila      </option>
								<option value="Asia/Muscat">
								UTC/GMT +04:00 - Asia/Muscat      </option>
								<option value="Asia/Nicosia">
								UTC/GMT +03:00 - Asia/Nicosia      </option>
								<option value="Asia/Novokuznetsk">
								UTC/GMT +07:00 - Asia/Novokuznetsk      </option>
								<option value="Asia/Novosibirsk">
								UTC/GMT +07:00 - Asia/Novosibirsk      </option>
								<option value="Asia/Omsk">
								UTC/GMT +06:00 - Asia/Omsk      </option>
								<option value="Asia/Oral">
								UTC/GMT +05:00 - Asia/Oral      </option>
								<option value="Asia/Phnom_Penh">
								UTC/GMT +07:00 - Asia/Phnom_Penh      </option>
								<option value="Asia/Pontianak">
								UTC/GMT +07:00 - Asia/Pontianak      </option>
								<option value="Asia/Pyongyang">
								UTC/GMT +08:30 - Asia/Pyongyang      </option>
								<option value="Asia/Qatar">
								UTC/GMT +03:00 - Asia/Qatar      </option>
								<option value="Asia/Qyzylorda">
								UTC/GMT +06:00 - Asia/Qyzylorda      </option>
								<option value="Asia/Riyadh">
								UTC/GMT +03:00 - Asia/Riyadh      </option>
								<option value="Asia/Sakhalin">
								UTC/GMT +11:00 - Asia/Sakhalin      </option>
								<option value="Asia/Samarkand">
								UTC/GMT +05:00 - Asia/Samarkand      </option>
								<option value="Asia/Seoul">
								UTC/GMT +09:00 - Asia/Seoul      </option>
								<option value="Asia/Shanghai">
								UTC/GMT +08:00 - Asia/Shanghai      </option>
								<option value="Asia/Singapore">
								UTC/GMT +08:00 - Asia/Singapore      </option>
								<option value="Asia/Srednekolymsk">
								UTC/GMT +11:00 - Asia/Srednekolymsk      </option>
								<option value="Asia/Taipei">
								UTC/GMT +08:00 - Asia/Taipei      </option>
								<option value="Asia/Tashkent">
								UTC/GMT +05:00 - Asia/Tashkent      </option>
								<option value="Asia/Tbilisi">
								UTC/GMT +04:00 - Asia/Tbilisi      </option>
								<option value="Asia/Tehran">
								UTC/GMT +04:30 - Asia/Tehran      </option>
								<option value="Asia/Thimphu">
								UTC/GMT +06:00 - Asia/Thimphu      </option>
								<option value="Asia/Tokyo">
								UTC/GMT +09:00 - Asia/Tokyo      </option>
								<option value="Asia/Tomsk">
								UTC/GMT +07:00 - Asia/Tomsk      </option>
								<option value="Asia/Ulaanbaatar">
								UTC/GMT +08:00 - Asia/Ulaanbaatar      </option>
								<option value="Asia/Urumqi">
								UTC/GMT +06:00 - Asia/Urumqi      </option>
								<option value="Asia/Ust-Nera">
								UTC/GMT +10:00 - Asia/Ust-Nera      </option>
								<option value="Asia/Vientiane">
								UTC/GMT +07:00 - Asia/Vientiane      </option>
								<option value="Asia/Vladivostok">
								UTC/GMT +10:00 - Asia/Vladivostok      </option>
								<option value="Asia/Yakutsk">
								UTC/GMT +09:00 - Asia/Yakutsk      </option>
								<option value="Asia/Yangon">
								UTC/GMT +06:30 - Asia/Yangon      </option>
								<option value="Asia/Yekaterinburg">
								UTC/GMT +05:00 - Asia/Yekaterinburg      </option>
								<option value="Asia/Yerevan">
								UTC/GMT +04:00 - Asia/Yerevan      </option>
								<option value="Atlantic/Azores">
								UTC/GMT +00:00 - Atlantic/Azores      </option>
								<option value="Atlantic/Bermuda">
								UTC/GMT -03:00 - Atlantic/Bermuda      </option>
								<option value="Atlantic/Canary">
								UTC/GMT +01:00 - Atlantic/Canary      </option>
								<option value="Atlantic/Cape_Verde">
								UTC/GMT -01:00 - Atlantic/Cape_Verde      </option>
								<option value="Atlantic/Faroe">
								UTC/GMT +01:00 - Atlantic/Faroe      </option>
								<option value="Atlantic/Madeira">
								UTC/GMT +01:00 - Atlantic/Madeira      </option>
								<option value="Atlantic/Reykjavik">
								UTC/GMT +00:00 - Atlantic/Reykjavik      </option>
								<option value="Atlantic/South_Georgia">
								UTC/GMT -02:00 - Atlantic/South_Georgia      </option>
								<option value="Atlantic/St_Helena">
								UTC/GMT +00:00 - Atlantic/St_Helena      </option>
								<option value="Atlantic/Stanley">
								UTC/GMT -03:00 - Atlantic/Stanley      </option>
								<option value="Australia/Adelaide">
								UTC/GMT +09:30 - Australia/Adelaide      </option>
								<option value="Australia/Brisbane">
								UTC/GMT +10:00 - Australia/Brisbane      </option>
								<option value="Australia/Broken_Hill">
								UTC/GMT +09:30 - Australia/Broken_Hill      </option>
								<option value="Australia/Currie">
								UTC/GMT +10:00 - Australia/Currie      </option>
								<option value="Australia/Darwin">
								UTC/GMT +09:30 - Australia/Darwin      </option>
								<option value="Australia/Eucla">
								UTC/GMT +08:45 - Australia/Eucla      </option>
								<option value="Australia/Hobart">
								UTC/GMT +10:00 - Australia/Hobart      </option>
								<option value="Australia/Lindeman">
								UTC/GMT +10:00 - Australia/Lindeman      </option>
								<option value="Australia/Lord_Howe">
								UTC/GMT +10:30 - Australia/Lord_Howe      </option>
								<option value="Australia/Melbourne">
								UTC/GMT +10:00 - Australia/Melbourne      </option>
								<option value="Australia/Perth">
								UTC/GMT +08:00 - Australia/Perth      </option>
								<option value="Australia/Sydney">
								UTC/GMT +10:00 - Australia/Sydney      </option>
								<option value="Europe/Amsterdam">
								UTC/GMT +02:00 - Europe/Amsterdam      </option>
								<option value="Europe/Andorra">
								UTC/GMT +02:00 - Europe/Andorra      </option>
								<option value="Europe/Astrakhan">
								UTC/GMT +04:00 - Europe/Astrakhan      </option>
								<option value="Europe/Athens">
								UTC/GMT +03:00 - Europe/Athens      </option>
								<option value="Europe/Belgrade">
								UTC/GMT +02:00 - Europe/Belgrade      </option>
								<option value="Europe/Berlin">
								UTC/GMT +02:00 - Europe/Berlin      </option>
								<option value="Europe/Bratislava">
								UTC/GMT +02:00 - Europe/Bratislava      </option>
								<option value="Europe/Brussels">
								UTC/GMT +02:00 - Europe/Brussels      </option>
								<option value="Europe/Bucharest">
								UTC/GMT +03:00 - Europe/Bucharest      </option>
								<option value="Europe/Budapest">
								UTC/GMT +02:00 - Europe/Budapest      </option>
								<option value="Europe/Busingen">
								UTC/GMT +02:00 - Europe/Busingen      </option>
								<option value="Europe/Chisinau">
								UTC/GMT +03:00 - Europe/Chisinau      </option>
								<option value="Europe/Copenhagen">
								UTC/GMT +02:00 - Europe/Copenhagen      </option>
								<option value="Europe/Dublin">
								UTC/GMT +01:00 - Europe/Dublin      </option>
								<option value="Europe/Gibraltar">
								UTC/GMT +02:00 - Europe/Gibraltar      </option>
								<option value="Europe/Guernsey">
								UTC/GMT +01:00 - Europe/Guernsey      </option>
								<option value="Europe/Helsinki">
								UTC/GMT +03:00 - Europe/Helsinki      </option>
								<option value="Europe/Isle_of_Man">
								UTC/GMT +01:00 - Europe/Isle_of_Man      </option>
								<option value="Europe/Istanbul">
								UTC/GMT +03:00 - Europe/Istanbul      </option>
								<option value="Europe/Jersey">
								UTC/GMT +01:00 - Europe/Jersey      </option>
								<option value="Europe/Kaliningrad">
								UTC/GMT +02:00 - Europe/Kaliningrad      </option>
								<option value="Europe/Kiev">
								UTC/GMT +03:00 - Europe/Kiev      </option>
								<option value="Europe/Kirov">
								UTC/GMT +03:00 - Europe/Kirov      </option>
								<option value="Europe/Lisbon">
								UTC/GMT +01:00 - Europe/Lisbon      </option>
								<option value="Europe/Ljubljana">
								UTC/GMT +02:00 - Europe/Ljubljana      </option>
								<option value="Europe/London">
								UTC/GMT +01:00 - Europe/London      </option>
								<option value="Europe/Luxembourg">
								UTC/GMT +02:00 - Europe/Luxembourg      </option>
								<option value="Europe/Madrid">
								UTC/GMT +02:00 - Europe/Madrid      </option>
								<option value="Europe/Malta">
								UTC/GMT +02:00 - Europe/Malta      </option>
								<option value="Europe/Mariehamn">
								UTC/GMT +03:00 - Europe/Mariehamn      </option>
								<option value="Europe/Minsk">
								UTC/GMT +03:00 - Europe/Minsk      </option>
								<option value="Europe/Monaco">
								UTC/GMT +02:00 - Europe/Monaco      </option>
								<option value="Europe/Moscow">
								UTC/GMT +03:00 - Europe/Moscow      </option>
								<option value="Europe/Oslo">
								UTC/GMT +02:00 - Europe/Oslo      </option>
								<option value="Europe/Paris">
								UTC/GMT +02:00 - Europe/Paris      </option>
								<option value="Europe/Podgorica">
								UTC/GMT +02:00 - Europe/Podgorica      </option>
								<option value="Europe/Prague">
								UTC/GMT +02:00 - Europe/Prague      </option>
								<option value="Europe/Riga">
								UTC/GMT +03:00 - Europe/Riga      </option>
								<option value="Europe/Rome">
								UTC/GMT +02:00 - Europe/Rome      </option>
								<option value="Europe/Samara">
								UTC/GMT +04:00 - Europe/Samara      </option>
								<option value="Europe/San_Marino">
								UTC/GMT +02:00 - Europe/San_Marino      </option>
								<option value="Europe/Sarajevo">
								UTC/GMT +02:00 - Europe/Sarajevo      </option>
								<option value="Europe/Saratov">
								UTC/GMT +04:00 - Europe/Saratov      </option>
								<option value="Europe/Simferopol">
								UTC/GMT +03:00 - Europe/Simferopol      </option>
								<option value="Europe/Skopje">
								UTC/GMT +02:00 - Europe/Skopje      </option>
								<option value="Europe/Sofia">
								UTC/GMT +03:00 - Europe/Sofia      </option>
								<option value="Europe/Stockholm">
								UTC/GMT +02:00 - Europe/Stockholm      </option>
								<option value="Europe/Tallinn">
								UTC/GMT +03:00 - Europe/Tallinn      </option>
								<option value="Europe/Tirane">
								UTC/GMT +02:00 - Europe/Tirane      </option>
								<option value="Europe/Ulyanovsk">
								UTC/GMT +04:00 - Europe/Ulyanovsk      </option>
								<option value="Europe/Uzhgorod">
								UTC/GMT +03:00 - Europe/Uzhgorod      </option>
								<option value="Europe/Vaduz">
								UTC/GMT +02:00 - Europe/Vaduz      </option>
								<option value="Europe/Vatican">
								UTC/GMT +02:00 - Europe/Vatican      </option>
								<option value="Europe/Vienna">
								UTC/GMT +02:00 - Europe/Vienna      </option>
								<option value="Europe/Vilnius">
								UTC/GMT +03:00 - Europe/Vilnius      </option>
								<option value="Europe/Volgograd">
								UTC/GMT +03:00 - Europe/Volgograd      </option>
								<option value="Europe/Warsaw">
								UTC/GMT +02:00 - Europe/Warsaw      </option>
								<option value="Europe/Zagreb">
								UTC/GMT +02:00 - Europe/Zagreb      </option>
								<option value="Europe/Zaporozhye">
								UTC/GMT +03:00 - Europe/Zaporozhye      </option>
								<option value="Europe/Zurich">
								UTC/GMT +02:00 - Europe/Zurich      </option>
								<option value="Indian/Antananarivo">
								UTC/GMT +03:00 - Indian/Antananarivo      </option>
								<option value="Indian/Chagos">
								UTC/GMT +06:00 - Indian/Chagos      </option>
								<option value="Indian/Christmas">
								UTC/GMT +07:00 - Indian/Christmas      </option>
								<option value="Indian/Cocos">
								UTC/GMT +06:30 - Indian/Cocos      </option>
								<option value="Indian/Comoro">
								UTC/GMT +03:00 - Indian/Comoro      </option>
								<option value="Indian/Kerguelen">
								UTC/GMT +05:00 - Indian/Kerguelen      </option>
								<option value="Indian/Mahe">
								UTC/GMT +04:00 - Indian/Mahe      </option>
								<option value="Indian/Maldives">
								UTC/GMT +05:00 - Indian/Maldives      </option>
								<option value="Indian/Mauritius">
								UTC/GMT +04:00 - Indian/Mauritius      </option>
								<option value="Indian/Mayotte">
								UTC/GMT +03:00 - Indian/Mayotte      </option>
								<option value="Indian/Reunion">
								UTC/GMT +04:00 - Indian/Reunion      </option>
								<option value="Pacific/Apia">
								UTC/GMT +13:00 - Pacific/Apia      </option>
								<option value="Pacific/Auckland">
								UTC/GMT +12:00 - Pacific/Auckland      </option>
								<option value="Pacific/Bougainville">
								UTC/GMT +11:00 - Pacific/Bougainville      </option>
								<option value="Pacific/Chatham">
								UTC/GMT +12:45 - Pacific/Chatham      </option>
								<option value="Pacific/Chuuk">
								UTC/GMT +10:00 - Pacific/Chuuk      </option>
								<option value="Pacific/Easter">
								UTC/GMT -06:00 - Pacific/Easter      </option>
								<option value="Pacific/Efate">
								UTC/GMT +11:00 - Pacific/Efate      </option>
								<option value="Pacific/Enderbury">
								UTC/GMT +13:00 - Pacific/Enderbury      </option>
								<option value="Pacific/Fakaofo">
								UTC/GMT +13:00 - Pacific/Fakaofo      </option>
								<option value="Pacific/Fiji">
								UTC/GMT +12:00 - Pacific/Fiji      </option>
								<option value="Pacific/Funafuti">
								UTC/GMT +12:00 - Pacific/Funafuti      </option>
								<option value="Pacific/Galapagos">
								UTC/GMT -06:00 - Pacific/Galapagos      </option>
								<option value="Pacific/Gambier">
								UTC/GMT -09:00 - Pacific/Gambier      </option>
								<option value="Pacific/Guadalcanal">
								UTC/GMT +11:00 - Pacific/Guadalcanal      </option>
								<option value="Pacific/Guam">
								UTC/GMT +10:00 - Pacific/Guam      </option>
								<option value="Pacific/Honolulu">
								UTC/GMT -10:00 - Pacific/Honolulu      </option>
								<option value="Pacific/Kiritimati">
								UTC/GMT +14:00 - Pacific/Kiritimati      </option>
								<option value="Pacific/Kosrae">
								UTC/GMT +11:00 - Pacific/Kosrae      </option>
								<option value="Pacific/Kwajalein">
								UTC/GMT +12:00 - Pacific/Kwajalein      </option>
								<option value="Pacific/Majuro">
								UTC/GMT +12:00 - Pacific/Majuro      </option>
								<option value="Pacific/Marquesas">
								UTC/GMT -09:30 - Pacific/Marquesas      </option>
								<option value="Pacific/Midway">
								UTC/GMT -11:00 - Pacific/Midway      </option>
								<option value="Pacific/Nauru">
								UTC/GMT +12:00 - Pacific/Nauru      </option>
								<option value="Pacific/Niue">
								UTC/GMT -11:00 - Pacific/Niue      </option>
								<option value="Pacific/Norfolk">
								UTC/GMT +11:00 - Pacific/Norfolk      </option>
								<option value="Pacific/Noumea">
								UTC/GMT +11:00 - Pacific/Noumea      </option>
								<option value="Pacific/Pago_Pago">
								UTC/GMT -11:00 - Pacific/Pago_Pago      </option>
								<option value="Pacific/Palau">
								UTC/GMT +09:00 - Pacific/Palau      </option>
								<option value="Pacific/Pitcairn">
								UTC/GMT -08:00 - Pacific/Pitcairn      </option>
								<option value="Pacific/Pohnpei">
								UTC/GMT +11:00 - Pacific/Pohnpei      </option>
								<option value="Pacific/Port_Moresby">
								UTC/GMT +10:00 - Pacific/Port_Moresby      </option>
								<option value="Pacific/Rarotonga">
								UTC/GMT -10:00 - Pacific/Rarotonga      </option>
								<option value="Pacific/Saipan">
								UTC/GMT +10:00 - Pacific/Saipan      </option>
								<option value="Pacific/Tahiti">
								UTC/GMT -10:00 - Pacific/Tahiti      </option>
								<option value="Pacific/Tarawa">
								UTC/GMT +12:00 - Pacific/Tarawa      </option>
								<option value="Pacific/Tongatapu">
								UTC/GMT +13:00 - Pacific/Tongatapu      </option>
								<option value="Pacific/Wake">
								UTC/GMT +12:00 - Pacific/Wake      </option>
								<option value="Pacific/Wallis">
								UTC/GMT +12:00 - Pacific/Wallis      </option>
								<option value="UTC">
								UTC/GMT +00:00 - UTC      </option>
							</select>
						</span>
					</div>
				</div>
			</div>
		</div>

	</div>

    <!-- IMPORT EMAIL -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="body">
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<label>E-Mail Import</label>
						<P>
							Manage e-mail inboxes for automatic document import
						</P>
					</div>
					<a href="/imap/new_credential">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<span class="pull-right text-right">
								<i class="material-icons" style="font-size:50px; position:absolute; top:0; right:0">keyboard_arrow_right</i>
							</span><br>
						</div>
					</a>	
				</div>
			</div>
		</div>
	</div>

   <!-- FTP -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="body">
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<label>FTP Import documents</label>
						<P>
							Access / Download PDF files using FTP.
						</P>
					</div>
					<a href="/ftp_create_credentials">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<span class="pull-right text-right">
								<i class="material-icons" style="font-size:50px; position:absolute; top:0; right:0">keyboard_arrow_right</i>
							</span><br>
						</div>
					</a>	
				</div>
			</div>
		</div>
	</div>

    <!-- WEBDAV -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="body">
				<div class="row clearfix">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<label>WEBDAV Import documents</label>
						<P>
							Access / Download PDF files using Webdav.
						</P>
					</div>
					<a href="/webdav_create_credentials">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<span class="pull-right text-right">
								<i class="material-icons" style="font-size:50px; position:absolute; top:0; right:0">keyboard_arrow_right</i>
							</span><br>
						</div>
					</a>	
				</div>
			</div>
		</div>
	</div>

		

	 <!-- FILENAME FORMAT FOR DOWNLOADING -->
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		<div class="card card_filename">
	
			    <div class="advancedDemo" ng-repeat="containers in model">
			        <div class="dropzone box box-yellow">
			        	
						<ul dnd-list="containers"
						    dnd-allowed-types="['container']">
						    <li ng-repeat="container in containers">
						        <div class="container-element box box-blue">
						            <label>Drag and drop to customize download filename format</label>
						            <ul dnd-list="container.items"
						                dnd-allowed-types="['item']"
						                dnd-horizontal-list="true"
						                dnd-external-sources="true"
						                class="itemlist">
						                <li ng-repeat="item in container.items"
						                    dnd-draggable="item"
						                    dnd-type="'item'"
						                    dnd-moved="container.items.splice($index, 1)"
						                    dnd-dragstart="logEvent()"
						                    >
						                    <# item.label #>
						                </li>
						            </ul>
						            <div class="clearfix"></div>
						            <hr>
	
					            	{{-- save filename format --}}
					            	<button class="btn-flat btn_color main_color waves-effect lg-btn_x2 btn_no_folders" ng-click="saveFilenameFormat()">
					            		<span class="lg-btn-tx">Save filename format</span>
					            	</button>
					            	{{-- reset filename items --}}
					                <button class="btn-flat btn_color main_color waves-effect lg-btn_x2 btn_no_folders" ng-click="resetFilenameItems()">
					            		<span class="lg-btn-tx">Reset Filename</span>
					            	</button>
						        </div>
						    </li>
						</ul>

			        </div>
			    </div>
				<div class="alert bg-grey trashcan" style="background-color:#ccc !important" role="alert" dnd-list="[]" dnd-disable-if="filenameCount==1">
					<label>Drag here to delete</label>
				</div>
		</div>    


	</div>



</div>

@endsection

@section('scripts')
<script src="{{ asset('static/js/settings.js') }}"></script>
<script type="text/javascript">

//inject this app to rootApp
var app = angular.module('app', ['dndLists']);

app.controller('settingsController', function($scope, $http, $timeout) {

    
	$scope.filenameCount = 0;

	$scope.wait = function(){
	    $('.card_filename').waitMe({
	        effect: 'win8_linear',
	        text: 'Please wait...',
	        bg: 'rgba(255,255,255,0.90)',
	        color: '#555'
	    });
	}

    $scope.saveFilenameFormat = function(){


    	$scope.wait();

    	angular.forEach($scope.model, function(e){
             angular.forEach(e, function(a){
                 console.log(a.items);
                 data = {
                 	dataFormat: a.items
                 }
             });
    	})

	    $http.post('/settings/update_filename_format', data).success(function(data){
	         if(data=="success"){
	         	 swal("Success", "Filename format updated", "success");
	         }else{
	         	 swal("Error", "Something went wrong", "error");
	         }

	         $('.card_filename').waitMe("hide");
	    });

    } 

	$scope.getFilenameFormat = function(){

		$scope.wait();
	  
	    $http.get('/settings/get_d_filename_format').success(function(data){
           // Initialize model
		    $scope.model = [[]];
		      var container = 
		      {
		      	items: data
		      };
		      $scope.model[0].push(container);
		      console.log($scope.model[0]);
		      
		      $scope.logEvent();

		      console.log("filename count:" + $scope.filenameCount);

		       $('.card_filename').waitMe("hide");

	    }); //end httpget

	}

	$scope.getFilenameFormat(); 

	$scope.logEvent = function(){
		console.log('dragged');

	    angular.forEach($scope.model, function(e){
  		   angular.forEach(e, function(a){
  		   		 console.log(a.items.length);
  		   		 $scope.filenameCount = a.items.length;
  		   });
	    });
	}

	$scope.resetFilenameItems = function(){

		data = { reset:'reset' }
		$http.post('/settings/reset_filename_format', data).success(function(data){
			$scope.getFilenameFormat();
	    });

	};

	    
});

//copy clipboard
$(document).on("change", ".t-zone", function() {
	//your code here...
	var placementFrom = $(this).data('placement-from');
	var placementAlign = $(this).data('placement-align');
	var animateEnter = $(this).data('animate-enter');
	var animateExit = $(this).data('animate-exit');
	var colorName = $(this).data('color-name');
	var timeZone = $(this).val();
	var text = "Timezone set to: " + $(this).val();

	$.ajax({
	    url: '/settings/change_timezone',
	    data: {
	        timezone: timeZone
	    },
	    type: 'POST',
	    success: function(data) {
	    	showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
	    }
	}); //end ajax
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

