@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Delivery Charge') }}
@endsection

@section('main_content')
<div class="erp-table-section">
        <div class="container-fluid">
                <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                    <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                        <a href="{{ route('business.delivery-charge.index') }}">/ {{ __('Settings') }}</a>
                        <span>/ {{ __('Delivery Charge') }}</span>
                    </h2>
                </div>
            <div class="card shadow-sm">
                <div class="card-bodys">
                    <div class="order-form-section p-16">
                        <form action="{{ route('business.delivery-charge.update', $delivery_charge->id ?? 0) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf
                            @method('put')
                            <div class="add-suplier-modal-wrapper d-block">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>{{ __('Amount') }}</label>
                                        <input type="number" name="amount" value="{{ $delivery_charge->value['amount'] ?? '' }}" class="form-control" placeholder="{{ __('Enter Amount') }}">
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


