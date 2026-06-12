@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Vat Reports') }}
@endsection

@section('main_content')
    <div class="min-vh-100">
        <div class="erp-table-section">
            <div class="container-fluid">

                <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                    <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                        <span>/ {{ __('Reports') }} </span>
                        <span>/ {{ __('Vat') }} </span>
                    </h2>
                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        {{-- Export --}}
                        <div class="dropdown custom-dropdown">
                            <div class="dropbtn custom-dropbtn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                </svg>
                                {{ __('Export') }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="dropdown-content custom-dropdown-content">
                                <a id="pdfExportLink" href="{{ route('business.vat-reports.pdf') }}" class="custom-item">
                                    {{ __('PDF') }} </a>
                                <a id="csvExportLink" href="{{ route('business.vat-reports.csv') }}" class="custom-item">
                                    {{ __('CSV') }} </a>
                                <a id="excelExportLink" href="{{ route('business.vat-reports.excel') }}" class="custom-item">
                                    {{ __('EXCEL') }} </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="tab-table-container">

                        <div class="custom-tabs">
                            <button class="tab-item active" onclick="showTab('sales')">{{ __('Sales') }}</button>
                            <button class="tab-item" onclick="showTab('purchase')">{{ __('Purchases') }}</button>
                        </div>

                        <div id="sales" class="tab-content dashboard-tab active">
                            <div class="table-container">
                                <table class="table dashboard-table-content">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-start" scope="col">{{ __('Date') }}</th>
                                            <th class="text-center" scope="col">{{ __('Invoice') }}</th>
                                            <th class="text-center" scope="col">{{ __('Customer') }}</th>
                                            <th class="text-center" scope="col">{{ __('Payment Method') }}</th>
                                            <th class="text-center" scope="col">{{ __('Total Amount') }}</th>
                                            <th class="text-center" scope="col">{{ __('Discount') }}</th>
                                            @foreach ($vats as $vat)
                                                <th class="text-center">{{ $vat->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales as $sale)
                                            <tr>
                                                <td class="text-start">{{ formatted_date($sale->created_at) }}</td>
                                                <td class="text-center">{{ $sale->invoiceNumber }}</td>
                                                <td class="text-center">{{ $sale->party->name ?? '' }}</td>
                                                <td class="text-center">{{ $sale->payment_type->name ?? '' }}</td>
                                                <td class="text-center">
                                                    {{ currency_format($sale->totalAmount, currency: business_currency()) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ currency_format($sale->discountAmount, currency: business_currency()) }}
                                                </td>
                                                @foreach ($vats as $vat)
                                                    <td class="text-center">
                                                        {{ $sale->tax_id == $vat->id ? currency_format($sale->tax_amount, currency: business_currency()) : '0' }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-center fw-bold">
                                                {{ currency_format($sales->sum('totalAmount'), currency: business_currency()) }}
                                            </td>
                                            <td class="text-center fw-bold">
                                                {{ currency_format($sales->sum('discountAmount'), currency: business_currency()) }}
                                            </td>
                                            @foreach ($vats as $vat)
                                                <td class="text-center fw-bold">
                                                    {{ currency_format($saleVatTotals[$vat->id] ?? 0, currency: business_currency()) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $sales->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>

                        <div id="purchase" class="tab-content dashboard-tab">
                            <div class="table-container">
                                <table class="table dashboard-table-content">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-start" scope="col">{{ __('Date') }}</th>
                                            <th class="text-center" scope="col">{{ __('Invoice') }}</th>
                                            <th class="text-center" scope="col">{{ __('Supplier') }}</th>
                                            <th class="text-center" scope="col">{{ __('Total Amount') }}</th>
                                            <th class="text-center" scope="col">{{ __('Payment Method') }}</th>
                                            <th class="text-center" scope="col">{{ __('Discount') }}</th>
                                            <th class="text-center" scope="col">{{ __('VAT') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchases as $purchase)
                                            <tr>
                                                <td class="text-start">{{ formatted_date($purchase->created_at) }}</td>
                                                <td class="text-center">{{ $purchase->invoiceNumber }}</td>
                                                <td class="text-center">{{ $purchase->party->name ?? '' }}</td>
                                                <td class="text-center">
                                                    {{ currency_format($purchase->totalAmount, currency: business_currency()) }}
                                                </td>
                                                <td class="text-center">{{ $purchase->payment_type->name ?? '' }}</td>
                                                <td class="text-center">
                                                    {{ currency_format($purchase->discountAmount, currency: business_currency()) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ currency_format($purchase->tax_amount, currency: business_currency()) ?? 0 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td class="text-center fw-bold">
                                                {{ currency_format($purchases->sum('discountAmount'), currency: business_currency()) }}
                                            </td>
                                            <td class="text-center fw-bold">
                                                {{ currency_format($purchases->sum('tax_amount'), currency: business_currency()) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $purchases->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
