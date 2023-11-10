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
                        <h6 class="fw-semibold mb-2">Your Current Plan is Premium: ${{ env('BASIC_PLAN_PRICE') }} Per Month</h6>
                        <p>You can do unlimited tests.</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Active until {{ date("F jS, Y", strtotime(Auth::user()->expire_at)) }}</h6>
                        <p>We will send you a notification upon Subscription expiration</p>
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
                        <h6 class="fw-semibold mb-2"><span class="me-2">${{ env('BASIC_PLAN_PRICE') }} Per Month</span> <span class="badge bg-label-primary">Popular</span></h6>
                        <p>Unlimit test access feature</p>
                        
                    </div>
                </div>
                <div class="col-12">
                    <a class="btn btn-primary" href="{{ route('pages-checkout') }}" style="height: fit-content">Upgrade Account</a>
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