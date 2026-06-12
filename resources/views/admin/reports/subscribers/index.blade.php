@extends('layouts.master')

@section('title')
    {{ __('Subscriptions Report') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card">
                <div class="card-bodys ">
                    <div class="table-header p-16">
                        <h4>{{ __('Subscription Report') }}</h4>
                    </div>

                    <div class="table-header justify-content-center border-0 text-center d-none d-block d-print-block">
                        <h4 class="mt-2">{{ __('Subscriptions Report') }}</h4>
                    </div>

                    <div class="table-top-form sec-header d-print-none">
                        <form action="{{ route('admin.subscription-reports.filter') }}" method="post" class="filter-form mb-0"
                            table="#subscriber-data">
                            @csrf

                            <div class="table-top-left d-flex flex-wrap gap-3 ">
                                <div class="gpt-up-down-arrow position-relative">
                                    <select name="per_page" class="form-control header-input">
                                        <option value="10">{{ __('Show- 10') }}</option>
                                        <option value="25">{{ __('Show- 25') }}</option>
                                        <option value="50">{{ __('Show- 50') }}</option>
                                        <option value="100">{{ __('Show- 100') }}</option>
                                    </select>
                                    <span></span>
                                </div>

                                <div class="gpt-up-down-arrow position-relative">
                                    <select name="custom_days" class="form-control header-input custom-filter-select">
                                        <option value="today">{{ __('Today') }}</option>
                                        <option value="yesterday">{{ __('Yesterday') }}</option>
                                        <option value="last_seven_days">{{ __('Last 7 Days') }}</option>
                                        <option value="last_thirty_days">{{ __('Last 30 Days') }}</option>
                                        <option value="current_month">{{ __('Current Month') }}</option>
                                        <option value="last_month">{{ __('Last Month') }}</option>
                                        <option value="current_year">{{ __('Current Year') }}</option>
                                        <option value="custom_date">{{ __('Custom Date') }}</option>
                                    </select>
                                    <span></span>
                                </div>

                          
                                    <div class="from-to align-items-center date-filters d-none">
                                        <label class="header-label">{{ __('From Date') }}</label>
                                        <input type="date" name="from_date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control">
                                    </div>
                                    <div class="from-to align-items-center date-filters d-none">
                                        <label class="header-label">{{ __('To Date') }}</label>
                                        <input type="date" name="to_date"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control">
                                    </div>
                           


                                <div class="table-search position-relative">
                                    <input class="form-control header-input" type="text" name="search"
                                        placeholder="{{ __('Search...') }}" value="{{ request('search') }}">
                                    <span class="position-absolute">
                                        <img src="{{ asset('assets/images/search.svg') }}" alt="">
                                    </span>
                                </div>
                            </div>
                        </form>

                        <div class="d-flex align-items-center gap-3 d-print-none margin-top-print">
                            <a href="{{ route('admin.subscription-reports.csv') }}">
                                <img src="{{ asset('assets/images/icons/cvg.svg') }}" alt="user" id="">
                            </a>
                            <a href="{{ route('admin.subscription-reports.excel') }}">
                                <img src="{{ asset('assets/images/icons/exel.svg') }}" alt="user" id="">
                            </a>

                            <a  class="print-window">
                                <img src="{{ asset('assets/images/icons/print.svg') }}" alt="user" id="">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="responsive-table table-container">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th class="table-header-content">{{ __('SL') }}.</th>
                                <th class="table-header-content text-center">{{ __('Last Enroll') }}</th>
                                <th class="table-header-content text-center">{{ __('Store Name') }}</th>
                                <th class="table-header-content text-center">{{ __('Category') }}</th>
                                <th class="table-header-content text-center">{{ __('Start Date') }}</th>
                                <th class="table-header-content text-center">{{ __('End Date') }}</th>
                                <th class="table-header-content text-center">{{ __('Package') }}</th>
                                <th class="table-header-content text-center d-print-none">{{ __('Payment Method') }}</th>
                                <th class="table-header-content text-center d-print-none">{{ __('Status') }}</th>
                            </tr>
                        </thead>

                        <tbody id="subscriber-data">
                            @include('admin.reports.subscribers.datas')
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $subscribers->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
