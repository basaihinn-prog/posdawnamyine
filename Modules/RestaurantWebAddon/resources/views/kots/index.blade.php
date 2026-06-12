@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{__('Kot List')}}
@endsection

@section('main_content')
<div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                <h2> <a href=""> {{__('Dashboard')}} </a>
                    <span>/ {{__('Kot List')}}</span>
                </h2>
                <form action="{{ route('business.kots.index') }}" method="Get" class="ajax-filter-form" table="#kots-data">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('business.kots.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">{{__('Pending')}} (<span class="count-pending">{{ $kotCounts['pending'] ?? 0 }}</span>)</a>
                        <a href="{{ route('business.kots.index', array_merge(request()->except('status'), ['status' => 'preparing'])) }}" class="filter-btn {{ request('status') == 'preparing' ? 'active' : '' }}">{{__('Preparing')}} (<span class="count-preparing">{{ $kotCounts['preparing'] ?? 0 }}</span>)</a>
                        <a href="{{ route('business.kots.index', array_merge(request()->except('status'), ['status' => 'ready'])) }}" class="filter-btn {{ request('status') == 'ready' ? 'active' : '' }}">{{__('Ready')}} (<span class="count-ready">{{ $kotCounts['ready'] ?? 0 }}</span>)</a>
                        <a href="{{ route('business.kots.index', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}" class="filter-btn {{ request('status') == 'cancelled' ? 'active' : '' }}">{{__('Cancelled')}} (<span class="count-cancelled">{{ $kotCounts['cancelled'] ?? 0 }}</span>)</a>

                        <input type="hidden" name="status" value="{{ request('status') }}">

                        {{-- Search  --}}
                        <div class="search-wrapper">
                            <div class="custom-search-btn" id="searchBtn">
                                <div class="initial-search-icon">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"  xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_4034_56140)">
                                            <path d="M11.6667 11.6641L14.6667 14.6641" stroke="#2F5A76" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M13.3333 7.33594C13.3333 4.02223 10.647 1.33594 7.33325 1.33594C4.01955 1.33594 1.33325 4.02223 1.33325 7.33594C1.33325 10.6497 4.01955 13.3359 7.33325 13.3359C10.647 13.3359 13.3333 10.6497 13.3333 7.33594Z" stroke="#2F5A76" stroke-width="1.5" stroke-linejoin="round" />
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

                        <div class="gpt-up-down-arrow position-relative">
                            <select name="food_type" class="custom-select">
                                <option value="">{{ __('All Type') }}</option>
                                <option value="veg" {{ request('food_type') == 'veg' ? 'selected' : '' }}>{{ __('Veg') }}</option>
                                <option value="non_veg" {{ request('food_type') == 'non_veg' ? 'selected' : '' }}>{{ __('Non Veg') }}</option>
                            </select>
                            <span></span>
                        </div>

                        {{-- Custom days --}}
                        <div class="m-0 p-0 d-print-none">
                            <div class="date-filters-container">
                                <div class="input-wrapper align-items-center date-filters d-none">
                                    <label class="header-label">{{ __('From Date') }}</label>
                                    <input type="date" name="from_date" value="{{ request('from_date') ?? now()->format('Y-m-d') }}" class="form-control">
                                </div>
                                <div class="input-wrapper align-items-center date-filters d-none">
                                    <label class="header-label">{{ __('To Date') }}</label>
                                    <input type="date" name="to_date" value="{{ request('to_date') ?? now()->format('Y-m-d') }}" class="form-control">
                                </div>
                                <div class="gpt-up-down-arrow position-relative d-print-none custom-date-filter">
                                    <select name="custom_days" class="form-control custom-days">
                                        <option value="today" {{ request()->get('custom_days') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                                        <option value="yesterday" {{ request()->get('custom_days') == 'yesterday' ? 'selected' : '' }}>{{ __('Yesterday') }}</option>
                                        <option value="last_seven_days" {{ request()->get('custom_days') == 'last_seven_days' ? 'selected' : '' }}>{{ __('Last 7 Days') }}</option>
                                        <option value="last_thirty_days" {{ request()->get('custom_days') == 'last_thirty_days' ? 'selected' : '' }}>{{ __('Last 30 Days') }}</option>
                                        <option value="current_month" {{ request()->get('custom_days') == 'current_month' ? 'selected' : '' }}>{{ __('Current Month') }}</option>
                                        <option value="last_month" {{ request()->get('custom_days') == 'last_month' ? 'selected' : '' }}>{{ __('Last Month') }}</option>
                                        <option value="current_year" {{ request()->get('custom_days') == 'current_year' ? 'selected' : '' }}>{{ __('Current Year') }}</option>
                                        <option value="custom_date" {{ request()->get('custom_days') == 'custom_date' ? 'selected' : '' }}>{{ __('Custom Date') }}</option>
                                    </select>
                                    <span></span>
                                    <div class="calendar-icon">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 4.66797H4" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M2 11.332H6" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 11.332H14" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M10 4.66797H14" stroke="#5B5B5B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M4 4.66797C4 4.04672 4 3.73609 4.10149 3.49106C4.23682 3.16436 4.49639 2.90479 4.82309 2.76946C5.06812 2.66797 5.37875 2.66797 6 2.66797C6.62125 2.66797 6.93187 2.66797 7.17693 2.76946C7.5036 2.90479 7.7632 3.16436 7.89853 3.49106C8 3.73609 8 4.04672 8 4.66797C8 5.28922 8 5.59985 7.89853 5.84488C7.7632 6.17158 7.5036 6.43115 7.17693 6.56648C6.93187 6.66797 6.62125 6.66797 6 6.66797C5.37875 6.66797 5.06812 6.66797 4.82309 6.56648C4.49639 6.43115 4.23682 6.17158 4.10149 5.84488C4 5.59985 4 5.28922 4 4.66797Z" stroke="#5B5B5B" stroke-width="1.25" />
                                            <path d="M8 11.332C8 10.7108 8 10.4002 8.10147 10.1551C8.2368 9.82843 8.4964 9.56883 8.82307 9.4335C9.06813 9.33203 9.37873 9.33203 10 9.33203C10.6213 9.33203 10.9319 9.33203 11.1769 9.4335C11.5036 9.56883 11.7632 9.82843 11.8985 10.1551C12 10.4002 12 10.7108 12 11.332C12 11.9533 12 12.2639 11.8985 12.509C11.7632 12.8356 11.5036 13.0952 11.1769 13.2306C10.9319 13.332 10.6213 13.332 10 13.332C9.37873 13.332 9.06813 13.332 8.82307 13.2306C8.4964 13.0952 8.2368 12.8356 8.10147 12.509C8 12.2639 8 11.9533 8 11.332Z" stroke="#5B5B5B" stroke-width="1.25" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="kots-data" id="kots-data">
                @include('restaurantwebaddon::kots.datas')
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('restaurantwebaddon::kots.cancel')
@endpush

