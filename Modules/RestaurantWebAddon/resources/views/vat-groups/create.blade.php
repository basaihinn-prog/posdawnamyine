@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Vat Group') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
                <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                    <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                        <span>/ {{ __('Add Vat Group') }}</span>
                    </h2>
                </div>
            <div class="card">
                <div>
                    <div class="order-form-section p-16">
                        {{-- form start --}}
                        <form action="{{ route('business.vats.store') }}" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload">
                        @csrf

                        <div class="add-suplier-modal-wrapper">
                            <div class="row">
                                <div class="col-lg-6 mt-2">
                                    <label>{{ __('Vat Group Name') }}</label>
                                    <input type="text" name="name" id="name" required class="form-control"
                                        placeholder="{{ __('Enter Name') }}">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label>{{ __('Select vats') }}</label>
                                    <div class=" w-100">
                                        <select id="choice_sub_vat" name="vat_ids[]" class="w-100" multiple>
                                            @foreach ($vats as $vat)
                                                <option value="{{ $vat->id }}">{{ $vat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2 col-lg-6">
                                    <label class="custom-top-label">{{ __('Status') }}</label>
                                    <div class="gpt-up-down-arrow position-relative">
                                        <select class="form-control form-selected" name="status">
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Deactive') }}</option>
                                        </select>
                                        <span></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3 d-flex align-items-center">
                                    <label for="vats_sale" class="me-4">{{__('Vat On Sale')}}</label>
                                    <label class="switch mb-0 me-3">
                                        <input type="hidden" name="vat_on_sale" value="0">
                                        <input type="checkbox" id="vats_sale" name="vat_on_sale" value="1" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="offcanvas-footer mt-3 d-flex justify-content-center">
                                    @usercan ('vat.create')
                                    <a href="{{ route('business.vats.index') }}" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
                                    <button class="theme-btn m-2 submit-btn" type="submit">{{ __('Save') }}</button>
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
@endsection

