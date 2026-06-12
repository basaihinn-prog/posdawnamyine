@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Order List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span>/ {{ __('Order List') }}</span>
                </h2>
                <form action="{{ route('business.sales.filter') }}" method="post" class="filter-form" table="#sales-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        {{-- Search field --}}
                        <div class="search-wrapper">
                            <div class="custom-search-btn" id="searchBtn">
                                <div class="initial-search-icon">
                                    <!-- SVG ICON -->
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_4034_56140)">
                                            <path d="M11.6667 11.6641L14.6667 14.6641" stroke="#2F5A76" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M13.3333 7.33594C13.3333 4.02223 10.647 1.33594 7.33325 1.33594C4.01955 1.33594 1.33325 4.02223 1.33325 7.33594C1.33325 10.6497 4.01955 13.3359 7.33325 13.3359C10.647 13.3359 13.3333 10.6497 13.3333 7.33594Z"
                                                stroke="#2F5A76" stroke-width="1.5" stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_4034_56140">
                                                <rect width="16" height="16" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <input type="text" name="search" placeholder="{{ __('Search...') }}">
                            </div>
                        </div>

                        {{-- Show per page --}}
                        <div class="select-wrapper filter-dropdown">
                            <select name="per_page" class="custom-select">
                                <option value="10"> {{ __('10') }} </option>
                                <option value="25"> {{ __('25') }} </option>
                                <option value="50"> {{ __('50') }} </option>
                                <option value="100"> {{ __('100') }} </option>
                            </select>
                            <img src="{{ asset('assets/images/icons/arrow.svg') }}" alt="" srcset="">
                        </div>

                        {{-- Filter dropdown --}}
                        <div class="select-wrapper filter-dropdown">
                            <select name="sales_type" class="custom-select filter-dropdown">
                                <option value="">{{ __('Type') }}</option>
                                <option value="dine_in">{{ __('Dine In') }}</option>
                                <option value="pick_up">{{ __('Pick Up') }}</option>
                                <option value="delivery">{{ __('Delivery') }}</option>
                            </select>
                            <img src="{{ asset('assets/images/icons/arrow.svg') }}" alt="" srcset="">
                        </div>
                        <div class="select-wrapper filter-dropdown">
                            <select name="payment_status" class="custom-select">
                                <option value="">{{ __('All') }}</option>
                                <option value="paid">{{ __('Paid') }}</option>
                                <option value="partial">{{ __('Partial') }}</option>
                                <option value="unpaid">{{ __('Unpaid') }}</option>
                            </select>
                            <img src="{{ asset('assets/images/icons/arrow.svg') }}" alt="" srcset="">
                        </div>

                        {{-- Custom days --}}
                        <div class="m-0 p-0 d-print-none">
                            <div class="date-filters-container">
                                <div class="input-wrapper align-items-center date-filters d-none">
                                    <label class="header-label">{{ __('From Date') }}</label>
                                    <input type="date" name="from_date"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control">
                                </div>
                                <div class="input-wrapper align-items-center date-filters d-none">
                                    <label class="header-label">{{ __('To Date') }}</label>
                                    <input type="date" name="to_date"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control">
                                </div>
                                <div class="gpt-up-down-arrow position-relative d-print-none custom-date-filter">
                                    <select name="custom_days" class="form-control custom-days">
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
                                    <div class="calendar-icon">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 4.66797H4" stroke="#5B5B5B" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M2 11.332H6" stroke="#5B5B5B" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 11.332H14" stroke="#5B5B5B" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10 4.66797H14" stroke="#5B5B5B" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M4 4.66797C4 4.04672 4 3.73609 4.10149 3.49106C4.23682 3.16436 4.49639 2.90479 4.82309 2.76946C5.06812 2.66797 5.37875 2.66797 6 2.66797C6.62125 2.66797 6.93187 2.66797 7.17693 2.76946C7.5036 2.90479 7.7632 3.16436 7.89853 3.49106C8 3.73609 8 4.04672 8 4.66797C8 5.28922 8 5.59985 7.89853 5.84488C7.7632 6.17158 7.5036 6.43115 7.17693 6.56648C6.93187 6.66797 6.62125 6.66797 6 6.66797C5.37875 6.66797 5.06812 6.66797 4.82309 6.56648C4.49639 6.43115 4.23682 6.17158 4.10149 5.84488C4 5.59985 4 5.28922 4 4.66797Z"
                                                stroke="#5B5B5B" stroke-width="1.25" />
                                            <path
                                                d="M8 11.332C8 10.7108 8 10.4002 8.10147 10.1551C8.2368 9.82843 8.4964 9.56883 8.82307 9.4335C9.06813 9.33203 9.37873 9.33203 10 9.33203C10.6213 9.33203 10.9319 9.33203 11.1769 9.4335C11.5036 9.56883 11.7632 9.82843 11.8985 10.1551C12 10.4002 12 10.7108 12 11.332C12 11.9533 12 12.2639 11.8985 12.509C11.7632 12.8356 11.5036 13.0952 11.1769 13.2306C10.9319 13.332 10.6213 13.332 10 13.332C9.37873 13.332 9.06813 13.332 8.82307 13.2306C8.4964 13.0952 8.2368 12.8356 8.10147 12.509C8 12.2639 8 11.9533 8 11.332Z"
                                                stroke="#5B5B5B" stroke-width="1.25" />
                                        </svg>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Export --}}
                        <div class="dropdown custom-dropdown">
                            <div class="dropbtn custom-dropbtn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                </svg>
                                {{ __('Export') }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="dropdown-content custom-dropdown-content">
                                <a href="{{ route('business.sales.pdf') }}" class="custom-item">
                                    {{ __('PDF') }} </a>
                                <a href="{{ route('business.sales.csv') }}" class="custom-item">
                                    {{ __('CSV') }} </a>
                                <a href="{{ route('business.sales.excel') }}" class="custom-item">
                                    {{ __('EXCEL') }} </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-bodys">
                    @usercan ('sales.delete')
                    <div class="delete-item delete-show multi-delete-container d-none mb-3">
                        <div class="delete-item-show d-flex align-items-center justify-content-between w-100">
                            <p class="fw-bold"><span class="selected-count"></span> {{ __('items selected') }}</p>
                            <button data-bs-toggle="modal" class="trigger-modal" data-bs-target="#multi-delete-modal"
                                data-url="{{ route('business.sales.delete-all') }}">{{ __('Delete') }}</button>
                        </div>
                    </div>
                    @endusercan
                </div>

                <div class="responsive-table m-0">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                @usercan ('sales.delete')
                                <th class="w-60 text-end">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="checkbox" class="select-all-delete multi-delete ">
                                    </div>
                                </th>
                                @endusercan
                                <th>{{ __('SL') }}.</th>
                                <th class="text-start">{{ __('Date') }}</th>
                                <th class="text-start">{{ __('Invoice No') }}</th>
                                @if (request('type') != 'online-sale')
                                <th class="text-start">{{ __('Party Name') }}</th>
                                @endif
                                <th class="text-start">{{ __('Total') }}</th>
                                <th class="text-start">{{ __('Paid') }}</th>
                                <th class="text-start">{{ __('Due') }}</th>
                                @if (!request('type') == 'online-sale')
                                <th class="text-start">{{ __('Payment Type') }}</th>
                                @endif
                                @if (request('type') == 'online-sale')
                                <th class="text-start">{{ __('Gateway') }}</th>
                                @endif
                                <th>{{ __('Payment Status') }}</th>
                                @usercan ('sales.update')
                                <th>{{ __('Table Release') }}</th>
                                @endusercan
                                @usercan ('sales.update')
                                <th>{{ __('Order Status') }}</th>
                                @endusercan
                                <th>{{ __('Type') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="sales-data">
                            @include('restaurantwebaddon::sales.datas')
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $sales->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('restaurantwebaddon::component.delete-modal')
@endpush
