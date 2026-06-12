@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Message List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">

            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span>/ {{ __('Message List') }}</span>
                </h2>

                <form action="{{ route('business.messages.index') }}" method="GET" class="ajax-filter-form" table="#messages-data">
                    @csrf
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
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-bodys">
                    @usercan('messages.delete')
                    <div class="delete-item delete-show multi-delete-container d-none mb-3">
                        <div class="delete-item-show d-flex align-items-center justify-content-between w-100">
                            <p class="fw-bold"><span class="selected-count"></span> {{ __('items selected') }}</p>
                            <button data-bs-toggle="modal" class="trigger-modal" data-bs-target="#multi-delete-modal" data-url="{{ route('business.messages.delete-all') }}">{{ __('Delete') }}</button>
                        </div>
                    </div>
                    @endusercan
                </div>

                <div id="messages-data">
                    @include('restaurantwebaddon::messages.datas')
                </div>

            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('restaurantwebaddon::component.delete-modal')
    @include('restaurantwebaddon::messages.view')
@endpush
