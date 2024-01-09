@extends('layouts/mainLayout')

@section('title', 'Dashboard')

@section('vendor-style')
@endsection

@section('page-style')
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">  
        <div class="col-sm-5">
            <div class="card mt-4 daily-reivew-card">
                <div class="card-body">
                    {{date("F jS, Y")}}
                    <hr>
                    <h2><strong>Daily review</strong></h2>
                    <div class="d-flex justify-content-between" style="min-height: 120px;  flex-direction: column">
                        <div class="d-flex align-items-center">
                            @if($today_last_test)
                            <h1>{{ $today_last_test->score }}</h1> <h3>/{{ $today_last_test->number_of_questions }}</h3>
                            @endif
                        </div>
                        <div>
                            @if($today_last_test)
                            <p class="mb-0"><i class='bx bxs-badge-check'></i> You've completed today's test</p>
                            @else
                            <p class="mb-0"> You haven't completed today's test yet. Click <a href="/jeopardy-test">here</a> to take the test </p>
                            @endif
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="card mt-4">
                <div class="card-header pb-2">
                    Number of Tests Per Day
                </div>
                <div class="card-body" id="daily_review_chart" style="height: 220px">

                </div>
            </div>
        </div>
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
                    <p class="mb-0">Currenct Streak</p>
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