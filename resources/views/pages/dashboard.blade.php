@extends('layouts/mainLayout')

@section('title', 'Dashboard page')

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
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table" id="recent_history">
                    <thead>
                        <tr class="text-nowrap">
                            <th>Started At</th>
                            <th>Ended At</th>
                            <th>Progress Time</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                        <tr>
                            <td>{{ $item->started_at }}</td>
                            <td>{{ $item->ended_at }}</td>
                            <td>{{ $item->progress_time }}<span class="d-none sort-value">{{ $item->progress_time_second }}</span></td>
                            <td>{{ $item->score }} <small class="text-muted">/50</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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