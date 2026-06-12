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
            <h4 class="company-name">{{ $walk_in_customer->dueCollect->business?->companyName ?? 'Restaurant App' }}</h4>
            <div class="company-info">
                <p><span class="invoice-bold">{{ __('Address') }} :</span> {{ $walk_in_customer->dueCollect->business?->address ?? '' }}</p>
                <p><span class="invoice-bold">{{ __('Mobile') }} :</span> {{ $walk_in_customer->dueCollect->business?->phoneNumber ?? '' }}</p>
                <p><span class="invoice-bold">{{ __('Email') }} :</span> {{ get_business_option('business-settings')['email'] ?? '' }}</p>
                @if (!empty($walk_in_customer->dueCollect->business->vat_name))
                    <p><span class="invoice-bold">{{ $walk_in_customer->dueCollect->business->vat_name }} :</span> {{ $walk_in_customer->dueCollect->business->vat_no ?? '' }}</p>
                @endif
            </div>
        </div>

        <h3 class="invoice-title my-1 invoice-bold">
            {{ __('Invoice') }}
        </h3>

        <div class="invoice-info">
            <div>
                <p><span class="invoice-bold">{{ __('Order No') }} :</span> {{ $due_collect->invoiceNumber }}</p>
                <p><span class="invoice-bold">{{ __('Name') }} :</span> {{ $due_collect->party->name ?? 'Cash' }}</p>
                <p><span class="invoice-bold">{{ __('Mobile') }} :</span> {{ $due_collect->party->phone ?? '' }}</p>
            </div>

            <div>
                <p class="text-end date"><span class="invoice-bold">{{ __('Date') }} :</span> {{ formatted_date($due_collect->paymentDate) }}</p>
                <p class="text-end time"><span class="invoice-bold">{{ __('Time') }} :</span> {{ formatted_time($due_collect->paymentDate) }}</p>
                <p class="text-end">
                    <span class="invoice-bold">{{ __('Collected By') }} :</span>
                    {{ $due_collect->user->role != 'staff' ? 'Admin' : $due_collect->user->name ?? '' }}
                </p>
            </div>
        </div>

        <table class="ph-invoice-table">
            <thead>
                <tr>
                    <th class="text-start invoice-bold">{{ __('SL') }}</th>
                    <th class="invoice-bold">{{ __('Total Due') }}</th>
                    <th class="invoice-bold">{{ __('Pay Amount') }}</th>
                    <th class="text-end invoice-bold">{{ __('Due Amount') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="text-start">1</td>
                    <td class="text-start">{{ currency_format($due_collect->totalDue ?? 0, 'icon', 2, business_currency()) }}</td>
                    <td class="text-center">{{ currency_format($due_collect->payDueAmount ?? 0, 'icon', 2, business_currency()) }}</td>
                    <td class="text-end">{{ currency_format($due_collect->dueAmountAfterPay ?? 0, 'icon', 2, business_currency()) }}</td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="payment-type-container">
                            <h6 class="text-start payment-type-text">
                                <span class="invoice-bold">{{ __('Payment Type') }} :</span>
                                {{ $due_collect->payment_type->name ?? '' }}
                            </h6>
                        </div>
                    </td>

                    <td colspan="3">
                        <div class="calculate-amount">
                            <div class="d-flex justify-content-between">
                                <p class="invoice-bold">{{ __('Payable') }} :</p>
                                <p>{{ currency_format($due_collect->totalDue ?? 0, 'icon', 2, business_currency()) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="invoice-bold">{{ __('Received') }} :</p>
                                <p>{{ currency_format($due_collect->payDueAmount ?? 0, 'icon', 2, business_currency()) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="invoice-bold">{{ __('Due') }} :</p>
                                <p>{{ currency_format($due_collect->dueAmountAfterPay ?? 0, 'icon', 2, business_currency()) }}</p>
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
