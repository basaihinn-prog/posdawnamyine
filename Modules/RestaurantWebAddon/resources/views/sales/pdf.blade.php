@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Sale List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Invoice No') }}</th>
                <th>{{ __('Party Name') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Discount') }}</th>
                <th>{{ __('Paid') }}</th>
                <th>{{ __('Due') }}</th>
                <th>{{ __('Payment Type') }}</th>
                <th>{{ __('Payment Status') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ formatted_date($sale->saleDate) }}</td>
                    <td>{{ $sale->invoiceNumber }}</td>
                    <td>{{ $sale->party->name ?? '' }}</td>
                    <td>{{ currency_format($sale->totalAmount, 'icon', 2, $currency) }}</td>
                    <td>{{ currency_format($sale->discountAmount, 'icon', 2, $currency) }}</td>
                    <td>{{ currency_format($sale->paidAmount, 'icon', 2, $currency) }}</td>
                    <td>{{ currency_format($sale->dueAmount, 'icon', 2, $currency) }}</td>
                    <td>{{ $sale->payment_type->name ?? '' }}</td>
                    <td>
                        @if ($sale->dueAmount == 0)
                            <div>{{ __('Paid') }}</div>
                        @elseif($sale->dueAmount > 0 && $sale->dueAmount < $sale->totalAmount)
                            <div>{{ __('Partial') }}</div>
                        @else
                            <div>{{ __('Unpaid') }}</div>
                        @endif
                    </td>
                    <td>{{ ucfirst($sale->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
