@extends('layouts/mainLayout')

@section('title', 'Dashboard')

@section('vendor-style')
@endsection

@section('page-style')
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mt-4">
        <div class="card-header">
            Daily Reviews
        </div>
        <div class="card-body" id="daily_review_chart" style="height: 300px">

        </div>
    </div>

    <div class="row mt-4">   
        <!-- Longest Streak All - time -->
        <div class="col-sm-4">
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

        <div class="col-sm-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3>#{{ $rank }}</h3>
                    <p class="mb-0">Currenct Streak Rank</p>
                    <p class="mb-0">
                        <a href="/my-tests">View My Test Results</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
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