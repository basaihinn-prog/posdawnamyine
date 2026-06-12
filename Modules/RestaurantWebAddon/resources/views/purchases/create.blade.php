@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Purchase') }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/custom-choice.css') }}?v={{ time() }}">
@endpush

@section('main_content')
    <div class="section-main-container">
        <div class="section-title">
            <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                <span>/ {{ __('Add Purchase') }}</span>
            </h2>
        </div>
        <form action="{{ route('business.purchases.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_redirect_invoice">
            @csrf
        <div class="section-main-content">
             <div class="row g-4">
                <!-- Inventory Items -->
                <div class="col-md-4">
                    <label class="form-label">{{__('Inventory Items')}}</label>
                    <div class="custom-select-wrapper gpt-up-down-arrow position-relative w-100">
                    <select id="ingredient" class="custom-select-2 choices-select w-100" data-modal="#ingredient-create-modal">
                        <option selected>{{__('Select inventory item')}}</option>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}" data-id="{{ $ingredient->id }}" data-name="{{ $ingredient->name }}" data-route="{{ route('business.carts.store') }}">{{ $ingredient->name }}</option>
                        @endforeach
                    </select>
                    <span></span>
                    @usercan('ingredients.create')
                    <a class="custom-btn-2" data-bs-toggle="modal" data-bs-target="#ingredient-create-modal">
                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.9009 4.16797V15.8346" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5.06689 10H16.7336" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    @endusercan
                    </div>
                </div>

                <!-- Supplier -->
                <div class="col-md-4">
                    <label class="form-label">{{__('Supplier')}}</label>
                    <div class="custom-select-wrapper gpt-up-down-arrow position-relative w-100">
                    <select name="party_id" class="custom-select-2 choices-select" data-modal="#supplier-create-modal">
                        <option value="">{{__('Select a supplier')}}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" data-type="{{ $supplier->type }}" data-phone="{{ $supplier->phone }}">
                                {{ $supplier->name }}({{__('Due')}}: {{ currency_format($supplier->due ?? 0, currency: business_currency()) }}) {{ $supplier->phone }}
                            </option>
                        @endforeach
                    </select>
                    <span></span>
                    @usercan('parties.create')
                    <a class="custom-btn-2" data-bs-toggle="modal" data-bs-target="#supplier-create-modal">
                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.9009 4.16797V15.8346" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5.06689 10H16.7336" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    @endusercan
                    </div>
                </div>
                <!-- Date -->
                <div class="col-md-4">
                    <label class="form-label">{{__('Date')}}</label>
                    <input type="date" name="purchaseDate" class="date-input custom-date w-100" value="{{ date('Y-m-d') }}">
                </div>
                </div>

                <div class="responsive-table overflow-auto cart-table mt-4">
                    <table class="table  align-middle">
                    <thead>
                    <tr>
                        <th>{{__('SL')}}</th>
                        <th class="text-center">{{__('Items')}}</th>
                        <th class="text-center">{{__('Unit')}}</th>
                        <th class="text-center">{{__('Qty')}}</th>
                        <th class="text-center">{{__('Unit Price')}}</th>
                        <th class="text-center">{{__('Amount')}}</th>
                        <th class="text-center">{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="purchase_cart_list">
                       @include('restaurantwebaddon::purchases.cart-list')
                    </tbody>
                </table>
                </div>

                <div class=" mt-4 calculation-container">
                                    <!-- Sub Total -->
                    <div class="d-flex justify-content-between mb-2">
                    <span class="label-text">{{__('Sub Total')}}: </span>
                    <span class="value-text text-muted" id="sub_total"></span>
                    </div>

                    <!-- Discount -->
                    <div class="d-flex justify-content-between mb-2">
                    <span class="label-text">{{__('Discount')}} (<span id="discountPercentShow"></span> %)</span>
                    <input type="number" name="discountAmount" class="form-control form-control-sm text-end discount_input purchase-sm-input" placeholder="{{__('Ex: 10')}}">
                    </div>
                    <input type="hidden" name="discountPercentage" id="discountPercentage">

                    <!-- Vat -->
                    <div class="d-flex justify-content-between mb-2">
                    <span class="label-text">{{__('Vat')}} (<span id="taxPercentShow"></span> %)</span>
                    <input type="number" name="tax_amount" class="form-control form-control-sm purchase-sm-input text-end tax_input" placeholder="{{__('Ex: 10')}}">
                    </div>
                    <input type="hidden" name="tax_percentage" id="tax_percentage">

                    <!-- Total -->
                    <div class="d-flex justify-content-between mb-2">
                    <span class="highlight">{{__('Total')}}:</span>
                    <span class="highlight" id="total_amount"></span>
                    </div>

                    <!-- Pay Amount -->
                    <div class="d-flex justify-content-between mb-2">
                    <span class="label-text">{{__('Pay Amount')}}</span>
                    <input type="number" name="paidAmount" id="receive_amount" class="form-control purchase-sm-input form-control-sm text-end" placeholder="{{__('Ex: 10')}}">
                    </div>

                    <!-- Due -->
                    <div class="d-flex justify-content-between mb-2">
                    <span class="label-text">{{__('Due')}}</span>
                    <input type="number" name="dueAmount" id="due_amount" class="form-control purchase-sm-input form-control-sm text-end" readonly>
                    </div>

                    <!-- Payment Type -->
                    <div class="d-flex justify-content-between mb-2">
                    <span class="label-text">{{__('Payment Type')}}</span>
                    <select name="payment_type_id" class=" payment-select outline-none form-control-sm">
                        @foreach($payment_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    </div>
            </div>
            @usercan('purchases.create')
            <div class="col-lg-12">
                <div class="button-group text-center mt-3">
                    <button data-route="{{ route('business.carts.remove-all') }}" class="theme-btn border-btn m-2 cancel-purchase-btn">{{ __('Reset') }}</button>
                    <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                </div>
            </div>
            @endusercan
        </div>
     </form>
    </div>
    <input type="hidden" value="{{ route('business.purchases.cart') }}" id="purchase-cart">
    <input type="hidden" value="{{ route('business.carts.remove-all') }}" id="clear-cart">
    @php
        $currency = business_currency();
    @endphp
    {{-- Hidden input fields to store currency details --}}
    <input type="hidden" id="currency_symbol" value="{{ $currency->symbol }}">
    <input type="hidden" id="currency_position" value="{{ $currency->position }}">
    <input type="hidden" id="currency_code" value="{{ $currency->code }}">
@endsection

@push('modal')
@include('restaurantwebaddon::purchases.supplier')
@include('restaurantwebaddon::purchases.ingredient')
@endpush

@push('js')
    <script src="{{ asset('assets/js/custom/purchase.js') }}?v={{ time() }}"></script>
@endpush
