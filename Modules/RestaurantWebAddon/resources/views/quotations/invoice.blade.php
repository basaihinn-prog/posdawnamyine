@extends('restaurantwebaddon::layouts.blank')

@section('title')
    {{ __('Invoice') }}
@endsection

@section('main_content')
<div class="invoice-container-sm">
<div class="invoice-content invoice-content-size">
    <div class="invoice-logo">
        <img src="{{ asset(get_business_option('business-settings')['invoice_logo'] ?? 'assets/images/icons/logo.svg') }}" alt="Logo">
    </div>

    <div class="mt-2">
        <h4 class="company-name">{{ $quotation->business->companyName ?? '' }}</h4>
        <div class="company-info">
            <p><span class="invoice-bold">{{__('Address')}}</span> : {{ $quotation->business->address ?? '' }}</p>
            <p><span class="invoice-bold">{{__('Mobile')}}</span> : {{ $quotation->business->phoneNumber ?? '' }}</p>
            <p><span class="invoice-bold">{{__('Email')}}</span> : {{ get_business_option('business-settings')['email'] ?? '' }}</p>
            @if (!empty($quotation->business->vat_name))
                <p><span class="invoice-bold">{{ $quotation->business->vat_name }}</span> : {{ $quotation->business->vat_no ?? '' }}</p>
            @endif
        </div>
    </div>

    <h3 class="invoice-title my-1">
        <span class="invoice-bold">{{__('Invoice')}}</span>
    </h3>

    <div class="invoice-info">
        <div>
            <p><span class="invoice-bold">{{__('Order No')}}</span> : {{ $quotation->invoiceNumber ?? '' }}</p>
            <p><span class="invoice-bold">{{__('Name')}}</span> : {{ $quotation->party->name ?? 'Cash' }}</p>
        </div>
        <div>
            <p class="text-end date"><span class="invoice-bold">{{__('Date')}}</span> : {{ formatted_date($quotation->quotationDate ?? '') }}</p>
            <p class="text-end time"><span class="invoice-bold">{{__('Time')}}</span> : {{ formatted_time($quotation->quotationDate ?? '') }}</p>
            <p class="text-end"><span class="invoice-bold">{{ __('Quotation By') }}</span> : {{ $quotation->user->role != 'staff' ? 'Admin' : $quotation->user->name ?? '' }}</p>
        </div>
    </div>

    <table class="ph-invoice-table">
        <thead>
            <tr>
                <th class="text-start"><span class="invoice-bold">{{__('SL')}}</span></th>
                <th><span class="invoice-bold">{{__('Items')}}</span></th>
                <th><span class="invoice-bold">{{__('QTY')}}</span></th>
                <th><span class="invoice-bold">{{__('U.Price')}}</span></th>
                <th class="text-end"><span class="invoice-bold">{{__('Amount')}}</span></th>
            </tr>
        </thead>

        @php $subTotal = 0; @endphp
        <tbody>
            @foreach ($quotation->details ?? [] as $detail)
                @php
                    $productTotal = ($detail->price ?? 0) * ($detail->quantities ?? 0);
                    $subTotal += $productTotal;
                @endphp
                <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>
                    <td>
                        {{ $detail->product->productName ?? '' }}
                        @foreach ($detail->detail_options ?? [] as $modifier)
                            <ul class="modifier-ul">
                                <li>
                                    {{ $modifier->modifier_group_option->name ?? '' }}
                                    (<span>{{ currency_format($modifier->modifier_group_option->price ?? 0, currency: business_currency()) }}</span>)
                                </li>
                            </ul>
                        @endforeach
                    </td>
                    <td class="text-center">{{ $detail->quantities }}</td>
                    <td class="text-center">{{ currency_format($detail->price ?? 0, currency: business_currency()) }}</td>
                    <td class="text-end">{{ currency_format($productTotal, currency: business_currency()) }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="2">
                    <div class="payment-type-container">
                        <h6 class="text-start payment-type-text">
                            <span class="invoice-bold">{{__('Payment Type')}}</span> : {{ $quotation->payment_type->name ?? '' }}
                        </h6>
                    </div>
                </td>
                <td colspan="3">
                    <div class="calculate-amount">
                        <div class="d-flex justify-content-between">
                            <p><span class="invoice-bold">{{__('Sub-Total')}}</span> :</p>
                            <p>{{ currency_format($subTotal, currency: business_currency()) }}</p>
                        </div>

                        @if (!empty($quotation->tax_amount))
                        <div class="d-flex justify-content-between">
                            <p><span class="invoice-bold">{{__('Vat')}}</span> :</p>
                            <p>{{ currency_format($quotation->tax_amount, currency: business_currency()) }}</p>
                        </div>
                        @endif

                        @if (!empty($quotation->discountAmount))
                        <div class="d-flex justify-content-between">
                            <p><span class="invoice-bold">{{__('Discount')}}</span> :</p>
                            <p>{{ currency_format($quotation->discountAmount, currency: business_currency()) }}</p>
                        </div>
                        @endif

                        @if (!empty($quotation->coupon_amount))
                        <div class="d-flex justify-content-between">
                            <p><span class="invoice-bold">{{__('Coupon')}}</span> :</p>
                            <p>{{ currency_format($quotation->coupon_amount, currency: business_currency()) }}</p>
                        </div>
                        @endif

                        @if (!empty($quotation->meta['tip']))
                        <div class="d-flex justify-content-between">
                            <p><span class="invoice-bold">{{__('Tips')}}</span> :</p>
                            <p>{{ currency_format($quotation->meta['tip'], currency: business_currency()) }}</p>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between total-amount">
                            <p class="net-payable"><span class="invoice-bold">{{__('Net Payable')}}</span> :</p>
                            <p class="net-payable">{{ currency_format($quotation->totalAmount ?? 0, currency: business_currency()) }}</p>
                        </div>

                        <div class="d-flex justify-content-between paid">
                            <p><span class="invoice-bold">{{__('Paid')}}</span> :</p>
                            <p>{{ currency_format($quotation->paidAmount ?? 0, currency: business_currency()) }}</p>
                        </div>

                        <div class="d-flex justify-content-between">
                            <p><span class="invoice-bold">{{__('Due')}}</span> :</p>
                            <p>{{ currency_format($quotation->dueAmount ?? 0, currency: business_currency()) }}</p>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="invoice-footer-sm mt-3">
        <h5>{{ get_business_option('business-settings')['gratitude_message'] ?? '' }}</h5>

        @if (!empty(get_business_option('business-settings')['note']))
            <p class="text-center note-pera">
                <span class="invoice-bold">{{ get_business_option('business-settings')['note_label'] ?? '' }}</span> :
                {{ get_business_option('business-settings')['note'] ?? '' }}
            </p>
        @endif

        <div class="scanner-2">
            <img src="{{ asset('uploads/qr-codes/qrcode.svg') }}" alt="scanner">
        </div>

        <h6>
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
