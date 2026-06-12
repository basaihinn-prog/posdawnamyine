@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Cancel Reason') }}
@endsection

@section('main_content')
<div class="erp-table-section">
        <div class="container-fluid">

            <div class="flex-wrap mb-3 section-title d-flex align-items-center justify-content-between">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span>/ {{ __('Cancel Reason List') }}</span>
                </h2>

                <form action="{{ route('business.cancel-reasons.filter') }}" method="post" class="filter-form" table="#cancel-reasons-data">
                    @csrf
                    <div class="flex-wrap gap-2 d-flex align-items-center">
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
                            <a type="button" href="#cancel-reason-create-modal" class="add-order-btn rounded-2"
                                class="btn btn-primary" data-bs-toggle="modal"><i
                                    class="fas fa-plus-circle me-1"></i>{{ __('Add Cancel Reason') }}
                            </a>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-bodys">
                    <div class="mb-3 delete-item delete-show multi-delete-container d-none">
                        <div class="delete-item-show d-flex align-items-center justify-content-between w-100">
                            <p class="fw-bold"><span class="selected-count"></span> {{ __('items selected') }}</p>
                            <button data-bs-toggle="modal" class="trigger-modal" data-bs-target="#multi-delete-modal"
                                data-url="{{ route('business.cancel-reasons.delete-all') }}">{{ __('Delete') }}</button>
                        </div>
                    </div>
                </div>

                <div class="m-0 responsive-table">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th class="w-60">
                                    <div class="gap-3 d-flex align-items-center">
                                        <input type="checkbox" class="select-all-delete multi-delete">
                                    </div>
                                </th>
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Reason') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="cancel-reasons-data">
                            @include('restaurantwebaddon::cancel-reasons.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $cancel_reasons->links('vendor.pagination.bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('restaurantwebaddon::component.delete-modal')
    @include('restaurantwebaddon::cancel-reasons.create')
    @include('restaurantwebaddon::cancel-reasons.edit')
@endpush

