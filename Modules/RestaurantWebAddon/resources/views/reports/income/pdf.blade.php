@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
<div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
    @include('restaurantwebaddon::print.header')
    <h4 class="mt-2">{{ __('Income Report List') }}</h4>
</div>
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Income For') }}</th>
                <th>{{ __('Payment Type') }}</th>
                <th>{{ __('Reference Number') }}</th>
                <th>{{ __('Income Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($income_reports as $income_report)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ currency_format($income_report->amount, currency: business_currency()) }}</td>
                    <td>{{ $income_report->category->categoryName }}</td>
                    <td>{{ $income_report->incomeFor }}</td>
                    <td>{{ $income_report->payment_type_id != null ? $income_report->payment_type->name ?? '' : $income_report->paymentType }}</td>
                    <td>{{ $income_report->referenceNo }}</td>
                    <td>{{ formatted_date($income_report->incomeDate) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
