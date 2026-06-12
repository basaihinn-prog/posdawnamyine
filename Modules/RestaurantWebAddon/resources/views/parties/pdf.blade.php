@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ ucfirst(request('type')) . __(' List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th> {{ __('Image') }} </th>
                <th> {{ __('Name') }} </th>
                <th> {{ __('Email') }} </th>
                <th> {{ __('Type') }} </th>
                <th> {{ __('Phone') }} </th>
                <th> {{ __('Due') }} </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($parties as $party)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        <img class="table-img" src="{{ public_path($party->image ?? 'assets/img/icon/no-image.svg') }}">
                    </td>
                    <td>{{ $party->name }}</td>
                    <td>{{ $party->email }}</td>
                    <td>{{ ucfirst($party->type) }}</td>
                    <td>{{ $party->phone }}</td>
                    <td>{{ currency_format($party->due, currency: business_currency()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
