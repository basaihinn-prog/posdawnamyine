@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Kot Report List') }}</h4>
        @if ($from_date && $to_date)
            <h4 style="text-align: center; margin-top: 7px;">{{ __('Duration:') }}
                @if ($duration === 'today')
                    {{ Carbon\Carbon::parse($from_date)->format('d-m-Y') }}
                @elseif ($duration === 'yesterday')
                    {{ Carbon\Carbon::parse($from_date)->format('d-m-Y') }}
                @else
                    {{ Carbon\Carbon::parse($from_date)->format('d-m-Y') }}
                    {{ __('to') }}
                    {{ Carbon\Carbon::parse($to_date)->format('d-m-Y') }}
                @endif
            </h4>
        @endif
    </div>
@endsection

@section('pdf_content')
    <table class="table" id="datatable">
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th class="text-start">{{ __('Kot') }}</th>
                <th class="text-start">{{ __('Order') }}</th>
                <th class="text-start">{{ __('Date & Time') }}</th>
                <th class="text-start">{{ __('Customers') }}</th>
                <th class="text-center">{{ __('Items') }}</th>
                <th class="text-start">{{ __('Table') }}</th>
                <th class="text-center">{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kots as $kot)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="text-start">{{ $kot->kot_ticket?->kot_number }}</td>
                    <td class="text-start">{{ $kot->invoiceNumber }}</td>
                    <td class="text-start">{{ formatted_date($kot->saleDate) }} {{ formatted_time($kot->saleDate) }}</td>
                    <td class="text-start">{{ $kot->party?->name }}</td>
                    <td class="text-center">{{ $kot->total_item }}</td>
                    <td class="text-start">{{ $kot->table?->name }}</td>
                    <td class="text-center">{{ ucfirst($kot->kot_ticket?->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
