@extends('layouts/mainLayout')

@section('title', 'Jeopardy Test')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y main-container">
    @if(Auth::user()->subscription_status == 0 && Auth::user()->is_trial_used == 1 && $free_count == 0)
        <div class="centered-div mt-5">
            <h3>You have already completed the today's FREE test.</h3>
            <p>If you would like to access more tests, please click the button below to subscribe.</p>
            <a class="btn btn-primary" href="{{ route('pages-pricing') }}">Upgrade Account</a>
        </div>
    @else
        @if(Auth::user()->subscription_status == 1 && Auth::user()->subscription_plan == "Monthly" && $tested_count >= env('MONTHLY_PLAN_TEST_COUNT') + $free_count )
            <div class="centered-div mt-5">
                <h3>Exceeded monthly limit.</h3>
                <p>If you would like to unlock unlimit test, please click the button below and upgrade your account with Annually plan.</p>
                <a class="btn btn-primary" href="{{ route('pages-pricing') }}">Upgrade Account</a>
            </div>
        @else
            <div class="centered-div mt-5" id="start_step">
                <h2 class="mb-5">Click the Start button below to start the test</h2>
                <div class="row mb-3">
                    <label class="col ol-form-label d-flex justify-content-end align-items-center">Select the Number of Questions: </label>
                    <div class="col">
                    @if(Auth::user()->subscription_status == 0)
                        <select class="form-select " style="max-width: 100px" id="question_count_sel">
                            <option value="10" selected>10</option>
                        </select>
                    @else
                        <select class="form-select " style="max-width: 100px" id="question_count_sel">
                            <option value="10" {{ Auth::user()->default_question_count == 10? "selected":""}} >10</option>
                            <option value="25" {{ Auth::user()->default_question_count == 25? "selected":""}} >25</option>
                            <option value="50" {{ Auth::user()->default_question_count == 50? "selected":""}} >50</option>
                        </select>
                    @endif
                    </div>
                </div>
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
                    @if(Auth::user()->subscription_status == 1)
                        <h1 class="mb-3">YOU'VE COMPLETED THE TEST!</h1>
                    @else
                        <h1 class="mb-3">You’ve completed today’s free test!</h1>
                    @endif
                    <!-- <h1><span class="your-score text-success"></span><small class="text-muted">/<span class="question-count"></span></small></h1> -->
                    <!-- Share to socials -->

                    <div class="share-wrapper mt-4 d-none">
                        <div>
                            <h5>My J!Study Score Today:</h5>
                            <div class="score-wrapper d-flex">
                                <div class="answer-check"></div>
                                <span class="your-score text-success ms-2"></span> / <span class="question-count"></span>
                            </div>
                            <div class="mt-2">
                                ⏳ <span class="test-time"></span>
                            </div>
                            <div class="mt-2">
                                ⚡ <span class="current-streak"></span> Day Streak
                            </div>
                        </div>
                        <ul class="share-btns-wrapper mt-3">

                        </ul>
                    </div>
                    <p class="mt-3">
                    👆 Share your score and earn free tests when others sign up.<br>
                    Click <a href="javascript:goto_detailPage();">here</a> to review your responses and compare them to the correct answers.<br>
                    TIP: If the auto-grader mistakenly marked a correct response as incorrect, you can click “INCORRECT” to toggle the response to “CORRECT”
                    </p>
                    @if(Auth::user()->subscription_status == 1)
                        <div class="row mb-3">
                            <label class="col ol-form-label d-flex justify-content-end align-items-center">Select the Number of Questions: </label>
                            <div class="col">
                                <select class="form-select" style="max-width: 100px" id="question_count_again_sel">
                                    <option value="10" {{ Auth::user()->default_question_count == 10? "selected":""}} >10</option>
                                    <option value="25" {{ Auth::user()->default_question_count == 25? "selected":""}} >25</option>
                                    <option value="50" {{ Auth::user()->default_question_count == 50? "selected":""}} >50</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary start-again-btn" style="width: 200px" id="">Start Again<i class="fas fa-spinner fa-spin ms-2 d-none"></i></button>
                    @else
                        <p class="mt-5">If you would like to conduct more tests, please click the button below to subscribe.</p>
                        <a class="btn btn-primary" href="{{ route('pages-pricing') }}">Upgrade Account</a>
                    @endif
                </div>
            </div>
        @endif
    @endif

</div>
@endsection


@section('page-script')
<script src="{{asset('assets/js/pages/jeopardy-test.js')}}"></script>
@endsection