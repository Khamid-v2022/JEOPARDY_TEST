@extends('layouts/mainLayout')

@section('title', 'My Test Results')

@section('vendor-style')
@endsection

@section('page-style')
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
        
    <div class="card">
        <h5 class="card-header">Recent Tests</h5>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table border-top" id="recent_history">
                <thead>
                    <tr class="text-nowrap">
                        <th>Test Type</th>
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
                        <td>
                            @if($item->test_type == 0)
                                Random
                            @elseif($item->test_type == 1)
                                Daily Free
                            @elseif($item->test_type == 2)
                                @php
                                    $feature_test_info = $item->getFeatureTestInfo();
                                @endphp
                                @if($feature_test_info)
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Featured Test{{$feature_test_info->is_delete ? ' (Deleted) ' : '' }}">
                                    <img src="{{ asset($feature_test_info->thumbnail) }}" alt class="thumbnail-img me-2" />{{ $feature_test_info->title }}
                                </span>
                                @endif
                            @endif
                        </td>
                        <td>{{ $item->started_at }}</td>
                        <td>{{ $item->ended_at }}</td>
                        <td>{{ $item->progress_time }}<span class="d-none sort-value">{{ $item->progress_time_second }}</span></td>
                        <td>{{ $item->score }} <small class="text-muted">/{{ $item->number_of_questions }}</small></td>
                        <td>
                            <a href="{{ route('pages-view-detail', [$item->id]) }}" class="me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details"><i class='bx bx-show'></i></a>

                            @if(Auth::user()->subscription_status == 1 && Auth::user()->subscription_plan == "Annually")
                            <a href="javascript:;" class="delete-record" data-id="{{ $item->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class='bx bx-trash'></i></a>
                            @endif
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
<script src="{{asset('assets/js/pages/my-test-result.js')}}"></script>
@endsection