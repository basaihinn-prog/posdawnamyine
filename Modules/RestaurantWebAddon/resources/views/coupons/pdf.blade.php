@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Coupon List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Code') }}</th>
                <th>{{ __('Discount') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $coupon)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        <img class="table-img" src="{{ public_path($coupon->image ?? 'assets/img/icon/no-image.svg') }}">
                    </td>
                    <td>{{ $coupon->name }}</td>
                    <td>{{ $coupon->code }}</td>
                    <td>
                        @if ($coupon->discount_type == 'percentage')
                            {{ $coupon->discount }}%
                        @else
                            {{ currency_format($coupon->discount) }}
                        @endif
                    </td>
                    <td>{{ formatted_date($coupon->start_date) }}</td>
                    <td>{{ formatted_date($coupon->end_date) }}</td>
                    <td>
                        @if (\Carbon\Carbon::parse($coupon->end_date)->isPast())
                            <span class="text-danger">{{ __('Expired') }}</span>
                        @else
                            <span class="text-success">{{ __('Active') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
