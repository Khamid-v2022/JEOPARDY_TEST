@extends('layouts/blankLayout')

@section('title', 'Forgot Password')

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner mt-5">
            <!-- Login -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="#" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{asset('assets/img/icons/brands/brand.png')}}">
                            </span>
                            <span class="app-brand-text demo text-body fw-bolder">{{ env('APP_SHORT_NAME') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Forgot Password? 🔒</h4>
                    <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>

                    <form id="forgotPasswordForm" class="mb-3" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus autocomplete="off" required />
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary w-100" id="submit_btn" type="submit">Send Reset Link <i class="fas fa-spinner fa-spin ms-2 d-none"></i></button>
                        </div>
                    </form>

                    <p class="text-center">
                        <a href="{{route('pages-login')}}">
                            <span>Back to login</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Login -->
           
        </div>

        <div class="login-footer text-center mt-5">
            <p>Copyright JStudyGuide <script>document.write(new Date().getFullYear())</script>. All Rights Reserved</p>
            <p>J!Study is a product of loyal fans to our favorite game show. <br>
            The Jeopardy! game show and all elements thereof, including but not limited to copyright and trademark thereto, are the property of Jeopardy Productions, Inc. / Sony Inc. and are protected under law.</p> 
            This website is not affiliated with, sponsored by, or operated by Jeopardy Productions, Inc. or Sony Inc.
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/login.js')}}"></script>
@endsection