@extends('restaurantwebaddon::layouts.blank')

@section('title')
    {{ __('Invoice') }}
@endsection

@section('main_content')
    <div class="invoice-container-sm">
    <div class="invoice-content invoice-content-size">
        <div class="invoice-logo">
            <img src="{{ asset(get_business_option('business-settings')['invoice_logo'] ?? 'assets/images/icons/logo.svg') ?? '' }}" alt="Logo">
        </div>
        <h2 class="text-center logo-title pt-2">{{ $sale->business->companyName ?? '' }}</h2>
        <h3 class="text-center kot-title">{{__('Order')}} : {{ $sale->invoiceNumber }}</h3>
        <div class="mb-3">
            <p class="text-black kot-info">{{__('Table No')}} : {{ $sale->table->name ?? '' }}</p>
            <p class="text-black kot-info">{{__('Date')}} : {{ formatted_date($sale->saleDate ?? '') }}  {{ formatted_time($sale->saleDate ?? '') }}</p>
        </div>
        <!-- Table -->
        <table class="ph-invoice-table">
            <thead>
                <tr>
                    <th class="text-start">{{__('SL')}}</th>
                    <th>{{__('Items')}}</th>
                    <th class="text-center">{{__('QTY')}}</th>
                </tr>
            </thead>
            @php
                $totalItems = $sale->details->count() ?? 0;
                $totalQty = $sale->details->sum('quantities') ?? 0;
            @endphp
            <tbody>
                @foreach ($sale->details ?? [] as $detail)
                <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>
                    <td>
                        {{ $detail->product->productName ?? '' }}
                        @foreach ($detail->detail_options ?? [] as $modifier)
                        <ul class="modifier-ul">
                            <li>{{ $modifier->modifier_group_option->name ?? '' }} (<span>{{  currency_format($modifier->modifier_group_option->price ?? 0, currency: business_currency() )}}</span>)</li>
                        </ul>
                        @endforeach
                    </td>
                    <td class="text-center">{{ $detail->quantities }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="text-start total-items fw-bold" colspan="2">{{__('Items')}} : {{ $totalItems }}</td>
                    <td class="text-center total-items fw-bold">{{__('Qty')}} : {{ $totalQty }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/custom/onloadPrint.js') }}"></script>
@endpush
