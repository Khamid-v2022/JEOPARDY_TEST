@extends('layouts/mainLayout')

@section('title', 'Dashboard')

@section('vendor-style')
@endsection

@section('page-style')
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">  
        @if(Auth::user()->subscription_status == 0)
        <div class="col-sm-4">
        @else
        <div class="col-sm-5">
        @endif
            <div class="card mt-4 daily-reivew-card">
                <div class="card-body">
                    {{date("F jS, Y")}}
                    <input type="hidden" value="{{date('Y-m-d H:i:s')}}">
                    <hr>
                    <h2><strong>Daily review</strong></h2>
                    <div class="d-flex justify-content-between" style="min-height: 120px;  flex-direction: column">
                        <div class="d-flex align-items-center">
                            @if($today_last_test)
                            <h1>{{ $today_last_test->score }}</h1> <h3>/{{ $today_last_test->number_of_questions }}</h3>
                            @endif
                        </div>
                        <div>
                            @if(Auth::user()->is_trial_used == 1)
                            <p class="mb-0">
                                <i class='bx bxs-badge-check'></i> You've completed today's test.
                                @if(Auth::user()->subscription_status == 0)
                                Your new free test will be activated within {{ $next_test_hours }} {{$next_test_hours == 1 ? 'hour' : 'hours'}}.
                                @endif
                            </p>
                            @else
                            <p class="mb-0"> You haven't completed today's test yet. Click <a href="/jeopardy-test">here</a> to take the test </p>
                            @endif
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        @if(Auth::user()->subscription_status == 0)
        <div class="col-sm-4">
        @else
        <div class="col-sm-7">
        @endif
            <div class="card mt-4">
                <div class="card-header pb-2">
                    Number of Tests Per Day
                </div>
                <div class="card-body" id="daily_review_chart" style="height: 220px">

                </div>
            </div>
        </div>
        @if(Auth::user()->subscription_status == 0)
        <div class="col-sm-4">
            <div class="card mt-4 d-flex flex-direction-colum justify-content-between free-test-expire" style="min-height: calc(100% - 26px); padding: 1.5rem">
                <div>
                    @php
                        $remain_trail_test_times = Auth::user()->remain_trail_test_times;

                        $today = new DateTime();
                        $today->modify('+' . $remain_trail_test_times . ' days');
                        $futureDate = $today->format('F jS, Y');
                    @endphp
                    @if($remain_trail_test_times == 0)
                        <h2>Your Free Trial Has Expired - Subscribe Now!</h2>
                    @else
                        <h2 class="mb-3">Your FREE TRIAL will expire on {{$futureDate}}</h2>
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
                </div>
                <a class="btn btn-primary mt-3 w-100" href="{{ route('pages-pricing') }}">Upgrade Account</a>
            </div>
        </div>
        @endif
    </div>
    <div class="row">   
        <!-- Longest Streak All - time -->
        <div class="col-sm-4 mt-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ Auth::user()->lognest_streak_days }} {{ Auth::user()->lognest_streak_days > 1 ? "Days" : "Day"}}</h3>
                    <p class="mb-0">Longest Streak All-Time </p>
                    <p class="mb-0">
                        @if(Auth::user()->lognest_streak_started_at)
                            {{date("M jS, Y", strtotime(Auth::user()->lognest_streak_started_at))}} ~ {{date("M jS, Y", strtotime(Auth::user()->lognest_streak_ended_at))}}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mt-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3>#{{ $rank }}</h3>
                    <p class="mb-0">Longest Streak Rank</p>
                    <p class="mb-0">
                        <a href="/my-tests">View My Test Results</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-4 mt-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ $streak_days }} {{ $streak_days > 1 ? "Days" : "Day"}}</h3>
                    <p class="mb-0">Current Streak</p>
                    <p class="mb-0">
                        @if($streak_started_date)
                            {{date("M jS, Y", strtotime($streak_started_date))}} ~ {{date("M jS, Y", strtotime($streak_end_date))}}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection


@section('vendor-script')
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
@endsection