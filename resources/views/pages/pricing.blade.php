@extends('layouts/mainLayout')

@section('title', 'Pricing page')



@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Pricing Plans -->
    <div class="pb-sm-5 pb-2 rounded-top">
        <div class="container py-5">
            <h2 class="text-center mb-2 mt-0 mt-md-4">Super Simple Pricing</h2>
            <p class="text-center pb-3"> Pick between our two simple plans. With our Annual Plan, you get access to a few more perks! </p>
            
            <div class="row mx-0 gy-3 px-lg-5">
                <!-- Monthly -->
                <div class="col-lg">
                    <div class="card border rounded shadow-none">
                        <div class="card-body">
                            <div class="my-3 pt-2 text-center">
                                <img src="{{asset('assets/img/icons/unicons/briefcase-round.png')}}" alt="Pro Image" height="80">
                            </div>
                            <h3 class="card-title text-center text-capitalize fw-semibold mb-1">Monthly</h3>
                            <p class="text-center">For the trivia hobbyist</p>

                            <div class="text-center">
                                <div class="d-flex justify-content-center">
                                    <sup class="h6 text-primary pricing-currency mt-3 mb-0 me-1">$</sup>
                                    <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0">{{env('MONTHLY_PLAN_PRICE')}} </h1>
                                    <sub class="h6 pricing-duration mt-auto mb-2 fw-normal text-muted">/month</sub>
                                </div>
                                
                            </div>

                            <ul class="ps-3 my-4 list-unstyled">
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access to online test sim</li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> 10 tests per month </li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Standard progress tracking </li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Thousands of clues</li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> New questions added every month</li>
                                <li class="mb-0"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access to Featured Tests</li>
                            </ul>

                            <a href="{{ route('pages-checkout', ['monthly'])}}" class="btn btn-primary d-grid w-100">Upgrade</a>
                        </div>
                    </div>
                </div>

                <!-- Annual -->
                <div class="col-lg mb-md-0 mb-4">
                    <div class="card border-primary border shadow-none">
                        <div class="card-body position-relative">
                            <div class="position-absolute end-0 me-4 top-0 mt-4">
                                <span class="badge bg-label-primary">Most Popular</span>
                            </div>
                            <div class="my-3 pt-2 text-center">
                                <img src="{{asset('assets/img/icons/unicons/wallet-round.png')}}" alt="Pro Image" height="80">
                            </div>
                            <h3 class="card-title fw-semibold text-center text-capitalize mb-1">Annual </h3>
                            <p class="text-center">For the future triva champ</p>
                            <div class="text-center">
                                <div class="d-flex justify-content-center">
                                    <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">$</sup>
                                    <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0">{{ env('ANNUALLY_PLAN_PRICE') }}</h1>
                                    <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/year</sub>
                                </div>
                            </div>

                            <ul class="ps-3 my-4 list-unstyled">
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access to online test sim</li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> <strong>Unlimted</strong> tests</li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> <strong>Advanced</strong> progress tracking</li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Thousands of clues</li>
                                <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> New questions added every month</li>
                                <li class="mb-0"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access to Featured Tests</li>
                            </ul>

                            <a href="{{ route('pages-checkout', ['annually']) }}" class="btn btn-primary d-grid w-100">Upgrade</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Pricing Plans -->
</div>
@endsection
