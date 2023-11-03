@extends('layouts/commonLayout')

@section('layoutContent')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <div class="container-xxl">
                    @yield('content')

                    <!-- Footer -->
                </div>
            </div>
        </div>
    </div>
@endsection