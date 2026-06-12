@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
<div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
    @include('restaurantwebaddon::print.header')
    <h4 class="mt-2">{{ __('Expenses Report List') }}</h4>
</div>
@endsection

@section('pdf_content')
    <table class="styled-table">
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
            @foreach ($expense_reports as $expense_report)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ currency_format($expense_report->amount, currency: business_currency()) }}</td>
                    <td>{{ $expense_report->category->categoryName }}</td>
                    <td>{{ $expense_report->expanseFor }}</td>
                    <td>{{ $expense_report->payment_type_id != null ? $expense_report->payment_type->name ?? '' : $expense_report->paymentType }}</td>
                    <td>{{ $expense_report->referenceNo }}</td>
                    <td>{{ formatted_date($expense_report->expenseDate) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
