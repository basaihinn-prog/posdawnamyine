@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Due Collection List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Invoice') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Paid') }}</th>
                <th>{{ __('Payment Type') }}</th>
                <th>{{ __('Stauts') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($due_collections as $due_collection)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $due_collection->invoiceNumber }}</td>
                    <td>{{ formatted_date($due_collection->paymentDate) }}</td>
                    <td>{{ currency_format($due_collection->totalDue, currency: business_currency()) }}</td>
                    <td>{{ currency_format($due_collection->payDueAmount, currency: business_currency()) }}</td>
                    <td>{{ $due_collection->payment_type?->name }}</td>
                    @if ($due_collection->dueAmountAfterPay == 0)
                        <td>{{ __('Paid') }}</td>
                    @else
                        <td>{{ __('Partial') }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
