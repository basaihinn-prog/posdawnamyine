@extends('restaurantwebaddon::layouts.blank')

@section('title')
    {{ __('Invoice') }}
@endsection

@section('main_content')
<div class="invoice-container-sm" lang="{{ app()->getLocale() }}">
    <div class="invoice-content invoice-content-size">
        <div class="invoice-logo">
            <img src="{{ asset(get_business_option('business-settings')['invoice_logo'] ?? 'assets/images/icons/logo.svg') ?? '' }}" alt="Logo">
        </div>

        <div class="mt-2">
            <h4 class="company-name">{{ $purchase->business?->companyName ?? 'Restaurant App' }}</h4>
            <div class="company-info">
                <p><span class="invoice-bold">{{ __('Address') }} :</span> {{ $purchase->business?->address ?? '' }}</p>
                <p><span class="invoice-bold">{{ __('Mobile') }} :</span> {{ $purchase->business?->phoneNumber ?? '' }}</p>
                <p><span class="invoice-bold">{{ __('Email') }} :</span> {{ get_business_option('business-settings')['email'] ?? '' }}</p>
                @if (!empty($purchase->business->vat_name))
                    <p><span class="">{{ $purchase->business->vat_name }} :</span> {{ $purchase->business->vat_no ?? '' }}</p>
                @endif
            </div>
        </div>

        <h3 class="invoice-title my-1 invoice-bold">
            {{ __('Invoice') }}
        </h3>

        <div class="invoice-info">
            <div>
                <p><span class="invoice-bold">{{ __('Order No') }} :</span> {{ $purchase->invoiceNumber }}</p>
                <p><span class="invoice-bold">{{ __('Name') }} :</span> {{ $purchase->party->name ?? '' }}</p>
            </div>

            <div>
                <p class="text-end date"><span class="invoice-bold">{{ __('Date') }} :</span> {{ formatted_date($purchase->purchaseDate) }}</p>
                <p class="text-end time"><span class="invoice-bold">{{ __('Time') }} :</span> {{ formatted_time($purchase->purchaseDate) }}</p>
                <p class="text-end">
                    <span class="invoice-bold">{{ __('Purchase By') }} :</span>
                    {{ $purchase->user->role != 'staff' ? 'Admin' : $purchase->user->name ?? '' }}
                </p>
            </div>
        </div>

        <table class="ph-invoice-table">
            <thead>
                <tr>
                    <th class="text-start invoice-bold">{{ __('SL') }}</th>
                    <th class="invoice-bold">{{ __('Items') }}</th>
                    <th class="invoice-bold">{{ __('QTY') }}</th>
                    <th class="invoice-bold">{{ __('U.Price') }}</th>
                    <th class="text-end invoice-bold">{{ __('Amount') }}</th>
                </tr>
            </thead>

            @php $subtotal = 0; @endphp
            <tbody>
                @foreach ($purchase->details as $detail)
                    @php
                        $total_amount = ($detail->unit_price ?? 0) * ($detail->quantities ?? 0);
                        $subtotal += $total_amount;
                    @endphp
                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>
                        <td>{{ $detail->ingredient->name ?? '' }}</td>
                        <td class="text-center">{{ $detail->quantities }}</td>
                        <td class="text-center">{{ currency_format($detail->unit_price, currency: business_currency()) }}</td>
                        <td class="text-end">{{ currency_format($total_amount, currency: business_currency()) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2">
                        <div class="payment-type-container">
                            <h6 class="text-start payment-type-text">
                                <span class="invoice-bold">{{ __('Payment Type') }} :</span>
                                {{ $purchase->payment_type->name ?? '' }}
                            </h6>
                        </div>
                    </td>

                    <td colspan="3">
                        <div class="calculate-amount">
                            <div class="d-flex justify-content-between">
                                <p class="invoice-bold">{{ __('Sub-Total') }} :</p>
                                <p>{{ currency_format($subtotal, currency: business_currency()) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="invoice-bold">{{ __('Vat') }} :</p>
                                <p>{{ currency_format($purchase->tax_amount, currency: business_currency()) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="invoice-bold">{{ __('Discount') }} :</p>
                                <p>{{ currency_format($purchase->discountAmount, currency: business_currency()) }}</p>
                            </div>

                            <div class="d-flex justify-content-between total-amount">
                                <p class="net-payable invoice-bold">{{ __('Net Payable') }} :</p>
                                <p class="net-payable">
                                    {{ currency_format($subtotal + ($purchase->tax_amount ?? 0) - ($purchase->discountAmount ?? 0), currency: business_currency()) }}
                                </p>
                            </div>

                            <div class="d-flex justify-content-between paid">
                                <p class="invoice-bold">{{ __('Paid') }} :</p>
                                <p>{{ currency_format($purchase->paidAmount, currency: business_currency()) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="invoice-bold">{{ __('Due') }} :</p>
                                <p>{{ currency_format($purchase->dueAmount, currency: business_currency()) }}</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-footer-sm mt-3">
            <h5 class="invoice-bold">{{ get_business_option('business-settings')['gratitude_message'] ?? '' }}</h5>

            @if (!empty(get_business_option('business-settings')['note']))
                <p class="text-center note-pera">
                    <span class="invoice-bold">{{ get_business_option('business-settings')['note_label'] ?? '' }} :</span>
                    {{ get_business_option('business-settings')['note'] ?? '' }}
                </p>
            @endif

            <div class="scanner-2">
                <img src="{{ asset('uploads/qr-codes/qrcode.svg') }}" alt="scanner">
            </div>

            <h6 class="invoice-bold">
                {{ get_option('general')['admin_footer_text'] ?? '' }}
                <a href="{{ get_option('general')['admin_footer_link'] ?? '#' }}" target="_blank">
                    {{ get_option('general')['admin_footer_link_text'] ?? '' }}
                </a>
            </h6>
        </div>
    </div>
</div>

@endsection
@push('js')
    <script src="{{ asset('assets/js/custom/onloadPrint.js') }}"></script>
@endpush
