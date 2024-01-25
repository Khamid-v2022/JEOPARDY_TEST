@extends('layouts/blankLayout')

@section('title', 'My Score')

@section('content')
    <div class="share-wrapper">
        <div>
            <div class="score-wrapper d-flex">
                <div class="answer-check">
                    @php
                    $index = 0;
                    @endphp
                    @foreach($my_answers as $answer)
                        @if($index != 0 && $index / 10 == (int)($index / 10))
                            <br>
                        @endif 
                        
                        @if($answer->is_correct == 1)
                        ✅
                        @else
                        ❌
                        @endif

                        @php 
                            $index++;
                        @endphp
                    @endforeach
                </div>
                <span class="your-score text-success ms-3">{{$header->score}}</span> / <span class="question-count">{{$header->number_of_questions}}</span>
            </div>
            <div class="mt-2">
                ⏳ <span class="test-time">{{$test_time}}</span>
            </div>
            <div class="mt-2">
                ⚡ <span class="current-streak">{{$streak_days}}</span> {{$streak_days > 1 ? 'Days':'Day'}} Streak
            </div>

            <div class="refferral-link mt-3">
                See how you do: 
                <a href="/ref/{{$ref_str}}" target="_blank">{{env('APP_URL')}}/ref/{{$ref_str}}</a>
            </div>
        </div>
    </div>
@endsection