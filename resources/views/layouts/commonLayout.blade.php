<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

        <title>@yield('title') - {{env('APP_NAME')}}</title>

        <meta name="description" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}" >

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon/favicon.ico')}}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="{{asset('assets/vendor/fonts/boxicons.css')}}" />
        <script src="https://kit.fontawesome.com/930584b15c.js" crossorigin="anonymous"></script>

        <!-- Core CSS -->
        <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
        
        @yield('vendor-style')

        <!-- Page CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/global.css')}}" />
        @yield('page-style')

        <!-- Helpers -->
        <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>
        <script src="{{asset('assets/js/config.js')}}"></script>
    </head>

    <body>
        @yield('layoutContent')
    
        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
        <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
        <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
        <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

        <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
        @yield('vendor-script')


        <script src="{{asset('assets/js/global.js')}}"></script>
        <!-- Page JS -->
        @yield('page-script')
   
    </body>
</html>
