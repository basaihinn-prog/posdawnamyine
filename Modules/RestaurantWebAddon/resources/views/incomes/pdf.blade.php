@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Income List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
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
            @foreach ($incomes as $income)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ currency_format($income->amount, currency: business_currency()) }}</td>
                    <td>{{ $income->category?->categoryName }}</td>
                    <td>{{ $income->incomeFor }}</td>
                    <td>{{ $income->payment_type->name ?? '' }}</td>
                    <td>{{ $income->referenceNo }}</td>
                    <td>{{ formatted_date($income->incomeDate) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
