@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Expense Category List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Name') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expense_categories as $category)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $category->categoryName }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
