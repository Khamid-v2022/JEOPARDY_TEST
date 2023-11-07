@extends('layouts/mainLayout')

@section('title', 'Detail page')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">Answer Details</h5>
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
                        <td>{{ $detail->get_question()->question }}</td>
                        <td>{{ $detail->get_question()->answer }}</td>
                        <td>{{ $detail->user_answer }}</td>
                        <td class="text-end">{{ $detail->answer_time }} s</td>
                        <td class="text-center">
                            @if($detail->is_correct == 1)
                                <span class="badge rounded-pill bg-label-success">correct</span>
                            @else
                                <span class="badge rounded-pill bg-label-danger">Incorrect</span>
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