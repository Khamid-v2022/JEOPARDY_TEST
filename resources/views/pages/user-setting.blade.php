@extends('layouts/mainLayout')
@section('title', 'Profile page')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header">Profile Details</h5>
        <!-- Account -->
        <div class="card-body">
            <form id="formAccountSettings" method="POST" onsubmit="return false">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="username" class="form-label">FULL NAME OR COMPANY NAME</label>
                        <input class="form-control" type="text" id="username" name="username" value="{{ Auth::user()->name }}" maxlength="30" autofocus required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="text" id="email" name="email" value="{{ Auth::user()->email }}" maxlength="50"  placeholder="E-mail" required />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label">STREET ADDRESS</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ Auth::user()->address }}" maxlength="255" />
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="city" class="form-label">City</label>
                        <input class="form-control" type="text" id="city" name="city" value="{{ Auth::user()->city }}" placeholder="City" maxlength="50" />
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="zipCode" class="form-label">Zip Code</label>
                        <input type="text" class="form-control" id="zipCode" name="zipCode" value="{{ Auth::user()->zipcode }}" placeholder="" maxlength="6" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="country">Country</label>
                        <select id="country" class="select2 form-select" value="{{ Auth::user()->country }}">
                            <option value="">Select</option>
                            @foreach(config('variables.countries') as $country)
                                @if($country == Auth::user()->country)
                                    <option value="{{ $country }}" selected>{{ $country }}</option>
                                @else
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="default_question_count" class="form-label">Default Test Question Count</label>
                        <select id="default_question_count" class="form-select" name="default_question_count" value="{{ Auth::user()->default_question_count }}">
                            <option value="10" {{ Auth::user()->default_question_count == 10? "selected":""}} >10</option>
                            <option value="25" {{ Auth::user()->default_question_count == 25? "selected":""}} >25</option>
                            <option value="50" {{ Auth::user()->default_question_count == 50? "selected":""}} >50</option>
                        </select>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" id="submit_btn" class="btn btn-primary me-2">
                        Save changes
                        <i class="fas fa-spinner fa-spin ms-2 d-none"></i>
                    </button>
                    <button type="reset" class="btn btn-label-secondary">Cancel</button>
                </div>
            </form>
        </div>
        <!-- /Account -->
    </div>
    <div class="card">
        <h5 class="card-header">Delete Account</h5>
        <div class="card-body">
            <div class="mb-3 col-12 mb-0">
                <div class="alert alert-warning">
                    <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                    <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                </div>
            </div>
            <form id="formAccountDeactivation" onsubmit="return false">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" required />
                    <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                </div>
                <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
            </form>
        </div>
    </div>
   
</div>
@endsection


@section('page-script')
<script src="{{asset('assets/js/pages/user-setting.js')}}"></script>
@endsection