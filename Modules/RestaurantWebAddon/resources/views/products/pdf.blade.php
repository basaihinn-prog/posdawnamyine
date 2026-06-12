@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Item List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th> {{ __('Image') }} </th>
                <th> {{ __('Item Name') }} </th>
                <th> {{ __('Menu') }} </th>
                <th> {{ __('Category') }} </th>
                <th> {{ __('Variation') }} </th>
                <th> {{ __('Sale price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>
                        <img class="table-img" src="{{ public_path($product->images[0] ?? 'assets/img/icon/no-image.svg') }}">
                    </td>
                    <td>{{ $product->productName }}</td>
                    <td>{{ $product->menu->name ?? '' }}</td>
                    <td>{{ $product->category->categoryName ?? '' }}</td>
                    <td>{{ $product->variations_count ?? '' }}</td>
                    <td>{{ currency_format($product->sales_price, currency: business_currency()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
