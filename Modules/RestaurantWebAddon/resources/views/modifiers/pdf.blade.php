@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
    <div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
        @include('restaurantwebaddon::print.header')
        <h4 class="mt-2">{{ __('Modifier List') }}</h4>
    </div>
@endsection

@section('pdf_content')
    <table>
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Product Name') }}</th>
                <th>{{ __('Group Name') }}</th>
                <th>{{ __('Required') }}</th>
                <th>{{ __('Allow Multiple') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modifiers as $modifier)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $modifier->product->productName ?? '' }}</td>
                    <td>{{ $modifier->modifier_group->name ?? '' }}</td>
                    <td>
                        @if ($modifier->is_required == '1')
                            <span>{{ __('Required') }}</span>
                        @else
                            <span>{{ __('Optional') }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($modifier->is_multiple == '1')
                            <span>{{ __('Yes') }}</span>
                        @else
                            <span>{{ __('No') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
