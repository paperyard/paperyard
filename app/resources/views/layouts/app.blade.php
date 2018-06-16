<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{{ asset('static/img/favicon.ico') }}}" type="image/x-icon">
    <link rel="icon" href="{{{ asset('static/img/favicon.ico') }}}" type="image/x-icon">
    <title>{{ config('app.name', 'Paperyard') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('static/js/app.js') }}" defer></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link href="{{ asset('static/css/core_mix.css') }}" rel="stylesheet">

    <style type="text/css" media="screen">
    .ln_mg {
        margin-top:30px;
    }
    .sidebar {
        margin-right: -200px;
        margin-top:-10px;
    }
    @media screen and (max-width:500px) {
        .x_1 { margin-left: 0px !important; }
    }
    .router_link_icon {
        font-size:23px !important;
        margin-left:30px !important;
        color:#999!important;
    }
    .ul li a.selected {
        border-bottom:none;
    }
    .cstm_nav {
        background-color:#fff;
        height:65px;
    }
    .nv_bar {
        position:relative; float:right; width:70px; margin-top:-15px;
    }
    .nav_logo {
        padding:0px !important; margin:0px !important;
    }
    .nav_m_logo {
        height:45px; margin-left:10px
    }

    .p_active_nav {
        border-right:4px solid #017cff;
    }
    .p_active_nav span {
        color:#017cff !important;
    }
    .cstm_lst li{
        margin-top:25px;
    }
    .lang_flags {
        height:17px;
        cursor: pointer;
    }
</style>
@yield('custom_style')
</head>
<body style="background-color:#fff">
    <div id="app">
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
        <!-- #END# Page Loader -->
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <!-- #END# Overlay For Sidebars -->
        <!-- #END# Search Bar -->
        <!-- Top Bar -->
        <nav class="navbar cstm_nav navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="nv_bar">
                        <a href="javascript:void(0);" class="bars" ></a>
                    </div>
                    <a class="navbar-brand nav_logo" href="#">
                        <img src="{{ asset('static/img/paperyard_logo.png') }}" class="img-responsive nav_m_logo">
                    </a>
                </div>
                <div class="text-center visible-xs visble-sm" style="margin-top:-55px">
                    <p style="font-size:24px; height:50px; color:#017cff">Paperyard</p>
                </div>
                <div class="text-center hidden-xs hidden-sm">
                    <h4 style="color:#017cff; margin-top:22px">Paperyard</h4>
                </div>

            </div>
        </nav>
        <!-- #Top Bar -->
        <!-- Left Sidebar -->
        <section id="leftsidebar" class="sidebar" style="width:110px;">
            <!-- Menu -->
            <div class="menu" style="margin-top:10px">
                <ul class="list cstm_lst">
                    <li>
                        <a href="dashboard" class="waves-effect waves-blue @yield('active_dashboard')">
                            <span class="fa fa-dashboard router_link_icon"></span>
                        </a>
                    </li>
                    <li>
                        <a href="search" class="waves-effect waves-blue @yield('active_search')">
                            <span class="fa fa-search router_link_icon" ></span>
                        </a>
                    </li>
                    <li>
                        <a href="notifications" class="waves-effect waves-blue @yield('active_notification')">
                            <span class="fa fa-bell router_link_icon" ></span>
                        </a>
                    </li>
                    <li>
                        <a href="folders" class="waves-effect waves-blue @yield('active_folder')">
                            <span class="fa fa-folder-open router_link_icon" ></span>
                        </a>
                    </li>
                    <li>
                        <a href="share" class="waves-effect waves-blue @yield('active_share')">
                            <span class="fa fa-share-alt router_link_icon" ></span>
                        </a>
                    </li>
                    <li>
                        <a href="settings" class="waves-effect waves-blue @yield('active_settings')">
                            <span class="fa fa-gears router_link_icon"></span>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- #Menu -->
            <!-- #Footer -->
        </section>
        <!-- #END# Left Sidebar -->
        <section class="content x_1" style="margin-left:120px" >
 <div class="pull-right">
<a href="{{ route('logout') }}"
onclick="event.preventDefault();
document.getElementById('logout-form').submit();">
{{ __('Logout') }}
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
@csrf
</form>
</div>
<div class="container-fluid">
    <div class="row" style="margin-bottom:10px">
        <div class="col-md-12 col-sm-12 col-xs-12 ">
            <h3 style="margin:0px; padding:0px" class="pull-left">@yield('page_title')</h3>
            @if(View::hasSection('search_page_filter'))
            @yield('search_page_filter')
            @endif
            @if(View::hasSection('show_localization'))
            @yield('show_localization')
            @endif
        </div>
    </div>
    @yield('content')
</div>
</section>
</div>
<!-- defer ensures that DOM is loaded before executing scripts -->
<script src="{{ asset('static/js/core_mix.js') }}"></script>
@yield('scripts')
</body>
</html>
