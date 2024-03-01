@extends('layouts/commonLayout')
@php
$currentRouteName = Route::currentRouteName();
@endphp
@section('layoutContent')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="container-xxl ">
            <div class="navbar-brand app-brand demo d-flex py-0 me-4">
                <ul class="menu-inner" style="margin-left: 0px;">
                    <li class="menu-item {{ $currentRouteName == 'question-management-page' ? 'active' : ''}}">
                        <a href="{{ route('question-management-page') }}" class="menu-link">
                            <i class='bx bx-chat'></i>
                            <div>Questions</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $currentRouteName == 'feature-question-page' ? 'active' : ''}}">
                        <a href="{{ route('feature-question-page') }}" class="menu-link">
                            <i class='bx bx-chat'></i>
                            <div>Featured Tests</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $currentRouteName == 'user-management-page' ? 'active' : ''}}">
                        <a href="{{ route('user-management-page') }}" class="menu-link">
                            <i class='bx bx-user-check' ></i>
                            <div>Users</div>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="navbar-nav flex-row align-items-center ms-auto" id="navbar-collapse">
                <ul class="navbar-nav flex-row align-items-center ms-2">
                    <!-- User -->
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="dropdown-item" href="{{route('admin-logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Logout</span>
                        </a>
                        <form method="GET" id="logout-form" action="{{route('admin-logout')}}" style="margin: 0px">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </li>
                    <!--/ User -->
                </ul>
            </div>
        </div>
    </nav>

    <div class="">
        <!-- Content wrapper -->
        <div class="content-wrapper">
           
            <div class="container-fluid flex-grow-1 container-p-y">
                @yield('content')
            </div>
            <!-- / Content -->

            <div class="content-backdrop fade"></div>
        </div>
        <!--/ Content wrapper -->
    </div>
@endsection