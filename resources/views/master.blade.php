
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ url("/css/app.css") }}" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @yield("title")

    <style>
        #loader {
            transition: all 0.3s ease-in-out;
            opacity: 1;
            visibility: visible;
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            z-index: 90000;
        }

        #loader.fadeOut {
            opacity: 0;
            visibility: hidden;
        }

        .spinner {
            width: 40px;
            height: 40px;
            position: absolute;
            top: calc(50% - 20px);
            left: calc(50% - 20px);
            background-color: #333;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
            animation: sk-scaleout 1.0s infinite ease-in-out;
        }

        @-webkit-keyframes sk-scaleout {
            0% { -webkit-transform: scale(0) }
            100% {
                -webkit-transform: scale(1.0);
                opacity: 0;
            }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            } 100% {
                  -webkit-transform: scale(1.0);
                  transform: scale(1.0);
                  opacity: 0;
              }
        }
    </style>
</head>
<body class="app">
<!-- @TOC -->
<!-- =================================================== -->
<!--
  + @Page Loader
  + @App Content
      - #Left Sidebar
          > $Sidebar Header
          > $Sidebar Menu

      - #Main
          > $Topbar
          > $App Screen Content
-->

<!-- @Page Loader -->
<!-- =================================================== -->
<div id='loader'>
    <div class="spinner"></div>
</div>

<script>
    window.addEventListener('load', function load() {
        const loader = document.getElementById('loader');
        setTimeout(function() {
            loader.classList.add('fadeOut');
        }, 300);
    });
</script>

<!-- @App Content -->
<!-- =================================================== -->
<div>
    <!-- #Left Sidebar ==================== -->
    <div class="sidebar">
        <div class="sidebar-inner">
            <!-- ### $Sidebar Header ### -->
            <div class="sidebar-logo">
                <div class="peers ai-c fxw-nw">
                    <div class="peer peer-greed">
                        <a class="sidebar-link td-n" href="{{ url("/") }}">
                            <div class="peers ai-c fxw-nw">
                                <div class="peer">
                                    <div class="logo" style="display:flex; align-items:center;">
                                        <img width="30" src="{{ url("/images/logo.png") }}" alt="Ticket's logo" class="mx-auto">
                                    </div>
                                </div>
                                <div class="peer peer-greed">
                                    <h5 class="lh-1 mB-0 logo-text">Ticket</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="peer">
                        <div class="mobile-toggle sidebar-toggle">
                            <a href="" class="td-n">
                                <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ### $Sidebar Menu ### -->
            <ul class="sidebar-menu scrollable pos-r">

                <li class="nav-item mT-30 actived">
                    <a class="sidebar-link" href="{{ url("/") }}">
                <span class="icon-holder">
                  <i class="c-blue-500 fa fa-home"></i>
                </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="dropdown-toggle" href="javascript:void(0);">
                <span class="icon-holder">
                  <i class="c-orange-500 fa fa-users"></i>
                </span>
                        <span class="title">Users</span>
                        <span class="arrow">
                  <i class="fa fa-angle-right"></i>
                </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>

                            <a class='sidebar-link' href="{{ url("/users/create") }}">
                                <i class="fa fa-user-plus"></i> Create User
                            </a>
                        </li>
                        <li>
                            <a class='sidebar-link' href="{{ url("/users") }}">
                                <i class="fa fa-users"></i> View All Users
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="dropdown-toggle" href="javascript:void(0);">
                <span class="icon-holder">
                  <i class="c-red-500 fa fa-user-secret"></i>
                </span>
                        <span class="title">Admins</span>
                        <span class="arrow">
                  <i class="fa fa-angle-right"></i>
                </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>

                            <a class='sidebar-link' href="{{ url("/admins/create") }}">
                                <i class="fa fa-user-plus"></i> Create Admin
                            </a>
                        </li>
                        <li>
                            <a class='sidebar-link' href="{{ url("/admins") }}">
                                <i class="fa fa-users"></i> View All Admins
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="dropdown-toggle" href="javascript:void(0);">
                <span class="icon-holder">
                  <i class="c-teal-500 fa fa-calendar"></i>
                </span>
                        <span class="title">Mass</span>
                        <span class="arrow">
                  <i class="fa fa-angle-right"></i>
                </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>

                            <a class='sidebar-link' href="{{ url("/masses/create") }}">
                                <i class="fa fa-plus"></i> Create Event
                            </a>
                        </li>
                        <li>
                            <a class='sidebar-link' href="{{ url("/masses") }}">
                                <i class="fa fa-calendar"></i> View All
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- #Main ============================ -->
    <div class="page-container">
        <!-- ### $Topbar ### -->
        <div class="header navbar">
            <div class="header-container">
                <ul class="nav-left">
                    <li>
                        <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                    <li class="search-box">
                        <a class="search-toggle no-pdd-right" href="javascript:void(0);">
                            <i class="search-icon fa fa-search pdd-right-10"></i>
                            <i class="search-icon-close fa fa-close pdd-right-10"></i>
                        </a>
                    </li>
                    <li class="search-input">
                        <input class="form-control" type="text" placeholder="Search...">
                    </li>
                </ul>
                <ul class="nav-right">
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                            <div class="peer mR-10">
                                <img class="w-2r bdrs-50p" src="{{ auth()->user()->picture }}" alt="">
                            </div>
                            <div class="peer">
                                <span class="fsz-sm c-grey-900">{{ auth()->user()->name }}</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu fsz-sm">
                            <li>
                                <a href="{{ url("settings") }}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                    <i class="fa fa-cogs mR-10"></i>
                                    <span>Setting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url("users/" . auth()->user()->username) }}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                    <i class="fa fa-user mR-10"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="{{ url("/logout") }}" class="d-b td-n pY-5 bgcH-grey-100 c-red-500">
                                    <i class="fa fa-power-off mR-10"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!-- ### $App Screen Content ### -->
        <main class='main-content bgc-grey-100'>
            <div id='mainContent'>

                @yield("content")

            </div>
        </main>

        <!-- ### $App Screen Footer ### -->
        <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
            <span>Copyright © 2020 Created by Fadi Sarwat. All rights reserved.</span>
        </footer>
    </div>
</div>

<script src="{{ url("js/app.js") }}"></script>
@include("flash")

@yield("javascript")

</body>
</html>
