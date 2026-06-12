@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Roles') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
              <div class="section-title mb-3">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <a href="{{ route('business.manage-settings.index') }}">/ {{ __('Settings') }}</a>
                    <span>/ {{ __('Add Role') }}</span>
                </h2>
            </div>
            <div class="section-main-content">
                <div class="card-bodys">
                    <div class="row justify-content-center roles-permissions">
                        <div class="col-lg-12">
                            <form action="{{ route('business.roles.store') }}" method="post" class="row ajaxform_instant_reload">
                             @csrf
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-4 form-group role-input-label">
                                        <div class="w-100">
                                            <label class="custom-top-label">{{ __('Select Staff') }}</label>
                                            <div class="gpt-up-down-arrow position-relative w-100">
                                                <select class="form-control form-selected w-100" name="staff_id" required>
                                                    <option value="">{{ __('Select a staff') }}</option>
                                                    @foreach ($staffs as $staff)
                                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->designation) }})</option>
                                                    @endforeach
                                                </select>
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group role-input-label">
                                        <div class="w-100">
                                            <label for="text" class="required">{{ __('Login User Email') }}</label>
                                            <input type="text" name="email" id="email" class="form-control w-100" placeholder="{{ __('Enter Email Address') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group role-input-label">
                                        <div class="w-100">
                                            <label for="password" class="required">{{ __('Password') }}</label>
                                            <input type="password" name="password" id="password" class="form-control w-100" placeholder="{{ __('Enter Password') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12 ">
                                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                                            <h4 class="mt-3 mb-3 permission-title">{{ __('Select Permission') }}</h4>
                                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                                <label for="" class="table-custom-checkbox">
                                                    <input type="checkbox"
                                                        class="custom-control-input table-hidden-checkbox checkbox-item"
                                                        id="selectAll">
                                                    <label for="selectAll"
                                                        class="table-custom-checkmark custom-control-label custom-checkmark"></label>
                                                </label>
                                                <label class="custom-control-label fw-bold"
                                                    for="selectAll">{{ __('Select All') }}</label>
                                            </div>
                                        </div>

                                        <div class="top-customer-table add-role-table table-container table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th class="table-header-content">{{ __('SL') }}.</th>
                                                        <th class="text-nowrap fw-bolder table-header-content text-start">
                                                            {{ __('Setup role permissions') }}
                                                        </th>
                                                        <th class="table-header-content text-start">
                                                            {{ __('Permission') }}
                                                        </th>
                                                    </tr>
                                                    @foreach($permissions as $module => $actions)
                                                    <tr class="table-content">
                                                        <td class="table-single-content">{{ $loop->iteration }}</td>
                                                        <td class="text-nowrap  text-start table-single-content">
                                                            {{ Str::headline($module) }}
                                                        </td>
                                                        <td class="text-start table-single-content">
                                                            <div class="d-flex">
                                                                @foreach($actions as $action)
                                                                <div class="custom-control custom-checkbox mr-3 me-4 d-flex align-items-center">
                                                                    <label class="table-custom-checkbox">
                                                                        <input type="checkbox" name="visibility[{{ $module }}][{{ $action }}]" value="1" class="custom-control-input
                                                                        table-hidden-checkbox checkbox-item" id="{{ $module }}_{{ $action }}">
                                                                        <label for="{{ $module }}_{{ $action }}" class="table-custom-checkmark custom-checkmark custom-control-label"></label>
                                                                    </label>
                                                                    <label class="custom-control-label" for="{{ $module }}_{{ $action }}">{{ Str::headline($action) }}</label>
                                                                </div>
                                                              @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @usercan('rolePermission.create')
                                        <div class="button-group text-center mt-3">
                                            <button type="reset"
                                                class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                            <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                                        </div>
                                        @endusercan
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
