@extends('layouts.app')

@section('page_title', 'Search')

@section('active_search', 'p_active_nav')

@section('custom_style')
<style type="text/css" media="screen">
.tab_view_icon {
	color:#999;
	font-size:30px;
}
.view1_div {
	padding:0px; margin:0px; max-height:600px
}
.view1_div img {
	border: 3px solid #b1d5ff; border-radius: 4px;;
}
.view2_div {
	border: 3px solid #b1d5ff; border-radius: 4px; margin-top: 10px;
}
.view2_div img {
	width:100%;
}
.f_cover {
	border: 3px solid #b1d5ff; border-radius: 4px; margin-top: 10px;
}
.f_cover img {
	width:100%;
}
.view3_container {
	padding:10px;
}
.view3_div {

}
.view3_div img {
	width:130px;
	border: 3px solid #b1d5ff; border-radius: 2px;
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
</style>
@endsection

@section('search_page_filter')
<ul class="list-inline pull-right">
	<li>
		<input name="filter_g" type="radio" id="radio_1" class="radio-col-yellow xx"/>
		<label for="radio_1">= @lang('search_doc.full_text_tx')</label>
		<input name="filter_g" type="radio" id="radio_2" class="radio-col-green" />
		<label for="radio_2">= @lang('search_doc.tag_tx')</label>
		<input name="filter_g" type="radio" id="radio_3" class="radio-col-red" />
		<label for="radio_3">= @lang('search_doc.f_folder_tx')</label>
	</li>
</ul>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<input type="text" class="form-control" id="usr" ><br>
	</div>
	<div class="col-md-12">
		<ul class="list-inline">
			<li>
				<a href="#home_only_icon_title"     data-toggle="tab"><i class="fa  fa-th-large tab_view_icon"></i></a>
			</li>
			<li>
				<a href="#profile_only_icon_title"  data-toggle="tab"><i class="fa  fa-th tab_view_icon"></i></a>
			</li>
			<li>
				<a href="#messages_only_icon_title" data-toggle="tab"><i class="fa  fa-th-list tab_view_icon"></i></a>
			</li>
			<li>
				<a href="#settings_only_icon_title" data-toggle="tab"><i class="fa  fa-align-justify tab_view_icon"></i></a>
			</li>
		</ul>
	</div>
	<div class="col-md-12">
		<div class="tab-content">
			<!-- VIEW - FULL DOC VIEW  -->
			<div role="tabpanel" class="tab-pane fade in active" id="home_only_icon_title" >
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
						<div class="f_cover">
							<img src="{{ asset('static/img/docs/s_doc1.png') }}">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
						<div class="f_cover"">
							<img src="{{ asset('static/img/docs/s_doc2.png') }}">
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" >
						<div class="f_cover">
							<img src="{{ asset('static/img/docs/s_doc3.png') }}">
						</div>
					</div>
				</div>
			</div>
			<!-- VIEW - MIN VIEW -->
			<div role="tabpanel" class="tab-pane fade" id="profile_only_icon_title">
				<div class="row">
					@for($i = 0; $i < 12; $i++)
					<div class="col-md-2 col-sm-6 col-xs-6 ">
						<div class="view2_div">
							<img src="{{ asset('static/img/docs/s_doc1.png') }}">
						</div>
					</div>
					@endfor
				</div>
			</div>
			<!-- VIEW - TILE LIST VIEW -->
			<div role="tabpanel" class="tab-pane fade" id="messages_only_icon_title">
				<div class="row">
					@for($i = 0; $i < 4; $i++)
					<div class="col-md-6">
						<div class="card view3_container">
							<table clas="table-responsive">
								<tr>
									<td>
										<div class="view3_div">
											<img src="{{ asset('static/img/docs/s_doc1.png') }}">
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
					</div>
					@endfor
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
										<th>KategorieKategorie</th>
										<th>Tags</th>
										<th>Größe</th>
										<th class="text-right">empfangen am</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>consider it GmbH</td>
										<td>Jannik Kramer</td>
										<td>Gehaltsabrechnung</td>
										<td>Arbeit, Steuern 2017</td>
										<td>2,8 mb</td>
										<td class="text-right">03.03.17</td>
									</tr>
									<tr>
										<td>Saturn</td>
										<td>Jannik Kramer</td>
										<td>Rechnung</td>
										<td>Steuern 2017, iPhone</td>
										<td>334 kb</td>
										<td class="text-right">27.12.16</td>
									</tr>
									<tr>
										<td>consider it GmbH</td>
										<td>Jannik Kramer</td>
										<td>Gehaltsabrechnung</td>
										<td>Arbeit, Steuern 2017</td>
										<td>738 kb</td>
										<td class="text-right">02.04.17</td>
									</tr>
									<tr>
										<td>Butjer</td>
										<td>Jannik Kramer</td>
										<td>Rechnung</td>
										<td>neues Bad, Steuern 2017, …</td>
										<td>0,9 mb</td>
										<td class="text-right">22.03.17</td>
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
	@endsection

	@section('scripts')

	@endsection
