@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Table List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Table') }}</th>
                <th>{{ __('Capacity') }}</th>
                <th>{{ __('Availability') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tables as $table)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $table->name }}</td>
                    <td>{{ $table->capacity }}</td>
                    @if ($table->is_booked == '1')
                        <td>{{ __('Booked') }}</td>
                    @else
                        <td>{{ __('Avaiable') }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
