@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Expense List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Expense For') }}</th>
                <th>{{ __('Payment Type') }}</th>
                <th>{{ __('Reference Number') }}</th>
                <th>{{ __('Expense Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ currency_format($expense->amount, currency: business_currency()) }}</td>
                    <td>{{ $expense->category?->categoryName }}</td>
                    <td>{{ $expense->expanseFor }}</td>
                    <td>{{ $expense->payment_type->name ?? '' }}</td>
                    <td>{{ $expense->referenceNo }}</td>
                    <td>{{ formatted_date($expense->expenseDate) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
