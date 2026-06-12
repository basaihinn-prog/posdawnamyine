@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ request('type') !== 'supplier' ? __('Edit Customer') : __('Edit Supplier') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="section-title mb-3">

            @php $type = ucfirst(request('type')); @endphp

            <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                <span> <a href="{{ route('business.parties.index', ['type' => request('type')]) }}">/ {{ __(':type List', ['type' => __($type)]) }}</a></span>
                <span>/ {{ __('Edit :type', ['type' => __($type)]) }}</span>
            </h2>
        </div>
        <div class="card border-0">
            <div class="card-bodys ">
                <div class="order-form-section p-16">
                    <form action="{{ route('business.parties.update', $party->id) }}" method="POST" class="ajaxform_instant_reload">
                        @csrf
                        @method('put')
                        <div class="add-suplier-modal-wrapper d-block">
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Name') }}</label>
                                    <input type="text" value="{{ $party->name }}" name="name" required class="form-control" placeholder="{{ __('Enter Name') }}">
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="number" value="{{ $party->phone }}" name="phone" class="form-control" placeholder="{{ __('Enter phone number') }}">
                                </div>

                                @if(request('type') !== 'supplier')
                                <div>
                                    <input type="hidden" name="type" value="customer">
                                </div>
                                @else
                                <div>
                                    <input type="hidden" name="type" value="supplier">
                                </div>
                                @endif

                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Email') }}</label>
                                    <input type="email" value="{{ $party->email }}" name="email" class="form-control" placeholder="{{ __('Enter Email') }}">
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <label>{{ __('Address') }}</label>
                                    <input type="text" value="{{ $party->address }}" name="address" class="form-control" placeholder="{{ __('Enter Address') }}">
                                </div>
                                 @if (request('type') != 'customer')
                                    <div class="col-lg-6 mb-2">
                                        <label>{{ __('Opening Balance') }}</label>
                                        <input type="number" value="{{ $party->opening_balance }}" name="opening_balance" step="any" class="form-control" placeholder="{{ __('Enter amount') }}">
                                    </div>
                                @endif
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-10">
                                            <label class="img-label">{{ __('Image') }}</label>
                                            <input type="file" accept="image/*" name="image" class="form-control file-input-change" data-id="image">
                                        </div>
                                        <div class="col-2 align-self-center mt-4 pt-2">
                                            <img src="{{ asset( $party->image ?? 'assets/images/icons/upload.png') }}" id="image" class="table-img">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label>{{ __('Note') }}</label>
                                    <textarea type="text" name="notes" class="form-control" placeholder="{{ __('Enter note') }}">{{ $party->notes }}</textarea>
                                </div>
                                @if (request('type') != 'supplier')
                                <h4 class="mt-4">{{__('Delivery Addresses')}}</h4>
                                <div class="col-12 mb-2">
                                    <div class="address-manual-rows">
                                        @foreach ($party->delivery_addresses ?? [] as $key => $address)
                                        <div class="row address-row-items">
                                            <div class="col-sm-5">
                                                <label for="">{{ __('Name') }}</label>
                                                <input type="text" name="delivery_name[]" value="{{ $address['name'] ?? '' }}" class="form-control" required placeholder="{{ __('Enter your name') }}">
                                            </div>
                                            <div class="col-sm-5">
                                                <label for="">{{ __('Phone') }}</label>
                                                <input type="text" name="delivery_phone[]" value="{{ $address['phone'] ?? '' }}" class="form-control" required placeholder="{{ __('Enter your phone') }}">
                                            </div>
                                            <div class="col-sm-5">
                                                <label for="">{{ __('Address') }}</label>
                                                <input type="text" name="delivery_address[]" value="{{ $address['address'] ?? '' }}" class="form-control" required placeholder="{{ __('Enter delivery address') }}">
                                            </div>
                                            <div class="col-sm-2 align-self-center mt-3">
                                                <button type="button" class="btn text-danger trash remove-btn-features mt-3">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-2">
                                            <a href="javascript:void(0)" class="fw-bold primary add-address-row d-inline-flex align-items-center gap-2"><i class="fas fa-plus-circle"></i>{{ __('Add new row') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-lg-12">
                                    @usercan('parties.update')
                                    <div class="button-group text-center mt-3">
                                        <a href="{{ route('business.parties.index') }}" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
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
