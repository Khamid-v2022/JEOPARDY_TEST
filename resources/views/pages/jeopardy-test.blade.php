@extends('layouts/mainLayout')

@section('title', 'Jeopardy Test')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y main-container">
    <div class="centered-div" id="start_step">
        <h2 class="mb-5">Click the Start button below to start the test</h2>
        <button class="btn btn-primary" style="width: 200px" id="start_btn">Start<i class="fas fa-spinner fa-spin ms-2 d-none"></i></button>
        <p class="mt-4">To submit your responses, you may press enter or wait for the time to run out.</p>
    </div>

    <div class="centered-div d-none" id="begin_step">
        <h3 class="mb-3">THE TEST WILL BEGIN IN:</h3>
        <div class="text-center begin-count-down">0:10</div>
        <p>To submit your responses, you may press enter or wait for the time to run out.</p>
    </div>

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
                <div class="text-center mt-3 count-down">0:15</div>
            </div>
        </div>
        <div class="text-end" id="question_index"></div>
        <div class="submit-form text-center">
            <form id="submit_form">
                <p class="mt-3">Responses do NOT need to be in the form of a question.<br>
                To submit your responses, you may press enter or wait for the time to run out
                </p>
                <input type="text" class="form-control" id="answer_input">
            </form>
        </div>
    </div>
</div>
@endsection


@section('page-script')
<script src="{{asset('assets/js/pages/jeopardy-test.js')}}"></script>
@endsection