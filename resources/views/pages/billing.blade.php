@extends('layouts/mainLayout')
@section('title', 'Billing page')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    @if(Auth::user()->subscription_status == 1)
    <div class="card mb-4">
        <!-- Current Plan -->
        <h5 class="card-header">Current Plan</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Your Current Plan is {{ Auth::user()->subscription_plan }}</h6>
                        @if(Auth::user()->subscription_plan == "Monthly")
                            <p class="">You can do {{ env('MONTHLY_PLAN_TEST_COUNT') }} tests per month.</p>
                            <label>Started This Month at:</label> <strong>{{ $started_this_month }}</strong>
                            <p>And you have used {{ $tested_count }} out of {{ env('MONTHLY_PLAN_TEST_COUNT') }} attempts.</p>
                        @elseif(Auth::user()->subscription_plan == "Annually")
                            <p>You can do unlimited tests.</p>
                        @endif
                    </div>
                    <div class="col-12">
                        <button class="btn btn-secondary" id="cancel_subscription">Cancel Subscription</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Current Plan -->
    </div>
    @else
    <div class="card mb-4">
        <!-- Current Plan -->
        <h5 class="card-header">Current Plan</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Your Current Plan is FREE</h6>
                        @if(Auth::user()->is_trial_used == 1)
                            <p>
                                You have already done the FREE test.<br>
                                Please upgrade your account to unlock infinite testing features.
                            </p>
                        @else
                            <p>
                                You can do only one FREE test.<br>
                                If you would like to do unlimit tests, please upgrade your account.
                            </p>
                        @endif
                    </div>
                    
                </div>
                <div class="col-md-6 mb-1">
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2"><span class="me-2">${{ env('MONTHLY_PLAN_PRICE') }} Per Month</span></h6>
                        <h6 class="fw-semibold mb-2"><span class="me-2">${{ env('ANNUALLY_PLAN_PRICE') }} Per Year</span> <span class="badge bg-label-primary">Popular</span></h6>
                        <p>Unlock a unlimit test access feature</p>
                    </div>
                </div>
                <div class="col-12">
                    <a class="btn btn-primary" href="{{ route('pages-pricing') }}" style="height: fit-content">Upgrade Account</a>
                </div>
            </div>
        </div>
        <!-- /Current Plan -->
    </div>
    @endif

    <div class="card">
        <!-- Billing History -->
        <h5 class="card-header border-bottom">Billing History</h5>
        <div class="card-datatable table-responsive">
            <table class="invoice-list-table table border-top" id="billing_history_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Period</th>
                        <th>Transaction ID</th>
                        <th>Invoice Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr>
                            <td>{{ $history->id }}</td>
                            <td>{{ $history->package }}</td>
                            <td>{{ $history->amount }}</td>
                            <td>{{ $history->method }}</td>
                            <td>{{ $history->period }}</td>
                            <td>{{ $history->transaction_id }}</td>
                            <td>
                                @if($history->status == 0)
                                    <span class="badge bg-label-warning">Pending</span>
                                @elseif($history->status == 1)
                                    <span class="badge bg-label-success">Paid</span>
                                @elseif($history->status == 2)
                                    <span class="badge bg-label-secondary">Canceled</span>
                                @endif
                            </td>
                            <td>{{ $history->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!--/ Billing History -->
    </div>
</div>
@endsection


@section('page-script')
<script src="{{asset('assets/js/pages/billing.js')}}"></script>
@endsection