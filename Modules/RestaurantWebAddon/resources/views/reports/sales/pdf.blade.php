@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
<div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
    @include('restaurantwebaddon::print.header')
    <h4 class="mt-2">{{ __('Sales Report List') }}</h4>
</div>
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Invoice No') }}</th>
                <th>{{ __('Party Name') }}</th>
                <th>{{ __('Total Amount') }}</th>
                <th>{{ __('Discount Amount') }}</th>
                <th>{{ __('Paid Amount') }}</th>
                <th>{{ __('Due Amount') }}</th>
                <th>{{ __('Payment Type') }}</th>
                <th>{{ __('Sale Date') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $sale->invoiceNumber }}</td>
                <td>{{ $sale->party?->name }}</td>
                <td>{{ currency_format($sale->totalAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($sale->discountAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($sale->paidAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($sale->dueAmount, currency: business_currency()) }}</td>
                <td>{{ $sale->payment_type->name ?? '' }}</td>
                <td>{{ formatted_date($sale->saleDate) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

