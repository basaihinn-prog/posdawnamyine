@extends('layouts.master')

@section('title')
    {{ __('Dashboard') }}
@endsection

@section('main_content')
    <div class="container-fluid m-h-100">
        <div class="gpt-dashboard-card counter-grid-6 mt-30 mb-30">
            <div class="couter-box">
                <div class="stat-icon d-flex flex-column">
                    <img src="{{ asset('assets/images/admin-dashboard/stat1.svg') }}" alt="">
                    <h6>{{ __('Total Store') }}</h6>
                </div>
                <div class="content-side">
                    <h5 id="total_businesses"></h5>
                    <p>{{ __('This Month') }}:
                        <span class="up" id="this_month_total_businesses"></span>
                        <span class="up" id="total_business_arrow"></span>
                    </p>
                </div>

            </div>
            <div class="couter-box">
                <div class="stat-icon d-flex flex-column">
                    <img src="{{ asset('assets/images/admin-dashboard/stat2.svg') }}" alt="">
                    <h6>{{ __('Expired Store') }}</h6>
                </div>
                <div class="content-side">
                    <h5 id="expired_businesses"></h5>
                    <p>{{ __('This Month') }}:
                        <span class="down" id="this_month_expired_businesses"></span>
                        <span class="down" id="expired_arrow"></span>

                    </p>
                </div>

            </div>
            <div class="couter-box">
                <div class="stat-icon d-flex flex-column">
                    <img src="{{ asset('assets/images/admin-dashboard/stat3.svg') }}" alt="">
                    <h6>{{ __('Paid Users') }}</h6>
                </div>
                <div class="content-side">
                    <h5 id="paid_users"></h5>
                    <p>{{ __('This Month') }}:
                        <span id="this_month_paid_users"></span>
                        <span id="paid_arrow"></span>
                    </p>
                </div>
            </div>
            <div class="couter-box">
                <div class="stat-icon d-flex flex-column">
                    <img src="{{ asset('assets/images/admin-dashboard/stat4.svg') }}" alt="">
                    <h6>{{ __('Free Users') }}</h6>
                </div>
                <div class="content-side">
                    <h5 id="free_users"></h5>
                    <p>{{ __('This Month') }}:
                        <span id="this_month_free_users"></span>
                        <span id="free_arrow"></span>
                     </p>
                </div>
            </div>
        </div>

        <div class="row gpt-dashboard-chart">
            <div class="col-xxl-8 mb-30">
                <div class="card new-card dashboard-card border-0 p-0 h-100">
                    <div class="chart-header">
                        <h4>{{ __('Income Overview') }}</h4>
                        <div class="gpt-up-down-arrow position-relative">
                            <select class="form-control income-overview-yearly">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                    <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap  subscription-label ">
                            <div class="d-flex align-items-center justify-content-center gap-1 mt-2">
                                <div class="circle plan-indicator"></div>
                                <p>{{ __('Total Subscription Amount') }}: <strong class="total_sub_amt">$0</strong></p>
                            </div>
                        </div>
                        <div class="content">
                            <canvas id="revenueChart"  class="subscription-css"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 mb-30">
                <div class="card new-card dashboard-card border-0 p-0 h-100">
                    <div class="chart-header">
                        <h4>{{ __('Subscription Plan') }}</h4>
                        <div class="gpt-up-down-arrow position-relative">
                            <select class="form-control plan-overview-yearly">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                    <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="content mt-4">
                              <canvas id="planChart" class="chart-css"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="erp-table-section dashboard">
            <div class="card new-card dashboard-card border-0 p-0 h-100">
                <div class="card-bodys">
                    <div class="chart-header p-16 border-0">
                        <h4>{{ __('Store List') }}</h4>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('admin.business.index') }}" class="view-btn">{{ __('View All') }} <i class="fas fa-arrow-right view-arrow"></i> </a>
                        </div>
                    </div>
                    <div class="erp-box-content ">
                        <div class="top-customer-table table-container mt-0">
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="table-header-content"> {{ __('SL') }}. </th>
                                        <th class="table-header-content">{{ __('Date & Time') }}</th>
                                        <th class="table-header-content">{{ __('Name') }}</th>
                                        <th class="table-header-content">{{ __('Category') }}</th>
                                        <th class="table-header-content">{{ __('Phone') }}</th>
                                        <th class="table-header-content">{{ __('Subscription Plan') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($businesses as $business)
                                        <tr class="table-content">
                                            <td class="table-single-content">
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td class="table-single-content">
                                                {{ formatted_date($business->created_at) }}
                                            </td>
                                            <td class="table-single-content">
                                                {{ $business->companyName }}
                                            </td>
                                            <td class="table-single-content">
                                                {{ $business->category->name }}
                                            </td>
                                            <td class="table-single-content">
                                                {{ $business->phoneNumber }}
                                            </td>
                                            <td class="table-single-content">
                                              {{ $business->enrolled_plan?->plan?->subscriptionName }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/adminDashboard.js') }}"></script>
    @endpush

    @php
        $currency = default_currency();
    @endphp
    {{-- Hidden input fields to store currency details --}}
    <input type="hidden" id="currency_symbol" value="{{ $currency->symbol }}">
    <input type="hidden" id="currency_position" value="{{ $currency->position }}">
    <input type="hidden" id="currency_code" value="{{ $currency->code }}">

    <input type="hidden" value="{{ route('admin.dashboard.data') }}" id="get-dashboard">
    <input type="hidden" value="{{ route('admin.dashboard.plans-overview') }}" id="get-plans-overview">
    <input type="hidden" value="{{ route('admin.dashboard.subscriptions') }}" id="yearly-subscriptions-url">
@endsection

