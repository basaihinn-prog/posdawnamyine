@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Pos Sale') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/splide.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-choice.css') }}?v={{ time() }}">
@endpush
@section('main_content')
    <div class="sales-containers">
        <div id="salesContent2" class="sales-content">

            <div id="salesRight2" class="sales-right-content">
                <form action="{{ route('business.sales.product-filter') }}" method="post"
                    class="product-filter product-filter-form w-100" table="#products-list">
                    @csrf
                    <div class="search-container">
                        <i class="search-icon">
                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.3534 12.1467L10.7927 9.586C11.6487 8.57067 12.1667 7.262 12.1667 5.83333C12.1667 2.61667 9.55 0 6.33333 0C3.11667 0 0.5 2.61667 0.5 5.83333C0.5 9.05 3.11667 11.6667 6.33333 11.6667C7.762 11.6667 9.07069 11.1487 10.086 10.2927L12.6466 12.8533C12.744 12.9507 12.872 13 13 13C13.128 13 13.256 12.9513 13.3534 12.8533C13.5487 12.6587 13.5487 12.342 13.3534 12.1467ZM1.5 5.83333C1.5 3.168 3.668 1 6.33333 1C8.99867 1 11.1667 3.168 11.1667 5.83333C11.1667 8.49867 8.99867 10.6667 6.33333 10.6667C3.668 10.6667 1.5 8.49867 1.5 5.83333Z" fill="#1C1C1C" />
                            </svg>
                        </i>
                        <input type="text" class="search-box search-input" name="search" placeholder="{{__('Search...')}}">
                        @usercan('products.create')
                        <a href="{{ route('business.products.create') }}" class="add-btn">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.9565 4.74426V18.5318" stroke="#FC8019" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M4.89233 11.6376H19.0202" stroke="#FC8019" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        @endusercan
                    </div>
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="category-btn-container">
                        <div id="sales-splide" class="splide">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    <li class="splide__slide">
                                        <button class="category-btn active">{{__('Show all')}}</button>
                                    </li>
                                    @foreach ($categories as $category)
                                        <li class="splide__slide">
                                            <button class="category-btn" data-id="{{ $category->id }}">
                                                {{ $category->categoryName }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Navigation buttons -->
                            <div class="splide__arrows">
                                <button class="splide__arrow splide__arrow--prev">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 18L9 12L15 6" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </button>
                                <button class="splide__arrow splide__arrow--next">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 18L15 12L9 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                    @include('restaurantwebaddon::sales.products')
            </div>

            <div  id="salesLeft2" class="left-content-sales">
                <form action="{{ route('business.quotations.update', $quotation->id) }}" method="post"
                    enctype="multipart/form-data" class="ajaxform_redirect_invoice">
                    @csrf
                    @method('put')
                    <div class="sales-left-content">
                        <div class="order-form-section select-container">

                            {{-- for quotation --}}
                            <div class="col-md-12">
                                <div class="">
                                    <div class="gpt-up-down-arrow position-relative custom-select-wrapper">
                                        <select name="party_id" class="form-control table-select customer-select w-100 choices-select" data-modal="#customer-create-modal">
                                            <option value="">{{__('Select Customer')}}</option>
                                            @foreach ($customers as $cstomer)
                                                <option @selected($quotation->party_id == $cstomer->id) value="{{ $cstomer->id }}">
                                                    {{ $cstomer->name }}( {{ $cstomer->due ? __('Due:') . ' ' . currency_format($cstomer->due, currency: business_currency()) : '' }} )</option>
                                            @endforeach
                                        </select>
                                        <span></span>
                                        <div class="custom-add-button">
                                            @usercan('parties.create')
                                            <a type="button" href="#customer-create-modal" data-bs-toggle="modal">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10.0009 4.16675V15.8351" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.835 10.0017H4.16663" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            @endusercan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive sales-table mt-3">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th class="table-background text-start">{{__('Items Name')}}</th>
                                        <th class="table-background">{{__('Qty')}}</th>
                                        <th class="table-background">{{__('Unit Price')}}</th>
                                        <th class="table-background">{{__('Amount')}}</th>
                                        <th class="table-background">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-list">
                                    @include('restaurantwebaddon::sales.cart-list')
                                </tbody>
                            </table>
                        </div>

                        <div class="bottom-amount-section">
                            <div class="payment-container">
                                <div class="custom-form-group">
                                    <p>{{__('Receive Amount')}}x</p>
                                    <select name="payment_type_id" id="payment-type" class="custom-input">
                                        @foreach ($payment_types as $payment_type)
                                            <option @selected($quotation->payment_type_id == $payment_type->id) value="{{ $payment_type->id }}">{{ $payment_type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="custom-form-group">
                                    <p>{{__('Receive Amount')}}</p>
                                    <input type="number" name="paidAmount" value="{{ $quotation->paidAmount }}"
                                        id="receive_amount" class="custom-input" placeholder="{{__('Ex: 10')}}">
                                </div>

                                <div class="custom-form-group">
                                    <p>{{__('Change Amount')}}</p>
                                 <input type="number" id="change_amount" class="custom-input" placeholder="{{__('Ex: 10')}}" readonly>
                                </div>

                                <div class="custom-form-group">
                                    <p>{{__('Due Amount')}}</p>
                                    <input type="number" name="dueAmount" id="due_amount" class="custom-input "
                                        placeholder="{{__('Ex: 10')}}" readonly>
                                </div>
                            </div>

                            <div class="payment-container">
                                @if (!empty($vat_on_sale))
                                <div class="pay-row">
                                    <span class="pay-label-text vatOnSale"
                                        data-vat-sale="{{ $vat_on_sale->rate ?? 0 }}">{{ $vat_on_sale->name ?? 'N/A' }}
                                        ({{ $vat_on_sale->rate ?? 0 }} %)</span>
                                    <span class="value vatAmountValue"></span>
                                    <input type="hidden" name="vat_id" value="{{ $vat_on_sale->id ?? null }}">
                                    <input type="hidden" name="tax_amount" class="vatAmountValue">
                                </div>
                                @endif
                                <div class="pay-row coupon-row">
                                    <div class="d-flex align-items-center">
                                        <a href="#couponModal" class="pay-label-text coupon getCoupon"
                                            data-bs-toggle="modal">{{__('Coupon')}} <span
                                                class="text-black couponPercentageShow"></span></a>
                                        <div class="cross-icon crossBtnRemove d-none" id="crossBtn">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 4L4 12" stroke="#FF5F57" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M4 4L12 12" stroke="#FF5F57" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="coupon-box">
                                        <span class="value couponAmountShow"></span>
                                    </div>
                                </div>
                                {{-- For Coupon Store Fileld --}}
                                <input type="hidden" name="coupon_id" class="couponId">
                                <input type="hidden" name="coupon_percentage" class="couponPercentageVal">
                                <input type="hidden" name="coupon_amount" class="couponAmountVal">
                                {{-- For Coupon js value pass --}}
                                <input type="hidden" class="coupondiscount"
                                    value="{{ $quotation->coupon->discount ?? '' }}" id="coupondiscount">
                                <input type="hidden" class="couponType"
                                    value="{{ $quotation->coupon->discount_type ?? '' }}" id="couponType">

                                <div class="pay-row discount-row">
                                    <span class="pay-label-text">{{__('Discount')}} (<span class="discountPercentageShow"></span>%)</span>
                                    <input type="number" name="discountAmount" value="{{ $quotation->discountAmount }}"
                                        class="discount-input discount_amount" placeholder="{{__('Ex: 10')}}">
                                </div>
                                <input type="hidden" name="discountPercentage" class="discountPercentageShow">

                                <div class="pay-row discount-row">
                                    <span class="pay-label-text">{{__('Tips')}}</span>
                                    <input type="number" name="tip" value="{{ $quotation->meta['tip'] ?? '' }}"
                                        class="discount-input tip" placeholder="{{__('Ex: 10')}}">
                                </div>
                            </div>
                        </div>
                        @usercan ('quotations.update')
                        <div class="d-flex align-items-center justify-content-center">
                            <button type="submit" class="submit-btn sale-update">{{__('Update')}}</button>
                        </div>
                        @endusercan
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('restaurantwebaddon::sales.coupons')
    @include('restaurantwebaddon::sales.customer')

    <input type="hidden" value="{{ route('business.sale-carts.index') }}" id="get-cart">
    <input type="hidden" value="{{ route('business.sale-carts.remove-all') }}" id="clear-cart">
    <input type="hidden" value="{{ route('business.sales.getSaleCoupon') }}" id="getSaleCoupon">
    @php
        $currency = business_currency();
        $cart_sound = get_business_option('business-settings')['cart_sound'] ?? ''
    @endphp
    {{-- Hidden input fields to store currency details --}}
    <input type="hidden" id="currency_symbol" value="{{ $currency->symbol }}">
    <input type="hidden" id="currency_position" value="{{ $currency->position }}">
    <input type="hidden" id="currency_code" value="{{ $currency->code }}">
    <input type="hidden" id="cart-sound-path" value="{{  asset($cart_sound ?? '') }}">
@endsection

@push('modal')
    @include('restaurantwebaddon::sales.variation-product')
@endpush

@push('js')
    <script src="{{ asset('assets/js/custom/sales.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/splide.js') }}"></script>
    <script src="{{ asset('assets/js/custom/responsive.js') }}"></script>
@endpush
