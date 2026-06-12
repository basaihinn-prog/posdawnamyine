@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
<div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
    @include('restaurantwebaddon::print.header')
    <h4 class="mt-2">{{ __('Quotation Report List') }}</h4>
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
                <th>{{ __('Quotation Date') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($quotations as $quotation)
            <tr>
                <td>{{ $loop->index+1 }}</td>
                <td>{{ $quotation->invoiceNumber }}</td>
                <td>{{ $quotation->party?->name }}</td>
                <td>{{ currency_format($quotation->totalAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($quotation->discountAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($quotation->paidAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($quotation->dueAmount, currency: business_currency()) }}</td>
                <td>{{ $quotation->payment_type->name ?? '' }}</td>
                <td>{{ formatted_date($quotation->quotationDate) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

