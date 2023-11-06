@extends('layouts/mainLayout')

@section('title', 'Jeopardy Test')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y main-container">
    @if(Auth::user()->subscription_status == 0 && Auth::user()->is_trial_used == 1)
    <div class="centered-div mt-5">
        <h3>You have already completed the trial test.<br><br> <small>If you would like to access more tests, please click the button below to subscribe.</small></h3>
        <a class="btn btn-primary" href="/checkout">Upgrade Account</a>
    </div>
    @else
        <div class="centered-div mt-5" id="start_step">
            <h2 class="mb-5">Click the Start button below to start the test</h2>
            <button class="btn btn-primary start-btn" style="width: 200px" id="">Start<i class="fas fa-spinner fa-spin ms-2 d-none"></i></button>
            <p class="mt-4">To submit your responses, you may press enter or wait for the time to run out.</p>
        </div>

        <div class="centered-div mt-5 d-none" id="begin_step">
            <h3 class="mb-3">THE TEST WILL BEGIN IN:</h3>
            <div class="text-center begin-count-down">0:10</div>
            <p>To submit your responses, you may press enter or wait for the time to run out.</p>
        </div>

        <!-- question step -->
        <div class="centered-div d-none" id="question_form">
            <div class="question-wrapper card">
                <div class="category category-title">
                    IT BORDERS JUST ONE OTHER COUNTRY
                </div>
                <div class="question-scene">
                    <div class="card-header category category-subtitle">
                        IT BORDERS JUST ONE OTHER COUNTRY
                    </div>
                    <div class="card-body question mt-3">
                        Tonight we sup on this animal's jowls, used to flavor stews as a southern delicacy; it's a motorcycle term, too, my love
                    </div>
                    <div class="text-center mt-3 answer-count-down">0:<span class="count-down">15</span></div>
                </div>
            </div>
            <div class="text-end" id="question_index"></div>
            <div class="submit-form text-center">
                <form id="submit_form" autocomplete="off">
                    <p class="mt-3">Responses do NOT need to be in the form of a question.<br>
                    To submit your responses, you may press enter or wait for the time to run out
                    </p>
                    <input type="text" class="form-control" id="answer_input" autocomplete="off">
                </form>
            </div>
        </div>

        <!-- result step -->
        <div class="centered-div mt-5 d-none" id="complete_step">
            <div class="submitting-wrapper text-center">
                <h3>Your response is being transmitted.<i class="fas fa-spinner fa-spin ms-2"></i></h3>
            </div>
            <div class="result-wrapper d-none">
                <h1 class="mb-3">YOU'VE COMPLETED THE TEST!</h1>
                <h1><span class="your-score text-success">30</span><small class="text-muted">/50</small></h1>
                @if(Auth::user()->subscription_status == 1)
                    <button class="btn btn-primary start-btn" style="width: 200px" id="">Start Again<i class="fas fa-spinner fa-spin ms-2 d-none"></i></button>
                @else
                    <p class="mt-5">If you would like to conduct more tests, please click the button below to subscribe.</p>
                    <a class="btn btn-primary" href="/checkout">Upgrade Account</a>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection


@section('page-script')
<script src="{{asset('assets/js/pages/jeopardy-test.js')}}"></script>
@endsection