@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Vat List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Rate') }}</th>
                <th>{{ __('Sub Taxs') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vats as $vat)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $vat->name }}</td>
                    <td>{{ $vat->rate }}%</td>
                    <td>
                        @if (!empty($vat->sub_tax))
                            {{ collect($vat->sub_tax)->pluck('name')->implode(', ') }}
                        @else
                            {{__('N/A')}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
