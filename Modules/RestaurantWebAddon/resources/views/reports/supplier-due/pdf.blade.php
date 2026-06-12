@extends('restaurantwebaddon::layouts.pdf.pdf_layout')

@push('css')
    @include('restaurantwebaddon::pdf.style')
@endpush

@section('pdf_title')
<div class="table-header justify-content-center border-0 d-none d-block d-print-block  text-center">
    @include('restaurantwebaddon::print.header')
    <h4 class="mt-2">{{ __('Supplier Due List') }}</h4>
</div>
@endsection

@section('pdf_content')
    <table class="styled-table">
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Due Amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($due_lists as $due_list)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $due_list->name }}</td>
                    <td>{{ $due_list->email }}</td>
                    <td>{{ $due_list->phone }}</td>
                    <td>{{ ucfirst($due_list->type) }}</td>
                    <td>{{ currency_format($due_list->due) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
