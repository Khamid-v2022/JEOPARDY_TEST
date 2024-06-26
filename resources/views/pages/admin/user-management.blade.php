@extends('layouts/adminLayout')

@section('title', 'User Management')

@section('content')
<div class="container-xxl">
    <div class="row">
       
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="d-block fw-medium mb-1">Annual Subscribers:</span>
                    <h3 class="card-title mb-1">{{ $annual_subscribers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="d-block fw-medium mb-1">Monthly Subscribers:</span>
                    <h3 class="card-title mb-1">{{ $monthly_subscribers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="d-block fw-medium mb-1">New Subs (Last 7 Days):</span>
                    <h3 class="card-title mb-1">{{ $new_sub_7 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="d-block fw-medium mb-1">New Subs (Last 30 Days):</span>
                    <h3 class="card-title mb-1">{{ $new_sub_30 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="d-block fw-medium mb-1">Total Users:</span>
                    <h3 class="card-title mb-1">{{ count($users) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="d-block fw-medium mb-1">New Users Last 7 Days:</span>
                    <h3 class="card-title mb-1">{{ $new_users_7 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <span class="d-block fw-medium mb-1">New Users Last 30 Days:</span>
                    <h3 class="card-title mb-1">{{ $new_users_30 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Users</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dt-responsive table border-top" id="users_table">
                <thead>
                    <tr class="text-nowrap">
                        <th></th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Test Count</th>
                        <th>Plan</th>
                        <th>Subscribed At</th>
                        <th>Last Login At</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><img src="{{ $user->avatar ? $user->avatar : asset('assets/img/avatars/default.png') }}" alt class="avatar-img rounded-circle" /></td>
                        <td>
                            <span class="">{{ $user->name }}</span>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="/admin/user/test-data/{{$user->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="View test result in detail">{{ count($user->get_test_count()) }}</a>
                        </td>
                        <td>
                            @if($user->subscription_status == 1)
                                @if($user->subscription_plan == "Annually")
                                <span class="badge rounded-pill bg-label-success">
                                @else
                                <span class="badge rounded-pill bg-label-info">
                                @endif
                                {{ $user->subscription_plan }}
                                </span>
                            @else
                                <span class="badge rounded-pill bg-label-secondary">Free</span>  
                            @endif
                        </td>
                        <td>
                            @if($user->subscription_status == 1)
                            {{ date("Y-m-d", strtotime($user->subscribed_at)) }}
                            @endif
                        </td>
                        <td>
                            {{ date("Y-m-d", strtotime($user->last_login_at)) }}
                        </td>
                        <td>
                            {{ date("Y-m-d", strtotime($user->created_at)) }}
                        </td>
                        <td>
                            <a href="javascript:;" class="me-2 view-detail" data-id="{{$user->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="View profile"><i class='bx bx-show '></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Add Detail Modal -->
<div class="modal fade" id="user_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">User Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="user_form">
                <input type="hidden" value="" id="m_selected_q_id">
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="m_name" class="form-label">Name</label>
                            <input type="text" id="m_name" class="form-control" placeholder="User Name">
                        </div>
                        <div class="col mb-3">
                            <label for="m_email" class="form-label">Email</label>
                            <input type="text" id="m_email" class="form-control" placeholder="Email Address">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="m_address" class="form-label">Address</label>
                            <input type="text" id="m_address" class="form-control" placeholder="Address">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="m_city" class="form-label">City</label>
                            <input type="text" id="m_city" class="form-control" placeholder="City">
                        </div>
                        <div class="col mb-3">
                            <label for="m_zipcode" class="form-label">Zip Code</label>
                            <input type="text" id="m_zipcode" class="form-control" placeholder="Zip Code">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="m_country" class="form-label">Country</label>
                            <input type="text" id="m_country" class="form-control" placeholder="Country">
                        </div> 
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label for="m_plan" class="form-label">Plan</label>
                            <div id="m_plan"></div>
                        </div>
                        <div class="col mb-3">
                            <label for="m_subscribed_at" class="form-label">Subscribed At</label>
                            <input type="text" id="m_subscribed_at" class="form-control" placeholder="Subscribed At">
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/admin/user-management.js')}}"></script>
@endsection