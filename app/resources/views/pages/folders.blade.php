@extends('layouts.app')

@section('page_title', 'Folders')

@section('active_folder', 'p_active_nav')

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
	background-color:#b1d5ff
}
.w_folder {
	margin-top:50px
}
.w_folder_icon {
	color:#b1d5ff; font-size:100px
}
.w_folder_tx {
	color:#999; font-size:22px
}

</style>
@endsection

@section('content')
<div>
	<center>
		<div class="w_folder">
			<div><i class="fa fa-folder-open w_folder_icon"></i></div><br>
			<div><p class="w_folder_tx">
				@lang('folders.w_folder_tx1')<br>
				@lang('folders.w_folder_tx2')
			</p></div><br>
			<div>
				<button class="btn-flat btn_color main_color waves-effect lg-btn_x2" type="submit"><span class="lg-btn-tx">@lang('folders.w_folder_btn_tx')</span></button>
			</div>
		</div>
	</center>
</div>
@endsection

@section('scripts')

@endsection
