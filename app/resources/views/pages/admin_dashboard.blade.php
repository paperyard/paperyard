@extends('layouts.admin_layout')

@section('page_title', '')

@section('active_admin_dashboard', 'p_active_nav')

@section('custom_style')
<link href="{{ asset('static/css/admin_dashboard.css') }}" rel="stylesheet">
<style type="text/css" media="screen">
  .scanningBG {
  	background-color: #f2f6fa !important;
  }
</style>
@endsection

@section('content')
<div class="row" id="dashboard_container">

    <div class="col-md-12" id="imgScann">
        <div class="alert alert-info">
            <p>Please wait while OCR is running on the document. page will automatically refresh if the document is ready.</p>
        </div>
        <img  src="{{ asset('static/img/scanning.gif')}}" class="img-responsive center-block" style="height:400px">
    </div>

<!--
    @if(!empty(session('fileDeleted')))
    <div class="alert alert-success">
        <strong>{{ session('fileDeleted') }}</strong>
    </div>
    @endif -->

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="table_docs">
        <div class="card">
            <div class="header">
                <h2 onclick="showSuccessMessage()">
                    DOCUMENTS
                    <small>upload scanned document </small>
                </h2>

                <hr>
                <form method="#" action="#" id="ocr_form"  enctype="multipart/form-data" >
                    @csrf
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="file" class="form-control" placeholder="Select a scanned pdf" name="doc_file" accept="image/png,image/jpeg,application/pdf" id="docf">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-primary btn-lg m-l-15 waves-effect">UPLOAD AND RUN OCR</button>
                        </div>
                    </div>
                </form>

                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:void(0);">Action</a></li>
                            <li><a href="javascript:void(0);">Another action</a></li>
                            <li><a href="javascript:void(0);">Something else here</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead style="background-color:#017cff; color:#fff">
                        <tr>
                            <th>DOCUMENT</th>
                            <th>DATE PROCESSED</th>
                            <th class="text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(count($ocr_documents)>0)
                        @foreach($ocr_documents as $docs)
                        <tr>
                            <td>{{ $docs->doc_ocr }}</td>
                            <td>{{ $docs->created_at }}</td>
                            <td class="text-right">
                                <button type="button" class="btn bg-red btn-circle waves-effect waves-circle waves-float" onclick="window.location='{{ url('remove_file/') .'/'. $docs->id .'/'.$docs->doc_origin .'/'.$docs->doc_ocr .'/'.$docs->doc_img  }}'">
                                    <i class="material-icons">delete_forever</i>
                                </button>
                                <a href="{{asset('static/documents/' . $docs->doc_ocr )}}">
                                    <button type="button" class="btn bg-cyan btn-circle waves-effect waves-circle waves-float">
                                        <i class="material-icons">cloud_download</i>
                                    </button>
                                </a>
                                <button type="button" class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

<script src="{{ asset('static/js/admin_dashboard.js') }}"></script>
<script type="text/javascript">

    $('#imgScann').hide();

    $('#ocr_form').on('submit', function(e){
        e.preventDefault();

        if($('#docf').val()==''){
            alert('please select a file')
        }else{
            showSuccessMessage();

            $('#table_docs').hide();
            $('#imgScann').show();
            $('#dashboard_container').addClass('scanningBG');
            $('#dashboard_bd').addClass('scanningBG');

            var form = $('#ocr_form');
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            var formAction = form.attr('action');
            $.ajax({
                url: '/run_ocrmypdf',
                data: formdata ? formdata : form.serialize(),
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if(data=="complete"){
                        window.location.reload();
                    }
                },
            timeout:60000,//1 minute timeout
            }).fail(function(jqXHR, textStatus){
                if(textStatus === 'timeout')
                {
                    window.location.reload();
                }
            }); //end fail function
            }
    }); //end ocr_form submit

    function showSuccessMessage() {
        swal("Upload successful", "we are now applying ocr in your pdf", "success");
    }

</script>
@endsection

