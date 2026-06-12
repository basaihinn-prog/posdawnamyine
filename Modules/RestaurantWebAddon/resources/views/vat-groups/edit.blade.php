@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Edit Vat Group') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                    <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                        <span>/ {{__('Edit Vat Group')}}</span>
                    </h2>
                </div>
            <div class="card">
                <div class="card-body">

                    <div class="order-form-section">
                        {{-- form start --}}
                        <form action="{{ route('business.vats.update',$vat->id) }}" method="post" enctype="multipart/form-data"
                            class="ajaxform_instant_reload">
                            @csrf
                            @method('PUT')

                            <div class="add-suplier-modal-wrapper">
                                <div class="row">
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Vat Group Name') }}</label>
                                        <input type="text" name="name" value="{{ $vat->name }}" required
                                            class="form-control" placeholder="{{ __('Enter Name') }}">
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <label>{{ __('Select vats') }}</label>
                                        <div class="w-100">
                                            <select class="w-100" id="choice_sub_vat" name="vat_ids[]" multiple>
                                                @php
                                                    $selectedVatIds = collect($vat->sub_tax)->pluck('id')->toArray();
                                                @endphp

                                                @foreach ($vats as $vat_item)
                                                    <option value="{{ $vat_item->id }}" @selected(in_array($vat_item->id, $selectedVatIds))>
                                                        {{ $vat_item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-2 col-lg-6">
                                        <label class="custom-top-label">{{ __('Status') }}</label>
                                        <div class="gpt-up-down-arrow position-relative">
                                            <select class="form-control form-selected" name="status">
                                                <option value="1" @selected($vat->status == 1)>{{ __('Active') }}</option>
                                                <option value="0" @selected($vat->status == 0)>{{ __('Deactive') }}</option>
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mt-3 d-flex align-items-center">
                                        <label for="vat_on_sale" class="me-4">{{__('Vat On Sale')}}</label>
                                        <label class="switch mb-0 me-3">
                                            <input type="hidden" name="vat_on_sale" value="0">
                                            <input type="checkbox" id="vat_on_sale" name="vat_on_sale" value="1" {{ isset($vat) && $vat->vat_on_sale == 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="offcanvas-footer mt-3 d-flex justify-content-center">
                                        @usercan ('vat.update')
                                        <a href="{{ route('business.vats.index') }}" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
                                        <button class="theme-btn m-2 submit-btn" type="submit">{{ __('Update') }}</button>
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
