@extends('layouts/mainLayout')

@section('title', 'My Test Result')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">My Test Result</h5>
        <div class="card-body pb-0">
            <span class="ms-2">{{ $header->started_at }}</span>
            <span id="my_score" class="text-success ms-4">{{ $header->score }}</span><span class="text-muted"> /{{ $header->number_of_questions }}</span>
            <label class="ms-4">Examination time: </label><span class="ms-2">{{ $header->get_test_time()['formated'] }}</span>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table border-top" id="detail_table">
                <thead>
                    <tr class="text-nowrap">
                        <th>#</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Your Answer</th>
                        <th class="text-end">Response Time</th>
                        <th class="text-center">Is Correct</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $detail)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ strip_tags($detail->get_question()->question) }}</td>
                        <td>{{ $detail->get_question()->answer }}</td>
                        <td>{{ $detail->user_answer }}</td>
                        <td class="text-end">{{ $detail->answer_time }} s</td>
                        <td class="text-center">
                            @if($detail->is_correct == 1)
                                <a href="javascript:;" class="switch-answer-btn correct-answer" data-id="{{$detail->id}}" ><span class="badge rounded-pill bg-label-success ">Correct</span></a>
                                <a href="javascript:;" class="switch-answer-btn incorrect-answer d-none" data-id="{{$detail->id}}"><span class="badge rounded-pill bg-label-danger">Incorrect</span></a>
                            @else
                                <a href="javascript:;" class="switch-answer-btn incorrect-answer" data-id="{{$detail->id}}"><span class="badge rounded-pill bg-label-danger ">Incorrect</span></a>
                                <a href="javascript:;" class="switch-answer-btn correct-answer d-none" data-id="{{$detail->id}}"><span class="badge rounded-pill bg-label-success ">Correct</span></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@section('page-script')
<script src="{{asset('assets/js/pages/view-detail.js')}}"></script>
@endsection