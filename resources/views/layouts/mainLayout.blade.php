@extends('layouts/commonLayout')

@section('layoutContent')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{asset('assets/img/icons/brands/brand.png')}}">
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-1">Study Sim</span>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner mt-4 py-1">
                    @php
                    $currentRouteName = Route::currentRouteName();
                    @endphp

                    <!-- Dashboard -->
                    <li class="menu-item {{ $currentRouteName == 'pages-dashboard' ? 'active' : ''}}">
                        <a href="/" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $currentRouteName == 'pages-jeopardy-test' ? 'active' : ''}}">
                        <a href="{{ route('pages-jeopardy-test') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-collection"></i>
                            <div data-i18n="Basic">Take New Test</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $currentRouteName == 'pages-my-tests' ? 'active' : ''}}">
                        <a href="/my-tests" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-notepad"></i>
                            <div data-i18n="Analytics">My Test Results</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="https://www.jstudyguide.com/" class="menu-link" target="_blank">
                            <i class="menu-icon tf-icons bx bx-book-reader"></i>
                            <div data-i18n="Analytics">Study Guides</div>
                        </a>
                    </li>
                    
                    
                    @if(Auth::user()->subscription_status == 0)
                    @php
                        $remain_trail_test_times = Auth::user()->remain_trail_test_times;
                    @endphp
                    <li class="free-test-expire-wrapper">
                        <div class="free-test-expire">
                            @if($remain_trail_test_times == 0)
                            <h3>Your Free Trial Has Expired - Subscribe Now!</h3>
                            @else
                            <h3>You have {{$remain_trail_test_times}} {{$remain_trail_test_times == 1 ? 'day' : 'days'}} of Free Trial left.</h3>
                            @endif
                            <div class="plan-statistics">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold mb-2">Days</span>
                                    <span class="fw-semibold mb-2">{{env('FREE_TRIAL_TIMES') - $remain_trail_test_times}} of {{env('FREE_TRIAL_TIMES')}} Days</span>
                                </div>
                                <div class="progress" style="height: 8px">
                                    <div class="progress-bar" role="progressbar" style="width:{{(env('FREE_TRIAL_TIMES') - $remain_trail_test_times) / env('FREE_TRIAL_TIMES') * 100}}%" aria-valuenow="{{(env('FREE_TRIAL_TIMES') - $remain_trail_test_times) / env('FREE_TRIAL_TIMES') * 100}}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <a class="btn btn-primary mt-3 w-100" href="{{ route('pages-pricing') }}">Upgrade Account</a>
                        </div>
                    </li>
                    @endif
                </ul>

            </aside>

            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar" >
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div>
                            <span data-bs-toggle="tooltip" data-bs-placement="right" title="Current Streak" class="cursor-pointer"><span class="current-streak">{{ $streak_days }}</span> {{ $streak_days > 1 ? "Days" : "Day"}} ⚡️</span>
                        </div>
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            @if(Auth::user()->subscription_status == 0)
                            <li class="d-none d-sm-flex">
                                <a class="btn btn-primary me-3" href="{{ route('pages-pricing') }}">Upgrade Account</a>
                            </li>
                            @endif
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown d-flex align-items-center">
                                <span class="me-2 d-none d-md-block">Account</span>
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : asset('assets/img/avatars/default.png') }}" alt class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : asset('assets/img/avatars/default.png') }}" alt class="rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{Auth::user()->email}}</span>
                                                    <small class="text-muted">{{Auth::user()->name}}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    @if(Auth::user()->subscription_status == 0)
                                    <li>
                                        <a class="dropdown-item text-success" href="{{ route('pages-pricing') }}">
                                            <i class="bx bx-upvote me-2 text-success"></i>
                                            <span class="align-middle">Upgrade Account</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('my-profile') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pages-billing') }}">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                                <span class="flex-grow-1 align-middle">Billing</span>
                                                <!-- <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span> -->
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{route('logout')}}">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @if(Auth::user()->subscription_status == 0 && Auth::user()->is_trial_used == 0 && Route::current()->getName() != 'pages-jeopardy-test')
                        <div class="container-xxl">
                            <div class="free-test-notification-wrapper mt-4">
                                <div class="free-test-notification">
                                    <div class="message">You haven't completed today's test yet. Click <a href="/jeopardy-test">here</a> to take the test.</div>
                                    <a class="close-btn">✖</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                Copyright JStudyGuide <script>document.write(new Date().getFullYear())</script>. All Rights Reserved
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
@endsection