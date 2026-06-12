@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Notification List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span>/ {{ __('Notification List') }}</span>
                </h2>
            </div>
            <div class="card">
                <div class="responsive-table m-0">
                    <table class="table" id="erp-table">
                        <thead>
                            <tr>
                                <th>{{ __('SL.') }}</th>
                                <th>{{ __('Message') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Read At') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="notifications-data" class="searchResults">
                            @include('restaurantwebaddon::notifications.datas')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
