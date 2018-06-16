@extends('layouts.app')

@section('page_title', 'Notifications')

@section('active_notification', 'p_active_nav')

@section('custom_style')
<style type="text/css" media="screen">
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
	background-color:#b1d5ff;
}
.notify_w_tx {
	color:#999; font-size:22px;
}
.notify_ico {
	color:#b1d5ff; font-size:100px;
}
.notify_pos {
	margin-top:50px;
}
</style>
@endsection

@section('content')
<div>
	<center>
		<div class="notify_pos">
			<div><i class="fa fa-bell notify_ico"></i></div><br>
			<div>
				<p class="notify_w_tx">
					@lang('notifications.notify_f_m_1')<br>
					@lang('notifications.notify_f_m_2')
				</p>
			</div><br>
			<div>
				<button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">@lang('notifications.notify_f_btn_tx')</span></button>
			</div>
		</div>
	</center>
</div>
@endsection

@section('scripts')

@endsection
