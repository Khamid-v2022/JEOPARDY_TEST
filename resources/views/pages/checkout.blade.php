@extends('layouts/mainLayout')

@section('title', 'Checkout page')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-7 col-md-6">
            <div class="card my-3">
                <div class="card-body">
                     <!-- Credit Card Details -->
                     <div id="stripe-details-box">
                        <h6 class="fw-semibold">Card Details</h6>
                        <hr>
                        <div class="stripe-form">
                            <div class="row" id="new_stripe_card">
                                <div class="col-12 mt-2">
                                    <label class="form-label w-100" for="stripe_number">Card Number</label>
                                    <div class="input-group input-group-merge">
                                        <input id="stripe_number" name="stripe_number" class="form-control input-group-text credit-card-mask" type="text" placeholder="1356 3215 6548 7898" />
                                        <span class="input-group-text cursor-pointer p-1" id="stripe_number2"><span class="card-type"></span></span>
                                    </div>
                                    <input id="card_type" type="hidden">
                                </div>
                                <div class="col-12 col-md-6 mt-2">
                                    <label class="form-label" for="stripe_name">Name</label>
                                    <input type="text" id="stripe_name" name="stripe_name" class="form-control" placeholder=""/>
                                </div>
                                <div class="col-6 col-md-3 mt-2">
                                    <label class="form-label" for="stripe_exp">Exp. Date</label>
                                    <input type="text" id="stripe_exp" name="stripe_exp" class="form-control expiry-date-mask" placeholder="MM/YY"/>
                                </div>
                                <div class="col-6 col-md-3 mt-2">
                                    <label class="form-label" for="stripe_cvv">CVV Code</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="stripe_cvv" name="stripe_cvv" class="form-control input-group-text cvv-code-mask" maxlength="3" placeholder="654" />
                                        <span class="input-group-text cursor-pointer" id="stripe_cvv2"><i class="bx bx-help-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Card Verification Value"></i></span>
                                    </div>
                                </div>
                            </div>                         
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div>
                        <h6 class="fw-semibold mb-2 mt-5">Customer Details</h6>
                        <hr>
                        <form id="custom_detail_form">
                            <div class="row">
                                <div class="form-group mb-1 col-md-12">
                                    <label for="full_name" class="form-label">Full Name or Company Name</label>
                                    <input class="form-control" type="text" id="full_name" name="full_name" maxlength="50" value="{{ Auth::user()->name }}" />
                                </div>
                                <div class="form-group mb-1 col-md-12">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" maxlength="255" value="{{ Auth::user()->address }}" />
                                </div>
                                <div class="form-group mb-1 col-md-12">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" maxlength="50" value="{{ Auth::user()->city }}" />
                                </div>
                                <div class="form-group mb-1 col-md-12">
                                    <label for="zipCode" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="" maxlength="15" value="{{ Auth::user()->zipcode }}" />
                                </div>
                                <div class="form-group mb-1 col-md-12">
                                    <label class="form-label" for="country">Country</label>
                                    <select id="country" name="country" class="select2 form-select" value="{{ Auth::user()->country }}" >
                                        <option value="">Select</option>
                                      
                                        @foreach(config('variables.countries') as $country)
                                            @if($country == Auth::user()->country)
                                                <option value="{{ $country }}" selected>{{ $country }}</option>
                                            @else
                                                <option value="{{ $country }}">{{ $country }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-6">
            <div class="card my-3" id="payment_info_for_price">
                <h5 class="card-header gray-background">Your Purchase</h5>
                <div class="card-body">
                    <h4 class="fw-bold page-heading">
                        <span class="">{{ $plan }} Package</span>
                    </h4>
                    <p>Payment details for {{Auth::user()->name}}</p>
                    <div class="price-detail my-3">
                        <dl class="row mb-0">
                            <dt class="col-6 fw-semibold">Price:</dt>
                            <dd class="col-6 fw-semibold text-end">$<span class="total-price">{{ $price }}</span></dd>

                            <dt class="col-6 fw-semibold">Billing Method:</dt>
                            <dd class="col-6 fw-semibold text-end" id="billing_method">Credit Card</dd>

                            <dt class="col-6 fw-semibold">Billing Period:</dt>
                            @if($plan == "Monthly")
                            <dd class="col-6 text-end" id="billing_period" data-billing_period="month">Every Month</dd>
                            @elseif($plan == "Annually")
                            <dd class="col-6 text-end" id="billing_period" data-billing_period="year">Every Year</dd>
                            @endif
                            <hr class="mt-2">
                            <dt class="col-6 fw-bold" style="font-size:18px">Subtotal</dt>
                            <dd class="col-6 fw-bold text-end text-primary mb-0" style="font-size:18px">$<span class="total-price" id="total_price">{{ $price }}</span></dd>
                        </dl>
                        <div class="payment-btns">
                            <!-- Credit Card - Stripe -->
                            <div class="payment-form row stripe-form" id="stripe_form">
                                <div class="col-12 mt-5">
                                    <form id="checkout_form" data-stripe-publishable-key="{{ env('STRIPE_PUBLICK_KEY') }}">
                                        <button class="btn btn-primary w-100 payment-button d-none" type="submit">
                                            Subscribe</span>
                                            <i class="fas fa-spinner fa-spin" style="display:none"></i>
                                        </button>
                                        <div class="payment-fake-button" title="Fill in the details" data-bs-toggle="tooltip" data-bs-placement="top">
                                            <button class="btn btn-primary w-100" type="button" disabled>
                                                Subscribe</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- / Credit Card - Stripe -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/checkout.js')}}"></script>
@endsection