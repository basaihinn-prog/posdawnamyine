@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ request('type') !== 'supplier' ? __('Create Customer') : __('Create Supplier') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="section-title mb-3">

            @php $type = ucfirst(request('type')); @endphp

            <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                <span> <a href="{{ route('business.parties.index', ['type' => request('type')]) }}">/ {{ __(':type List', ['type' => __($type)]) }}</span></a></span>
                <span>/ {{ __('Add :type', ['type' => __($type)]) }}</span>
            </h2>
        </div>
        <div class="card border-0">
            <div class="card-bodys">
                <div class="order-form-section p-16">
                    <form action="{{ route('business.parties.store') }}" method="POST" class="ajaxform_instant_reload">
                        @csrf
                        <div class="add-suplier-modal-wrapper d-block">
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Name') }}</label>
                                    <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter Name') }}">
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="number" name="phone" class="form-control" placeholder="{{ __('Enter phone number') }}">
                                </div>

                                @if(request('type') !== 'supplier')
                                <input type="hidden" name="type" value="customer">
                                @else
                                <div>
                                    <input type="hidden" name="type" value="supplier">
                                </div>
                                @endif

                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('Enter Email') }}">
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Address') }}</label>
                                    <input type="text" name="address" class="form-control" placeholder="{{ __('Enter Address') }}">
                                </div>
                                 @if (request('type') != 'customer')
                                    <div class="col-lg-6 mb-2">
                                        <label>{{ __('Opening Balance') }}</label>
                                        <input type="number" name="opening_balance" step="any" class="form-control" placeholder="{{ __('Enter amount') }}">
                                    </div>
                                @endif
                                <div class="col-lg-6">
                                  <div class="row">
                                        <div class="col-9 col-md-10">
                                            <label class="img-label">{{ __('Image') }}</label>
                                            <input type="file" accept="image/*" name="image" class="form-control file-input-change" data-id="image">
                                        </div>
                                        <div class="col-3 col-md-2 align-self-center mt-4 pt-2">
                                            <img src="{{ asset('assets/images/icons/upload.png') }}" id="image" class="table-img">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label>{{ __('Note') }}</label>
                                    <textarea type="text" name="notes" class="form-control" placeholder="{{ __('Enter note') }}"></textarea>
                                </div>

                                @if (request('type') != 'supplier')
                                <h4 class="mt-4">{{__('Delivery Addresses')}}</h4>
                                <div class="col-12 mb-2">
                                    <div class="address-manual-rows"></div>
                                    <div class="row">
                                        <div class="col-12 mt-2">
                                            <a href="javascript:void(0)" class="fw-bold primary add-address-row d-inline-flex align-items-center gap-2"><i class="fas fa-plus-circle"></i>{{ __('Add new row') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-lg-12">
                                    @usercan('parties.create')
                                    <div class="button-group text-center mt-3">
                                        <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                        <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                                    </div>
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
