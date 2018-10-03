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
	border: 2px solid #b1d5ff; border-radius: 4px; margin-top: 10px; cursor: pointer;
}

.view2_div:hover {
	border: 2px solid #017cff;
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

.view3_container:hover {
	-webkit-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
	-moz-box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
	box-shadow: 0px 1px 5px 1px rgba(145,177,214,1);
	cursor: pointer;
}

.view3_container:hover img{
    border: 2px solid #017cff;
} 

.view3_div img {
	width:130px;
	border: 2px solid #b1d5ff; border-radius: 2px;
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
   z-index:5 !important;
   cursor:pointer;
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

.tab_view_space {
  margin-left:15px;
}

.image{
    position:relative;
    overflow:hidden;
    padding-bottom:120%;
    margin-top:15px;
}
.image img{
    position:absolute;
    width:100%;
    max-height:100%;
    border:1px solid #b1d5ff;
    border-radius: 5px;
}

.image img:hover{
    border:1px solid #017cff;
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

.cstm_input {
	background-color:#ebedf8;
	border-top-right-radius: 0px;
	border-bottom-right-radius: 0px;

	outline: none;
	border: none !important;
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
}

.canvasStyle {
	width:100% !important; max-height:110px; margin-left:-20px;

}

@media handheld and (min-width: 1000px), 
  screen and (min-width: 1000px){
     .filter-m {
        float:right;
     }
}

@media handheld and (max-width: 500px), 
  screen and (max-width: 500px){
     .filter-txt-m{
     
     }
}

.fx_index {
	z-index:2;
}
.q_btn {
     color:#b1d5ff;
     font-size:22px;
     margin-left:5px;
     cursor: pointer;
}
.q_btn:hover {
     color:#017cff;
}



.cstm-srch-btn {
   color:#017cff;
   font-size:18px;
}

.input-group{
  display: table;
  width:100%;

}
.input-group > div{
  display: table-cell;
  vertical-align: middle;  /* needed for Safari */
}

.input-group-icon{
  background:#ebedf8;
  color: #017cff;
  padding: 0 12px;
  border-left:2px solid #ccc;
}

.inp-g-hv:hover {
	background-color:#dcdeef;
}
.input-group-area{
  width:100%;
}

.input-group input{
  border: 0;
  display: block;
  width: 100%;
  padding: 8px;
border-top-left-radius: 3px !important;
border-bottom-left-radius: 3px !important;
text-indent: 15px;
}

.input-group input:focus {
  
  outline:none !important;
 border-color: inherit !important;
  -webkit-box-shadow: none !important;
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
	 border-right:1px solid #ccc;
}

.ic_edit {
	 background-color:#017cff !important;
	 margin-right:-3px;
	 color:#fff;
}
.ic_edit:hover {
    background-color:#3295ff !important;
}

.ic_trash {
	 background-color:#ff9c1c !important;
	 color:#fff;
}

.ic_trash:hover {
	 background-color:#fca535 !important;
	 color:#fff;
}

.ic_edit i {
	font-size:17px;
}

.ic_trash i {
	font-size:17px;
}

.ss_list_a {
	width:100%; padding:10px; display:inline-block;
}
.ss_list_a:hover {
	background-color:#b1d5ff;
}

.ss_list_index {
	position:absolute;
	z-index:5 !important;
}

.tags_list_a {
	 width:100%; padding:10px; display:inline-block;
	 background-color: #4ddb9f;
}

.tags_list_a:hover {
	 background-color:#29c684
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
<div class="row" ng-controller="searc_doc_controller" ng-cloack ng-click="clear_autocomplete()">


	<div class="col-md-3 col-xs-12 col-sm-12 hidden-xs" style="margin-top:-3px !important">
	   <h3 style="margin:0px; padding:0px" class="pull-left">Search<i class="fa fa-question-circle q_btn fx_index" data-toggle="modal" data-target="#largeModal"></i></h3>
    </div>
	<div class="col-md-5  filter-m">
		<!-- Tablet | Desktop filter -->
	    <ul class="list-inline  filter-m filter-txt-m">
			<li>
                <input ng-model="filter" ng-value="'no_filter'" name="no_filter" type="radio" id="radio_0" class="radio-col-grey"/>
				<label for="radio_0">= No filter</label>
             </li>
            <li> 
				<input ng-model="filter" ng-value="'full_text'" name="filter_g"  type="radio" id="radio_1" class="radio-col-yellow "/>
				<label for="radio_1">= @lang('search_doc.full_text_tx')</label>
             </li> 
             <li> 
				<input ng-model="filter" ng-value="'tag'" name="filter_g" type="radio" id="radio_2"  class="radio-col-green" />
				<label for="radio_2">= @lang('search_doc.tag_tx')</label>
             </li>
             <li>
				<input ng-model="filter" ng-value="'folder'" name="filter_g" type="radio" id="radio_3"  class="radio-col-red"/>
				<label for="radio_3">= @lang('search_doc.f_folder_tx')</label>
			</li>
		</ul>


	</div>

	<div class="col-md-12">
      
    
    <!-- search input | buttons container -->
     <div>
		<div class="input-group" style="margin-bottom:0px;">
		  <div class="input-group-area">
		 	<input type="text" class="form-control input-lg cstm_input InputAddOn-field" id="usr" ng-click="ss_list_show=false; tags_list_show = false" ng-model="keyword" ng-model-options='{ debounce: 1000 }' ng-change="autoComplete()" ng-keydown="myFunct($event)">
		  </div>

		  	<div class="input-group-icon  waves-effect inp-g-hv ng-hide" ng-show="ss_tag_btn" ng-click="tags_list_show = !tags_list_show; ss_list_show = false"><i class="glyphicon glyphicon-tags cstm-srch-btn" style="color:#017cff;"></i></div>
			<div class="input-group-icon  waves-effect inp-g-hv ng-hide" ng-show="ss_list_btn" ng-click="ss_list_show = !ss_list_show; tags_list_show = false"><i class="glyphicon glyphicon-list cstm-srch-btn" style="color:#017cff;"></i></div>
			<div class="input-group-icon  waves-effect inp-g-hv ng-hide" ng-show="ss_btn" style="border-top-right-radius: 3px;border-bottom-right-radius: 3px !important" ng-click="saveSearched()">
				 <i class="glyphicon glyphicon-floppy-disk cstm-srch-btn" style="color:#017cff;"></i>
		    </div>
		</div>
		<!-- Ssave search list -->
		 <div class="row clearfix" >
			 <div class="col-md-12 col-xs-12 col-sm-12 ss_list_index" >
                   <div class="list-group" ng-class="{true: 'card'}[ss_list.length > 1]" ng-show="ss_list!=null && ss_list.length>0 && ss_list_show">
                   	    <div class="list-group-item" style="padding:0px; margin:0px" ng-repeat="data in ss_list track by $index">
				            <span class="ss_list_a" ng-click="queSavedSearch(data)"><span><# data.ss_name #></span></span>
				            <span class="list_btn_container">
									<span class="inside_close ic_edit waves-effect"  ng-click="renameSaveSearch(data.ss_id,data.ss_name)"><i class="fa fa-edit"></i></span>
									<span class="inside_close ic_trash waves-effect" ng-click="deleteSaveSearch(data.ss_id)"><i class="fa fa-trash"></i></span>
				            </span>
				        </div>
                   </div>	
			 </div>
		</div>
		<!-- tags list -->
		 <div class="row clearfix" >
			 <div class="col-md-12 col-xs-12 col-sm-12 ss_list_index" >
                   <div class="list-group" ng-class="{true: 'card'}[tags_list.length > 1]" ng-show="tags_list!=null && tags_list.length>0 && tags_list_show">
                   	    <div class="list-group-item" style="padding:0px; marign:0px" ng-repeat="tags in tags_list track by $index">
				            <span class="tags_list_a"><span><# tags #></span></span>
				        </div>
                   </div>	
			 </div>
		</div>
	</div>	


        <div class="row clearfix">
	        <div class="col-md-3 ">
			        <div class="list-group list-group-autocomplete" ng-class="{true: 'card'}[find_tags.length > 1 || find_folders.length > 1 || find_fText.length > 1]">
			            <a  ng-click="selectAutocompleteSearch(tag,'tag')" class="list-group-item th-t typeHeadCstm" ng-repeat="tag in find_tags track by $index" ng-show="find_tags!=null && find_tags.length>0">
				            <# tag #>
				        </a>
				        <a  ng-click="selectAutocompleteSearch(folder.folder_name,'folder')" class="list-group-item th-f typeHeadCstm" ng-repeat="folder in find_folders track by $index" ng-show="find_folders!=null && find_folders.length>0">
	                        <# folder.folder_name #>
				        </a>
				        <a  ng-click="selectAutocompleteSearch(text,'full_text')" class="list-group-item th-ft typeHeadCstm" ng-repeat="text in find_fText track by $index" ng-show="find_fText!=null && find_fText.length>0">
				            <# text #>
				        </a>
				        <a  class="list-group-item typeHeadCstm ng-hide" ng-show="autocomplte_no_result">
				            No result found..
				        </a>
				    </div>
			    </div>
		</div>

		
		<br>
	</div>
    
    <div class="col-md-12" style="max-height:120px;" >
    	<table style="width:100%;" id="myChart" class="hideBarChart">
    		 <tr  id="canvasHolder"></tr>
    		 <tr>
    		 	<td  ng-repeat="year in bar_year_range track by $index" ><label style="position:absolute; margin-top:-8px; font-weight: bold"><#year | short_year #></label></td>
    		 </tr>
    	</table>
    </div>

    <!-- preloader -->
    <div class="col-md-12 ">
      <center>
        <div class="preloader ng-hide center-block" ng-show="doc_loader">
            <div class="spinner-layer pl-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>

        <div class="center-block ng-hide" ng-show="not_found">
        	 <h3 style="color:red">No document found.</h3>
        </div>

        <div class="center-block ng-hide" ng-show="invalid_format">
        	 <h3 style="color:red">Invalid search format.</h3>
        </div>

      </center>
    </div>


<div class="clearfix ng-hide" ng-show="doc_view">

	<!-- VIEW NAVIGATIONS -->
	<div class="col-md-4 col-sm-12 col-xs-12 fx_index" style="margin-top:20px">
		<ul class="list-inline ng-hide" ng-show="doc_view">
			<li ng-click="selected='lg_view'">
				<a href="#home_only_icon_title"     data-toggle="tab"><i class="fa  fa-th-large tab_view_icon" ng-class="{activeView: selected=='lg_view'}"></i></a>
			</li>
			<li ng-click="selected='md_view'">
				<a href="#profile_only_icon_title"  data-toggle="tab"><i class="fa  fa-th tab_view_icon tab_view_space" ng-class="{activeView: selected=='md_view'}"></i></a>
			</li>
			<li ng-click="selected='grid_view'">
				<a href="#messages_only_icon_title" data-toggle="tab"><i class="fa  fa-th-list tab_view_icon tab_view_space" ng-class="{activeView: selected=='grid_view'}"></i></a>
			</li>
			<li ng-click="selected='table_view'">
				<a href="#settings_only_icon_title" data-toggle="tab"><i class="fa  fa-align-justify tab_view_icon tab_view_space" ng-class="{activeView: selected=='table_view'}"></i></a>
			</li>
		</ul>
	</div>

    <!-- show total rows -->
    <div class="col-md-2 pull-right col-xs-12 col-sm-12 fx_index" >Num. of documents
      <select ng-model="entryLimit" class="form-control">
	        <option>10</option>
	        <option>20</option>
	        <option>50</option>
	        <option>100</option>
	        <option>500</option>
      </select>
    </div>

  	<div class="col-md-3 col-sm-12 col-xs-12 pull-right fx_index">
          Sort by:
          <select class="form-control" style="width: 100%;" ng-model="sort">
				<option value="">None</option>
				<option value="sender">Sender ascending</option>
				<option value="-sender">Sender descending</option>
				<option value="receiver">Receiver ascending</option>
				<option value="-receiver">Receiver descending</option>
				<option value="date">Date ascending</option>
				<option value="-date">Date descending</option>
				<option value="tags">Tags ascending</option>
				<option value="-tags">Tags descending</option>
				<option value="category">Category ascending</option>
				<option value="-category">Category descending</option>
				<option value="size">Size ascending</option>
				<option value="-size">Size descending</option>
				<option value="origin">Origin ascending</option>
				<option value="-origin">Origin descending</option>
          </select>
    </div><!-- /.form-group -->  

	<div class="col-md-12">
		<div class="tab-content">
			<!-- VIEW - FULL DOC VIEW  -->
			<div role="tabpanel" class="tab-pane fade in active" id="home_only_icon_title" >
				<div class="row " >
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 doc_hvr" ng-repeat="doc1 in filtered = (list | orderBy : sort )  | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index">
						<a ng-href="/document/<#doc1.doc_id#>">
							
							 <div class="image">
					              <img ng-src="/files/image/<# doc1.doc_page_image_preview #>" class="img img-responsive full-width"/>
					          </div>
						</a>
					</div>
				</div>
			</div>
			<!-- GRID VIEW  -->
			<div role="tabpanel" class="tab-pane fade" id="profile_only_icon_title">
				<div class="row">

					<div class="col-md-2 col-sm-6 col-xs-6 doc_hvr" ng-repeat="doc2 in filtered = (list | orderBy : sort )  | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index">
					    <a ng-href="/document/<#doc2.doc_id#>">
							<div class="view2_div">
								<img ng-src="/files/image/<# doc2.doc_page_thumbnail_preview #>">
							</div>
						</a>
					</div>

				</div>
			</div>
			<!-- VIEW - TILE LIST VIEW -->
			<div role="tabpanel" class="tab-pane fade" id="messages_only_icon_title">
				<div class="row" >
					<div class="col-md-6 col-xs-12 col-sm-12 doc_hvr"ng-repeat="doc3 in filtered = (list | orderBy : sort )  | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index">
					  
					  <a ng-href="/document/<#doc3.doc_id#>" >
						<div class="card view3_container"  style="margin-top:15px">
							<table clas="table-responsive">
								<tr>
									<td>
										<div class="view3_div">
											<img ng-src="/files/image/<# doc3.doc_page_thumbnail_preview #>">
										</div>
									</td>
									<td style="color:#000">
										<div class="view_date"><label><#doc3.date#></label></div>
										<ul class="list-unstyled view3_card_d">
											<li><b><#doc3.sender#></b></li>
											<li><#doc3.origin#></li>
											<li><#doc3.category#> </li>
											<li><#doc3.receiver#></li>
										</ul>
										<div class="view_tags">
											<span ng-repeat="tag in doc3.tags_array" > 
												<span class="badge bg-green">
													 <# tag #>
												</span>
											</span>	
											<span class="badge bg-pink"><#doc3.folder_name#></span>
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
				<div class="row" >
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="body table-responsive" style="margin-top:15px">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Kontakt</th>
										<th>Empfänger</th>
										<th>Kategorie</th>
										<th>Tags</th>
										<th>Größe</th>
										<th>empfangen am</th>
									</tr>
								</thead>
								<tbody>
									 <tr ng-click="editDoc(doc4.doc_id)" ng-repeat="doc4 in filtered = (list | orderBy : sort )  | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit track by $index">
											<td><# doc4.sender   | default #></td>
											<td><# doc4.receiver | default #></td>
											<td><# doc4.category | default #></td>
											<td><# doc4.tags 	 | default #></td>
											<td><# doc4.size 	 | default #></td>
											<td><# doc4.date 	 | default #></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- end able -->
				</div>
			</div>
		</div>
	</div> <!-- // end row -->



	<!-- show if document does not exist -->
    <div class="col-md-12" ng-show="filteredItems == 0">
        <div class="col-md-12">
            <h4>No document found</h4>
        </div>
    </div>
    <!-- Pagination -->
    <div class="col-md-6 ng-hide" ng-show="filteredItems > 0">
        <div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>
    </div>
            <!-- show total number of found row -->
    <div class="col-md-6" ng-show="filteredItems > 0">
      <p style="color:#999; margin-top:20px" class="pull-right">Filtered <# filtered.length #> of <# totalItems #> total Documents</p>
    </div>

</div>
<!-- CLEARFIX  -->


<!-- Search Guide Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" style="margin-top:40px">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Custom search guides and rules.</h4>
            </div>
            <div class="modal-body" style="text-overflow: auto">
                <p>Pressing enter when no input specified will return all your archived documents, filter must be in "no filter".</p>
                <label>Valid column names to use.</label><br>
						<span class="badge bg-light-blue">sender</span>
						<span class="badge bg-light-blue">receiver</span>
						<span class="badge bg-light-blue">tags</span>
						<span class="badge bg-light-blue">category</span>
						<span class="badge bg-light-blue">note</span>
						<span class="badge bg-light-blue">date</span>
						<span class="badge bg-light-blue">number_of_pages</span>
						<span class="badge bg-light-blue">reminder</span>
						<span class="badge bg-light-blue">tax_relevant</span><br><br>
				<label>Valid operators</label><br>
						<span class="badge bg-green">=</span>
						<span class="badge bg-green">></span>
						<span class="badge bg-green">>=</span>
						<span class="badge bg-green"><</span>
						<span class="badge bg-green"><=</span>
						<span class="badge bg-green">!=</span><br><br>
			    <label>Valid Binary operators</label><br>
			    
			    <span class="badge bg-orange">and</span>
			    <span class="badge bg-orange">or</span>
			    <span class="badge bg-orange">not</span>
			    <span class="badge bg-orange">xor</span><br><br>
       
				<label>Column names that can only use operator</label>
				<span class="badge bg-green">></span>
				<span class="badge bg-green">>=</span>
				<span class="badge bg-green"><</span>
				<span class="badge bg-green"><=</span><br>

				<span class="badge bg-light-blue">date</span>
				<span class="badge bg-light-blue">number_of_pages</span>
				<span class="badge bg-light-blue">reminder</span>
				<span class="badge bg-light-blue">tax_relevant</span><br><br>

                <label>When searching for date and reminder use format 	<span class="badge">YYYY-MM-DD</span></label><br>
                <span>eg. date=2018-12-31, reminder>2018-12-31</span><br><br>

                <label>When searching for tax_relevant, use this format.</label><br>
                <p>eg. tax_relevant=2018, tax_relevant>2015.</p>

                <hr>

                <label>BASIC CUSTOM SEARCH FORMAT</label><br>
                <label><span class="badge">COLUMN NAME, OPERATOR, VALUE</span></label><br>
                <p>eg(single expression). sender=Till</p>

                <label><span class="badge">COLUMN NAME, OPERATOR, VALUE, SPACE, COLUMN NAME, OPERATOR, VALUE</span></label><br>
                <p>eg(multiple expression). sender=Till receiver=Jannik | the result is equivalent to query (where sender=Till and receiver=Jannik)</p>
                <br><br>

                <label>ADVANCE CUSTOM SEARCH FORMAT.</label><br>
                <p>in advnace custom search you can use binary operations such as "and,or,not,xor"</p>
                <label><span class="badge">COLUMN NAME, OPERATOR, VALUE, SPACE, BINARY OPERATOR, SPACE, COLUMN NAME, OPERATOR, VALUE</span></label><br>   
                <p>eg. sender=Till or sender=Jannik</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="{{ asset('static/js/search_documents.js') }}"></script>

<script type="text/javascript" differ>

//inject this app to rootApp
var app = angular.module('app', ['ui.bootstrap']);

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
//convert year to short year.
app.filter('short_year', function(){
    return function(data)
      {
         return "'"+data.toString().slice(-2);
      }
});

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


app.controller('searc_doc_controller', function($scope, $http, $timeout, $q, $compile) {
// cancel previous http request
// eg running autocomplete. if user press enter search. cancel all previous running http request.
$scope.canceler = $q.defer();
$scope.search_canceler = $q.defer();
//active tab.
$scope.selected='lg_view';
//default filter
$scope.filter  = 'no_filter';
$scope.keyword = '';
$scope.list   = [];
$scope.tags_list = [];


//autocomplte no result
$scope.autocomplte_no_result = false;
//document div to show documents. 
$scope.doc_view =   false;
//documents preloader when searching document
$scope.doc_loader = false;
//not found status
$scope.not_found =  false;
//search format 
$scope.invalid_format = false;

//-----save search--------
$scope.save_search_data = [];
$scope.ss_tag_btn = false;
$scope.ss_list_btn = false;
$scope.ss_btn = false;
$scope.ss_list_show = false;
$scope.tags_list_show = false;


//clear autocomplete
$scope.clear_autocomplete = function(){
	$scope.find_tags    = null;
	$scope.find_fText   = null;
	$scope.find_folders = null;
	$scope.autocomplte_no_result = false;
	//hide save search list when typing

}

//edit document
$scope.editDoc = function(doc_id){
    window.location.replace('/document/'+doc_id);
}

//run function on key press
$scope.myFunct = function(keyEvent) {
    //key 13 = ENTER
    if (keyEvent.which === 13){
  		//delay function for 1 second
        $timeout( function()
        {
			$scope.enterKeySearch();
		},1000); //end timeout.
    }//end key 13
};

//on keypress run autocomplete
$scope.autoComplete = function(){
    
	$scope.ss_list_show = false;
	$scope.tags_list_show = false;
    // empty dropdown autocomplete.
    $scope.clear_autocomplete();
    //cancel previous autocomplete post request.
    $scope.canceler.resolve();
    //reinit $q.defer make new autocomplete post request
    $scope.canceler = $q.defer();
    
    //set datas. keyword. acf(autocomplete filter)
    data = {
   	  keyword:$scope.keyword,
   	  autocomplte_filter:$scope.filter
    }
    //send post request.
    $http({method:'POST',url:'/search/typhead', data, timeout: $scope.canceler.promise}).success(function(data){
         //store data if found
         if(data.tags!="not_found"){
             //if json_encode return object. convert to array 
         	 if(typeof data.tags === 'object'){
			     $scope.find_tags = Object.keys(data.tags).map(function(key){
			          return data.tags[key];
			     });

			}else{
			    $scope.find_tags = data.tags;
			}
         }
         //store data if found
         if(data.folders!="not_found"){
         	if(typeof data.folders === 'object'){
			     $scope.find_folders = Object.keys(data.folders).map(function(key){
			          return data.folders[key];
			     });

			}else{
			    $scope.find_folders = data.folders;
			}
         }
         //store data if found
         if(data.fulltext!="not_found"){
			if(typeof data.fulltext === 'object'){
			     $scope.find_fText = Object.keys(data.fulltext).map(function(key){
			          return data.fulltext[key];
			     });

			}else{
			    $scope.find_fText = data.fulltext;
			}
         }

         if(data.tags=="not_found" && data.folders=="not_found" && data.fulltext=="not_found"){
         	//not result.
         	$scope.autocomplte_no_result = true;
         }

    });
}


$scope.reInit = function(){
    //add class to hide chart
    $('#myChart').addClass('hideBarChart');
    //hide current doc view
    $scope.doc_view = false;
    $scope.canceler.resolve();
    //cancel previous selectSearch post request
    $scope.search_canceler.resolve();
    //reinit $q.defer to make new post request.
    $scope.search_canceler = $q.defer();
    //clear autocomplete
	$scope.clear_autocomplete();
	//show search preloader
	$scope.doc_loader = true;
	//hide doc not found status.
	$scope.not_found = false;
    //put selected autocomplete keyword to search bar
    $scope.invalid_format = false;
    //hide saved search list
    $scope.ss_list_show = false;
    $scope.tags_list_show = false;
    //empty list 
    $scope.list = [];
    $scope.tags_list = [];

}

$scope.docsFound = function(){
    $('#myChart').removeClass('hideBarChart');
	//hide notfound status
    $scope.not_found  = false;
	//show doc views
    $scope.doc_view   = true;
    //hide preloader
    $scope.doc_loader = false;
    $scope.invalid_format = false;
    //create barchart
    $scope.makeBarchart();
}

$scope.formatNotValid = function(){
	$('#myChart').addClass('hideBarChart');
  	//show notfound status
  	$scope.not_found  = false;
  	//hide document view.
  	$scope.doc_view   = false;
  	//hide preloader
    $scope.doc_loader = false;
    $scope.invalid_format = true;
	//empty list 
	$scope.list = [];
	$scope.tags_list = [];
}

$scope.docsNotFound = function(){
    $('#myChart').addClass('hideBarChart');
  	//show notfound status
  	$scope.not_found  = true;
  	//hide document view.
  	$scope.doc_view   = false;
  	//hide preloader
    $scope.doc_loader = false;
    $scope.invalid_format = false;
    //empty list 
    $scope.list = [];
    $scope.tags_list = [];
}

//-=======================================  SAVE SEARCH ==================================================

  $scope.ss_list_check = [];
  $scope.getSaveSearches = function(){
  	  $scope.ss_list = [];
  	  $http.get('/ss_user').success(function(data){
           $scope.ss_list = data;

           if($scope.ss_list.length>0 && $scope.ss_list!=null){
               $scope.ss_list_btn = true;
           }

           angular.forEach(data, function(value, key) {
		      this.push(value.ss_name.toUpperCase());
	       }, $scope.ss_list_check);
  	  });
  }

  $scope.getSaveSearches();

  $scope.storeSaveSearchDatas = function(kw,ft){
 
      $scope.save_search_data = [];
      $scope.save_search_data.splice(0,0,kw);
      $scope.save_search_data.splice(1,0,ft);

      if($scope.list.length>0 && $scope.list!=null){
      	 $scope.ss_btn = true;
      }else{
      	 $scope.ss_btn = false;
      }
      console.log($scope.save_search_data);
  }

  $scope.toggleTagsList = function(){

	  console.log("tags_list:" + $scope.tags_list);
	  if($scope.tags_list.length>0 && $scope.tags_list!=null){
	       $scope.ss_tag_btn = true;
	  }else{
	       $scope.ss_tag_btn = false;
	  }
  }

  //save search result
  $scope.saveSearched = function(){
    swal({
        title: "Save searched documents",
        text: "Please name your save search:",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Save search name"
    }, function (inputValue) {
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("You need to write something!"); return false
        }
        if($scope.ss_list_check.includes(inputValue.toUpperCase())==true ){
            swal.showInputError("Save search name exist"); return false
        }
        $scope.save_search_data.splice(2,0,inputValue); 
        $.ajax({
            url: '/ss_save',
            data: {
                ss_datas: $scope.save_search_data
            },
            type: 'POST',
            success: function(data) {
            	if(data=="success"){
                	swal("Success", "New save search created","success");
                	$scope.getSaveSearches();
                }
            }
        }); //end ajax
    });
  }

  $scope.renameSaveSearch = function(ss_id,ss_name){

  	swal({
        title: "Rename save search",
        text: "Enter new name:",
        type: "input",
        input: 'ss_name',
        inputValue: ss_name,
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Save search name"
    }, function (ss_new_name) {
        if (ss_new_name === false) return false;
        if (ss_new_name === "") {
            swal.showInputError("You need to write something!"); return false;
        }
        if($scope.ss_list_check.includes(ss_new_name.toUpperCase())==true ){
            swal.showInputError("Name already exist"); return false
        }
        ss_data = [ss_id,ss_new_name];
        $.ajax({
            url: '/ss_rename',
            data: { ss_data },
            type: 'POST',
            success: function(data) {
            	if(data=="success_renamed"){
                	swal("Success", "Save search name changed to: " + ss_new_name, "success");
                	$scope.getSaveSearches();
                } 
            }
        }); //end ajax
    });

  }

    //delete saveSearch
    $scope.deleteSaveSearch = function(ss_id){

	    swal({
	        title: "Delete Saved Search",
	        text: "Are you sure you want to delete this saved search?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonColor: "#DD6B55",
	        confirmButtonText: "Yes, delete it!",
	        cancelButtonText: "No, cancel please!",
	        closeOnConfirm: false,
	        closeOnCancel: false
	    }, function (isConfirm) {
	        if (isConfirm) {
	            //ajax send post delete with id.
	             $.ajax({
		            url: '/ss_delete',
		            data: {
		                ss_id: ss_id
		            },
		            type: 'POST',
		            success: function(data) {
		            	if(data=="success_deleted"){
		            		console.log(data);
		                    $scope.getSaveSearches();
		                }
		               swal("Deleted!", "Your folder has been deleted.", "success");
		            }
		        }); //end ajax
	        } else {
	            swal("Cancelled", "Nothing is deleted.", "error");
	        }
	    });

	 }


  $scope.queSavedSearch = function(data){

  	$scope.reInit();
    //store datas for post request
    data = {
       keyword: data['ss_keyword'],
       filter:  data['ss_filter']
    }
    //filter = tag,folder,fulltext
    $http({method:'POST',url:'/search/documents', data, timeout: $scope.search_canceler.promise}).success(function(data){
        if(data=="error"){
	        $scope.docsNotFound();
	    }
	    else{ 
	        $scope.docsFound();         
	        //store datas in different views
		    $scope.list = data.doc_datas;
		    $scope.tags_list = data.doc_tags;
		    $scope.currentPage = 1; //current page
			$scope.entryLimit = 10; //max no of items to display in a page
			$scope.filteredItems = $scope.list.length; //Initially for no filter
			$scope.totalItems = $scope.list.length;

			$scope.toggleTagsList();
	    }
    });
  	  
  }



//-----------------------------------------------------











//================================================================================================================================================
//search on select autocomplete
$scope.selectAutocompleteSearch = function(keyword,filter){
    
    $scope.reInit();
    //put selected autocomplete keyword to search bar
    $scope.keyword = keyword;
    $scope.filter  = filter;
    //store datas for post request
    data = {
       keyword: keyword,
       filter:  filter
    }
    //filter = tag,folder,fulltext
    $http({method:'POST',url:'/search/documents', data, timeout: $scope.search_canceler.promise}).success(function(data){
        if(data=="error"){
	        $scope.docsNotFound();
	    }
	    else{ 
	        $scope.docsFound();         
	        //store datas in different views
		    $scope.list = data.doc_datas;
		    $scope.tags_list = data.doc_tags;
		    $scope.currentPage = 1; //current page
			$scope.entryLimit = 10; //max no of items to display in a page
			$scope.filteredItems = $scope.list.length; //Initially for no filter
			$scope.totalItems = $scope.list.length;

            $scope.toggleTagsList();
			$scope.storeSaveSearchDatas($scope.keyword,$scope.filter);

	    }
    });

}

// search when key enter pressed
$scope.enterKeySearch = function(){
        
        $scope.reInit();
	    //store datas for post request
	    data = {
	       keyword: $scope.keyword,
	       filter:  $scope.filter
	    }
	    //filter = tag,folder,fulltext
	    $http({method:'POST',url:'/search/documents', data, timeout: $scope.search_canceler.promise}).success(function(data){
            if(data=="error" || data.length==0){
                $scope.docsNotFound();
            }
            else if(data=="invalid_format" || data.length==0){
                $scope.formatNotValid();
            }else{    	
                $scope.docsFound();         
		        //store datas in different views

		        $scope.list = data.doc_datas;
		        $scope.tags_list = data.doc_tags;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 10; //max no of items to display in a page
				$scope.filteredItems = $scope.list.length; //Initially for no filter
				$scope.totalItems = $scope.list.length;

	        }

            $scope.toggleTagsList();
            $scope.storeSaveSearchDatas($scope.keyword,$scope.filter);


	    });

}

// set page number
$scope.setPage = function(pageNo){
    $scope.currentPage = pageNo;
};


//==================================================================================================================================================




//get barchart datas.
$scope.getBarchartDatas = function(){
    $http.get('/search/barchar_datas').success(function(data){
    $scope.bar_year_range = data.year_range;
    $scope.bar_mdr = data.mdr;
      
		var canvas_html = [];
		var element =     [];
		for(var i in data.year_range){         
			canvas_html[i] = '<td><canvas id="myChart'+i+'" class="canvasStyle"></canvas></td>';
			element[i] = angular.element(canvas_html[i]);
			$compile(element[i])($scope);
			angular.element('#canvasHolder').append(element[i]);
		};
		$scope.initiateBarDatas();
	});   	
}

$scope.getBarchartDatas();

$scope.initiateBarDatas = function(){

	var data = $scope.bar_year_range;
	$scope.generateBCD = [];

	for (var i in data) {
	  (function(i){
	    $scope.generateBCD.push(function (callback) {

	          var dataSize = $scope.bar_mdr[i]['month'].length;
	          //colors of chart
	          var evenBackgroundColor = 'rgba(0, 119, 255, 1)';
	          var oddBackgroundColor =  'rgba(177,213,255, 1)';
	          //labels of bar
	          //months.

	          var months =  $scope.bar_mdr[i]['month'];
	          //array to store labels
	          var labels = [];

	          //
	          var docDatas = {
	            //Main label for data
	            label: 'Documents:',
	            //documents datas $scopes / here
	            //documents.
	            data: $scope.bar_mdr[i]['docs'],
	            backgroundColor: [],
	            borderColor: [],
	            borderWidth: 1,
	            //hover background oclors
	            hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
	            hoverBorderColor: 'rgba(200, 200, 200, 1)',
	          };

	          //loop/push weeks labels and bar color
	          for (var x = 0; x < dataSize; x++) {
	            labels.push(months[x]);
	            if (x % 2 === 0) {
	              docDatas.backgroundColor.push(evenBackgroundColor);
	            } else {
	              docDatas.backgroundColor.push(oddBackgroundColor);
	            }
	          }

	          return {
	            labels: labels,
	            datasets: [docDatas],
	          };

	    });
	  })(i);
	}
} //initiateBarDatas


$scope.myBar  = [];
$scope.makeBarchart = function(){

    var data = $scope.bar_year_range;

	for (var i in data) {
	  // destroy previous created chart canvas.
	  if($scope.myBar[i]) {
	  $scope.myBar[i].destroy();
	  }
	  //loop
	  $scope.myBar[i] = new Chart(document.getElementById("myChart"+i).getContext("2d"), {
	      type: 'bar',
	      data: $scope.generateBCD[i](),
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

	}; // end for loop

}

// END CONTROLLER
});


//-- ================================================   Barchart mod ================================================== -->

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

</script>

@endsection
