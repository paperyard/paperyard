@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('active_dashboard', 'p_active_nav')

@section('custom_style')
<style type="text/css" media="screen">
.dashboard_card {
  height:150px;
}
.search_inp {
  text-align:center
}
.cstm_list_g {
  padding-top:20px;
}
.cstm_knob_div {
  padding-left:0px
}
.prev_viewed_doc_div {
  width:120px; height:100%; border-right:3px solid #b1d5ff;
}
.prev_viewd_doc_div_img {
  height:120px !important; border:1px solid #b1d5ff;
}
.prev_viewed_docs {
  border: 1px solid #b1d5ff; padding:0px; width:60px; margin:3px
}
.prev_viewd_docs img {
  height:85px;
}

.prev_viewed_docs_m {
  border: 1px solid #b1d5ff; padding:0px; width:40px; margin:3px
}
.prev_viewed_docs_m img {
  height:60px;
}
</style>
@endsection

@section('show_localization')
<ul class="list-inline pull-right">
  <li>@lang('home.c_language_tx') :</li>
  <li onclick="window.location='{{ url('language/ge') }}'">
    <img src="{{asset('static/img/language/german_flag.png')}}"  class="lang_flags">
  </li>
  <li onclick="window.location='{{ url('language/en') }}'">
    <img src="{{asset('static/img/language/america_flag.png')}}" class="lang_flags">
  </li>
</ul>
@endsection

@section('content')
<div class="row">
  <div class="col-md-6 text-center col-md-offset-3">
    <input type="text" class="form-control search_inp" id="usr" placeholder="@lang('dashboard.input_search_p_holder')">
  </div>
</div>
<br>
<div class="row">

  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card dashboard_card">
      <div class="body ">
        <ul class="list-group" >
          <li class="list-group-item cstm_list_g" ><strong>@lang('dashboard.to_edit_tx')</strong><span class="badge bg-light-blue">8</span></li>
          <li class="list-group-item cstm_list_g" ><strong>@lang('dashboard.This_whole_week_tx')</strong><span class="badge bg-light-blue">19</span></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card dashboard_card">
      <div class="body">

      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <div class="card dashboard_card hidden-xs hidde-sm">
      <div class="container-fluid" style="padding-top:10px">
        <div class="pull-left cstm_knob_div" >
          <input type="text" class="knob" data-linecap="round" value="60" data-width="130" data-height="130" data-thickness="0.25" data-angleoffset="-180"
          data-fgColor="#017cff" data-bgColor="#b1d5ff" >
        </div>
<!-- <div class="pull-right" style="border:1px solid red">
<div class="row">
<label class="pull-right">zuletzt geöffnet</label>
</div>
<div class="row">
<label class="pull-right">zuletzt geöffnet</label>
</div>
</div> -->
</div>
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <div class="card dashboard_card">
    <div style="padding:17px">

      <div class="col-xs-5 col-sm-3 " style="padding-left:0px;">
        <div class="prev_viewed_doc_div">
          <img src="{{ asset('static/img/docs/doc1.png') }}" class="img-responsive prev_viewd_doc_div_img">
        </div>
      </div>

      <!-- Last opened desktop  -->
      <div class="col-xs-7 col-sm-9 hidden-xs hidden-sm hidden-md">
        <div class="row" >
          <label class="pull-right">@lang('dashboard.last_opened_tx')</label>
        </div>
        <div class="row pull-right" style="margin-top:4px">
          <div class="col-xs-2 col-sm-1 prev_viewed_docs">
            <img src="{{ asset('static/img/docs/doc1.png') }}" class="img-responsive">
          </div>
          <div class="col-xs-2 col-sm-1 prev_viewed_docs">
            <img src="{{ asset('static/img/docs/doc2.png') }}" class="img-responsive">
          </div>
          <div class="col-xs-2 col-sm-1 prev_viewed_docs">
            <img src="{{ asset('static/img/docs/doc3.png') }}" class="img-responsive">
          </div>
          <div class="col-xs-2 col-sm-1 prev_viewed_docs">
            <img src="{{ asset('static/img/docs/doc4.png') }}" class="img-responsive">
          </div>
          <div class="col-xs-2 col-sm-1 prev_viewed_docs">
            <img src="{{ asset('static/img/docs/doc5.png') }}" class="img-responsive">
          </div>
          <div class="col-xs-2 col-sm-1 prev_viewed_docs">
            <img src="{{ asset('static/img/docs/doc1.png') }}" class="img-responsive">
          </div>
        </div>
      </div>
      <!-- Last opened mobile  -->
      <div class="col-xs-7 col-sm-9 visible-xs visible-sm">
        <div class="row" >
          <label class="pull-right">zuletzt geöffnet</label>
        </div>
        <div class="row pull-right" style="margin-top:24px">

          <div class="col-xs-2 col-sm-1 prev_viewed_docs_m">
           <img src="{{ asset('static/img/docs/doc1.png') }}"class="img-responsive">
          </div>
          <div class="col-xs-2 col-sm-1 prev_viewed_docs_m">
           <img src="{{ asset('static/img/docs/doc2.png') }}" class="img-responsive">
          </div>
          <div class="col-xs-2 col-sm-1 prev_viewed_docs_m">
           <img src="{{ asset('static/img/docs/doc3.png') }}" class="img-responsive">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <div class="card dashboard_card">
    <div class="body">

    </div>
  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <div class="card dashboard_card">
    <div class="body">

    </div>
  </div>
</div>
</div>
@endsection


@section('scripts')
<script src="{{ asset('static/js/dashboard.js') }}" defer></script>
@endsection
