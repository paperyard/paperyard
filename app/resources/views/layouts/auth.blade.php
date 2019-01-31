<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{{ asset('static/img/paperyard_logo.png') }}}">
    <title>{{ config('app.name', 'Paperyard') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('static/js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('static/css/core_mix.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   
    <style type="text/css" media="screen">
         .main_color {
            color:#017cff;
         }
         .pw_p {
            font-size:18px;
            font-weight: bold;
            margin-top:-7px;
         }
         .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            color: #017cff;
            text-align: center;
            background-color:#fff;
          }
          .nav_h {
            height:65px;
            background-color:#fff;
          }
          .cmp_logo {
            position:absolute;
            height:45px;
          }
          .authbdy {
            background-color:#fff
          }
          .ppyrd_txt {
             margin-top:23px;
          }
          .customInputStyle::-webkit-input-placeholder {
          font-style: italic;
          }
          .customInputStyle:-moz-placeholder {
             font-style: italic;
          }
          .customInputStyle::-moz-placeholder {
             font-style: italic;
          }
          .customInputStyle:-ms-input-placeholder {
             font-style: italic;
          }
          .imprint {
            cursor: pointer;
          }
          .lang_tx {
            cursor: pointer;
          }
          @yield('style')
    </style>
</head>
<body class="authbdy">

  <!-- Page Loader -->
  <div class="page-loader-wrapper">
    <div class="loader">
      <div class="preloader">
        <div class="spinner-layer pl-light-blue">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
      <p>PAPERYARD</p>
    </div>
  </div>

  <div id="app" >
    <!-- Top Bar -->
    <nav class="navbar cstm_nav">
      <div class="container-fluid nav_h">
        <div class="navbar-header">
          <img src="{{ asset('static/img/paperyard_logo.png') }}" class="img-responsive cmp_logo">
        </div>
        <div class="navbar-btn main_color">
          <h4 class="text-center hidden-xs hidden-sm ppyrd_txt" style="">Paperyard</h4>
          <p class="text-center align-middle pw_p visible-xs">Paperyard</p>
        </div>
      </div>
    </nav>
    <main class="container">
      @yield('content')
    </main>
    <div class="footer">
      <div class="container">
        <ul class="list-inline center-block">
          <li>
            <p>@lang("auth.select_lang_tx")</p>
          </li>
          <li>
            <label class="lang_tx" onclick="window.location='{{ url('language/ge') }}'">German</label>
          </li>
          <li>
            <label class="lang_tx" onclick="window.location='{{ url('language/en') }}'">English</label>
          </li>
        </ul>
      </div>
      <div class="col-md-6 col-md-12 col-xs-12">
         <p class="pull-left"><a href="http://www.consider-it.de/impressum" class="imprint">@lang('auth.footer_txt')</a></p>
      </div>
      <div class="col-md-6 col-md-12 col-xs-12">   
        <label class="pull-right">v 0.5.2</label>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('static/js/core_mix.js') }}" ></script>
  <script type="text/javascript">
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  </script>
  @yield('scripts')

</body>
</html>
