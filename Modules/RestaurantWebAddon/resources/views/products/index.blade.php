@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Item List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">

            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span>/ {{ __('Item List') }}</span>
                </h2>

                <form action="{{ route('business.products.filter') }}" method="post" class="filter-form" table="#products-data">
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
                                <a href="{{ route('business.products.pdf') }}" class="custom-item">
                                    {{ __('PDF') }} </a>
                                <a href="{{ route('business.products.csv') }}" class="custom-item">
                                    {{ __('CSV') }} </a>
                                <a href="{{ route('business.products.excel') }}" class="custom-item">
                                    {{ __('EXCEL') }} </a>
                            </div>
                        </div>
                        @usercan('products.create')
                            <a type="button" href="{{ route('business.products.create') }}" class="add-order-btn rounded-2"
                                class="btn btn-primary"><i class="fas fa-plus-circle me-1"></i>{{ __('Add Item') }}
                            </a>
                        @endusercan
                    </div>
                </form>
            </div>
            <div class="card ">
                <div class="card-bodys">
                    @usercan ('products.delete')
                    <div class="delete-item delete-show multi-delete-container d-none mb-3">
                        <div class="delete-item-show d-flex align-items-center justify-content-between w-100">
                            <p class="fw-bold"><span class="selected-count"></span> {{ __('items selected') }}</p>
                            <button data-bs-toggle="modal" class="trigger-modal" data-bs-target="#multi-delete-modal"
                                data-url="{{ route('business.products.delete-all') }}">{{ __('Delete') }}</button>
                        </div>
                    </div>
                    @endusercan
                </div>

                <div class="responsive-table m-0">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                @usercan ('products.delete')
                                <th class="w-60 d-print-none">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="checkbox" class="select-all-delete multi-delete">
                                    </div>
                                </th>
                                @endusercan
                                <th> {{ __('SL') }}. </th>
                                <th> {{ __('Image') }} </th>
                                <th> {{ __('Item Name') }} </th>
                                <th> {{ __('Menu') }} </th>
                                <th> {{ __('Category') }} </th>
                                <th> {{ __('Variation') }} </th>
                                <th> {{ __('Sale price') }}</th>
                                <th> {{ __('Online Status') }}</th>
                                <th class="text-end d-print-none"> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody id="products-data">
                            @include('restaurantwebaddon::products.datas')
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $products->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    @php
        $currency = business_currency();
    @endphp
    {{-- Hidden input fields to store currency details --}}
    <input type="hidden" id="currency_symbol" value="{{ $currency->symbol }}">
    <input type="hidden" id="currency_position" value="{{ $currency->position }}">
    <input type="hidden" id="currency_code" value="{{ $currency->code }}">
    {{-- variation delete --}}
    <input type="hidden" id="variationDelete" value="{{ route('business.products.variationDelete', ':id') }}">
    <input type="hidden" id="variationUpdate" value="{{ route('business.products.variationUpdate', ':id') }}">
@endsection

@push('modal')
    @include('restaurantwebaddon::component.delete-modal')
    @include('restaurantwebaddon::products.variation')
    @include('restaurantwebaddon::products.variation-update')
    @include('restaurantwebaddon::products.view')
@endpush
