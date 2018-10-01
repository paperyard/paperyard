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
        height: 100%;
    }
    @media screen and (max-width:500px) {
        .x_1 { margin-left: 10px !important; }
        .navbar-collapse {
            border-top:1px solid #ccc;
            height:55px;
        }
        .m-drop-menu {
            border:1px solid #ccc !important;
        }
    }

    @media handheld and (min-width: 1200px), 
      screen and (min-width: 1200px){
         .m_tab_view {
            margin-left:140px !important;
         }
    }

    @media handheld and (max-width: 1200px), 
      screen and (max-width: 1200px){
        .nav_logo_hide {
            visibility: hidden;
        }
    }


    .router_link_icon {
        font-size:23px !important;
        margin-left:30px !important;
        color:#7e7e7e!important;
    }
    .ul li a.selected {
        border-bottom:none;
    }
    .cstm_nav {
        background-color:#fff;
        height:65px;
    }
    .nv_bar {
        position:relative; float:right; width:70px; margin-top:-15px; margin-right:35px;
    }
    .nav_logo {
        padding:0px !important; margin:0px !important;
    }
    .nav_m_logo {
        height:45px; margin-left:10px
    }
    .p_active_nav {
        border-right:5px solid #017cff;
    }
    .p_active_nav span {
        color:#017cff !important;
    }
    .cstm_lst li{
        margin-top:7px;
        -webkit-transition: background-color 0.4s;
           -moz-transition: background-color 0.4s;
            -ms-transition: background-color 0.4s;
             -o-transition: background-color 0.4s;
                transition: background-color 0.4s;
    }
    .cstm_lst li:hover{
        background-color:#e2e2e2;

    }
    .lang_flags {
        height:17px;
        cursor: pointer;
    }
    .lang-txt a:hover{
        background-color: #fff !important; cursor:default !important;
    }
    .custom_nav_icons {
        color:#017cff !important;
    }
    .ac_set_txt {
       text-decoration: none;
       color:#000;
    }


</style>
@yield('custom_style')

</head>

<body style="background-color:#fff" ng-app="rootApp" ng-controller="rootController">
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
                <p>PAPERYARD </p>
            </div>
        </div>
        <!-- #END# Page Loader -->
        <div class="overlay"></div>
        <!-- #END# Overlay For Sidebars -->

        <!-- Top Bar -->
    <nav class="navbar" style="background-color:white!important;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed custom_nav_icons" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars custom_nav_icons" style="height:50px"></a>
                <a class="navbar-brand nav_logo hidden-sm hidden-xs nav_logo_hide" href="#">
                    <img src="{{ asset('static/img/paperyard_logo.png') }}" class="img-responsive nav_m_logo">
                </a>
                <div style="position: absolute; left: 50%;" class="visible-sm visible-xs">
                    <div style="position: relative; left: -50%; ">
                          <h4 style="color:#017cff; margin-top:15px">Paperyard</h4>
                    </div>
                </div>

            </div>
            <div style="position: absolute; left: 50%;" class="hidden-sm hidden-xs">
                <div style="position: relative; left: -50%; margin-top:18px">

                    @if(View::hasSection('breadcrumb_nav'))
                       @yield('breadcrumb_nav')
                    @else
                       <h4 style="color:#017cff; margin-top:25px;">Paperyard</h4>
                    @endif

                </div>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
            <center>
                <ul class="nav navbar-nav navbar-right" style="margin-right:0px;">
                        <!-- Tasks -->
                        <li class="dropdown" >
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                <i class="material-icons custom_nav_icons">person</i>
                                <span class="label-count"></span>
                            </a>
                            <ul class="dropdown-menu m-drop-menu">
                                 <li class="header">{{ Auth::user()->name }}</li>
                                    <li class="body">
                                        <ul class="menu list-unstyled">
                                            <li onclick="window.location='{{ url('account_settings') }}'">
                                                <a href="#" class="ac_set_txt"><label>Account settings</label></a>
                                            </li>
                                            <li class="lang-txt">
                                                <a href="#" class="ac_set_txt">
                                                     <label>Select @lang('home.c_language_tx')</label>
                                                </a>
                                            </li>
                                             <li onclick="window.location='{{ url('language/ge') }}'">
                                                <a href="#" class="ac_set_txt">
                                                    <span>German</span>
                                                    <img src="{{asset('static/img/language/german_flag.png')}}"  class="lang_flags pull-right">
                                                </a>
                                            </li>
                                             <li onclick="window.location='{{ url('language/en') }}'">
                                                <a href="#" class="ac_set_txt">
                                                    <span>English</span>
                                                     <img src="{{asset('static/img/language/america_flag.png')}}" class="lang_flags pull-right">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();" class="ac_set_txt">
                                                    <label>{{ __('Logout') }}</label>
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>

                                        </ul>
                                    </li>
                            </ul>
                        </li>
                        <!-- #END# Tasks -->
                         <!-- Notifications  ADD ON FEATURE http://shrestha-manoj.blogspot.com/2014/06/can-angularjs-have-multiple-ng-app.html-->
                        <!-- <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                <i class="material-icons custom_nav_icons">notifications</i>
                                <span class="label-count" style="background-color:red !important; color:white!important">3</span>
                            </a>
                            <ul class="dropdown-menu m-drop-menu">
                                <li class="header">NOTIFICATIONS</li>
                                <li class="body">
                                    <ul class="menu list-unstyled">
                                        <li>
                                            <a href="javascript:void(0);">
                                                <div class="icon-circle bg-light-blue">
                                                    <i class="material-icons">picture_as_pdf</i>
                                                </div>
                                                <div class="menu-info">
                                                    <h4>3 new document received.</h4>
                                                    <p>
                                                        <i class="material-icons">access_time</i> 14 mins ago
                                                    </p>
                                                </div>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="javascript:void(0);">View All Notifications</a>
                                </li>
                            </ul>
                        </li> -->
                        <!-- #END# Notifications -->

                    </ul>
            </center>
            </div>
        </div>
    </nav>

        <!-- Left Sidebar -->
        <section id="leftsidebar" class="sidebar" style="width:110px;">
            <!-- Menu -->
            <div class="menu" style="margin-top:20px">
                <ul class="list cstm_lst">
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="Dashboard">
                        <a href="/dashboard" class="waves-effect waves-blue @yield('active_dashboard')">
                            <span class="fa fa-dashboard router_link_icon"></span>
                        </a>
                    </li>
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="Search Documents">
                        <a href="/search" class="waves-effect waves-blue @yield('active_search')">
                            <span class="fa fa-search router_link_icon" ></span>
                        </a>
                    </li>
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="Notifications">
                        <a href="/notifications" class="waves-effect waves-blue @yield('active_notification')">
                            <span class="fa fa-bell router_link_icon" ></span>
                        </a>
                    </li>
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="Reminders">
                        <a href="/reminders" class="waves-effect waves-blue @yield('active_reminder')">
                            <span class="fa fa-calendar-check-o router_link_icon" ></span>
                        </a>
                    </li>
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="Folders">
                        <a href="/folders" class="waves-effect waves-blue @yield('active_folder')">
                            <span class="fa fa-folder-open router_link_icon" ></span>
                        </a>
                    </li>
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="Shared Documents">
                        <a href="/share" class="waves-effect waves-blue @yield('active_share')">
                            <span class="fa fa-share-alt router_link_icon" ></span>
                        </a>
                    </li>
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="System Settings">
                        <a href="/settings" class="waves-effect waves-blue @yield('active_settings')">
                            <span class="fa fa-gears router_link_icon"></span>
                        </a>
                    </li>
                    <li data-toggle="tooltip" data-placement="right" title="" data-original-title="Upload Documents">
                        <a href="/upload_documents" class="waves-effect waves-blue @yield('active_upload')">
                            <span class="fa fa fa-upload router_link_icon"></span>
                        </a>
                    </li>


                </ul>
            </div>
            <!-- #Menu -->
        </section>
        <!-- #END# Left Sidebar -->
        <section class="content x_1 m_tab_view">

            <div class="container-fluid">
                <div class="row" style="margin-bottom:10px">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <h3 style="margin:0px; padding:0px" class="pull-left">@yield('page_title')</h3>

                        @if(View::hasSection('search_page_filter'))
                           @yield('search_page_filter')
                        @endif
                        @if(View::hasSection('new_notification_btn'))
                           @yield('new_notification_btn')
                        @endif
                        @if(View::hasSection('doc_pages'))
                           @yield('doc_pages')
                        @endif
                        @if(View::hasSection('notification_del_btn'))
                           @yield('notification_del_btn')
                        @endif
                    </div>
                </div>
                @yield('content')
            </div>
        </section>
    </div>
    <!-- defer ensures that DOM is loaded before executing scripts -->
    <script src="{{ asset('static/js/core_mix.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });

            //Popover
            $('[data-toggle="popover"]').popover();
        })

        //root app------------------------------------------------------------------
        var rootApp = angular.module('rootApp', ['app'], function($interpolateProvider) {
            $interpolateProvider.startSymbol('<#');
            $interpolateProvider.endSymbol('#>');
        });

        rootApp.controller('rootController', function($scope, $http, $timeout, $compile) {

        });


    </script>
    @yield('scripts')
</body>
</html>
