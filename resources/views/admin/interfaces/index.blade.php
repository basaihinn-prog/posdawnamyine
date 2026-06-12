@extends('layouts.master')

@section('title')
    {{ __('Interfaces List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys ">
                    <div class="table-header p-16">
                        <h4>{{ __('Interfaces List') }}</h4>
                        <a href="{{ route('admin.interfaces.create') }}" class="theme-btn print-btn text-light">
                            <i class="far fa-plus" aria-hidden="true"></i>
                            {{ __('Create New') }}
                        </a>
                    </div>

                    <div class="table-top-form p-16-0">
                        <form action="{{ route('admin.interfaces.filter') }}" method="post" class="filter-form"
                            table="#interfaces-data">
                            @csrf

                            <div class="table-top-left d-flex gap-3 margin-l-16">
                                <div class="gpt-up-down-arrow position-relative">
                                    <select name="per_page" class="form-control">
                                        <option value="10">{{ __('Show- 10') }}</option>
                                        <option value="25">{{ __('Show- 25') }}</option>
                                        <option value="50">{{ __('Show- 50') }}</option>
                                        <option value="100">{{ __('Show- 100') }}</option>
                                    </select>
                                    <span></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="delete-item delete-show d-none multi-delete-container">
                    <div class="delete-item-show d-flex align-items-center justify-content-between w-100">
                        <p class="fw-bold"><span class="selected-count"></span> {{ __('items selected') }}</p>
                        <button data-bs-toggle="modal" class="trigger-modal" data-bs-target="#multi-delete-modal"
                            data-url="{{ route('admin.interfaces.delete-all') }}">{{ __('Delete') }}</button>
                    </div>
                </div>

                <div class="responsive-table m-0">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th class="table-header-content d-print-none">
                                    <div class="d-flex align-items-center gap-1">
                                        <label class="table-custom-checkbox">
                                            <input type="checkbox"
                                                class="table-hidden-checkbox selectAllCheckbox select-all-delete multi-delete">
                                            <span class="table-custom-checkmark custom-checkmark"></span>
                                        </label>
                                    </div>
                                </th>
                                <th class="table-header-content">{{ __('SL') }}.</th>
                                <th class="table-header-content text-center">{{ __('Image') }}</th>
                                <th class="table-header-content text-center">{{ __('Status') }}</th>
                                <th class="table-header-content text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="interfaces-data" class="searchResults">
                            @include('admin.interfaces.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $interfaces->links('vendor.pagination.bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
