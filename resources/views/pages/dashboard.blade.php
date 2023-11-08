@extends('layouts/mainLayout')

@section('title', 'My Test Results')

@section('vendor-style')
@endsection

@section('page-style')
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- <a href="/read-csv" class="btn btn-primary">Read CSV</a> -->
    <!-- <a href="/structure-question" class="btn btn-primary">Make as structure questions</a>  -->
    <div class="card">
        <h5 class="card-header">Recent Tests</h5>
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
                        <td>{{ $item->score }} <small class="text-muted">/{{ count($item->get_questions()) }}</small></td>
                        <td>
                            <a href="{{ route('pages-view-detail', [$item->id]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details"><i class='bx bx-show'></i></a>
                            <a href="javascript:;" class="ms-2 delete-record" data-id="{{ $item->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class='bx bx-trash'></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@section('vendor-script')
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/dashboard.js')}}"></script>
@endsection