@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Discount Setting') }}
@endsection

@section('main_content')
<div class="erp-table-section">
        <div class="container-fluid">
                <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                    <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                        <a href="{{ route('business.discount.index') }}">/ {{ __('Settings') }}</a>
                        <span>/ {{ __('Discount Setting') }}</span>
                    </h2>
                </div>
            <div class="card shadow-sm">
                <div class="card-bodys">
                    <div class="order-form-section p-16">
                        <form action="{{ route('business.discount.update', $discount->id ?? 0) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf
                            @method('put')
                            <div class="add-suplier-modal-wrapper d-block">
                                <div class="row">
                                    <div class="mb-2 col-lg-6">
                                        <label class="custom-top-label">{{ __('Discount') }}</label>
                                        <div class=" percentage-flat-container">
                                            <input type="number" class="form-control" name="amount" value="{{ $discount->value['amount'] ?? 0 }}" placeholder="{{__('Enter Discount')}}">
                                            <select class="form-select percentage-flat" name="discount_type">
                                                <option @selected(($discount->value['discount_type'] ?? '') == 'percentage') value="percentage">{{__('Percentage')}}</option>
                                                <option @selected(($discount->value['discount_type'] ?? '') == 'flat') value="flat">{{__('Flat')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label>{{ __('Status') }}</label>
                                        <div class="gpt-up-down-arrow position-relative">
                                            <select name="status" class="form-control table-select w-100 role">
                                                <option value=""> {{ __('Select status') }}</option>
                                                    <option @selected(($discount->value['status'] ?? '') == '1') value="1">{{__('Active')}}</option>
                                                    <option @selected(($discount->value['status'] ?? '') == '2') value="2">{{__('Deactive')}}</option>
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-center mt-5">
                                            <button type="submit" class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                                        </div>
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


