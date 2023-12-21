@extends('layouts/adminLayout')
@section('title', 'User Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
        
    <div class="card">
        <h5 class="card-header">{{ $user_info->name }}'s Recent Tests</h5>
        <input type="hidden" value="{{$user_info->id}}" id="user_id">
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table border-top" id="recent_history">
                <thead>
                    <tr class="text-nowrap">
                        <th>Started At</th>
                        <th>Ended At</th>
                        <th>Progress Time</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                    <tr>
                        <td>{{ $item->started_at }}</td>
                        <td>{{ $item->ended_at }}</td>
                        <td>{{ $item->progress_time }}<span class="d-none sort-value">{{ $item->progress_time_second }}</span></td>
                        <td>{{ $item->score }} <small class="text-muted">/{{ $item->number_of_questions }}</small></td>
                        <td>
                            <a href="{{ route('user-test-detail', [$item->id]) }}" class="me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details"><i class='bx bx-show'></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            My Scores(Last 7 days)
        </div>
        <div class="card-body" id="score_chart" style="height: 400px">

        </div>
    </div>
</div>
@endsection


@section('vendor-script')
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/admin/user-test-info.js')}}"></script>
@endsection